<?php

namespace App\Repository;

use App\Entity\Negociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\NegociationEnum;

/**
 * @extends ServiceEntityRepository<Negociation>
 */
class NegociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Negociation::class);
    }

    public function findOpenNegociationsByUser(int $userId): array
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.room', 'r')
            ->leftJoin('r.hotel', 'h')
            ->addSelect('r', 'h')
            ->andWhere('n.isClose = false')
            ->andWhere('n.user = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Negociation[] Returns an array of Negociation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Negociation
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
