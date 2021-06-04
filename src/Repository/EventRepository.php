<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function search(array $s = null)
    {
        $qb =  $this->createQueryBuilder('e')
            // ->orderBy('e.id', 'ASC')
            ;

        if(isset($s['place']) && $s['place'] instanceof Place){
            $qb->andWhere('e.place = :place')
                ->setParameter('place', $s['place']->getId());
        }

        if(isset($s['eventType']) && $s['eventType'] !== null && count($s['eventType']) > 0 ){
            $qb->andWhere('e.eventType IN (:type)')
                ->setParameter('type', $s['eventType']);
        }

        if(isset($s['eventAt']) && $s['eventAt'] !== null){
            $qb->andWhere('e.eventAt BETWEEN :dateS AND :dateE')
                ->setParameter(
                    'dateS' , \DateTime::createFromFormat('d/m/Y', $s['eventAt'])->setTime(0,0,0)
                )
                ->setParameter(
                    'dateE', \DateTime::createFromFormat('d/m/Y', $s['eventAt'])->setTime(23,59,59)
                )
            ;
        }

        $qb
            ->andWhere('e.isPublished = 1')
            ->orderBy('e.eventAt', 'ASC')
        ;

        return $qb ->getQuery()
            ->getResult();
    }

    public function getEventsToModerate()
    {
        $query = $this->createQueryBuilder('e')
            ->where("e.isPublished = 0")
            ->orderBy("e.eventAt", "ASC")
        ;

        return $query->getQuery()->getResult();
    }
}
