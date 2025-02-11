<?php

namespace App\Models;

use config\Database;
use PDO;

class Cheque {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function createCheque($numero_cheque, $client_id, $etat = 'en_attente') {
        $query = "INSERT INTO cheque (numero_cheque, client_id, etat) VALUES (:numero_cheque, :client_id, :etat)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':numero_cheque' => $numero_cheque,
            ':client_id' => $client_id,
            ':etat' => $etat
        ]);
    }

    // Read
    public function getChequeById($cheque_id) {
        $query = "SELECT * FROM cheque WHERE cheque_id = :cheque_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cheque_id' => $cheque_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCheques($limit = 100, $offset = 0) {
        $query = "SELECT * FROM cheque LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateCheque($cheque_id, $numero_cheque, $client_id, $etat) {
        $query = "UPDATE cheque SET numero_cheque = :numero_cheque, client_id = :client_id, etat = :etat WHERE cheque_id = :cheque_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':cheque_id' => $cheque_id,
            ':numero_cheque' => $numero_cheque,
            ':client_id' => $client_id,
            ':etat' => $etat
        ]);
    }

    // Delete
    public function deleteCheque($cheque_id) {
        $query = "DELETE FROM cheque WHERE cheque_id = :cheque_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':cheque_id' => $cheque_id]);
    }
}