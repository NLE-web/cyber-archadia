<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ActionCrudController;
use App\Controller\Admin\EdgerunnerCrudController;
use App\Controller\Admin\ImageFileCrudController;
use App\Controller\Admin\ItemCrudController;
use App\Controller\Admin\SkillCrudController;
use App\Entity\Edgerunner;
use App\Entity\Log;
use App\Repository\LogRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function index(): Response
    {
        $latestLogs = $this->logRepository->findBy([], ['createdAt' => 'DESC']);
        
        $mercureUrl = $_ENV['MERCURE_PUBLIC_URL'] ?? 'https://example.com/.well-known/mercure';

        return $this->render('admin/index.html.twig', [
            'latestLogs' => $latestLogs,
            'mercure_url' => $mercureUrl . '?topic=' . urlencode('https://archadia.net/logs'),
        ]);
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
