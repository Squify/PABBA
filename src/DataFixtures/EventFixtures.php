<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Event;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $items = [
            'title' => [
                'Récupération de miel',
                'Attelier permaculture',
                'Garden Partyyyy !',
                'Vente de poivrons',
                'Vente de pomme de terre',
            ],
            'description' => [
                "
                Démonstration des apiculteurs Orléanais lors de la récolte du miel,
                vous aurez également la possibilité de goûter ce miel bio ! 
                ",
                "
                Vous avez la chance de pouvoir assister à une conference concernant la permaculture,
                la permaculture est la façon la plus respectueuse de l'environnement sur la durée.
                Apprenez vous aussi à cultiver respectueusement, vos légumes vous remercierons ! 
                ",
                "
                Garden Party ! Venez nombreux avec votre bonne humeur pour discuter de notre passion : 
                le jardinage ! 
                ",
                "
                Venez vendre vos plus beaux poivrons à cette évenement, vous pourrez également 
                partager quelles sont vos poivrons favoris, et discruter autour de vos préferences
                pour les déguster ! En salades ou à la plancha, les poivrons sont toujours bon ! 
                ",
                "
                Venez vendre vos plus belles pommes de terre à cette évenement, vous pourrez également 
                partager quelles sont vos patates favorites, et discruter autour de vos préferences
                pour les déguster ! En frites ou en purées, les pommes de terres seront vos 
                amies pour toujours ! 
                "

            ],
            'eventType' => [
                $this->getReference(EventTypeFixtures::EVENT_TYPE_DEMONSTRATION),
                $this->getReference(EventTypeFixtures::EVENT_TYPE_TUTO),
                $this->getReference(EventTypeFixtures::EVENT_TYPE_GARDEN_PARTY),
                $this->getReference(EventTypeFixtures::EVENT_TYPE_PRODUCT),
                $this->getReference(EventTypeFixtures::EVENT_TYPE_PRODUCT),
            ]
        ];

        for ($i = 0; $i<sizeof($items['title']); $i++) {
            $event = new Event();
            $event
                ->setTitle($items['title'][$i])
                ->setDescription($items['description'][$i])
                ->setEventAt($faker->dateTimeBetween('-2 days', '+1 week'))
                ->setIsPublished($faker->boolean(75))
                ->setPlace($faker->randomElement($this->getReference(PlaceFixtures::PLACES_EVENT)))
                ->setEventType($items['eventType'][$i]);

            // Ajout des participants
            $participants = $faker->randomElements($this->getReference(UserFixtures::USERS), rand(2, 8));
            foreach ($participants as $participant) {
                $event->addParticipant($participant);
            }
            // Selection des organisateurs parmi les participants
            $organisers = $faker->randomElements($event->getParticipants(), rand(2, $event->getParticipants()->count() / 2));
            foreach ($organisers as $organiser) {
                $event->addOrganiser($organiser);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PlaceFixtures::class,
            EventTypeFixtures::class,
        ];
    }
}
