<?php

namespace Core\Model;

interface ModelInterface
{
    public function __get(string $name);
    public function __set(string $name, $value): void;
    public static function findAll(): array;
    public static function findById(int $id): ?self;
    public static function findBy(string $column, $value): array;
    public static function create(array $data): int;
    public function save(): bool;
    public static function update(int $id, array $data): bool;
    public static function delete(int $id): bool;
    public function hydrate(array $data): self;
    public function toArray(): array;
} 