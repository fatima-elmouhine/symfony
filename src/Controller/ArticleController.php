<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManager;
use App\Form\NewArticleFormType;
use App\Repository\LikeRepository;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles')]
    public function index(
        ArticleRepository $repo,
        LikeRepository $likeRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
    $data = $repo->findBy(['is_public' => true]);
    $articles = $paginator->paginate(
        $data,
        $request->query->getInt('page', 1),
        4
    );


    $likes = $likeRepo->findAll();


        return $this->render('articles/articles.html.twig', [
            'articles'=> $articles,
            'likes'=>$likes,
            'controller_name' => 'ArticleController',
            'css' => 'asset/articles.css'

        ]);
}

    #[Route('/article/{id}', name: 'article')]
    public function article(
        $id,
        ArticleRepository $repo,
        CommentRepository $commentRepo,
        EntityManagerInterface $entityManager,
        Request $request)
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

        return $this->render('articles/article.html.twig', [
            'controller_name' => 'ArticleController',
            'article'=> $article,
            'comments'=> $comments,
            'form'=>$commentForm->createView(),
            'css' => 'asset/article.css'
        ]);
    }
    #[IsGranted('ROLE_USER')]
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

            $article->setIsPublic(true);
            $article->setTitle( $form->get('title')->getData());
            $article->setContent( $form->get('content')->getData());
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
            $article->setIdUser($this->getUser());
            $article->setCreatedAt(new \DateTimeImmutable);


            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('blog');
        }
        return $this->renderForm('articles/newArticle.html.twig', [
            'form' => $form,
            'css' => 'asset/articles.css'
        ]);
    }

    // #[IsGranted('ROLE_USER')]
    #[Route('article/like/{id_article}/{id_user}', name: 'article.isLike')]
    public function likeArticle(
        LikeRepository $likeRepo,
        ArticleRepository $articleRepo,
        UserRepository $userRepo,
        $id_article,
        $id_user,
        EntityManagerInterface $entityManager): Response
    {
        $arrayLike = $likeRepo->findBy(array('id_article' => $id_article, 'id_user' => $id_user));
        $article = $articleRepo->find($id_article);
        $user = $userRepo->find($id_user);
        if($arrayLike == []){
            $like = new Like();
            $like->setIdArticle($article);
            $like->setIdUser($user);
            $like->setIsLiked(true);
            $entityManager->persist($like);
        }else{
            $arrayLike[0]->setIsLiked(!$arrayLike[0]->isIsLiked());
            $entityManager->persist($arrayLike[0]);

        }
        $entityManager->flush();
        return $this->redirectToRoute('articles');

    }

}
