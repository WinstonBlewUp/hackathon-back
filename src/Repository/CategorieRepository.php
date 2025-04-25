<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function findTopLikedCategoriesByUser(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c')
            ->join('c.hotels', 'h')
            ->join('h.rooms', 'r')
            ->join('r.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findTopReservedCategoriesByUser(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c')
            ->join('c.hotels', 'h')
            ->join('h.rooms', 'r')
            ->join('r.reservations', 'res')
            ->join('res.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
