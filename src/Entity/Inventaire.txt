<?php

namespace App\Models;

use config\Database;
use PDO;

class Inventaire {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function create($produit_id, $stock) {
        $query = "INSERT INTO inventaire (produit_id, stock) VALUES (:produit_id, :stock)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':produit_id' => $produit_id,
            ':stock' => $stock
        ]);
    }

    // Read
    public function getById($produit_id) {
        $query = "SELECT * FROM inventaire WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':produit_id' => $produit_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT * FROM inventaire";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function update($produit_id, $stock) {
        $query = "UPDATE inventaire SET stock = :stock WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':produit_id' => $produit_id,
            ':stock' => $stock
        ]);
    }

    // Delete
    public function delete($produit_id) {
        $query = "DELETE FROM inventaire WHERE produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':produit_id' => $produit_id]);
    }
    public function alerte($produit_id) {
        $query = "SELECT p.declencher_alerte, p.alerte, i.stock 
                  FROM produits p 
                  JOIN inventaire i ON p.produit_id = i.produit_id 
                  WHERE p.produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':produit_id' => $produit_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['declencher_alerte'] === 'oui' && $result['stock'] < $result['alerte']) {
            return true;
        }
        return false;
    }
}