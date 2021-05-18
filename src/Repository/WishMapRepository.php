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


    public function findByCategory($category): QueryBuilder
    {
        return $this->createQueryBuilder('wm')
            ->select('wm.id, wm.name, wm.description, wm.image, wm.process, wm.startDate, wm.finishDate,
            identity(wm.category) AS category_id')
            ->andWhere('wm.category = :category')
            ->setParameter('category', $category)
            ->orderBy('wm.finishDate');
    }

    public function findByUser($user): array
    {
        return $this->createQueryBuilder('wm')
            ->andWhere('wm.user = :user')
            ->setParameter('user', $user)
            ->orderBy('wm.finishDate')
            ->getQuery()
            ->getResult();
    }

    public function wishMapsGetCategoryCount()
    {
        return $this->createQueryBuilder('wm')
            ->leftJoin('wm.category', 'cat')
            ->select('cat.name, COUNT(wm.category) AS count')
            ->andWhere('cat.id = wm.category')
            ->groupBy('wm.category')
            ->getQuery()
            ->getScalarResult();
    }

}
