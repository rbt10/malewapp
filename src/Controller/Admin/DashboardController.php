<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Difficulte;
use App\Entity\Favoris;
use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct( private AdminUrlGenerator $adminUrlGenerator)
    {

    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $url =$this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Malewapp');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', ' fas fa-user', User::class);
        yield MenuItem::linkToCrud('Categorie', ' fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Ingredient', ' fas fa-tag', Ingredient::class);
        yield MenuItem::linkToCrud('Difficulte', ' fas fa-plus', Difficulte::class);
        yield MenuItem::linkToCrud('Recette', ' fas fa-plus', Recette::class);
        yield MenuItem::linkToCrud('favoris', ' fas fa-plus', Favoris::class);
    }
}
