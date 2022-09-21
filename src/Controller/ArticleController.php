<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'css' => 'asset/articles.css'
        ]);
        // return $this->render('articles/index.html.twig', [
        //     'controller_name' => 'ArticleController',
        // ]);
    }
}
