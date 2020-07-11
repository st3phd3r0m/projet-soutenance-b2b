<?php

namespace App\Repository;

use App\Entity\Announcements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Announcements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcements[]    findAll()
 * @method Announcements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcements::class);
    }

    public function findMaxRef()
    {
        return $this->createQueryBuilder('a')
            ->select('MAX(a.ref)')
            ->getQuery()
            ->getResult();
    }

    public function searchFilter(array $criteria)
    {
        $query = $this->createQueryBuilder('a')
                ->select('a')
                ->innerJoin('a.category', 'c')
                ->innerJoin('a.activity_sector', 's');

        if( !empty($criteria["adTypeFilter"]) ){
            $query->andWhere('c.id = :category')
                ->setParameter('category', $criteria["adTypeFilter"]);
        }
        if( !empty($criteria["activitySectorFilter"]) ){
            $query->andWhere('s.id = :activitySector')
                ->setParameter('activitySector', $criteria["activitySectorFilter"]);
        }
        if( !empty($criteria["deptCode"]) ){
            $query->andWhere('a.dept_code = :deptCode')
                ->setParameter('deptCode', $criteria["deptCode"]);
        }
        if( !empty($criteria["search"]) ){
            $query->andWhere('MATCH_AGAINST(a.title, a.description) AGAINST (:searchterm boolean) >0')
                ->andWhere()
                ->setParameter('searchterm', $criteria["search"]);
        }

        $query->getQuery()->getResult();

        return $query;
    }






    public function search(string $searchterm)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('MATCH_AGAINST(p.title, p.content) AGAINST (:searchterm boolean) >0')
            ->setParameter('searchterm', $searchterm)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Announcements[] Returns an array of Announcements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Announcements
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
