<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param $user
     * @return Item[] Returns all Item objects
     */
    public function findAllByOwnerId($user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.owner = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param [type] $filters
     * @return Item[] Returns Item objects based on selected filters
     */
    public function findByFilters($filters)
    {

        $query = $this->createQueryBuilder('i')
            ->where('i.status = 0');

        if ($filters["state"]) {
            $query->andWhere('i.state = :stateId')
                ->setParameter("stateId", $filters["state"]);
        }

        if ($filters["toolType"]) {
            $query->andWhere('i.category = :toolTypeId')
                ->setParameter("toolTypeId", $filters["toolType"]);
        }

        if ($filters["name"]) {
            $query->andWhere('i.name LIKE :name')
                ->setParameter("name", '%'.$filters["name"].'%');
        }

        return $query->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
