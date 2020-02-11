<?php

namespace App\Repository;

use App\Entity\Unicorn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Unicorn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unicorn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unicorn[]    findAll()
 * @method Unicorn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnicornRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unicorn::class);
    }

    // /**
    //  * @return Unicorn[] Returns an array of Unicorn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Unicorn
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
