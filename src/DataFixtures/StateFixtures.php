<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;

class StateFixtures extends Fixture
{
    public const TOOL_STATES = 'states';

    public function load(ObjectManager $manager)
    {
        $states = ['Neuf', 'Utilisé', 'Usé' , 'Cassé'];
        foreach ($states as $label) {
            $state = new State();
            $state->setLabel($label);
            $manager->persist($state);
            $tabStates[]=$state;
        }

        $manager->flush();

        $this->addReference(self::TOOL_STATES, new ArrayCollection($tabStates));
    }
}
