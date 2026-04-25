<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ActionCrudController;
use App\Controller\Admin\EdgerunnerCrudController;
use App\Controller\Admin\FeatCrudController;
use App\Controller\Admin\ImageFileCrudController;
use App\Controller\Admin\ItemCrudController;
use App\Controller\Admin\SkillCrudController;
use App\Entity\Edgerunner;
use App\Entity\Log;
use App\Entity\User;
use App\Repository\EdgerunnerRepository;
use App\Repository\LogRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private LogRepository $logRepository,
        private UserRepository $userRepository,
        private ItemRepository $itemRepository,
        private EntityManagerInterface $entityManager,
        private HubInterface $hub,
    ) {
    }

    public function index(): Response
    {
        $latestLogs = $this->logRepository->findBy([], ['createdAt' => 'DESC']);
        
        $mercureUrl = $_ENV['MERCURE_PUBLIC_URL'] ?? 'https://example.com/.well-known/mercure';

        // Check global level up state
        $levelUpActive = false;
        $users = $this->userRepository->findAll();
        if (count($users) > 0) {
            $levelUpActive = $users[0]->isLevelUpActive();
        }

        $items = $this->itemRepository->findAll();
        $characters = $this->entityManager->getRepository(Edgerunner::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'latestLogs' => $latestLogs,
            'mercure_url' => $mercureUrl . '?topic=' . rawurlencode('https://archadia.net/logs'),
            'mercure_public_url' => $mercureUrl,
            'levelUpActive' => $levelUpActive,
            'items' => $items,
            'characters' => $characters,
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
        yield MenuItem::linkTo(FeatCrudController::class, 'Feats', 'fa fa-award');
        yield MenuItem::linkTo(ImageFileCrudController::class, 'Images', 'fa fa-image');
    }

    #[Route('/admin/xp/give', name: 'admin_xp_give', methods: ['POST'])]
    public function giveXp(Request $request, HubInterface $hub): Response
    {
        $amount = (int) $request->request->get('amount');
        $characterId = $request->request->get('character_id');

        if ($characterId === 'all') {
            $characters = $this->entityManager->getRepository(Edgerunner::class)->findAll();
            foreach ($characters as $character) {
                $character->setXp(($character->getXp() ?? 0) + $amount);
                $this->createLog($character, "Gain d'expérience (Distribution globale)", $amount, $hub);
            }
        } else {
            $character = $this->entityManager->getRepository(Edgerunner::class)->find($characterId);
            if ($character) {
                $character->setXp(($character->getXp() ?? 0) + $amount);
                $this->createLog($character, "Gain d'expérience", $amount, $hub);
            }
        }

        $this->entityManager->flush();
        return $this->redirectToRoute('admin');
    }

    private function createLog(Edgerunner $character, string $description, ?int $amount, HubInterface $hub): void
    {
        $log = new Log();
        $log->setCharacter($character);
        $log->setAmount($amount);
        $log->setDescription($description);
        $log->setIsCritical(false);
        $this->entityManager->persist($log);

        $hub->publish(new Update(
            'https://archadia.net/logs',
            json_encode([
                'character' => $character->getNom(),
                'amount' => $amount,
                'description' => $description,
                'isCritical' => false,
                'date' => $log->getCreatedAt()->format('H:i:s')
            ])
        ));
    }
}
