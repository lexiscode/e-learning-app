<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('instructor');
        $user->setEmail('instructor@gmail.com');
        $user->setGuard('1');
        $user->setPassword('$2y$10$gKwRuu0rx2zm1wzHF.ZLIuqiyxtS6o11hVF8N8k4qtm5menTYAWm.');

        $manager->persist($user);
        $manager->flush();
    }
}
