<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // Dans le repository de Reservation (ReservationRepository.php)

    public function findMostFrequentMaxGuestsByUser(int $userId): int
    {
        $sql = "
            SELECT r.maxGuests, COUNT(r.maxGuests) AS freq
            FROM App\Entity\Reservation res
            JOIN res.room r
            WHERE res.user = :userId
            GROUP BY r.maxGuests
            ORDER BY freq DESC
        ";

        $query = $this->getEntityManager()->createQuery($sql);
        $query->setParameter('userId', $userId);

        $result = $query->getOneOrNullResult(); 

        return $result ? $result['maxGuests'] : 0;
    }


    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
