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

    /**
     * Query return name and count of each category
     * Don`t read data from Private account and Archived wish map cards
     * @return int|mixed[]|string
     */
    public function wishMapsGetCategoryCount(): array|int|string
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

    /**
     * Query return name and count of user category
     * Don`t read data from archived wish map cards
     * @param string $nickname nickname of user
     * @return int|mixed[]|string
     */
    public function wishMapsGetUserCategoryCount(string $nickname): array|int|string
    {
        return $this->createQueryBuilder('wm')
            ->innerJoin('wm.category', 'cat')
            ->innerJoin('wm.user', 'u')
            ->select('cat.name, COUNT(wm.category) AS count')
            ->andWhere('cat.id = wm.category')
            ->andWhere('u.nickname = :name')
            ->andWhere('wm.isArchived != 1')
            ->setParameter('name', $nickname)
            ->groupBy('wm.category')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Query return wish maps,categories and count of comment of each wish map
     * Don`t read data from Private account and Archived wish map cards
     * @return int|mixed[]|string
     */
    public function wishMapsGetNotPrivateAccs(): array|int|string
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

    /**
     * Query return wish maps,categories and count of comment of each wish map
     * Don`t read data from Private account and Archived wish map cards
     * @param string $nickname
     * @return int|mixed[]|string
     */
    public function wishMapsGetAccByNickname(string $nickname): array|int|string
    {
        return $this->createQueryBuilder('wm')
            ->innerJoin('wm.user', 'u')
            ->innerJoin('wm.category', 'c')
            ->leftJoin('wm.comments', 'com')
            ->select('wm', 'c', 'COUNT(com.id) as count')
            ->andWhere('u.nickname=:name')
            ->andWhere('u.isPrivate != 1')
            ->andWhere('wm.isArchived != 1')
            ->setParameter('name', $nickname)
            ->groupBy('wm.id')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Query return all category with same name
     * Don`t read data from Private account and Archived wish map cards
     * @param string $categoryName
     * @return int|mixed[]|string
     */
    public function wishMapsGetNotPrivateAccs_withCategoryName(string $categoryName): array|int|string
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
