<?php

namespace App\Repository;

use App\Entity\Person;
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

    public function findByPerson(Person $person): QueryBuilder
    {
        return $this->createQueryBuilder('wm')
            ->select('wm.description, wm.image, wm.process, wm.startDate, wm.finishDate,
            identity(wm.category) AS category_id')
            ->andWhere('wm.person = :person')
            ->setParameter('person', $person)
            ->orderBy('wm.finishDate');
    }


}
