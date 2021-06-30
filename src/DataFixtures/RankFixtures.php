<?php

namespace App\DataFixtures;

use App\Entity\Rank;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RankFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 6; $i++){
            $rank = new Rank();
            $rank->setName('Niveau '. $i)
                ->setStart(($i * 150) - 150)
                ->setEnd($i * 150)
                ->setIsActive(1);
            $manager->persist($rank);
        }

        $manager->flush();
    }
}
