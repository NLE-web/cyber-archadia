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
        $actionsArray = [];
        $itemAmounts = [];
        $characterItemsByItemId = [];
        foreach ($character->getItems() as $item) {
            $itemAmounts[$item->getItem()->getId()] = $item->getAmount();
            $characterItemsByItemId[$item->getItem()->getId()] = $item;
        }

        foreach ($character->getActions() as $action)
        {
            $actionObj = $action->getAction();
            $type = $actionObj->getType();
            $actionId = $actionObj->getId();
            
            if (!isset($actionsArray[$type])) {
                $actionsArray[$type] = [];
            }

            $amount = null;
            $characterItemId = null;
            if ($actionObj->getItem() && $actionObj->getItem()->isConsume()) {
                $itemId = $actionObj->getItem()->getId();
                $amount = $itemAmounts[$itemId] ?? 0;
                $characterItemId = isset($characterItemsByItemId[$itemId]) ? $characterItemsByItemId[$itemId]->getId() : null;

                // Si l'objet lié à l'action est consommé et que le solde est <= 0, on n'affiche pas l'action
                if ($amount <= 0) {
                    continue;
                }
            }

            $actionsArray[$type][$actionId] = [
                'object' => $actionObj,
                'amount' => $amount,
                'characterItemId' => $characterItemId
            ];
        }
        foreach ($character->getItems() as $item)
        {
            if ($item->getItem()->getActions() != null)
            {
                foreach ($item->getItem()->getActions() as $action)
                {
                    $type = $action->getType();
                    $actionId = $action->getId();
                    
                    if (!isset($actionsArray[$type])) {
                        $actionsArray[$type] = [];
                    }

                    $amount = null;
                    if ($action->getItem() && $action->getItem()->isConsume()) {
                        $amount = $item->getAmount();
                        
                        // Si l'objet est consommé et que le solde est <= 0, on n'affiche pas l'action
                        if ($amount <= 0) {
                            continue;
                        }
                    }

                    $actionsArray[$type][$actionId] = [
                        'object' => $action,
                        'amount' => $amount,
                        'characterItemId' => $item->getId()
                    ];
                }
            }
        }
        return $this->render('main/actions.html.twig', [
            "character" => $character,
            "actions" => $actionsArray,
        ]);
    }

    #[Route('/character/action/decrement/{id}/{type}', name: 'app_character_action_decrement')]
    public function decrementAction(ManagerRegistry $manager, int $id, string $type): Response
    {
        $entityManager = $manager->getManager();
        if ($type === 'action') {
            $action = $entityManager->getRepository(\App\Entity\Action::class)->find($id);
            if ($action && $action->getUses() > 0) {
                $action->setUses($action->getUses() - 1);
            }
        } elseif ($type === 'item') {
            $characterItem = $entityManager->getRepository(\App\Entity\CharacterItem::class)->find($id);
            if ($characterItem && $characterItem->getAmount() > 0) {
                $characterItem->setAmount($characterItem->getAmount() - 1);
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_character_actions');
    }

    #[Route('/character/actions/reset', name: 'app_character_actions_reset')]
    public function resetActions(ManagerRegistry $manager): Response
    {
        $character = $manager->getRepository(Edgerunner::class)->findOneBy(['player' => $this->getUser()]);
        if (!$character) {
            return $this->redirectToRoute("app_main");
        }

        $entityManager = $manager->getManager();
        
        // Reset direct character actions
        foreach ($character->getActions() as $characterAction) {
            $action = $characterAction->getAction();
            if ($action && $action->getMaxUse() !== null) {
                $action->setUses($action->getMaxUse());
            }
        }

        // Reset actions from character items
        foreach ($character->getItems() as $characterItem) {
            $item = $characterItem->getItem();
            if ($item) {
                foreach ($item->getActions() as $action) {
                    if ($action->getMaxUse() !== null) {
                        $action->setUses($action->getMaxUse());
                    }
                }
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_character_actions');
    }
}
