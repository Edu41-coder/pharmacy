<?php

namespace config;

use PDO;
use PDOException;
use Exception;

class database
{
    private static $instance = null;
    private $connection;
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;
    private $options;

    private function __construct($host, $dbname, $username, $password, $charset = 'utf8mb4')
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    public static function getInstance($host = 'localhost', $dbname = 'pharmacie', $username = 'root', $password = '', $charset = 'utf8mb4')
    {
        if (self::$instance === null) {
            self::$instance = new self($host, $dbname, $username, $password, $charset);
        }
        return self::$instance;
    }

    public function connect()
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            try {
                $this->connection = new PDO($dsn, $this->username, $this->password, $this->options);
            } catch (PDOException $e) {
                // En production, loggez cette erreur plutôt que de l'afficher
                throw new PDOException("Erreur de connexion : " . $e->getMessage());
            }
        }
        return $this->connection;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}