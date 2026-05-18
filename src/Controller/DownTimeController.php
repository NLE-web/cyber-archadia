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
            'draft' => true,
            'discard' => false
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
                        'draft' => true,
                        'discard' => true
                    ]);

                    foreach ($toRecycle as $edt) {
                        $edt->setDraft(false);
                        $edt->setDiscard(false);
                    }
                    $entityManager->flush();

                    // On recharge les disponibles après recyclage
                    $availableDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
                        'edgerunner' => $character,
                        'draft' => false,
                        'discard' => false
                    ]);
                }
                shuffle($availableDowntimes);
                $toAdd = $availableDowntimes[array_rand($availableDowntimes)];
                $toAdd->setDraft(true);
                $draftDowntimes[] = $toAdd;
                $entityManager->flush();
            }
        }

        // Tri : les "forced" en premier
        usort($draftDowntimes, function($a, $b) {
            $aForced = $a->getDowntime()->isForced();
            $bForced = $b->getDowntime()->isForced();
            if ($aForced === $bForced) return 0;
            return $aForced ? -1 : 1;
        });

        return $this->render('character/downtime.html.twig', [
            'character' => $character,
            'downtimes' => $draftDowntimes,
        ]);
    }

    #[Route('/character/downtime/validate', name: 'app_character_downtime_validate', methods: ['POST'])]
    public function validate(
        Request $request,
        EntityManagerInterface $entityManager,
        HubInterface $hub
    ): Response {
        $user = $this->getUser();
        if (!$user->isDowntimeActive()) {
            return $this->redirectToRoute('app_main');
        }

        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        $selectedIds = $request->request->all('downtimes');

        $characterDowntimes = $entityManager->getRepository(EdgeRunnerDownTime::class)->findBy([
            'edgerunner' => $character
        ]);

        if (empty($selectedIds)) {
            return $this->redirectToRoute('app_character_downtime');
        }

        $totalTime = 0;
        $downtimesToValidate = [];

        // On inclut TOUJOURS les downtimes forcés non encore validés
        foreach ($characterDowntimes as $edt) {
            if ($edt->getDowntime()->isForced() && !$edt->isDiscard()) {
                $totalTime += $edt->getDowntime()->getTimeCost();
                $downtimesToValidate[] = $edt;
            }
        }

        foreach ($selectedIds as $id) {
            $edgeRunnerDownTime = $entityManager->getRepository(EdgeRunnerDownTime::class)->find($id);
            if ($edgeRunnerDownTime && $edgeRunnerDownTime->getEdgerunner() === $character) {
                // Si c'est un forcé, il est déjà ajouté au-dessus
                if ($edgeRunnerDownTime->getDowntime()->isForced()) {
                    continue;
                }
                
                if (!$edgeRunnerDownTime->isDiscard()) {
                    $totalTime += $edgeRunnerDownTime->getDowntime()->getTimeCost();
                    $downtimesToValidate[] = $edgeRunnerDownTime;
                }
            }
        }

        if ($totalTime > 24) {
            $this->addFlash('error', 'Le coût total ne peut pas dépasser 24 heures.');
            return $this->redirectToRoute('app_character_downtime');
        }

        foreach ($downtimesToValidate as $edt) {
            $edt->setDiscard(true);

            $description = "A validé son downtime : " . $edt->getDowntime()->getTitle();
            $this->createLog($entityManager, $hub, $character, $description);
        }

        $entityManager->flush();

        // Notification pour le MJ
        $hub->publish(new Update(
            'https://archadia.net/logs',
            json_encode([
                'type' => 'downtime_validated',
                'character' => $character->getNom()
            ])
        ));

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
