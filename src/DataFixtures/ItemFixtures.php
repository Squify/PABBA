<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Item;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        $labels = ['bol', 'pince', 'brouette', 'scie', 'pince', 'brouette', 'scie', 'perceuse', 'pince', 'marteau', 'scie', 'pelle', 'perceuse', 'pelle','perceuse', 'pelle', 'marteau', 'echelle', 'echelle', 'ciseau'];
        for ($i=0; $i < sizeof($labels); $i++) {
            $item = new Item();
            $item->setName($labels.' '.$i)
                ->setOwner($faker->randomElement($this->getReference(UserFixtures::USERS)))
                ->setState($faker->randomElement($this->getReference(StateFixtures::TOOL_STATES)))
                ->setStatus(0)
                ->setCategory($faker->randomElement($this->getReference($labels)));
            $manager->persist($item);
        }

        $manager->flush();
    }
}
