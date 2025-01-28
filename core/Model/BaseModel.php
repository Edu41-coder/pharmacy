<?php

namespace Core\Model;

use Core\Database\Database;

abstract class BaseModel implements ModelInterface
{
    protected static string $table;
    protected static array $fillable = [];
    protected array $attributes = [];
    protected static ?Database $db = null;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        if (in_array($name, static::$fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    public static function findAll(): array
    {
        self::initDb();
        return self::$db->fetchAll("SELECT * FROM " . static::$table);
    }

    public static function findById(int $id): ?self
    {
        self::initDb();
        $data = self::$db->fetchOne(
            "SELECT * FROM " . static::$table . " WHERE id = :id",
            ['id' => $id]
        );

        return $data ? new static($data) : null;
    }

    public static function findBy(string $column, $value): array
    {
        self::initDb();
        return self::$db->fetchAll(
            "SELECT * FROM " . static::$table . " WHERE {$column} = :value",
            ['value' => $value]
        );
    }

    public static function create(array $data): int
    {
        self::initDb();
        try {
            return self::$db->insert(static::$table, $data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function save(): bool
    {
        self::initDb();
        try {
            $data = array_intersect_key($this->attributes, array_flip(static::$fillable));

            if (isset($this->attributes['id'])) {
                return self::$db->update(static::$table, $this->attributes['id'], $data);
            }

            $id = self::$db->insert(static::$table, $data);
            if ($id) {
                $this->attributes['id'] = $id;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function update(int $id, array $data): bool
    {
        self::initDb();
        try {
            return self::$db->update(static::$table, $id, $data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        self::initDb();
        try {
            return self::$db->delete(static::$table, $id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            if (in_array($key, static::$fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    protected static function initDb(): void
    {
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
    }

    // Méthodes utilitaires
    public function getId(): ?int
    {
        return $this->attributes['id'] ?? null;
    }

    public static function getTable(): string
    {
        return static::$table;
    }

    public static function getFillable(): array
    {
        return static::$fillable;
    }
} 