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

    public function findMostFrequentMaxGuestsByUser(int $id): int
    {
        $sql = "
            SELECT r.maxGuests, COUNT(r.maxGuests) AS freq
            FROM App\Entity\Reservation res
            JOIN res.room r
            WHERE res.user = :id
            GROUP BY r.maxGuests
            ORDER BY freq DESC
        ";

        $query = $this->getEntityManager()->createQuery($sql);
        $query->setParameter('id', $id);

        $result = $query->getResult();

        if (!empty($result)) {
            return $result[0]['maxGuests'];
        }

        return 0;
    }

    public function isRoomAvailableById(int $id, \DateTime $startDate, \DateTime $endDate): bool
    {
        $qb = $this->createQueryBuilder('res')
            ->select('COUNT(res.id)')
            ->where('res.room = :room')
            ->andWhere(':startDate < res.endDate')
            ->andWhere(':endDate > res.startDate')
            ->setParameter('room', $id)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count == 0;
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
