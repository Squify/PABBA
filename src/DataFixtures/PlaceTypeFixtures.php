<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;

class PlaceTypeFixtures extends Fixture
{
    public const PLACE_TYPES = 'type';
    public const PLACE_TYPES_EVENT = 'place_event';

    public function load(ObjectManager $manager)
    {
        $typeLabels = ["Jardin", "Recyclage", "Point de collecte"];
        foreach ($typeLabels as $label) {
            $placeType = new Type();
            $placeType->setLabel($label);
            $manager->persist($placeType);
            $types[]=$placeType;
        }

        $placeEvent = new Type();
        $placeEvent->setLabel("Événement");
        $manager->persist($placeEvent);
        
        $manager->flush();

        $this->addReference(self::PLACE_TYPES, new ArrayCollection($types));
        $this->addReference(self::PLACE_TYPES_EVENT, $placeEvent);
    }
}
