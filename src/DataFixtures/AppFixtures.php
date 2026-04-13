<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\CharacterAction;
use App\Entity\CharacterItem;
use App\Entity\CharacterSkill;
use App\Entity\Edgerunner;
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

        $avatar = new ImageFile();
        $avatar->setDisplayName('Avatar principal');
        $avatar->setStorageName('avatar.png');

        $itemIllustration = new ImageFile();
        $itemIllustration->setDisplayName('Illustration objet');
        $itemIllustration->setStorageName('item.png');

        $edgerunner = new Edgerunner();
        $edgerunner->setNom('Vex');
        $edgerunner->setForce(6);
        $edgerunner->setDexterite(8);
        $edgerunner->setIntelligence(7);
        $edgerunner->setLifepoints(20);
        $edgerunner->setCyberpoints(5);
        $edgerunner->setStresspoints(0);
        $edgerunner->setLostlife(4);
        $edgerunner->setLostcyber(1);
        $edgerunner->setIsActive(true);
        $edgerunner->setPlayer($user);
        $edgerunner->setAvatar($avatar);

        $hacking = new Skill();
        $hacking->setName('hacking');
        $hacking->setMainStat('intelligence');

        $lame = new Skill();
        $lame->setName('lame');
        $lame->setMainStat('dexterite');

        $ballistique = new Skill();
        $ballistique->setName('ballistique');
        $ballistique->setMainStat('dexterite');

        $characterSkillHacking = new CharacterSkill();
        $characterSkillHacking->setCharacter($edgerunner);
        $characterSkillHacking->setSkill($hacking);
        $characterSkillHacking->setLevel(3);
        $characterSkillHacking->setXp(120);

        $characterSkillLame = new CharacterSkill();
        $characterSkillLame->setCharacter($edgerunner);
        $characterSkillLame->setSkill($lame);
        $characterSkillLame->setLevel(2);
        $characterSkillLame->setXp(65);

        $characterSkillBallistique = new CharacterSkill();
        $characterSkillBallistique->setCharacter($edgerunner);
        $characterSkillBallistique->setSkill($ballistique);
        $characterSkillBallistique->setLevel(4);
        $characterSkillBallistique->setXp(210);

        $edgerunner->addSkill($characterSkillHacking);
        $edgerunner->addSkill($characterSkillLame);
        $edgerunner->addSkill($characterSkillBallistique);

        $grenade = new Item();
        $grenade->setName('Grenade frag');
        $grenade->setType('consommable');
        $grenade->setIllustration($itemIllustration);
        $grenade->setIsConsume(true);
        $grenade->setPrice(120);
        $grenade->setChargePrice(120);
        $grenade->setDescription('Grenade explosive à fragmentation');

        $medkit = new Item();
        $medkit->setName('Medkit');
        $medkit->setType('soin');
        $medkit->setIllustration($itemIllustration);
        $medkit->setIsConsume(true);
        $medkit->setPrice(90);
        $medkit->setChargePrice(90);
        $medkit->setDescription('Kit de soin portable');

        $ammo = new Item();
        $ammo->setName('Munitions 9mm');
        $ammo->setType('munition');
        $ammo->setIllustration($itemIllustration);
        $ammo->setIsConsume(true);
        $ammo->setPrice(35);
        $ammo->setChargePrice(35);
        $ammo->setDescription('Boîte de munitions standard');

        $lockpick = new Item();
        $lockpick->setName('Kit de crochetage');
        $lockpick->setType('outil');
        $lockpick->setIllustration($itemIllustration);
        $lockpick->setIsConsume(false);
        $lockpick->setPrice(150);
        $lockpick->setChargePrice(0);
        $lockpick->setDescription('Outil de piratage mécanique discret');

        $stim = new Item();
        $stim->setName('Stimpack');
        $stim->setType('boost');
        $stim->setIllustration($itemIllustration);
        $stim->setIsConsume(true);
        $stim->setPrice(75);
        $stim->setChargePrice(75);
        $stim->setDescription('Injecteur de stimulation nerveuse');

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

        $attaqueLame = new Action();
        $attaqueLame->setName('Attaque de lame');
        $attaqueLame->setType('combat');
        $attaqueLame->setItem(null);
        $attaqueLame->setDescription('Frappe rapide au corps à corps avec une arme de lame.');
        $attaqueLame->setUsage(Action::USAGE_RAPIDE);

        $hackRapide = new Action();
        $hackRapide->setName('Hack rapide');
        $hackRapide->setType('tech');
        $hackRapide->setItem(null);
        $hackRapide->setDescription('Tente une intrusion rapide sur un système ou un implant.');
        $hackRapide->setUsage(Action::USAGE_RAPIDE);
        $hackRapide->setMaxUse(3);
        $hackRapide->setUses(3);

        $couvert = new Action();
        $couvert->setName('Se mettre à couvert');
        $couvert->setType('tactique');
        $couvert->setItem(null);
        $couvert->setDescription('Réduit l’exposition en prenant une position défensive.');
        $couvert->setUsage(Action::USAGE_RAPIDE);

        $lancerGrenade = new Action();
        $lancerGrenade->setName('Lancer grenade');
        $lancerGrenade->setType('objet');
        $lancerGrenade->setItem($grenade);
        $lancerGrenade->setDescription('Lance une grenade frag sur une zone ciblée.');
        $lancerGrenade->setUsage(Action::USAGE_ACTION);

        $utiliserMedkit = new Action();
        $utiliserMedkit->setName('Utiliser medkit');
        $utiliserMedkit->setType('objet');
        $utiliserMedkit->setItem($medkit);
        $utiliserMedkit->setDescription('Utilise un kit de soin pour récupérer des points de vie.');
        $utiliserMedkit->setUsage(Action::USAGE_RAPIDE);

        $injecterStim = new Action();
        $injecterStim->setName('Injecter stimpack');
        $injecterStim->setType('objet');
        $injecterStim->setItem($stim);
        $injecterStim->setDescription('Injecte un stimulant pour obtenir un bonus temporaire.');
        $injecterStim->setUsage(Action::USAGE_RAPIDE);
        $injecterStim->setMaxUse(1);
        $injecterStim->setUses(1);

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

        $manager->persist($tirPrecis);
        $manager->persist($attaqueLame);
        $manager->persist($hackRapide);
        $manager->persist($couvert);
        $manager->persist($lancerGrenade);
        $manager->persist($utiliserMedkit);
        $manager->persist($injecterStim);

        $manager->persist($characterActionTirPrecis);
        $manager->persist($characterActionAttaqueLame);
        $manager->persist($characterActionHackRapide);
        $manager->persist($characterActionCouvert);
        $manager->persist($characterActionGrenade);
        $manager->persist($characterActionMedkit);
        $manager->persist($characterActionStim);

        $manager->flush();
    }
}
