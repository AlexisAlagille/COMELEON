<?php

namespace App\Repository;

use App\Entity\Demande;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    //! Récupère TOUTES les demandes d'un utilisateur (la plus récente en premier)
    public function recupererDemandesParUtilisateur(User $user): array
    {
        return $this->createQueryBuilder('d')
            ->addSelect('s')
            ->join('d.statut', 's')
            ->where('d.user = :user')
            ->setParameter('user', $user)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function recupererToutesLesDemandes(): array
    {
        return $this->createQueryBuilder('d')
            ->addSelect('s')
            ->join('d.statut', 's')
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Demande[] Returns an array of Demande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demande
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
