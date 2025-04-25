<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hotel>
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /** @return Hotel[] */
    public function findHotelByCriteria(array $criteria)
    {
        $qb = $this->createQueryBuilder('h');

        if (isset($criteria['children'])) {
            $qb->andWhere('h.children = :children')
                ->setParameter('children', $criteria['children']);
        }

        if (isset($criteria['animal'])) {
            $qb->andWhere('h.animal = :animal')
                ->setParameter('animal', $criteria['animal']);
        }
        if (!empty($criteria['transport']) && is_array($criteria['transport'])) {
            $qb->andWhere('h.transport IN (:transport)')
                ->setParameter('transport', $criteria['transport']);
        }

        if (isset($criteria['restoration'])) {
            $qb->andWhere('h.restoration = :restoration')
                ->setParameter('restoration', $criteria['restoration']);
        }

        if (!empty($criteria['wellness']) && is_array($criteria['wellness'])) {
            $qb->andWhere('h.wellness IN (:wellness)')
                ->setParameter('wellness', $criteria['wellness']);
        }

        if (!empty($criteria['business']) && is_array($criteria['business'])) {
            $qb->andWhere('h.business IN (:business)')
                ->setParameter('business', $criteria['business']);
        }

        if (!empty($criteria['comfort']) && is_array($criteria['comfort'])) {
            $qb->andWhere('h.comfort IN (:comfort)')
                ->setParameter('comfort', $criteria['comfort']);
        }

        if (!empty($criteria['addServices']) && is_array($criteria['addServices'])) {
            $qb->andWhere('h.addServices IN (:addServices)')
                ->setParameter('addServices', $criteria['addServices']);
        }

        if (isset($criteria['pmr'])) {
            $qb->andWhere('h.pmr = :pmr')
                ->setParameter('pmr', $criteria['pmr']);
        }

        if (isset($criteria['baby'])) {
            $qb->andWhere('h.baby = :baby')
                ->setParameter('baby', $criteria['baby']);
        }

        if (isset($criteria['category'])) {
            $qb->join('h.categorie', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $criteria['category']);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Hotel[] Returns an array of Hotel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hotel
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
