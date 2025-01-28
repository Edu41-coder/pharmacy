<?php

namespace Core\Database;

use PDO;
use PDOStatement;

/**
 * Interface DatabaseInterface
 * 
 * Interface pour la gestion de la base de données
 * Définit les méthodes essentielles pour interagir avec la base de données
 * 
 * @package Core\Database
 */
interface DatabaseInterface
{
    /**
     * Obtient l'instance unique de la base de données (Pattern Singleton)
     * 
     * @return self Instance unique de la base de données
     */
    public static function getInstance(): self;

    /**
     * Obtient la connexion PDO active
     * 
     * @return PDO Instance de la connexion PDO
     */
    public function getConnection(): PDO;

    /**
     * Prépare et exécute une requête SQL
     * 
     * @param string $sql Requête SQL à préparer
     * @param array $params Paramètres de la requête [optionnel]
     * @return PDOStatement Statement PDO préparé
     * @throws \RuntimeException Si la préparation ou l'exécution échoue
     */
    public function prepare(string $sql, array $params = []): PDOStatement;

    /**
     * Exécute une requête SQL avec des paramètres
     * 
     * @param string $sql Requête SQL à exécuter
     * @param array $params Paramètres de la requête [optionnel]
     * @return PDOStatement Résultat de la requête
     * @throws \RuntimeException Si l'exécution de la requête échoue
     */
    public function query(string $sql, array $params = []): PDOStatement;

    /**
     * Récupère un seul enregistrement
     * 
     * @param string $sql Requête SQL pour la sélection
     * @param array $params Paramètres de la requête [optionnel]
     * @return array|null Tableau associatif des données ou null si aucun résultat
     */
    public function fetchOne(string $sql, array $params = []): ?array;

    /**
     * Récupère tous les enregistrements
     * 
     * @param string $sql Requête SQL pour la sélection
     * @param array $params Paramètres de la requête [optionnel]
     * @return array Tableau de tous les enregistrements
     */
    public function fetchAll(string $sql, array $params = []): array;

    /**
     * Insère des données dans une table
     * 
     * @param string $table Nom de la table
     * @param array $data Données à insérer (tableau associatif colonne => valeur)
     * @return int ID de l'enregistrement inséré
     * @throws \RuntimeException Si l'insertion échoue
     */
    public function insert(string $table, array $data): int;

    /**
     * Met à jour des données dans une table
     * 
     * @param string $table Nom de la table
     * @param int $id ID de l'enregistrement à mettre à jour
     * @param array $data Nouvelles données (tableau associatif colonne => valeur)
     * @return bool True si la mise à jour a réussi, False sinon
     */
    public function update(string $table, int $id, array $data): bool;

    /**
     * Supprime un enregistrement
     * 
     * @param string $table Nom de la table
     * @param int $id ID de l'enregistrement à supprimer
     * @return bool True si la suppression a réussi, False sinon
     */
    public function delete(string $table, int $id): bool;

    /**
     * Démarre une transaction
     * 
     * @return bool True si la transaction a démarré avec succès
     */
    public function beginTransaction(): bool;

    /**
     * Valide la transaction en cours
     * 
     * @return bool True si la validation a réussi
     */
    public function commit(): bool;

    /**
     * Annule la transaction en cours
     * 
     * @return bool True si l'annulation a réussi
     */
    public function rollback(): bool;

    /**
     * Vérifie l'existence d'un enregistrement
     * 
     * @param string $table Nom de la table
     * @param mixed $id ID de l'enregistrement à vérifier
     * @return bool True si l'enregistrement existe, False sinon
     */
    public function exists(string $table, $id): bool;

    /**
     * Retourne l'ID du dernier enregistrement inséré
     * 
     * @return string ID du dernier enregistrement inséré
     */
    public function lastInsertId(): string;
}
