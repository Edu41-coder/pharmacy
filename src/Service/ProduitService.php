<?php

namespace App\Service;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProduitService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProduitRepository $produitRepository
    ) {}

    public function createProduit(array $data): Produit
    {
        $produit = new Produit();
        $this->hydrateProduit($produit, $data);

        $this->entityManager->persist($produit);
        $this->entityManager->flush();

        return $produit;
    }

    public function updateProduit(Produit $produit, array $data): void
    {
        $this->hydrateProduit($produit, $data);
        $this->entityManager->flush();
    }

    public function deleteProduit(Produit $produit): void
    {
        $this->produitRepository->softDelete($produit);
    }

    public function findAll(): array
    {
        return $this->produitRepository->findAllActive();
    }

    public function findByNom(string $nom): array
    {
        return $this->produitRepository->findByNom($nom);
    }

    public function findLowStock(): array
    {
        return $this->produitRepository->findLowStock();
    }

    private function hydrateProduit(Produit $produit, array $data): void
    {
        if (isset($data['nom'])) {
            $produit->setNom($data['nom']);
        }
        if (isset($data['description'])) {
            $produit->setDescription($data['description']);
        }
        if (isset($data['prixVenteHt'])) {
            $produit->setPrixVenteHt($data['prixVenteHt']);
        }
        if (isset($data['prescription'])) {
            $produit->setPrescription($data['prescription']);
        }
        if (isset($data['tauxRemboursement'])) {
            $produit->setTauxRemboursement($data['tauxRemboursement']);
        }
        if (isset($data['alerte'])) {
            $produit->setAlerte($data['alerte']);
        }
        if (isset($data['declencherAlerte'])) {
            $produit->setDeclencherAlerte($data['declencherAlerte']);
        }
    }
} 