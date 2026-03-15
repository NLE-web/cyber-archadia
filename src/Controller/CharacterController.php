<?php

namespace App\Controller;

use App\Entity\Edgerunner;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CharacterController extends AbstractController
{
    #[Route('/character/majstat/{stat}/{math}/{value}', name: 'app_maj_stat')]
    public function majstat(ManagerRegistry $manager, $stat, $math, $value): Response
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character)
        {
            return $this->redirectToRoute("app_main");
        }
        if ($stat == "life")
        {
            if ($math == "add"){
                if ($character->getLostlife() > 0)
                {
                    $character->setLostlife($character->getLostlife() - $value);
                }
            } else {
                if ($character->getLostlife() < $character->getLifepoints())
                {
                    $character->setLostlife($character->getLostlife() + $value);
                }
            }
        }
        if ($stat == "cyber")
        {
            if ($math == "add"){
                if ($character->getLostcyber() > 0)
                {
                    $character->setLostcyber($character->getLostcyber() - $value);
                }
            } else {
                if ($character->getLostcyber() < $character->getCyberpoints())
                {
                    $character->setLostcyber($character->getLostcyber() + $value);
                }
            }
        }
        if ($stat == "stress")
        {
            if ($math == "add"){
                if ($character->getStresspoints() < $character->getIntelligence() * 5)
                {
                    $character->setStresspoints($character->getStresspoints() + $value);
                }
            } else {
                if ($character->getStresspoints() > 0)
                {
                    $character->setStresspoints($character->getStresspoints() - $value);
                }
            }
        }
        $manager->getManager()->flush();
        return $this->redirectToRoute("app_main");
    }

    #[Route('/character/skills', name: 'app_character_skills')]
    public function characterSkills(ManagerRegistry $manager,)
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character)
        {
            return $this->redirectToRoute("app_main");
        }
        return $this->render('main/skills.html.twig', [
            "character" => $character,
        ]);
    }
    #[Route('/character/items', name: 'app_character_items')]
    public function characterItems(ManagerRegistry $manager,)
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character)
        {
            return $this->redirectToRoute("app_main");
        }
        return $this->render('main/items.html.twig', [
            "character" => $character,
        ]);
    }

    #[Route('/character/actions', name: 'app_character_actions')]
    public function characterActions(ManagerRegistry $manager,)
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character)
        {
            return $this->redirectToRoute("app_main");
        }
        return $this->render('main/actions.html.twig', [
            "character" => $character,
        ]);
    }
}
