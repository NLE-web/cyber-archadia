<?php

namespace App\Controller;

use App\Entity\CharacterContact;
use App\Entity\Edgerunner;
use App\Entity\Message;
use App\Repository\CharacterContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class SocialController extends AbstractController
{
    #[Route('/social', name: 'app_social')]
    public function index(EntityManagerInterface $em): Response
    {
        $character = $em->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character) {
            return $this->redirectToRoute('app_character_new');
        }

        return $this->render('social/index.html.twig', [
            'character' => $character,
            'mercure_public_url' => $_ENV['MERCURE_PUBLIC_URL'] ?? null,
        ]);
    }

    #[Route('/social/conversation/{id}', name: 'app_social_conversation')]
    public function conversation(CharacterContact $characterContact, Request $request, EntityManagerInterface $em, HubInterface $hub): Response
    {
        if ($characterContact->getCharacter()->getPlayer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // Marquer les messages comme lus
        foreach ($characterContact->getMessages() as $message) {
            if ($message->isFromContact() && !$message->isRead()) {
                $message->setIsRead(true);
            }
        }
        $em->flush();

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
            if ($content) {
                $message = new Message();
                $message->setContent($content);
                $message->setCharacterContact($characterContact);
                $message->setIsFromContact(false);
                $message->setIsRead(false); // Le MJ ne l'a pas encore lu

                $em->persist($message);
                $em->flush();

                // Notification Mercure pour l'instantanéité (Joueur -> MJ)
                $hub->publish(new Update(
                    'https://archadia.net/social/admin',
                    json_encode([
                        'type' => 'message',
                        'characterName' => $characterContact->getCharacter()->getNom(),
                        'content' => $content,
                        'conversationId' => $characterContact->getId(),
                        'contactId' => $characterContact->getContact()->getId()
                    ])
                ));

                return $this->redirectToRoute('app_social_conversation', ['id' => $characterContact->getId()]);
            }
        }

        return $this->render('social/conversation.html.twig', [
            'characterContact' => $characterContact,
            'character' => $characterContact->getCharacter(),
            'mercure_public_url' => $_ENV['MERCURE_PUBLIC_URL'] ?? null,
        ]);
    }
}
