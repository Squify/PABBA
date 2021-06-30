<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Place;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{
    public const PLACES = 'places';
    public const PLACES_EVENT = 'places-event';

    public function load(ObjectManager $manager) 
    {
        $faker = Factory::create();

        $items = [
            "address" => [
                "La Source Pavillons Campus, 45100 Orléans",
                " Rue des Iris, 45590 Saint-Cyr-en-Val",
                "Rue de Vendôme, 45100 Orléans",
                "Avenue du Parc Floral, 45100 Orléans"

            ],
            "paragraph" => [
                "Ouvert de 12h à 18h tous les Lundi, Jeudi et Vendredi",
                "Ouvert de 8h à 12h tout les jours de semaine, et de 8h à 18h les weekends",
                "Ouvert de 14h à 17h tout le Mercredi, Jeudi et Vendredi, fermé les weekends",
                "Ouvert de 14h à 17h tout le Mercredi, Jeudi et Vendredi, fermé les weekends",
                "Ouvert tous les jours de 10h à 19h"
            ],
            "isValid" => [true, true, false, true],
            "latitude" => [47.844604, 47.827593, 47.845617, 47.847527, ],
            "longitude" => [1.938078, 1.964678, 1.929575, 1.937581],
        ];

        for($i=0; $i<sizeof($items["address"]); $i++) {
            $place = new Place();
            $place->setUser($faker->randomElement($this->getReference(UserFixtures::USERS)))
                ->setType($faker->randomElement($this->getReference(PlaceTypeFixtures::PLACE_TYPES)))
                ->setAddress($items["address"][$i])
                ->setIsValid($items["paragraph"][$i])
                ->setComments($items["isValid"][$i])
                ->setLatitude($items["latitude"][$i])
                ->setLongitude($items["latitude"][$i]);
            $manager->persist($place);
            $places[] = $place;
        }

        $itemsEvent = [
            "address" => [
                "Restaurant le Lac, Rue de Blois, 45100 Orléans",
            ],
            "paragraph" => [
                "Information sur la culture du miel et les insecticides"
            ],
            "isValid" => [true,],
            "latitude" => [47.843800, ],
            "longitude" => [1.932255, ],
        ];

        for($i=0; $i<sizeof($itemsEvent["address"]); $i++) {
            $place = new Place();
            $place->setUser($faker->randomElement($this->getReference(UserFixtures::USERS)))
                ->setType($faker->randomElement($this->getReference(PlaceTypeFixtures::PLACE_TYPES_EVENT)))
                ->setAddress($items["address"][$i])
                ->setIsValid($items["paragraph"][$i])
                ->setComments($items["isValid"][$i])
                ->setLatitude($items["latitude"][$i])
                ->setLongitude($items["latitude"][$i]);
            $manager->persist($place);
            $placesEvent[] = $place;
        }

        $manager->flush();

        $this->addReference(self::PLACES, $places);
        $this->addReference(self::PLACES_EVENT, $placesEvent);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PlaceTypeFixtures::class,
        ];
    }
}
