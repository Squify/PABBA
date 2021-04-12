<?php

namespace App\DataFixtures;

use App\Entity\Place;
use App\Entity\Type;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
//        User Fixture
        $user = new User();
        $user->setEmail("user@user.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($user, "user"))
            ->setRoles(["ROLE_USER"])
            ->setFirstname('user');
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($admin, "admin"))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('admin');
        $manager->persist($admin);


//       Type Fixture
        $type = [];
        $jardin = new Type();
        $jardin->setLabel("jardin");
        $manager->persist($jardin);
        $type[]=$jardin;

        $evenement = new Type();
        $evenement->setLabel("evenement");
        $manager->persist($evenement);
        $type[]=$evenement;

        $pointDeCollecte = new Type();
        $pointDeCollecte->setLabel("point de collecte");
        $manager->persist($pointDeCollecte);
        $type[]=$pointDeCollecte;

//        Lieu fixture
        for($i=0; $i<15; $i++) {
            $place = new Place();
            $place->setUser($admin)
                ->setType($faker->randomElement($type))
                ->setAddress($faker->address)
                ->setIsValid(0)
                ->setOpen($faker->paragraph);

            $manager->persist($place);
        }

        $manager->flush();
    }
}
