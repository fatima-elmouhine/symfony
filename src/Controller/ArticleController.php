<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManager;
use App\Form\NewArticleFormType;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
 

class ArticleController extends AbstractController
{
    #[Route('blog/articles', name: 'articles')]
    public function index(
        ArticleRepository $repo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $repo->findAll();
        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('blog/articles.html.twig', [
            'articles'=> $articles,
            'controller_name' => 'ArticleController',
            'css' => 'asset/articles.css'

        ]);
        // return $this->render('articles/index.html.twig', [
        //     'controller_name' => 'ArticleController',
        // ]);
    }

    #[Route('blog/article/{id}', name: 'article')]
    public function article($id, ArticleRepository $repo, CommentRepository $commentRepo, EntityManagerInterface $entityManager, Request $request)
    {
        $article = $repo->find($id);
        $comments = $commentRepo->findBy(['id_article' => $id]);

        $commentForm = $this->createForm(CommentFormType::class, new Comment());
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            $comment->setCreatedAt(new \DateTimeImmutable);
            $comment->setIdArticle($article);
            $comment->setIdUser($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('article', ['id' => $id]);

        }

        return $this->render('blog/article.html.twig', [
            'controller_name' => 'ArticleController',
            'article'=> $article,
            'comments'=> $comments,
            'form'=>$commentForm->createView(),
            'css' => 'asset/article.css'
        ]);
    }

    #[Route('/articles/new', name: 'newArticle')]
    public function newArticle(
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        Request $request)
    {
        $article = new Article();
        $form = $this->createForm(NewArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = 'uploads-'.$safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            try {

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $article->setImage($newFilename);
            $article->setIsPublic(true);
            $article->setTitle( $form->get('title')->getData());
            $article->setContent( $form->get('content')->getData());
            $article->setIdUser($this->getUser());
        //     dd($form->get('image')->getData(null));
            $article->setCreatedAt(new \DateTimeImmutable);


            $entityManager->persist($article);
            $entityManager->flush();
        // //     // do anything else you need here, like send an email

            return $this->redirectToRoute('blog');
        }
        // // $article = $repo->find($id);

        return $this->renderForm('articles/newArticle.html.twig', [
            'form' => $form,
            'css' => 'asset/articles.css'
        ]);
    }
}
