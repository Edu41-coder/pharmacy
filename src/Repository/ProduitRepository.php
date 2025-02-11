<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @return Produit[] Returns an array of active Produit objects
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[] Returns an array of Produit objects with low stock
     */
    public function findLowStock(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.declencherAlerte = :declencherAlerte')
            ->andWhere('p.alerte >= p.stock')
            ->andWhere('p.isDeleted = :isDeleted')
            ->setParameter('declencherAlerte', 'oui')
            ->setParameter('isDeleted', false)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNom(string $nom): array
    {
        return $this->createQueryBuilder('p')
            ->where('LOWER(p.nom) LIKE LOWER(:nom)')
            ->andWhere('p.isDeleted = :isDeleted')
            ->setParameter('nom', '%' . $nom . '%')
            ->setParameter('isDeleted', false)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function softDelete(Produit $produit): void
    {
        $produit->setIsDeleted(true);
        $this->_em->persist($produit);
        $this->_em->flush();
    }
} 