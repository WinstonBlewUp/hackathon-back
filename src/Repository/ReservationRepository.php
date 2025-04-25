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

    public function TotalNightsByUser($userId): int
    {
        // Récupérer les réservations de l'utilisateur
        $reservations = $this->createQueryBuilder('r')
            ->select('r.startDate', 'r.endDate')
            ->where('r.startDate IS NOT NULL')
            ->andWhere('r.endDate IS NOT NULL')
            ->andWhere('r.status = :status')
            ->setParameter('status', 'completed')
            ->andWhere('r.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        // Calculer la somme des nuits en PHP
        $totalNights = 0;
        foreach ($reservations as $reservation) {
            // Calculer la différence en jours entre startDate et endDate
            $startDate = $reservation['startDate'];
            $endDate = $reservation['endDate'];

            // Assurez-vous que startDate est avant endDate
            if ($startDate < $endDate) {
                $interval = $startDate->diff($endDate); // DateInterval
                $totalNights += $interval->days - 1; // Nombre total de nuits (jours - 1)
            }
        }

        return $totalNights;
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
