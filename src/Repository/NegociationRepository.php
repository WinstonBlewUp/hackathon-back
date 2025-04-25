<?php

namespace App\Repository;

use App\Entity\Negociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->setParameter('status', 'accepted')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
