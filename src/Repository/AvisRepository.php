<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function findDerniersAvisAvecAuteur(int $limit = 6): array
    {
        return $this->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('a.note', 'a.commentaire', 'a.date_avis AS dateAvis', 'u.prenom AS prenomAuteur', 'u.nom AS nomAuteur')
            ->from('avis', 'a')
            ->innerJoin('a', 'user', 'u', 'u.id = a.user_id')
            ->orderBy('a.date_avis', 'DESC')
            ->setMaxResults($limit)
            ->fetchAllAssociative();
    }
}
