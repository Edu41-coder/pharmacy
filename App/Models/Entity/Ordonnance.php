<?php

namespace App\Models;

use config\Database;
use PDO;

class Ordonnance {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // ... autres méthodes ...

    // Create
    public function createOrdonnance($numero_ordonnance, $numero_dordre, $image_path = null) {
        $query = "INSERT INTO ordonnance (numero_ordonnance, numero_dordre, image_path) VALUES (:numero_ordonnance, :numero_dordre, :image_path)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':numero_ordonnance' => $numero_ordonnance,
            ':numero_dordre' => $numero_dordre,
            ':image_path' => $image_path
        ]);
    }

    // Read
    public function getOrdonnanceById($ordonnance_id) {
        $query = "SELECT * FROM ordonnance WHERE ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':ordonnance_id' => $ordonnance_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllOrdonnances($limit = 100, $offset = 0) {
        $query = "SELECT * FROM ordonnance LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateOrdonnance($ordonnance_id, $numero_ordonnance, $numero_dordre, $image_path = null) {
        $query = "UPDATE ordonnance SET numero_ordonnance = :numero_ordonnance, numero_dordre = :numero_dordre, image_path = :image_path WHERE ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':ordonnance_id' => $ordonnance_id,
            ':numero_ordonnance' => $numero_ordonnance,
            ':numero_dordre' => $numero_dordre,
            ':image_path' => $image_path
        ]);
    }

    // Delete
    public function deleteOrdonnance($ordonnance_id) {
        $query = "DELETE FROM ordonnance WHERE ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':ordonnance_id' => $ordonnance_id]);
    }

    // Méthodes spécifiques pour gérer les images d'ordonnances
    public function addImageToOrdonnance($ordonnance_id, $image_path) {
        $query = "INSERT INTO image_ordonnance (ordonnance_id, image_path) VALUES (:ordonnance_id, :image_path)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':ordonnance_id' => $ordonnance_id,
            ':image_path' => $image_path
        ]);
    }

    public function getImagesByOrdonnance($ordonnance_id) {
        $query = "SELECT * FROM image_ordonnance WHERE ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':ordonnance_id' => $ordonnance_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageOrdonnanceById($image_id) {
        $query = "SELECT * FROM image_ordonnance WHERE image_id = :image_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':image_id' => $image_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteImageFromOrdonnance($image_id) {
        $query = "DELETE FROM image_ordonnance WHERE image_id = :image_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':image_id' => $image_id]);
    }
    // Méthodes spécifiques pour gérer les produits d'ordonnances
    public function getProduitsByOrdonnance($ordonnance_id) {
        $query = "SELECT p.*, op.quantite FROM produit p
                  JOIN ordonnance_produit op ON p.produit_id = op.produit_id
                  WHERE op.ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':ordonnance_id' => $ordonnance_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}