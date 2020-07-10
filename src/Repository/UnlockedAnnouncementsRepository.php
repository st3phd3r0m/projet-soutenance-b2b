<?php

namespace App\Repository;

use App\Entity\UnlockedAnnouncements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UnlockedAnnouncements|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnlockedAnnouncements|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnlockedAnnouncements[]    findAll()
 * @method UnlockedAnnouncements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnlockedAnnouncementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnlockedAnnouncements::class);
    }

    // /**
    //  * @return UnlockedAnnouncements[] Returns an array of UnlockedAnnouncements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UnlockedAnnouncements
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
