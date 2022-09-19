<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager ;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo)
    {
        // $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            // 'articles'=> $articles
        ]);
    }

    #[Route('/blog/signup', name: 'signup')]
    public function signup(ArticleRepository $repo)
    {
        return $this->render('blog/signup.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/register', name: 'register')]
    public function register(ArticleRepository $repo)
    {

        return $this->render('blog/register.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/account', name: 'account')]
    public function account(ArticleRepository $repo)
    {
        return $this->render('blog/account.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/articles', name: 'articles')]
    public function articles(ArticleRepository $repo)
    {
        // $articles = $repo->findAll();

        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
            // 'articles'=> $articles
        ]);
    }
    

    #[Route('/blog/article/:id', name: 'article')]
    public function article(ArticleRepository $repo)
    {
        // $articles = $repo->findAll();

        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
            // 'articles'=> $articles
        ]);
    }

    #[Route('/blog/new-post', name: 'newArticle')]
    public function new(Request $request)//, ObjectManager $manager)
    {

        // $article = new Article();

        // $form = $this->createFormBuilder($article)
        //     ->add('title')
        //     ->add('content')
        //     ->add('image')
        //     ->getForm();

        // $form->handleRequest($request);

        // if($form->isSubmitted() && $form->isValid()){
        //     $manager->persist($article);
        //     $manager->flush();

        //     return $this->redirectToRoute('blog');
        // }

        return $this->render('blog/newArticle.html.twig', [
            // 'formArticle' => $form->createView()
        ]);
    }

}