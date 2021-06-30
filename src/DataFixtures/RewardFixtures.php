<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Reward;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RewardFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i=0; $i < 5; $i++) {
            $reward = new Reward();
            $reward->setIsActive(true)
                ->setLink($faker->words(3, true))
                ->setName($faker->word())
                ->setPartner($faker->randomElement($this->getReference(PartnerFixtures::PARTNERS)))
                ->setDescription($faker->paragraph());
            $rewards[] = $reward;
            $manager->persist($reward);
        }

        $manager->flush();
    }
}
