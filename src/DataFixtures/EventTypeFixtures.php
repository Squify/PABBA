<?php

namespace App\DataFixtures;

use App\Entity\EventType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EventTypeFixtures extends Fixture
{
    const EVENT_TYPE_TUTO = "live-tutorial";
    const EVENT_TYPE_SPECIAL = "special";
    const EVENT_TYPE_PRODUCT = "product";
    const EVENT_TYPE_GARDEN_PARTY = "garden-party";
    const EVENT_TYPE_DEMONSTRATION = "demonstrate";

    public function load(ObjectManager $manager)
    {
        $tutorial = new EventType();
        $tutorial->setLabel("Tutoriel en live");
        $manager->persist($tutorial);
        
        $special = new EventType();
        $special->setLabel("Évènement spécial");
        $manager->persist($special);
        
        $product = new EventType();
        $product->setLabel("Partage de produit");
        $manager->persist($product);

        $garden = new EventType();
        $garden->setLabel("Garden party");
        $manager->persist($garden);

        $demonstrate = new EventType();
        $demonstrate->setLabel("Démonstration");
        $manager->persist($demonstrate);

        $manager->flush();

        $this->addReference(self::EVENT_TYPE_TUTO, $tutorial);
        $this->addReference(self::EVENT_TYPE_SPECIAL, $special);
        $this->addReference(self::EVENT_TYPE_PRODUCT, $product);
        $this->addReference(self::EVENT_TYPE_GARDEN_PARTY, $garden);
        $this->addReference(self::EVENT_TYPE_DEMONSTRATION, $demonstrate);
    }
}
