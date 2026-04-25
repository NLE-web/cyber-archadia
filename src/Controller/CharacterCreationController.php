<?php

namespace App\Controller;

use App\Entity\Log;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\Edgerunner;
use App\Entity\ImageFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\CharacterCreationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CharacterCreationController extends AbstractController
{
    #[Route('/character/new', name: 'app_character_new')]
    public function new(Request $request, ManagerRegistry $manager, SluggerInterface $slugger, HubInterface $hub): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur a déjà un personnage
        $existingCharacter = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $user]);
        if ($existingCharacter) {
            return $this->redirectToRoute('app_main');
        }

        $character = new Edgerunner();
        $character->setPlayer($user);
        // Valeurs par défaut
        $character->setCyberpoints(0);
        $character->setStresspoints(0);
        $character->setLostlife(0);
        $character->setLostcyber(0);
        $character->setMoney(0);
        $character->setHumanityLoss(0);
        $character->setIsActive(true);

        $form = $this->createForm(CharacterCreationType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            
            // Calculer les PV via la formule FOR x 3
            $character->setLifepoints($character->getForce() * 3);

            // Appliquer le thème de couleur à l'utilisateur
            $themeColor = $form->get('themeColor')->getData();
            $user->setThemeColor($themeColor);

            // Gestion de l'avatar
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/images',
                        $newFilename
                    );
                    
                    $imageFile = new ImageFile();
                    $imageFile->setDisplayName($character->getNom() . " Avatar");
                    $imageFile->setStorageName($newFilename);
                    $entityManager->persist($imageFile);
                    
                    $character->setAvatar($imageFile);
                } catch (FileException $e) {
                    // Gérer l'erreur si nécessaire
                }
            } else {
                // Optionnel : mettre un avatar par défaut si aucun n'est fourni
                // Pour l'instant on laisse null ou on peut chercher un avatar par défaut
            }

            $entityManager->persist($character);
            $entityManager->flush();

            $this->createLog($manager, $hub, $character, "Nouveau personnage créé : " . $character->getNom(), null, true);

            return $this->redirectToRoute('app_main');
        }

        return $this->render('character/new.html.twig', [
            'form' => $form->createView(),
        ]);
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
                'date' => $log->getCreatedAt()->format('H:i:s')
            ])
        ));
    }
}
