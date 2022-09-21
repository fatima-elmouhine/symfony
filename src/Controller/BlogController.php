<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo)
    
    {
        // dump(app);
        // die;
        // $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            // 'articles'=> $articles
            'css' => 'style.css'
        ]);
    }


    // #[Route('/blog/articles', name: 'articles')]
    // public function articles(ArticleRepository $repo)
    // {
    //     $articles = $repo->findAll();

    //     return $this->render('blog/articles.html.twig', [
    //         'controller_name' => 'BlogController',
    //         'articles'=> $articles
    //     ]);
    // }
    

    // #[Route('/blog/article/{id}', name: 'article')]
    // public function article($id, ArticleRepository $repo, CommentRepository $repoComment)
    // {
    //     $article = $repo->find($id);
    //     $comments = $repoComment->findBy(['id_article' => $id]);

    //     return $this->render('blog/article.html.twig', [
    //         'controller_name' => 'BlogController',
    //         'article'=> $article,
    //         'comments'=> $comments
    //     ]);
    // }

    // #[Route('/blog/new-post', name: 'newArticle')]
    // public function new(Request $request)//, ObjectManager $manager)
    // {

    //     // $article = new Article();

    //     // $form = $this->createFormBuilder($article)
    //     //     ->add('title')
    //     //     ->add('content')
    //     //     ->add('image')
    //     //     ->getForm();

    //     // $form->handleRequest($request);

    //     // if($form->isSubmitted() && $form->isValid()){
    //     //     $manager->persist($article);
    //     //     $manager->flush();

    //     //     return $this->redirectToRoute('blog');
    //     // }

    //     return $this->render('blog/newArticle.html.twig', [
    //         // 'formArticle' => $form->createView()
    //     ]);
    // }

}