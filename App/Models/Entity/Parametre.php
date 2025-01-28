<?php

namespace App\Models;

use config\Database;

class Parametre {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère le taux de TVA actuel
     * @return float Le taux de TVA
     */
    public function getTauxTVA() {
        return $this->getParametre('taux_tva') ?? 20.0; // Retourne 20% par défaut si non trouvé
    }

    /**
     * Met à jour le taux de TVA
     * @param float $nouveauTaux Le nouveau taux de TVA
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function updateTauxTVA($nouveauTaux) {
        return $this->updateParametre('taux_tva', $nouveauTaux);
    }

    /**
     * Récupère un paramètre par son nom
     * @param string $nom Le nom du paramètre
     * @return mixed La valeur du paramètre ou null si non trouvé
     */
    public function getParametre($nom) {
        $query = "SELECT valeur FROM parametres WHERE nom = :nom";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':nom' => $nom]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $result ? $result['valeur'] : null;
    }

    /**
     * Met à jour un paramètre
     * @param string $nom Le nom du paramètre
     * @param mixed $valeur La nouvelle valeur du paramètre
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function updateParametre($nom, $valeur) {
        $query = "UPDATE parametres SET valeur = :valeur WHERE nom = :nom";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':nom' => $nom, ':valeur' => $valeur]);
    }

    /**
     * Ajoute un nouveau paramètre
     * @param string $nom Le nom du paramètre
     * @param mixed $valeur La valeur du paramètre
     * @return bool True si l'ajout a réussi, false sinon
     */
    public function addParametre($nom, $valeur) {
        $query = "INSERT INTO parametres (nom, valeur) VALUES (:nom, :valeur)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':nom' => $nom, ':valeur' => $valeur]);
    }

    /**
     * Vérifie si un paramètre existe
     * @param string $nom Le nom du paramètre
     * @return bool True si le paramètre existe, false sinon
     */
    public function parametreExists($nom) {
        $query = "SELECT COUNT(*) FROM parametres WHERE nom = :nom";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':nom' => $nom]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Récupère tous les paramètres
     * @return array Un tableau associatif de tous les paramètres
     */
    public function getAllParametres() {
        $query = "SELECT nom, valeur FROM parametres";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}