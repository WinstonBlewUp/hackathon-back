<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Hotel;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findAvailableRoomsByCategoryAndDates(int $categoryId, \DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('room')
            ->join('room.hotel', 'hotel')
            ->join('hotel.categorie', 'categorie')
            ->leftJoin('room.reservations', 'res', 'WITH', 
                '(:startDate < res.endDate AND :endDate > res.startDate)'
            )
            ->where('categorie.id = :categorieId')
            ->andWhere('res.id IS NULL')
            ->setParameters([
                'categorieId' => $categoryId,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

        return $qb->getQuery()->getResult();
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
            ->andWhere('r.maxGuests = :maxGuests')
            ->setParameter('maxGuests', $maxGuests);

        return $qb->getQuery()->getResult();
    }

    public function findAvailableRoomsForTonight($tonight, $tomorrow): array
    {
        $qb = $this->createQueryBuilder('room')
            ->leftJoin('room.reservations', 'res', 'WITH', 
                ':tonight < res.endDate AND :tomorrow > res.startDate'
            )
            ->where('res.id IS NULL')
            ->setParameter('tonight', $tonight)
            ->setParameter('tomorrow', $tomorrow);

        return $qb->getQuery()->getResult();
    }

    public function findAvailableRoomsByCategoryAndUser(int $categoryId, int $userId, int $mostFrequentMaxGuests)
    {
        $qb = $this->createQueryBuilder('room')
            ->join('room.hotel', 'hotel')
            ->join('hotel.categorie', 'categorie')
            ->leftJoin('room.reservations', 'res', 'WITH', 
                'res.user = :userId' 
            )
            ->leftJoin('room.users', 'u', 'WITH', 'u.id = :userId')
            ->where('categorie.id = :categorieId')
            ->andWhere('res.id IS NULL') 
            ->andWhere('u.id IS NULL')
            ->andWhere('room.maxGuests = :maxGuests')
            ->setParameter('categorieId', $categoryId)
            ->setParameter('userId', $userId)
            ->setParameter('maxGuests', $mostFrequentMaxGuests);

        return $qb->getQuery()->getResult();
    }

    public function searchRoomsByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.hotel', 'h') // Jointure avec l'entité Hotel
            ->addSelect('h') // Inclure les détails de l'hôtel dans les résultats
            ->where('LOWER(r.name) LIKE LOWER(:term) OR LOWER(r.description) LIKE LOWER(:term)')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('r.name', 'ASC');

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
