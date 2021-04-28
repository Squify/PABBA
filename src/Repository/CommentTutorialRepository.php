<?php

namespace App\Repository;

use App\Entity\CommentTutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentTutorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentTutorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentTutorial[]    findAll()
 * @method CommentTutorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTutorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentTutorial::class);
    }

    // /**
    //  * @return CommentTutorial[] Returns an array of CommentTutorial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentTutorial
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
