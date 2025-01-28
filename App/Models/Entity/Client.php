<?php

namespace App\Models;

use config\Database;
use PDO;

class Client
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouveau client
     * @param array $data Données du client (nom, prenom, email, telephone, adresse, commentaire, numero_carte_vitale, cheques_impayes)
     * @return int|false L'ID du client inséré ou false si l'insertion a échoué
     */
    public function createClient(array $data)
    {
        $query = "INSERT INTO clients (nom, prenom, email, telephone, adresse, commentaire, numero_carte_vitale, cheques_impayes) 
                  VALUES (:nom, :prenom, :email, :telephone, :adresse, :commentaire, :numero_carte_vitale, :cheques_impayes)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':adresse' => $data['adresse'] ?? null,
            ':commentaire' => $data['commentaire'] ?? null,
            ':numero_carte_vitale' => $data['numero_carte_vitale'] ?? null,
            ':cheques_impayes' => $data['cheques_impayes'] ?? false
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Récupère un client par son ID
     * @param int $id ID du client
     * @return array|false Données du client ou false si non trouvé
     */
    public function getClientById($id)
    {
        $query = "SELECT * FROM clients WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un client par son email
     * @param string $email Email du client
     * @return array|false Données du client ou false si non trouvé
     */
    public function getClientByEmail($email)
    {
        $query = "SELECT * FROM clients WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations d'un client
     * @param int $id ID du client
     * @param array $data Données à mettre à jour
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function updateClient($id, array $data)
    {
        $allowedFields = ['nom', 'prenom', 'email', 'telephone', 'adresse', 'commentaire', 'numero_carte_vitale', 'cheques_impayes'];
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

        $query = "UPDATE clients SET " . implode(', ', $setFields) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Supprime un client
     * @param int $id ID du client
     * @return bool True si la suppression a réussi, false sinon
     */
    public function deleteClient($id)
    {
        $query = "DELETE FROM clients WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère tous les clients
     * @param int $limit Nombre maximum de clients à récupérer
     * @param int $offset Décalage pour la pagination
     * @return array Liste de tous les clients
     */
    public function getAllClients($limit = 100, $offset = 0)
    {
        $query = "SELECT * FROM clients LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche des clients
     * @param array $criteria Critères de recherche (nom, prenom, email, telephone, adresse)
     * @param int $limit Nombre maximum de clients à récupérer
     * @param int $offset Décalage pour la pagination
     * @return array Liste des clients correspondants
     */
    public function searchClients(array $criteria, $limit = 100, $offset = 0)
    {
        $whereClause = [];
        $params = [];

        foreach (['nom', 'prenom', 'email', 'telephone', 'adresse'] as $field) {
            if (!empty($criteria[$field])) {
                $whereClause[] = "$field LIKE :$field";
                $params[":$field"] = '%' . $criteria[$field] . '%';
            }
        }

        $whereClause = !empty($whereClause) ? 'WHERE ' . implode(' AND ', $whereClause) : '';

        $query = "SELECT * FROM clients $whereClause LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de clients
     * @return int Nombre total de clients
     */
    public function countClients()
    {
        $query = "SELECT COUNT(*) FROM clients";
        return $this->db->query($query)->fetchColumn();
    }

    /**
     * Récupère l'historique des achats d'un client
     * @param int $clientId ID du client
     * @return array Historique des achats du client
     */
    
     public function getClientPurchaseHistory($clientId) {
        $query = "SELECT v.id, v.date, v.montant_total, p.nom as produit_nom, vp.quantite, p.prix_unitaire
                  FROM ventes v
                  JOIN vente_produit vp ON v.id = vp.vente_id
                  JOIN produits p ON vp.produit_id = p.id
                  WHERE v.client_id = :client_id
                  ORDER BY v.date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':client_id' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
    
    
    
    
}