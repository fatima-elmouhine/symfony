<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\UserFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('user/{id}', name: 'app_user')]
    public function index(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepo,
        UserPasswordHasherInterface $userPasswordHasher
        ): Response
    {
        $userArticles = $articleRepo->findBy(['id_user' => $user->getId()]);
        // dd($userArticles);
        if(!$this->getUser()){
            return $this->redirectToRoute('signup');
        }

        if($this->getUser() !== $user){
            return $this->redirectToRoute('blog');
        }

        // dd($userArticles[0]->isIsPublic());
        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userForm->get('plainPassword')->getData()
                )
            );
            $user = $userForm->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('articles');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'css' => 'asset/account.css',
            'userForm' => $userForm->createView(),
            'articles' => $userArticles
        ]);
    }

    #[Route('article/show/{id}/{id_user}', name: 'article.state')]
    public function show(ArticleRepository $articleRepo, $id, $id_user, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepo->find($id);
        $article->setIsPublic(!$article->isIsPublic());
        $entityManager->persist($article);
        $entityManager->flush();
        // dd($id_user);
        return $this->redirectToRoute('app_user', ['id' => $id_user]);
        // return $this->render('article/show.html.twig', [
        //     'article' => $article,
        // ]);
    }

}
