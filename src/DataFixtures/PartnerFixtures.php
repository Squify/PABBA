<?php

namespace App\DataFixtures;

use App\Entity\Partner;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PartnerFixtures extends Fixture
{
    const PARTNERS = "partners";

    public function load(ObjectManager $manager)
    {
        foreach (['Bricorama', 'Leroy Merlin', 'Brico dépôt'] as $item) {
            $p = new Partner();
            $p->setName($item)
                ->setIsActive(true);
            $partners[] = $p;
            $manager->persist($p);
        }

        $manager->flush();

        $this->addReference(self::PARTNERS, $partners);
    }
}
