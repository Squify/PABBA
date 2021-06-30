<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const ADMIN = 'admin';
    public const MODERATOR = 'moderator';
    public const USERS = 'users';
    
    public function load(ObjectManager $manager)
    {
        $users = [];

        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($admin, "admin"))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('admin');
        $manager->persist($admin);
        $this->addReference(self::ADMIN, $admin);


        $moderator = new User();
        $moderator->setEmail("moderator@moderator.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($moderator, "moderator"))
            ->setRoles(["ROLE_MODERATOR"])
            ->setFirstname('moderator');
        $manager->persist($moderator);
        $this->addReference(self::MODERATOR, $moderator);

        for ($i=0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user$i@user.fr")
                ->setEnable(1)
                ->setPassword($this->encoder->encodePassword($user, "user"))
                ->setRoles(["ROLE_USER"])
                ->setFirstname("user$i");
            $manager->persist($user);
            $users[] = $user;
        }
        $this->addReference(self::USERS, $users);

        $manager->flush();
    }
}
