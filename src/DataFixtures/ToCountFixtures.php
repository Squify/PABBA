<?php

namespace App\DataFixtures;

use App\Entity\ToCount;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ToCountFixtures extends Fixture
{
    const TUTORIAL_SEEN = 'tutorials_seen';
    const TUTORIAL_CREATED = 'tutorials_created';
    const PLACES_SHARED = 'places_shared';
    const EVENT_CREATED = 'event_created';
    const EVENT_PARTICIPATED = 'event_participated';
    const TOOLS_SHARED = 'tools_shared';

    public function load(ObjectManager $manager)
    {

        $tutorials_seen = new ToCount();
        $tutorials_seen->setLibelle('Tutorials vue');
        $manager->persist($tutorials_seen);

        $tutorials_created = new ToCount();
        $tutorials_created->setLibelle('Tutorials créé');
        $manager->persist($tutorials_created);

        $places_shared = new ToCount();
        $places_shared->setLibelle('Places partagé');
        $manager->persist($places_shared);

        $event_created = new ToCount();
        $event_created->setLibelle('Évenements créé');
        $manager->persist($event_created);


        $event_participated = new ToCount();
        $event_participated->setLibelle('Évenements participé');
        $manager->persist($event_participated);

        $tools_shared = new ToCount();
        $tools_shared->setLibelle('Outils partagé');
        $manager->persist($tools_shared);

        $manager->flush();

        $this->addReference(self::TUTORIAL_SEEN, $tutorials_seen);
        $this->addReference(self::TUTORIAL_CREATED, $tutorials_created);
        $this->addReference(self::PLACES_SHARED, $places_shared);
        $this->addReference(self::EVENT_CREATED, $event_created);
        $this->addReference(self::EVENT_PARTICIPATED, $event_participated);
        $this->addReference(self::TOOLS_SHARED, $tools_shared);

    }
}
