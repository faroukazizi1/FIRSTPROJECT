<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    //    /**
    //     * @return Formation[] Returns an array of Formation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Formation
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function searchAndSort(array $criteria = [], ?string $sort = null)
    {
        $qb = $this->createQueryBuilder('f')
            ->leftJoin('f.formateur', 'formateur');
    
        // Filtres
        if (!empty($criteria['titre'])) {
            $qb->andWhere('f.Titre LIKE :titre')
               ->setParameter('titre', '%'.$criteria['titre'].'%');
        }
    
        if (!empty($criteria['formateur'])) {
            $qb->andWhere('formateur.nomF LIKE :formateur OR formateur.prenomF LIKE :formateur')
               ->setParameter('formateur', '%'.$criteria['formateur'].'%');
        }
    
        // Tri
        switch ($sort) {
            case 'titre_asc': $qb->orderBy('f.Titre', 'ASC'); break;
            case 'titre_desc': $qb->orderBy('f.Titre', 'DESC'); break;
            case 'date_asc': $qb->orderBy('f.dateD', 'ASC'); break;
            case 'date_desc': $qb->orderBy('f.dateD', 'DESC'); break;
            default: $qb->orderBy('f.dateD', 'DESC');
        }
    
        return $qb->getQuery()->getResult();
    }

    public function countByType(): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.Titre as type, COUNT(f.id_form) as count')
            ->groupBy('f.Titre')
            ->getQuery()
            ->getResult();
    }

    public function averageDuration(): float
    {
        return $this->createQueryBuilder('f')
            ->select('AVG(f.Duree)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUpcoming(int $limit = 5): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.dateD >= :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('f.dateD', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
   


