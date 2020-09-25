<?php

namespace App\DataFixtures;

use App\Entity\Update;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $update = new Update();
            $update->setContent("content$i");
            $manager->persist($update);
        }
        $manager->flush();
    }
}
