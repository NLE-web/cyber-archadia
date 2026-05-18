<?php

namespace App\Controller;

use App\Entity\DownTime;
use App\Entity\EdgeRunnerDownTime;
use App\Entity\Edgerunner;
use App\Entity\Log;
use App\Repository\DownTimeRepository;
use App\Repository\EdgeRunnerDownTimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class DownTimeController extends AbstractController
{
    #[Route('/character/downtime', name: 'app_character_downtime')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user->isDowntimeActive()) {
            return $this->redirectToRoute('app_character_summary');
        }

        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        if (!$character) {
            return $this->redirectToRoute('app_character_summary');
        }

        // Récupérer les downtimes du personnage (draft ou déjà validés)
        $characterDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
            'edgerunner' => $character
        ]);

        // On peut aussi proposer des downtimes globaux (non assignés spécifiquement au début)
        // Mais selon l'énoncé "chaque joueur voit un tableau avec ses différentes options de downtime"
        // Cela implique qu'on doit lui en assigner. 
        // Si la liste est vide, on pourrait en proposer des par défaut ou laisser le MJ les assigner via le CRUD.

        return $this->render('character/downtime.html.twig', [
            'character' => $character,
            'downtimes' => $characterDowntimes,
        ]);
    }

    #[Route('/character/downtime/validate/{id}', name: 'app_character_downtime_validate', methods: ['POST'])]
    public function validate(
        int $id,
        EntityManagerInterface $entityManager,
        HubInterface $hub
    ): Response {
        $user = $this->getUser();
        if (!$user->isDowntimeActive()) {
            return $this->redirectToRoute('app_character_summary');
        }

        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        $edgeRunnerDownTime = $entityManager->getRepository(EdgeRunnerDownTime::class)->find($id);

        if (!$edgeRunnerDownTime || $edgeRunnerDownTime->getEdgerunner() !== $character) {
            return $this->redirectToRoute('app_character_downtime');
        }

        if (!$edgeRunnerDownTime->isDiscard()) {
            $edgeRunnerDownTime->setDiscard(true);
            $edgeRunnerDownTime->setDraft(false);

            $description = "A validé son downtime : " . $edgeRunnerDownTime->getDowntime()->getTitle();
            $this->createLog($entityManager, $hub, $character, $description);

            $entityManager->flush();

            // Notification pour le MJ pour mettre à jour sa liste
            $hub->publish(new Update(
                'https://archadia.net/logs',
                json_encode([
                    'type' => 'downtime_validated',
                    'character' => $character->getNom()
                ])
            ));
        }

        return $this->redirectToRoute('app_character_downtime');
    }

    private function createLog(EntityManagerInterface $entityManager, HubInterface $hub, Edgerunner $character, string $description, ?int $amount = null, bool $isCritical = false): void
    {
        $log = new Log();
        $log->setCharacter($character);
        $log->setAmount($amount);
        $log->setDescription($description);
        $log->setIsCritical($isCritical);
        $entityManager->persist($log);
        $entityManager->flush();

        $hub->publish(new Update(
            'https://archadia.net/logs',
            json_encode([
                'character' => $character->getNom(),
                'amount' => $amount,
                'description' => $description,
                'isCritical' => $isCritical,
                'date' => $log->getCreatedAt() ? $log->getCreatedAt()->format('H:i:s') : date('H:i:s')
            ])
        ));
    }
}
