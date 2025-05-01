<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    public function searchUsers(?string $query = null, ?string $role = null, ?string $sexe = null): array
    {
        $qb = $this->createQueryBuilder('u');
    
        if ($query) {
            $qb->andWhere('u.nom LIKE :q OR u.prenom LIKE :q OR u.email LIKE :q OR u.cin LIKE :q')
               ->setParameter('q', '%' . $query . '%');
        }
    
        if ($role) {
            $qb->andWhere('u.role = :role')
               ->setParameter('role', $role);
        }
    
        if ($sexe) {
            $qb->andWhere('u.sexe = :sexe')
               ->setParameter('sexe', $sexe);
        }
    
        return $qb->getQuery()->getResult();
    }
    
    
}
