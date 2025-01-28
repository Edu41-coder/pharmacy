<?php

namespace App\Models;

use config\Database;
use PDO;

class ACommander {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function addProduit($produit_id, $quantite) {
        $query = "INSERT INTO a_commander (produit_id, quantite) VALUES (:produit_id, :quantite)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':produit_id' => $produit_id,
            ':quantite' => $quantite
        ]);
    }

    // Read
    public function getProduitById($produit_id) {
        $query = "SELECT * FROM a_commander WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':produit_id' => $produit_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProduits($limit = 100, $offset = 0) {
        $query = "SELECT * FROM a_commander LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateProduit($produit_id, $quantite) {
        $query = "UPDATE a_commander SET quantite = :quantite WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':produit_id' => $produit_id,
            ':quantite' => $quantite
        ]);
    }

    // Delete
    public function deleteProduit($produit_id) {
        $query = "DELETE FROM a_commander WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':produit_id' => $produit_id]);
    }
}