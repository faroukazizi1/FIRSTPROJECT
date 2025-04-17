<?php namespace App\Repository;

use App\Entity\Pret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry; class PretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pret::class);
    }

    // Récupère les CIN distincts
    public function findDistinctCins(): array
    {
        $results = $this->createQueryBuilder('p')
            ->select('DISTINCT p.cin AS cin') // On sélectionne uniquement le CIN
            ->where('p.cin IS NOT NULL')
            ->andWhere("p.cin != ''")
            ->orderBy('p.cin', 'ASC')
            ->getQuery()
            ->getResult();

        // On retourne un tableau simple des CIN
        return array_column($results, 'cin');
    }
}
