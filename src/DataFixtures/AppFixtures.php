<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('student');
        $user->setEmail('student@gmail.com');
        $user->setGuard('1');
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                'unlockme123'
            )
        );
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('instructor');
        $user2->setEmail('instructor@gmail.com');
        $user2->setGuard('1');
        $user2->setPassword(
            $this->hasher->hashPassword(
                $user,
                'unlockme123'
            )
        );
        $user2->setRoles(['ROLE_USER']);

        $manager->persist($user2);

        $manager->flush();
    }
}

