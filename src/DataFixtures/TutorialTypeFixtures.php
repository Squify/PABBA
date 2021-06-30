<?php

namespace App\DataFixtures;

use App\Entity\TutorialType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TutorialTypeFixtures extends Fixture
{
    public const TUTORIAL_TYPE_JARDINAGE = 'tutorial-type-jardinage';
    public const TUTORIAL_TYPE_CUISINE = 'tutorial-type-cuisine';
    public const TUTORIAL_TYPE_RECYCLAGE = 'tutorial-type-recyclage';
    public const TUTORIAL_TYPE_OUTILS = 'tutorial-type-outils';

    public function load(ObjectManager $manager)
    {
        $tutorialTypeJardinage = new TutorialType();
        $tutorialTypeJardinage->setLabel("Jardinage");
        $manager->persist($tutorialTypeJardinage);

        $tutorialTypeCuisine = new TutorialType();
        $tutorialTypeCuisine->setLabel("Cuisine");
        $manager->persist($tutorialTypeCuisine);

        $tutorialTypeRecyclage = new TutorialType();
        $tutorialTypeRecyclage->setLabel("Recyclage");
        $manager->persist($tutorialTypeRecyclage);

        $tutorialTypeOutils = new TutorialType();
        $tutorialTypeOutils->setLabel("Prise en main d'outillage");
        $manager->persist($tutorialTypeOutils);

        $manager->flush();

        $this->addReference(self::TUTORIAL_TYPE_JARDINAGE, $tutorialTypeJardinage);
        $this->addReference(self::TUTORIAL_TYPE_CUISINE, $tutorialTypeCuisine);
        $this->addReference(self::TUTORIAL_TYPE_RECYCLAGE, $tutorialTypeRecyclage);
        $this->addReference(self::TUTORIAL_TYPE_OUTILS, $tutorialTypeOutils);
    }
}
