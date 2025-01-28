<?php

namespace App\Models;

use config\Database;
use PDO;

class Impaye {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create
    public function createImpaye($numero_cheque, $client_id) {
        $query = "INSERT INTO impaye (numero_cheque, client_id) VALUES (:numero_cheque, :client_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':numero_cheque' => $numero_cheque,
            ':client_id' => $client_id
        ]);
    }

    // Read
    public function getImpayeById($impaye_id) {
        $query = "SELECT * FROM impaye WHERE impaye_id = :impaye_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':impaye_id' => $impaye_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllImpaye($limit = 100, $offset = 0) {
        $query = "SELECT * FROM impaye LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update
    public function updateImpaye($impaye_id, $numero_cheque, $client_id) {
        $query = "UPDATE impaye SET numero_cheque = :numero_cheque, client_id = :client_id WHERE impaye_id = :impaye_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':impaye_id' => $impaye_id,
            ':numero_cheque' => $numero_cheque,
            ':client_id' => $client_id
        ]);
    }

    // Delete
    public function deleteImpaye($impaye_id) {
        $query = "DELETE FROM impaye WHERE impaye_id = :impaye_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':impaye_id' => $impaye_id]);
    }
}