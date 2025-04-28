<?php

namespace App\Repository;

use App\Entity\Bulletinpaie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bulletinpaie>
 */
class BulletinpaieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bulletinpaie::class);
    }

    public function findByMonthAndYear(int $month, int $year)
    {
        $startDate = new \DateTime("$year-$month-01");
        $endDate = (clone $startDate)->modify('last day of this month');
    
        return $this->createQueryBuilder('b')
            ->where('b.dateCreation BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Bulletinpaie[] Returns an array of Bulletinpaie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Bulletinpaie
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    
}
