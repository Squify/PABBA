<?php

namespace App\Repository;

use App\Entity\ToolType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToolType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToolType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToolType[]    findAll()
 * @method ToolType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToolType::class);
    }

    // /**
    //  * @return SuppliesType[] Returns an array of SuppliesType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuppliesType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
