<?php

namespace App\Models;

use config\Database;
use PDO;

class Vente {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function createVente(array $data) {
        $query = "INSERT INTO vente (client_id, mode_encaissement, numero_cheque, commentaire) 
                  VALUES (:client_id, :mode_encaissement, :numero_cheque, :commentaire)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':client_id' => $data['client_id'] ?? null,
            ':mode_encaissement' => $data['mode_encaissement'],
            ':numero_cheque' => $data['numero_cheque'] ?? null,
            ':commentaire' => $data['commentaire'] ?? null
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    // Read
    public function getVenteById($id) {
        $query = "SELECT * FROM vente WHERE vente_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllVentes($limit = 100, $offset = 0) {
        $query = "SELECT * FROM vente LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateVente($id, array $data) {
        $allowedFields = ['client_id', 'mode_encaissement', 'numero_cheque', 'commentaire'];
        $setFields = [];
        $params = [':id' => $id];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $setFields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($setFields)) {
            return false;
        }

        $query = "UPDATE vente SET " . implode(', ', $setFields) . " WHERE vente_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    // Delete
    public function deleteVente($id) {
        $query = "DELETE FROM vente WHERE vente_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Méthodes spécifiques pour gérer les tables d'association
    public function addProduitToVente($vente_id, $produit_id, $quantite) {
        $query = "INSERT INTO vente_produit (vente_id, produit_id, quantite) VALUES (:vente_id, :produit_id, :quantite)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':vente_id' => $vente_id,
            ':produit_id' => $produit_id,
            ':quantite' => $quantite
        ]);
    }

    public function getProduitsByVente($vente_id) {
        $query = "SELECT p.id, p.nom, vp.quantite FROM produits p
                  JOIN vente_produit vp ON p.id = vp.produit_id
                  WHERE vp.vente_id = :vente_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':vente_id' => $vente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProduitFromVente($vente_id, $produit_id) {
        $query = "DELETE FROM vente_produit WHERE vente_id = :vente_id AND produit_id = :produit_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':vente_id' => $vente_id,
            ':produit_id' => $produit_id
        ]);
    }

    public function addOrdonnanceToVente($vente_id, $ordonnance_id) {
        $query = "INSERT INTO vente_ordonnance (vente_id, ordonnance_id) VALUES (:vente_id, :ordonnance_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':vente_id' => $vente_id,
            ':ordonnance_id' => $ordonnance_id
        ]);
    }

    public function getOrdonnancesByVente($vente_id) {
        $query = "SELECT o.id FROM ordonnances o
                  JOIN vente_ordonnance vo ON o.id = vo.ordonnance_id
                  WHERE vo.vente_id = :vente_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':vente_id' => $vente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrdonnanceFromVente($vente_id, $ordonnance_id) {
        $query = "DELETE FROM vente_ordonnance WHERE vente_id = :vente_id AND ordonnance_id = :ordonnance_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':vente_id' => $vente_id,
            ':ordonnance_id' => $ordonnance_id
        ]);
    }
     // Search
     public function searchVentes(array $criteria) {
        $query = "SELECT * FROM vente WHERE 1=1";
        $params = [];

        foreach ($criteria as $field => $value) {
            if (!empty($value)) {
                $query .= " AND $field LIKE :$field";
                $params[":$field"] = "%$value%";
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}