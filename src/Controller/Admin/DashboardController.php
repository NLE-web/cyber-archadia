<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ContactCrudController;
use App\Controller\Admin\ActionCrudController;
use App\Controller\Admin\EdgerunnerCrudController;
use App\Controller\Admin\FeatCrudController;
use App\Controller\Admin\ImageFileCrudController;
use App\Controller\Admin\ItemCrudController;
use App\Controller\Admin\SkillCrudController;
use App\Entity\Action as ActionEntity;
use App\Entity\CharacterContact;
use App\Entity\Contact;
use App\Entity\Edgerunner;
use App\Entity\Feat;
use App\Entity\ImageFile;
use App\Entity\Item;
use App\Entity\Log;
use App\Entity\Message;
use App\Entity\Skill;
use App\Entity\User;
use App\Repository\CharacterContactRepository;
use App\Repository\ContactRepository;
use App\Repository\EdgerunnerRepository;
use App\Repository\LogRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Component\Asset\Packages;
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
        private Packages $packages,
    ) {
    }

    public function index(): Response
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
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
        $contacts = $this->entityManager->getRepository(Contact::class)->findAll();
        $conversations = $this->entityManager->getRepository(CharacterContact::class)->findAll();
        
        $conversationId = $request->query->get('conversation_id');
        $activeConversation = null;
        if ($conversationId) {
            $activeConversation = $this->entityManager->getRepository(CharacterContact::class)->find($conversationId);
            
            if ($activeConversation) {
                // Marquer les messages du JOUEUR comme lus quand le MJ regarde
                foreach ($activeConversation->getMessages() as $message) {
                    if (!$message->isFromContact() && !$message->isRead()) {
                        $message->setIsRead(true);
                    }
                }
                $this->entityManager->flush();
            }
        }

        return $this->render('admin/index.html.twig', [
            'latestLogs' => $latestLogs,
            'mercure_url' => $mercureUrl . '?topic=' . rawurlencode('https://archadia.net/logs'),
            'mercure_social_url' => $mercureUrl . '?topic=' . rawurlencode('https://archadia.net/social/admin'),
            'mercure_public_url' => $mercureUrl,
            'levelUpActive' => $levelUpActive,
            'items' => $items,
            'characters' => $characters,
            'contacts' => $contacts,
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
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
        yield MenuItem::linkTo(EdgerunnerCrudController::class, 'Personnages', 'fa fa-users')->setAction('index');

        yield MenuItem::section('Données');
        yield MenuItem::linkTo(ContactCrudController::class, 'Contacts', 'fa fa-address-book')->setAction('index');
        yield MenuItem::linkTo(ItemCrudController::class, 'Items', 'fa fa-box')->setAction('index');
        yield MenuItem::linkTo(ActionCrudController::class, 'Actions', 'fa fa-bolt')->setAction('index');
        yield MenuItem::linkTo(SkillCrudController::class, 'Skills', 'fa fa-brain')->setAction('index');
        yield MenuItem::linkTo(FeatCrudController::class, 'Feats', 'fa fa-award')->setAction('index');
        yield MenuItem::linkTo(ImageFileCrudController::class, 'Images', 'fa fa-image')->setAction('index');
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
        $this->entityManager->flush(); // Flush to get ID and ensure creation

        $hub->publish(new Update(
            'https://archadia.net/logs',
            json_encode([
                'character' => $character->getNom(),
                'amount' => $amount,
                'description' => $description,
                'isCritical' => false,
                'date' => $log->getCreatedAt() ? $log->getCreatedAt()->format('H:i:s') : date('H:i:s')
            ])
        ));
    }

    #[Route('/admin/social/send', name: 'admin_social_send', methods: ['POST'])]
    public function sendSocialMessage(Request $request, HubInterface $hub): Response
    {
        $conversationId = $request->request->get('conversation_id');
        $content = $request->request->get('content');
        
        $conversation = $this->entityManager->getRepository(CharacterContact::class)->find($conversationId);
        
        if ($conversation && $content) {
            $message = new Message();
            $message->setContent($content);
            $message->setCharacterContact($conversation);
            $message->setIsFromContact(true);
            $message->setIsRead(true); // Lu par le MJ car il vient de l'envoyer
            
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            // Notification Mercure pour l'instantanéité
            $hub->publish(new Update(
                'https://archadia.net/social/' . $conversation->getCharacter()->getId(),
                json_encode([
                    'type' => 'message',
                    'contactName' => $conversation->getContact()->getName(),
                    'content' => $content,
                    'conversationId' => $conversation->getId(),
                    'image' => $conversation->getContact()->getImage() ? $this->packages->getUrl('uploads/images/' . $conversation->getContact()->getImage()->getStorageName()) : null
                ])
            ));
        }
        
        return $this->redirectToRoute('admin', ['conversation_id' => $conversationId]);
    }

    #[Route('/admin/social/contact/add', name: 'admin_social_contact_add', methods: ['POST'])]
    public function addContactToCharacter(Request $request): Response
    {
        $characterId = $request->request->get('character_id');
        $contactId = $request->request->get('contact_id');
        
        $character = $this->entityManager->getRepository(Edgerunner::class)->find($characterId);
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);
        
        if ($character && $contact) {
            $exists = $this->entityManager->getRepository(CharacterContact::class)->findOneBy([
                'character' => $character,
                'contact' => $contact
            ]);
            
            if (!$exists) {
                $cc = new CharacterContact();
                $cc->setCharacter($character);
                $cc->setContact($contact);
                $this->entityManager->persist($cc);
                $this->entityManager->flush();
            }
        }
        
        return $this->redirectToRoute('admin');
    }
}
