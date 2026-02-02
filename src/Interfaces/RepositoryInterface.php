<?php

/**
 * Interfaz base para todos los repositorios
 * Define operaciones CRUD estándar
 */
interface RepositoryInterface {
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

