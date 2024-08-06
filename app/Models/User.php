<?php

namespace app\Models;

use config\database;
use PDO;
use PDOException;

class User
{
    private $db;

    // Constructeur : initialise la connexion à la base de données
    public function __construct(PDO $db = null)
    {
        if ($db === null) {
            // Si aucune connexion n'est fournie, on en crée une nouvelle
            $this->db = database::getInstance()->connect();
        } else {
            // Sinon, on utilise la connexion fournie
            $this->db = $db;
        }
    }

    // Crée un nouvel utilisateur dans la base de données
    public function create($nom, $prenom, $email, $password, $role_id)
    {
        try {
            // Vérifie si l'email existe déjà
            if ($this->findByEmail($email)) {
                throw new \Exception("Cet email est déjà utilisé.");
            }

            // Prépare et exécute la requête d'insertion
            $sql = "INSERT INTO user (nom, prenom, email, password, role_id) VALUES (:nom, :prenom, :email, :password, :role_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT), // Hachage du mot de passe
                ':role_id' => $role_id
            ]);
            return $this->db->lastInsertId(); // Retourne l'ID du nouvel utilisateur
        } catch (PDOException $e) {
            // Log l'erreur et lance une exception générique
            error_log($e->getMessage());
            throw new \Exception("Une erreur est survenue lors de la création de l'utilisateur.");
        }
    }

    // Trouve un utilisateur par son ID
    public function findById($id)
    {
        $sql = "SELECT * FROM user WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Trouve un utilisateur par son email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Met à jour les informations d'un utilisateur
    public function update($id, $nom, $prenom, $email, $role_id)
    {
        try {
            $sql = "UPDATE user SET nom = :nom, prenom = :prenom, email = :email, role_id = :role_id WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':role_id' => $role_id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Une erreur est survenue lors de la mise à jour de l'utilisateur.");
        }
    }

    // Supprime un utilisateur de la base de données
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM user WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Une erreur est survenue lors de la suppression de l'utilisateur.");
        }
    }

    // Récupère tous les utilisateurs de la base de données
    public function getAllUsers()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Vérifie si le mot de passe fourni correspond à celui de l'utilisateur
    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user) {
            return password_verify($password, $user['password']);
        }
        return false;
    }
}