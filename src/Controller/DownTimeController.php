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
use Symfony\Component\HttpFoundation\Request;
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
            return $this->redirectToRoute('app_main');
        }

        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        if (!$character) {
            return $this->redirectToRoute('app_main');
        }

        // 1. Récupérer les downtimes déjà en draft pour le personnage
        $draftDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
            'edgerunner' => $character,
            'draft' => true
        ]);
        $needed = 10 - count($draftDowntimes);
        if (count($draftDowntimes) < 10) {
            for ($i = 0; $i < $needed; $i++) {
                $availableDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
                    'edgerunner' => $character,
                    'draft' => false,
                    'discard' => false
                ]);

                if (count($availableDowntimes) < 1) {
                    $toRecycle = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
                        'edgerunner' => $character,
                        'draft' => false,
                        'discard' => true,
                        'selected' => false
                    ]);

                    foreach ($toRecycle as $edt) {
                        $edt->setDraft(false);
                        $edt->setDiscard(false);
                        $edt->setSelected(false);
                    }
                    $entityManager->flush();

                    // On recharge les disponibles après recyclage
                    $availableDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
                        'edgerunner' => $character,
                        'draft' => false,
                        'discard' => false,
                        'selected' => false
                    ]);
                }
                shuffle($availableDowntimes);
                $toAdd = $availableDowntimes[array_rand($availableDowntimes)];
                $toAdd->setDraft(true);
                $toAdd->setSelected(false);
                $draftDowntimes[] = $toAdd;
                $entityManager->flush();
            }
        }

        // Tri : les "forced" en premier, puis par ID pour garder un ordre stable
        usort($draftDowntimes, function($a, $b) {
            $aForced = $a->getDowntime()->isForced();
            $bForced = $b->getDowntime()->isForced();
            
            if ($aForced !== $bForced) {
                return $aForced ? -1 : 1;
            }
            
            return $a->getId() <=> $b->getId();
        });

        return $this->render('character/downtime.html.twig', [
            'character' => $character,
            'downtimes' => $draftDowntimes,
        ]);
    }

    #[Route('/character/downtime/toggle/{id}', name: 'app_character_downtime_toggle', methods: ['POST'])]
    public function toggle(
        int $id,
        EntityManagerInterface $entityManager,
        HubInterface $hub
    ): Response {
        $user = $this->getUser();
        if (!$user->isDowntimeActive()) {
            return $this->redirectToRoute('app_main');
        }

        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        if (!$character) {
            return $this->redirectToRoute('app_main');
        }

        $edt = $entityManager->getRepository(EdgeRunnerDownTime::class)->find($id);
        if (!$edt || $edt->getEdgerunner() !== $character) {
            return $this->redirectToRoute('app_character_downtime');
        }

        // On ne peut pas désélectionner un forcé
        if ($edt->getDowntime()->isForced()) {
            return $this->redirectToRoute('app_character_downtime');
        }

        if ($edt->isSelected()) {
            $edt->setSelected(false);
        } else {
            // Vérification du budget de 24h
            $characterDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
                'edgerunner' => $character
            ]);
            
            $totalTime = 0;
            foreach ($characterDowntimes as $item) {
                if ($item->isDraft() && ($item->isSelected() || $item->getDowntime()->isForced())) {
                    $totalTime += $item->getDowntime()->getTimeCost();
                }
            }

            if ($totalTime + $edt->getDowntime()->getTimeCost() > 24) {
                $this->addFlash('error', 'Le coût total ne peut pas dépasser 24 heures.');
                return $this->redirectToRoute('app_character_downtime');
            }

            $edt->setSelected(true);
            $description = "A sélectionné son downtime : " . $edt->getDowntime()->getTitle();
            $this->createLog($entityManager, $hub, $character, $description);

            // Notification pour le MJ
            $hub->publish(new Update(
                'https://archadia.net/logs',
                json_encode([
                    'type' => 'downtime_validated',
                    'character' => $character->getNom()
                ])
            ));
        }

        $entityManager->flush();

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
