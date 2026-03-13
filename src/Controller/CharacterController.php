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
        $manager->getManager()->flush();
        return $this->redirectToRoute("app_main");
    }
}
