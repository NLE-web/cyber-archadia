<?php

namespace App\DataFixtures;

use App\Entity\Edgerunner;
use App\Entity\ImageFile;
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

        $edgerunner = new Edgerunner();
        $edgerunner->setNom('Vex');
        $edgerunner->setForce(6);
        $edgerunner->setDexterite(8);
        $edgerunner->setIntelligence(7);
        $edgerunner->setLifepoints(20);
        $edgerunner->setCyberpoints(5);
        $edgerunner->setStresspoints(0);
        $edgerunner->setIsActive(true);
        $edgerunner->setPlayer($user);
        $edgerunner->setAvatar($avatar);

        $manager->persist($user);
        $manager->persist($avatar);
        $manager->persist($edgerunner);

        $manager->flush();
    }
}
