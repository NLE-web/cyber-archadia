<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\CharacterAction;
use App\Entity\CharacterFeat;
use App\Entity\CharacterItem;
use App\Entity\CharacterSkill;
use App\Entity\Edgerunner;
use App\Entity\Feat;
use App\Entity\ImageFile;
use App\Entity\Item;
use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('demo');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'password')
        );

        $userNoChar = new User();
        $userNoChar->setUsername('new_player');
        $userNoChar->setRoles(['ROLE_USER']);
        $userNoChar->setPassword(
            $this->passwordHasher->hashPassword($userNoChar, 'password')
        );
        $manager->persist($userNoChar);

        $avatar = new ImageFile();
        $avatar->setDisplayName('Avatar principal');
        $avatar->setStorageName('avatar.png');
        $manager->persist($avatar);

        $itemIllustration = new ImageFile();
        $itemIllustration->setDisplayName('Illustration objet');
        $itemIllustration->setStorageName('item.png');
        $manager->persist($itemIllustration);

        $edgerunner = new Edgerunner();
        $edgerunner->setNom('Vex');
        $edgerunner->setForce(6);
        $edgerunner->setDexterite(8);
        $edgerunner->setIntelligence(7);
        $edgerunner->setLifepoints(18); // FOR 6 * 3
        $edgerunner->setCyberpoints(5);
        $edgerunner->setStresspoints(0);
        $edgerunner->setLostlife(4);
        $edgerunner->setLostcyber(1);
        $edgerunner->setIsActive(true);
        $edgerunner->setPlayer($user);
        $edgerunner->setAvatar($avatar);
        $edgerunner->setMoney(5000);
        $edgerunner->setXp(20);
        $edgerunner->setHumanityLoss(0);

        $hacking = new Skill();
        $hacking->setName('hacking');
        $hacking->setMainStat('intelligence');
        $hacking->setXpcost(10);

        $lame = new Skill();
        $lame->setName('lame');
        $lame->setMainStat('dexterite');
        $lame->setXpcost(8);

        $ballistique = new Skill();
        $ballistique->setName('ballistique');
        $ballistique->setMainStat('dexterite');
        $ballistique->setXpcost(12);

        $characterSkillHacking = new CharacterSkill();
        $characterSkillHacking->setCharacter($edgerunner);
        $characterSkillHacking->setSkill($hacking);
        $characterSkillHacking->setLevel(3);

        $characterSkillLame = new CharacterSkill();
        $characterSkillLame->setCharacter($edgerunner);
        $characterSkillLame->setSkill($lame);
        $characterSkillLame->setLevel(2);

        $characterSkillBallistique = new CharacterSkill();
        $characterSkillBallistique->setCharacter($edgerunner);
        $characterSkillBallistique->setSkill($ballistique);
        $characterSkillBallistique->setLevel(4);

        $edgerunner->addSkill($characterSkillHacking);
        $edgerunner->addSkill($characterSkillLame);
        $edgerunner->addSkill($characterSkillBallistique);

        $grenade = new Item();
        $grenade->setName('Grenade frag');
        $grenade->setType(Item::TYPE_CONSOMMABLE);
        $grenade->setIllustration($itemIllustration);
        $grenade->setIsConsume(true);
        $grenade->setPrice(120);
        $grenade->setChargePrice(120);
        $grenade->setDescription('Grenade explosive à fragmentation');
        $grenade->setIsLegal(false);
        $grenade->setIsCumbersome(false);
        $grenade->setStock(10);
        $grenade->setIsInfiniteStock(false);

        $medkit = new Item();
        $medkit->setName('Medkit');
        $medkit->setType(Item::TYPE_CONSOMMABLE);
        $medkit->setIllustration($itemIllustration);
        $medkit->setIsConsume(true);
        $medkit->setPrice(90);
        $medkit->setChargePrice(90);
        $medkit->setDescription('Kit de soin portable');
        $medkit->setIsLegal(true);
        $medkit->setIsCumbersome(false);
        $medkit->setIsInfiniteStock(true);

        $ammo = new Item();
        $ammo->setName('Munitions 9mm');
        $ammo->setType(Item::TYPE_CONSOMMABLE);
        $ammo->setIllustration($itemIllustration);
        $ammo->setIsConsume(true);
        $ammo->setPrice(35);
        $ammo->setChargePrice(35);
        $ammo->setDescription('Boîte de munitions standard');
        $ammo->setIsLegal(true);
        $ammo->setIsCumbersome(false);

        $lockpick = new Item();
        $lockpick->setName('Kit de crochetage');
        $lockpick->setType(Item::TYPE_DIVERS);
        $lockpick->setIllustration($itemIllustration);
        $lockpick->setIsConsume(false);
        $lockpick->setPrice(150);
        $lockpick->setChargePrice(0);
        $lockpick->setDescription('Outil de piratage mécanique discret');
        $lockpick->setIsLegal(false);
        $lockpick->setIsCumbersome(false);

        $stim = new Item();
        $stim->setName('Stimpack');
        $stim->setType(Item::TYPE_CONSOMMABLE);
        $stim->setIllustration($itemIllustration);
        $stim->setIsConsume(true);
        $stim->setPrice(75);
        $stim->setChargePrice(75);
        $stim->setDescription('Injecteur de stimulation nerveuse');
        $stim->setIsLegal(true);
        $stim->setIsCumbersome(false);

        $katana = new Item();
        $katana->setName('Katana thermique');
        $katana->setType(Item::TYPE_EQUIPEMENT);
        $katana->setIllustration($itemIllustration);
        $katana->setIsConsume(false);
        $katana->setPrice(1200);
        $katana->setChargePrice(0);
        $katana->setDescription('Lame élégante capable de trancher l\'acier.');
        $katana->setIsLegal(false);
        $katana->setIsCumbersome(true);
        $manager->persist($katana);

        $vest = new Item();
        $vest->setName('Veste pare-balles');
        $vest->setType(Item::TYPE_EQUIPEMENT);
        $vest->setIllustration($itemIllustration);
        $vest->setIsConsume(false);
        $vest->setPrice(450);
        $vest->setChargePrice(0);
        $vest->setDescription('Protection standard contre les petits calibres.');
        $vest->setIsLegal(true);
        $vest->setIsCumbersome(true);
        $manager->persist($vest);

        $characterGrenade = new CharacterItem();
        $characterGrenade->setCharacter($edgerunner);
        $characterGrenade->setItem($grenade);
        $characterGrenade->setAmount(2);

        $characterMedkit = new CharacterItem();
        $characterMedkit->setCharacter($edgerunner);
        $characterMedkit->setItem($medkit);
        $characterMedkit->setAmount(1);

        $characterAmmo = new CharacterItem();
        $characterAmmo->setCharacter($edgerunner);
        $characterAmmo->setItem($ammo);
        $characterAmmo->setAmount(3);

        $characterLockpick = new CharacterItem();
        $characterLockpick->setCharacter($edgerunner);
        $characterLockpick->setItem($lockpick);
        $characterLockpick->setAmount(1);

        $characterStim = new CharacterItem();
        $characterStim->setCharacter($edgerunner);
        $characterStim->setItem($stim);
        $characterStim->setAmount(2);

        $characterKatana = new CharacterItem();
        $characterKatana->setCharacter($edgerunner);
        $characterKatana->setItem($katana);
        $characterKatana->setAmount(1);

        $edgerunner->addItem($characterGrenade);
        $edgerunner->addItem($characterMedkit);
        $edgerunner->addItem($characterAmmo);
        $edgerunner->addItem($characterLockpick);
        $edgerunner->addItem($characterStim);

        /*
         * ACTIONS
         */
        $tirPrecis = new Action();
        $tirPrecis->setName('Tir précis');
        $tirPrecis->setType('combat');
        $tirPrecis->setItem(null);
        $tirPrecis->setDescription('Effectue un tir précis avec une arme balistique.');
        $tirPrecis->setUsage(Action::USAGE_ACTION);
        $tirPrecis->setCout(2);
        $manager->persist($tirPrecis);

        $attaqueLame = new Action();
        $attaqueLame->setName('Attaque de lame');
        $attaqueLame->setType('combat');
        $attaqueLame->setItem(null);
        $attaqueLame->setDescription('Frappe rapide au corps à corps avec une arme de lame.');
        $attaqueLame->setUsage(Action::USAGE_RAPIDE);
        $attaqueLame->setCout(1);
        $manager->persist($attaqueLame);

        $hackRapide = new Action();
        $hackRapide->setName('Hack rapide');
        $hackRapide->setType('tech');
        $hackRapide->setItem(null);
        $hackRapide->setDescription('Tente une intrusion rapide sur un système ou un implant.');
        $hackRapide->setUsage(Action::USAGE_RAPIDE);
        $hackRapide->setMaxUse(3);
        $hackRapide->setUses(3);
        $hackRapide->setCout(1);
        $manager->persist($hackRapide);

        $couvert = new Action();
        $couvert->setName('Se mettre à couvert');
        $couvert->setType('tactique');
        $couvert->setItem(null);
        $couvert->setDescription('Réduit l’exposition en prenant une position défensive.');
        $couvert->setUsage(Action::USAGE_RAPIDE);
        $couvert->setCout(0);
        $manager->persist($couvert);

        $lancerGrenade = new Action();
        $lancerGrenade->setName('Lancer grenade');
        $lancerGrenade->setType('objet');
        $lancerGrenade->setItem($grenade);
        $lancerGrenade->setDescription('Lance une grenade frag sur une zone ciblée.');
        $lancerGrenade->setUsage(Action::USAGE_ACTION);
        $lancerGrenade->setCout(1);
        $manager->persist($lancerGrenade);

        $utiliserMedkit = new Action();
        $utiliserMedkit->setName('Utiliser medkit');
        $utiliserMedkit->setType('objet');
        $utiliserMedkit->setItem($medkit);
        $utiliserMedkit->setDescription('Utilise un kit de soin pour récupérer des points de vie.');
        $utiliserMedkit->setUsage(Action::USAGE_RAPIDE);
        $utiliserMedkit->setCout(1);
        $manager->persist($utiliserMedkit);

        $injecterStim = new Action();
        $injecterStim->setName('Injecter stimpack');
        $injecterStim->setType('objet');
        $injecterStim->setItem($stim);
        $injecterStim->setDescription('Injecte un stimulant pour obtenir un bonus temporaire.');
        $injecterStim->setUsage(Action::USAGE_RAPIDE);
        $injecterStim->setMaxUse(1);
        $injecterStim->setUses(1);
        $injecterStim->setCout(0);
        $manager->persist($injecterStim);

        // Nouvelles actions liées aux Feats
        $vampirismeMental = new Action();
        $vampirismeMental->setName('Vampirisme mental');
        $vampirismeMental->setType('tech');
        $vampirismeMental->setDescription('Draine l\'énergie mentale pour réduire le stress.');
        $vampirismeMental->setUsage(Action::USAGE_RAPIDE);
        $vampirismeMental->setCout(1);
        $manager->persist($vampirismeMental);

        $surgeAdrenaline = new Action();
        $surgeAdrenaline->setName('Surge d\'adrénaline');
        $surgeAdrenaline->setType('tactique');
        $surgeAdrenaline->setDescription('Un boost de puissance temporaire.');
        $surgeAdrenaline->setUsage(Action::USAGE_RAPIDE);
        $surgeAdrenaline->setCout(0);
        $manager->persist($surgeAdrenaline);

        $characterActionTirPrecis = new CharacterAction();
        $characterActionTirPrecis->setCharacter($edgerunner);
        $characterActionTirPrecis->setAction($tirPrecis);

        $characterActionAttaqueLame = new CharacterAction();
        $characterActionAttaqueLame->setCharacter($edgerunner);
        $characterActionAttaqueLame->setAction($attaqueLame);

        $characterActionHackRapide = new CharacterAction();
        $characterActionHackRapide->setCharacter($edgerunner);
        $characterActionHackRapide->setAction($hackRapide);

        $characterActionCouvert = new CharacterAction();
        $characterActionCouvert->setCharacter($edgerunner);
        $characterActionCouvert->setAction($couvert);

        $characterActionGrenade = new CharacterAction();
        $characterActionGrenade->setCharacter($edgerunner);
        $characterActionGrenade->setAction($lancerGrenade);

        $characterActionMedkit = new CharacterAction();
        $characterActionMedkit->setCharacter($edgerunner);
        $characterActionMedkit->setAction($utiliserMedkit);

        $characterActionStim = new CharacterAction();
        $characterActionStim->setCharacter($edgerunner);
        $characterActionStim->setAction($injecterStim);

        $edgerunner->addAction($characterActionTirPrecis);
        $edgerunner->addAction($characterActionAttaqueLame);
        $edgerunner->addAction($characterActionHackRapide);
        $edgerunner->addAction($characterActionCouvert);
        $edgerunner->addAction($characterActionGrenade);
        $edgerunner->addAction($characterActionMedkit);
        $edgerunner->addAction($characterActionStim);

        $manager->persist($user);
        $manager->persist($avatar);
        $manager->persist($itemIllustration);
        $manager->persist($edgerunner);

        $manager->persist($hacking);
        $manager->persist($lame);
        $manager->persist($ballistique);

        $manager->persist($characterSkillHacking);
        $manager->persist($characterSkillLame);
        $manager->persist($characterSkillBallistique);

        $manager->persist($grenade);
        $manager->persist($medkit);
        $manager->persist($ammo);
        $manager->persist($lockpick);
        $manager->persist($stim);

        $manager->persist($characterGrenade);
        $manager->persist($characterMedkit);
        $manager->persist($characterAmmo);
        $manager->persist($characterLockpick);
        $manager->persist($characterStim);

        $manager->persist($characterActionTirPrecis);
        $manager->persist($characterActionAttaqueLame);
        $manager->persist($characterActionHackRapide);
        $manager->persist($characterActionCouvert);
        $manager->persist($characterActionGrenade);
        $manager->persist($characterActionMedkit);
        $manager->persist($characterActionStim);

        $adrenaline = new Feat();
        $adrenaline->setName('Adrénaline');
        $adrenaline->setDescription('Lorsque vous utilisez un stimpack, vous récupérez également 2 points de vie.');
        $adrenaline->addAction($injecterStim);
        $adrenaline->addAction($surgeAdrenaline);
        $adrenaline->setXpcost(15);
        $manager->persist($adrenaline);

        $empoisonneur = new Feat();
        $empoisonneur->setName('Empoisonneur');
        $empoisonneur->setDescription('Vos attaques de l\'âme infligent des dégâts toxiques persistants.');
        $empoisonneur->addAction($attaqueLame);
        $empoisonneur->setXpcost(10);
        $manager->persist($empoisonneur);

        $lanceurCouteaux = new Feat();
        $lanceurCouteaux->setName('Lanceur de couteaux');
        $lanceurCouteaux->setDescription('Vous pouvez lancer des couteaux avec une précision mortelle.');
        $lanceurCouteaux->addAction($attaqueLame);
        $lanceurCouteaux->setXpcost(8);
        $manager->persist($lanceurCouteaux);

        $psyVampire = new Feat();
        $psyVampire->setName('Psy Vampire');
        $psyVampire->setDescription('Vos hacks réussis drainent l\'énergie mentale de vos cibles pour réduire votre stress.');
        $psyVampire->addAction($hackRapide);
        $psyVampire->addAction($vampirismeMental);
        $psyVampire->setXpcost(12);
        $manager->persist($psyVampire);

        $mecanicien = new Feat();
        $mecanicien->setName('Mécanicien');
        $mecanicien->setDescription('Vous réparez les objets et drones deux fois plus vite.');
        $mecanicien->setXpcost(10);
        $manager->persist($mecanicien);

        $characterFeatAdrenaline = new CharacterFeat();
        $characterFeatAdrenaline->setCharacter($edgerunner);
        $characterFeatAdrenaline->setFeat($adrenaline);
        $manager->persist($characterFeatAdrenaline);

        $edgerunner->addFeat($characterFeatAdrenaline);

        $manager->flush();
    }
}
