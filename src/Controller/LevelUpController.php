<?php

namespace App\Controller;

use App\Entity\CharacterFeat;
use App\Entity\CharacterSkill;
use App\Entity\Edgerunner;
use App\Entity\Feat;
use App\Entity\Log;
use App\Entity\Skill;
use App\Repository\EdgerunnerRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class LevelUpController extends AbstractController
{
    #[Route('/character/levelup/interest/{type}/{id}', name: 'app_levelup_interest')]
    public function markInterest(ManagerRegistry $manager, HubInterface $hub, string $type, int $id): Response
    {
        $user = $this->getUser();
        if (!$user->isLevelUpActive()) {
            return $this->redirectToRoute('app_character_skills');
        }

        $entityManager = $manager->getManager();
        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);

        if ($type === 'skill') {
            $skill = $entityManager->getRepository(Skill::class)->find($id);
            if ($skill) {
                // Check if already exists
                $exists = false;
                foreach ($character->getSkills() as $cs) {
                    if ($cs->getSkill() === $skill) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $characterSkill = new CharacterSkill();
                    $characterSkill->setCharacter($character);
                    $characterSkill->setSkill($skill);
                    $characterSkill->setLevel(0);
                    $characterSkill->setXptot(0);
                    $entityManager->persist($characterSkill);
                    $this->createLog($manager, $hub, $character, "Marque un intérêt pour le skill : " . $skill->getName());
                }
            }
        } elseif ($type === 'feat') {
            $feat = $entityManager->getRepository(Feat::class)->find($id);
            if ($feat) {
                // Check if already exists
                $exists = false;
                foreach ($character->getFeats() as $cf) {
                    if ($cf->getFeat() === $feat) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $characterFeat = new CharacterFeat();
                    $characterFeat->setCharacter($character);
                    $characterFeat->setFeat($feat);
                    $characterFeat->setXptot(0);
                    $entityManager->persist($characterFeat);
                    $this->createLog($manager, $hub, $character, "Marque un intérêt pour le feat : " . $feat->getName());
                }
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_character_skills');
    }

    #[Route('/character/levelup/addxp/{type}/{id}', name: 'app_levelup_addxp')]
    public function addXp(ManagerRegistry $manager, HubInterface $hub, string $type, int $id): Response
    {
        $user = $this->getUser();
        if (!$user->isLevelUpActive()) {
            return $this->redirectToRoute('app_character_skills');
        }

        $entityManager = $manager->getManager();
        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);

        if ($type === 'skill') {
            $cs = $entityManager->getRepository(CharacterSkill::class)->find($id);
            if ($cs && $cs->getCharacter() === $character && $cs->getLevel() < 5) {
                $newXp = $cs->getXptot() + 1;
                $xpCost = $cs->getSkill()->getXpcost() ?? 10; // Valeur par défaut si non définie

                if ($newXp >= $xpCost) {
                    $cs->setXptot(0);
                    $cs->setLevel($cs->getLevel() + 1);
                    $this->createLog($manager, $hub, $character, "NIVEAU SUPÉRIEUR : " . $cs->getSkill()->getName() . " passe au niveau " . $cs->getLevel(), null, true);
                } else {
                    $cs->setXptot($newXp);
                    $this->createLog($manager, $hub, $character, "+1 XP sur " . $cs->getSkill()->getName());
                }
            }
        } elseif ($type === 'feat') {
            $cf = $entityManager->getRepository(CharacterFeat::class)->find($id);
            if ($cf && $cf->getCharacter() === $character) {
                $xpCost = $cf->getFeat()->getXpcost() ?? 10;
                if ($cf->getXptot() < $xpCost) {
                    $cf->setXptot($cf->getXptot() + 1);
                    $this->createLog($manager, $hub, $character, "+1 XP sur " . $cf->getFeat()->getName());
                    
                    if ($cf->getXptot() >= $xpCost) {
                        $this->createLog($manager, $hub, $character, "TALENT ACQUIS : " . $cf->getFeat()->getName(), null, true);
                    }
                }
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_character_skills');
    }

    #[Route('/admin/levelup/toggle', name: 'admin_levelup_toggle', methods: ['GET'])]
    public function toggleLevelUp(UserRepository $userRepository, EntityManagerInterface $entityManager, HubInterface $hub): Response
    {
        $users = $userRepository->findAll();
        $newState = true;
        
        if (count($users) > 0) {
            $newState = !$users[0]->isLevelUpActive();
            foreach ($users as $user) {
                $user->setLevelUpActive($newState);
            }
            $entityManager->flush();
        }

        // Notify via Mercure
        $hub->publish(new Update(
            'https://archadia.net/levelup',
            json_encode(['active' => $newState])
        ));

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/xp/give', name: 'admin_xp_give', methods: ['POST'])]
    public function giveXp(Request $request, ManagerRegistry $manager, HubInterface $hub): Response
    {
        $amount = (int) $request->request->get('amount');
        $characterId = $request->request->get('character_id');
        $entityManager = $manager->getManager();

        if ($characterId === 'all') {
            $characters = $entityManager->getRepository(Edgerunner::class)->findAll();
            foreach ($characters as $character) {
                $character->setXp(($character->getXp() ?? 0) + $amount);
                $this->createLog($manager, $hub, $character, "Gain d'expérience (Distribution globale)", $amount);
            }
        } else {
            $character = $entityManager->getRepository(Edgerunner::class)->find($characterId);
            if ($character) {
                $character->setXp(($character->getXp() ?? 0) + $amount);
                $this->createLog($manager, $hub, $character, "Gain d'expérience", $amount);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('admin');
    }

    private function createLog(ManagerRegistry $manager, HubInterface $hub, Edgerunner $character, string $description, ?int $amount = null, bool $isCritical = false): void
    {
        $log = new Log();
        $log->setCharacter($character);
        $log->setAmount($amount);
        $log->setDescription($description);
        $log->setIsCritical($isCritical);
        $manager->getManager()->persist($log);
        $manager->getManager()->flush();

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
