<?php

namespace App\Models;

use config\Database;
use PDO;

class Commande {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function createCommande($fournisseur_id) {
        $query = "INSERT INTO commande (fournisseur_id) VALUES (:fournisseur_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':fournisseur_id' => $fournisseur_id]);
    }

    // Read
    public function getCommandeById($commande_id) {
        $query = "SELECT * FROM commande WHERE commande_id = :commande_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':commande_id' => $commande_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCommandes($limit = 100, $offset = 0) {
        $query = "SELECT * FROM commande LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateCommande($commande_id, $fournisseur_id) {
        $query = "UPDATE commande SET fournisseur_id = :fournisseur_id WHERE commande_id = :commande_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':commande_id' => $commande_id,
            ':fournisseur_id' => $fournisseur_id
        ]);
    }

    // Delete
    public function deleteCommande($commande_id) {
        $query = "DELETE FROM commande WHERE commande_id = :commande_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':commande_id' => $commande_id]);
    }

    // Méthodes spécifiques pour gérer la table d'association commande_produit
    public function addProduitToCommande($commande_id, $produit_id, $quantite) {
        $query = "INSERT INTO commande_produit (commande_id, produit_id, quantite) VALUES (:commande_id, :produit_id, :quantite)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':commande_id' => $commande_id,
            ':produit_id' => $produit_id,
            ':quantite' => $quantite
        ]);
    }

    public function getProduitsByCommande($commande_id) {
        $query = "SELECT p.id, p.nom, cp.quantite FROM produits p
                  JOIN commande_produit cp ON p.id = cp.produit_id
                  WHERE cp.commande_id = :commande_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':commande_id' => $commande_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProduitFromCommande($commande_id, $produit_id) {
        $query = "DELETE FROM commande_produit WHERE commande_id = :commande_id AND produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':commande_id' => $commande_id,
            ':produit_id' => $produit_id
        ]);
    }
}