<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public $userRepository;
    public $articleRepository;
    public $commentRepository;
    function __construct(UserRepository $userRepository, ArticleRepository $articleRepository, CommentRepository $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }
        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        dd($this->userRepository);
        $users = $this->userRepository->findAll();
        dd($users);
        $data = array('toto');
        return $this->render('admin/index.html.twig', [
            'my_own_data' => $data,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog 9000');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Articles', 'fa-regular fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa-solid fa-message', Comment::class);
        yield MenuItem::linkToRoute('Retour au blog', 'fa-solid fa-circle-arrow-left', 'blog');
    }
}
