<?php

namespace App\Repository;

use App\Entity\WishMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishMap[]    findAll()
 * @method WishMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishMap::class);
    }

    // /**
    //  * @return WishMapController[] Returns an array of WishMapController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WishMapController
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
