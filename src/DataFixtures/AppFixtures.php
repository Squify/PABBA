<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Goal;
use App\Entity\Partner;
use Faker\Factory;
use App\Entity\Item;
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Rank;
use App\Entity\Reward;
use App\Entity\State;
use App\Entity\ToCount;
use App\Entity\ToolType;
use App\Entity\Tutorial;

use App\Entity\TutorialType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Reward Fixtures
        $rewards = [];
        for ($i=0; $i < 5; $i++) {
            $reward = new Reward();
            $reward->setIsActive(true)
                ->setLink($faker->words(3, true))
                ->setName($faker->word())
                ->setPartner($faker->randomElement($partners))
                ->setDescription($faker->paragraph());
            $rewards[] = $reward;
            $manager->persist($reward);
        }

        // Goal Fixtures
        for ($i=0; $i < 5; $i++) {
            $goal = new Goal();
            $goal->setLibelle($faker->name())
                ->setToCount($faker->randomElement($toCounts))
                ->setReward($faker->randomElement($rewards))
                ->setObjective($faker->randomDigitNot([0, null]))
                ->setDescription($faker->paragraph());
            $manager->persist($goal);
        }        

        $manager->flush();
    }
}
