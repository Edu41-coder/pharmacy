<?php

namespace Core\Database;

use PDO;
use PDOException;
use PDOStatement;
use Core\Exception\DatabaseException;

class Database implements DatabaseInterface
{
    private static ?Database $instance = null;
    private PDO $connection;
    private array $config;

    private function __construct()
    {
        // Charger la configuration
        $this->config = require dirname(__DIR__, 2) . '/config/database.php';
        $dbConfig = $this->config['connections'][$this->config['default']];

        try {
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $dbConfig['driver'],
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['database'],
                $dbConfig['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
            );
        } catch (PDOException $e) {
            throw new DatabaseException(
                "Erreur de connexion à la base de données: " . $e->getMessage()
            );
        }
    }

    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function prepare(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            throw DatabaseException::prepareError(
                "Erreur de préparation de la requête",
                $sql,
                $e
            );
        }
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            throw DatabaseException::queryError(
                "Erreur d'exécution de la requête",
                $sql,
                $params,
                $e
            );
        }
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $values = array_map(fn($field) => ":$field", $fields);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $fields),
            implode(', ', $values)
        );

        $this->query($sql, $data);
        return (int)$this->connection->lastInsertId();
    }

    public function update(string $table, int $id, array $data): bool
    {
        $fields = array_map(
            fn($field) => "$field = :$field",
            array_keys($data)
        );

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $table,
            implode(', ', $fields)
        );

        $data['id'] = $id;
        return $this->query($sql, $data)->rowCount() > 0;
    }

    public function delete(string $table, int $id): bool
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        return $this->query($sql, ['id' => $id])->rowCount() > 0;
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    public function exists(string $table, $id): bool
    {
        $stmt = $this->prepare(
            "SELECT 1 FROM {$table} WHERE id = ? LIMIT 1",
            [$id]
        );
        return (bool) $stmt->fetch();
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}
