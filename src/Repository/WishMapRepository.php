<?php

namespace App\Repository;

use App\Entity\WishMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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


    public function wishMapsGetCategoryCount()
    {
        return $this->createQueryBuilder('wm')
            ->innerJoin('wm.category', 'cat')
            ->innerJoin('wm.user', 'u')
            ->select('cat.name, COUNT(wm.category) AS count')
            ->andWhere('cat.id = wm.category')
            ->andWhere('u.isPrivate!=1')
            ->andWhere('wm.isArchived != 1')
            ->groupBy('wm.category')
            ->getQuery()
            ->getScalarResult();
    }

    public function wishMapsGetNotPrivateAccs()
    {
        return $this->createQueryBuilder('wm')
            ->innerJoin('wm.user', 'u')
            ->innerJoin('wm.category', 'c')
            ->leftJoin('wm.comments', 'com')
            ->select('wm', 'c', 'COUNT(com.id) as count')
            ->andWhere('u.isPrivate != 1')
            ->andWhere('wm.isArchived != 1')
            ->groupBy('wm.id')
            ->getQuery()
            ->getScalarResult();
    }

    public function wishMapsGetNotPrivateAccs_withCategoryName(string $categoryName)
    {
        return $this->createQueryBuilder('wm')
            ->innerJoin('wm.user', 'u')
            ->innerJoin('wm.category', 'c')
            ->leftJoin('wm.comments', 'com')
            ->select('wm', 'c', 'COUNT(com.id) as count')
            ->andWhere('c.name = :name')
            ->andWhere('u.isPrivate != 1')
            ->andWhere('wm.isArchived != 1')
            ->setParameter('name', $categoryName)
            ->groupBy('wm.id')
            ->getQuery()
            ->getScalarResult();
    }


}
