<?php

namespace App\Controller;

use App\Entity\CharacterItem;
use App\Entity\Edgerunner;
use App\Entity\Item;
use App\Entity\Log;
use App\Repository\ItemRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

final class ShopController extends AbstractController
{
    #[Route('/character/shop', name: 'app_character_shop')]
    public function shop(ItemRepository $itemRepository, ManagerRegistry $manager): Response
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character) {
            return $this->redirectToRoute('app_character_new');
        }

        $items = $itemRepository->findAll();
        // Filtrer les items sans stock
        $availableItems = array_filter($items, function(Item $item) {
            return $item->isInfiniteStock() || ($item->getStock() !== null && $item->getStock() > 0);
        });

        return $this->render('main/shop.html.twig', [
            'character' => $character,
            'items' => $availableItems,
            'mercure_public_url' => $_ENV['MERCURE_PUBLIC_URL'] ?? 'https://example.com/.well-known/mercure',
        ]);
    }

    #[Route('/character/shop/buy/{id}', name: 'app_character_shop_buy')]
    public function buy(Item $item, ManagerRegistry $manager, HubInterface $hub): Response
    {
        $entityManager = $manager->getManager();
        $character = $entityManager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);

        if (!$character) {
            return $this->redirectToRoute('app_character_new');
        }

        // Vérifier le stock
        if (!$item->isInfiniteStock() && ($item->getStock() === null || $item->getStock() <= 0)) {
            $this->addFlash('error', 'Cet objet n\'est plus en stock.');
            return $this->redirectToRoute('app_character_shop');
        }

        // Vérifier l'argent
        if ($character->getMoney() < $item->getPrice()) {
            $this->addFlash('error', 'Vous n\'avez pas assez de Dragons.');
            return $this->redirectToRoute('app_character_shop');
        }

        // Procéder à l'achat
        $character->setMoney($character->getMoney() - $item->getPrice());

        if (!$item->isInfiniteStock()) {
            $item->setStock($item->getStock() - 1);
        }

        // Ajouter l'item au personnage
        $characterItem = null;
        foreach ($character->getItems() as $ci) {
            if ($ci->getItem() === $item) {
                $characterItem = $ci;
                break;
            }
        }

        if ($characterItem) {
            $characterItem->setAmount($characterItem->getAmount() + 1);
        } else {
            $characterItem = new CharacterItem();
            $characterItem->setCharacter($character);
            $characterItem->setItem($item);
            $characterItem->setAmount(1);
            $entityManager->persist($characterItem);
        }

        // Log
        $this->createLog($manager, $hub, $character, "Achat au shop : " . $item->getName(), -$item->getPrice());

        $entityManager->flush();

        // Notification Mercure pour le shop (mise à jour des stocks pour tous)
        $hub->publish(new Update(
            'https://archadia.net/shop',
            json_encode(['update' => true])
        ));

        return $this->redirectToRoute('app_character_shop');
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
