<?php

namespace App\Repository;

use App\Entity\Rent;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{

    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Rent::class);
        $this->manager = $manager;
    }

    /**
     * @param $user
     * @return Rent[] Returns all Rent objects
     */
    public function findAllByOwnerIdOrderByDate($user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.owner = :val')
            ->setParameter('val', $user)
            ->orderBy('r.rentAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $user
     * @return Rent[] Returns all loaned objects
     */
    public function findAllByRenterIdOrderByDate($user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.renter = :val')
            ->setParameter('val', $user)
            ->orderBy('r.rentAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function countRenderToValidate(User $user)
    {

        // $result = $this
        //     ->createQueryBuilder("rent")
        //     // ->leftJoin('App\Entity\Render', 'render', 'WITH', 'rent.id = render.rent')
        //     // ->addSelect('r')
        //     ->leftJoin("rent.Renders")
        //     ->where("render.isValid = 0")
        //     ->andWhere("rent.owner = :userId")
        //     ->setParameter("userId", $user->getId())
        //     ->getQuery()
            // ->getResult()
        // ;

        //         SELECT rent.owner_id from rent
        // LEFT JOIN render on rent.id=render.rent_id
        // WHERE render.is_valid = 0 AND
        // rent.owner_id = 20

        $result = $this->manager->createQuery("SELECT COUNT(rent.owner) FROM App\Entity\Rent rent
        LEFT JOIN App\Entity\Render render WITH render MEMBER OF rent.renders
        WHERE render.isValid = 0
        AND rent.owner = :userId
        ")
        ->setParameter("userId", $user->getId())
        ->getSingleResult()
        ;
        // ->getResult();

        // dd($result[1]);

        return $result[1];

    }

    /*
    public function findOneBySomeField($value): ?Rent
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
