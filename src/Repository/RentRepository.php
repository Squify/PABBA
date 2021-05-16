<?php

namespace App\Repository;

use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
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
