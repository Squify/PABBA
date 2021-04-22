<?php

namespace App\Repository;

use App\Entity\Supply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Supply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supply[]    findAll()
 * @method Supply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supply::class);
    }

    // /**
    //  * @return Supply[] Returns an array of Supply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Supply
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
