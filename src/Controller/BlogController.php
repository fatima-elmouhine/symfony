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

    #[Route('/', name: 'blog')]
    public function index(ArticleRepository $repo)
    
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            // 'articles'=> $articles
            'css' => 'asset/style.css'
        ]);
    }




}