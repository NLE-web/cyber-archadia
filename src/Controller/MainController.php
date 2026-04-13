<?php

namespace App\Controller;

use App\Entity\Edgerunner;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ManagerRegistry $manager): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
            return $this->render('main/index.html.twig', [
                'character' => $character,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    #[Route('/test', name: 'app_test')]
    public function test(ManagerRegistry $manager): Response
    {
        return $this->render('main/test.html.twig');

    }

}
