<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\User;
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
        $conditions = [];

        if (isset($criteria['children'])) {
            $conditions[] = 'h.children = :children';
            $qb->setParameter('children', $criteria['children']);
        }

        if (isset($criteria['animal'])) {
            $conditions[] = 'h.animal = :animal';
            $qb->setParameter('animal', $criteria['animal']);
        }

        if (!empty($criteria['transport']) && is_array($criteria['transport'])) {
            $conditions[] = 'h.transport IN (:transport)';
            $qb->setParameter('transport', $criteria['transport']);
        }

        if (isset($criteria['restoration'])) {
            $conditions[] = 'h.restoration = :restoration';
            $qb->setParameter('restoration', $criteria['restoration']);
        }

        if (!empty($criteria['wellness']) && is_array($criteria['wellness'])) {
            $conditions[] = 'h.wellness IN (:wellness)';
            $qb->setParameter('wellness', $criteria['wellness']);
        }

        if (!empty($criteria['business']) && is_array($criteria['business'])) {
            $conditions[] = 'h.business IN (:business)';
            $qb->setParameter('business', $criteria['business']);
        }

        if (!empty($criteria['comfort']) && is_array($criteria['comfort'])) {
            $conditions[] = 'h.comfort IN (:comfort)';
            $qb->setParameter('comfort', $criteria['comfort']);
        }

        if (!empty($criteria['addServices']) && is_array($criteria['addServices'])) {
            $conditions[] = 'h.addServices IN (:addServices)';
            $qb->setParameter('addServices', $criteria['addServices']);
        }

        if (isset($criteria['pmr'])) {
            $conditions[] = 'h.pmr = :pmr';
            $qb->setParameter('pmr', $criteria['pmr']);
        }

        if (isset($criteria['baby'])) {
            $conditions[] = 'h.baby = :baby';
            $qb->setParameter('baby', $criteria['baby']);
        }

        if (isset($criteria['category'])) {
            $conditions[] = 'c.id = :categoryId';
            $qb->join('h.categorie', 'c')
                ->setParameter('categoryId', $criteria['category']);
        }

        if (count($conditions) > 0) {
            $qb->andWhere(implode(' OR ', $conditions));
        }

        return $qb->getQuery()->getResult();
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
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
