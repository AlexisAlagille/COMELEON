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
            ->select(
                'a.note',
                'a.commentaire',
                'a.date_avis AS dateAvis',
                'u.prenom AS prenomAuteur',
                'u.nom AS nomAuteur',
                'd.description AS demandeDescription',
                'd.date AS demandeDate'
            )
            ->from('avis', 'a')
            ->innerJoin('a', 'user', 'u', 'u.id = a.user_id')
            ->innerJoin('a', 'demande', 'd', 'd.id = a.demande_id')
            ->orderBy('a.date_avis', 'DESC')
            ->setMaxResults($limit)
            ->fetchAllAssociative();
    }

    public function findAvis(): array
    {
        return $this->createQueryBuilder('avis')
            ->addSelect('demande', 'user')
            ->innerJoin('avis.demande', 'demande')
            ->innerJoin('avis.user', 'user')
            ->orderBy('avis.dateAvis', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
