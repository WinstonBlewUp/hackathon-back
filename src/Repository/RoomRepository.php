<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /** @return Room[] */
    public function findAvailableRoomsForHotel(Hotel $hotel, int $maxGuests, \DateTime $startDate, \DateTime $endDate): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.hotel = :hotel')
            ->setParameter('hotel', $hotel)
            ->leftJoin('r.reservations', 'res')
            ->andWhere('res.startDate < :endDate')
            ->andWhere('res.endDate > :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->andWhere('res.id IS NULL')
            ->andWhere('h.maxGuests = :maxGuests')
            ->setParameter('maxGuests', $maxGuests);

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Room[] Returns an array of Room objects
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

    //    public function findOneBySomeField($value): ?Room
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
