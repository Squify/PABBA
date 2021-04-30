<?php

namespace App\Repository;

use App\Entity\Tutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tutorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutorial[]    findAll()
 * @method Tutorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutorial::class);
    }

    /**
     * @return Tutorial[] Returns an array of Tutorial objects
     */
    public function findByType($types)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->add('where', $qb->expr()->in('t.type', ':types'))
            ->setParameter('types', $types)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // public function findSearchResults($title = null, $category = null, $hasVideo = null)
    public function findSearchResults($data)
    {

        dump($data);

        $qb = $this->createQueryBuilder('t');
        $qb->where("t.disable = 0");

        foreach ($data as $key => $value) {
            if ($value != null && $value != false) {
                if ($key == "videoName") {
                    $qb->andWhere("t.videoName is not NULL");
                } elseif ($key == "type") {
                    $qb->andWhere("t.type = :value")
                        ->setParameter("value", $value->getId());
                } else {
                    $qb
                        ->andWhere("t.{$key} LIKE :value")
                        ->setParameter("value", "%" . $value . "%");
                }
            }
        }

        // dd($qb->getDQL(), $qb->getParameters());

        return $qb
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Tutorial[] Returns an array of Tutorial objects
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
    public function findOneBySomeField($value): ?Tutorial
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
