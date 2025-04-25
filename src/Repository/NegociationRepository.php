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
    public function findAcceptedNegociationsByUserId(int $userId): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.status = :status')
            ->andWhere('n.user = :userId')
            ->andWhere('n.isClose = true')
            ->setParameter('status', 'pendingClient')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
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

    public function isRoomReservedDuringNegotiation(Negociation $negociation): bool
    {
        $room = $negociation->getRoom();

        if (!$room) {
            return false;
        }

        $negociationStartDate = $negociation->getStartDate()->format('Y-m-d H:i:s');
        $negociationEndDate = $negociation->getEndDate()->format('Y-m-d H:i:s');

        $dql = "
            SELECT COUNT(r) > 0
            FROM App\Entity\Reservation res
            JOIN res.room r
            WHERE r.id = :roomId
            AND (
                (res.startDate < :negociationEndDate AND res.endDate > :negociationStartDate)
                OR
                (res.startDate < :negociationStartDate AND res.endDate > :negociationStartDate)
            )
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('roomId', $room->getId());
        $query->setParameter('negociationStartDate', $negociationStartDate);
        $query->setParameter('negociationEndDate', $negociationEndDate);

        return (bool) $query->getSingleScalarResult();
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
