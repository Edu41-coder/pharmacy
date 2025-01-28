<?php

namespace App\Models;

use config\Database;
use PDO;

class Produit
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function createProduit($nom, $description, $prix_vente_ht, $prescription, $taux_remboursement, $alerte, $declencher_alerte)
    {
        $query = "INSERT INTO produits (nom, description, prix_vente_ht, prescription, taux_remboursement, alerte, declencher_alerte) 
                  VALUES (:nom, :description, :prix_vente_ht, :prescription, :taux_remboursement, :alerte, :declencher_alerte)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':prix_vente_ht' => $prix_vente_ht,
            ':prescription' => $prescription,
            ':taux_remboursement' => $taux_remboursement,
            ':alerte' => $alerte,
            ':declencher_alerte' => $declencher_alerte
        ]);
    }

    // Read
    public function getProduitById($id)
    {
        $query = "SELECT * FROM produits WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProduits()
    {
        $query = "SELECT * FROM produits";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateProduit($id, $nom, $description, $prix_vente_ht, $prescription, $taux_remboursement, $alerte, $declencher_alerte)
    {
        $query = "UPDATE produits SET nom = :nom, description = :description, prix_vente_ht = :prix_vente_ht, 
                  prescription = :prescription, taux_remboursement = :taux_remboursement, alerte = :alerte, declencher_alerte = :declencher_alerte WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':description' => $description,
            ':prix_vente_ht' => $prix_vente_ht,
            ':prescription' => $prescription,
            ':taux_remboursement' => $taux_remboursement,
            ':alerte' => $alerte,
            ':declencher_alerte' => $declencher_alerte
        ]);
    }

    // Delete
    public function deleteProduit($id)
    {
        $query = "DELETE FROM produits WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }



    // Méthodes spécifiques
    public function addFournisseurPrix($produit_id, $fournisseur_id, $prix_achat_ht)
    {
        $query = "INSERT INTO produit_fournisseur (produit_id, fournisseur_id, prix_achat_ht) VALUES (:produit_id, :fournisseur_id, :prix_achat_ht)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':produit_id' => $produit_id,
            ':fournisseur_id' => $fournisseur_id,
            ':prix_achat_ht' => $prix_achat_ht
        ]);
    }

    public function getFournisseursPrix($produit_id)
    {
        $query = "SELECT f.id, f.nom, pf.prix_achat_ht FROM fournisseurs f
                  JOIN produit_fournisseur pf ON f.id = pf.fournisseur_id
                  WHERE pf.produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':produit_id' => $produit_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFournisseurs()
    {
        $query = "SELECT * FROM fournisseurs";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthodes pour gérer la table d'association ordonnance_produit
    public function addOrdonnanceProduit($ordonnance_id, $produit_id, $quantite)
    {
        $query = "INSERT INTO ordonnance_produit (ordonnance_id, produit_id, quantite) VALUES (:ordonnance_id, :produit_id, :quantite)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':ordonnance_id' => $ordonnance_id,
            ':produit_id' => $produit_id,
            ':quantite' => $quantite
        ]);
    }

    public function getOrdonnanceProduits($ordonnance_id)
    {
        $query = "SELECT p.id, p.nom, op.quantite FROM produits p
                  JOIN ordonnance_produit op ON p.id = op.produit_id
                  WHERE op.ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':ordonnance_id' => $ordonnance_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrdonnanceProduit($ordonnance_id, $produit_id)
    {
        $query = "DELETE FROM ordonnance_produit WHERE ordonnance_id = :ordonnance_id AND produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':ordonnance_id' => $ordonnance_id,
            ':produit_id' => $produit_id
        ]);
    }
}
