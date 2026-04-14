<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ActionCrudController;
use App\Controller\Admin\EdgerunnerCrudController;
use App\Controller\Admin\ImageFileCrudController;
use App\Controller\Admin\ItemCrudController;
use App\Controller\Admin\SkillCrudController;
use App\Entity\Edgerunner;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Archadia Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Console MJ', 'fa fa-gamepad');

        yield MenuItem::section('Jeu');
        yield MenuItem::linkTo(EdgerunnerCrudController::class, 'Personnages', 'fa fa-users');

        yield MenuItem::section('Données');
        yield MenuItem::linkTo(ItemCrudController::class, 'Items', 'fa fa-box');
        yield MenuItem::linkTo(ActionCrudController::class, 'Actions', 'fa fa-bolt');
        yield MenuItem::linkTo(SkillCrudController::class, 'Skills', 'fa fa-brain');
        yield MenuItem::linkTo(ImageFileCrudController::class, 'Images', 'fa fa-image');
    }
}
