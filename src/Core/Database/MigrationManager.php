<?php

namespace App\Core\Database;

use PDO;

class MigrationManager
{
    private PDO $db;

    private string $migrationsTable = 'migrations';

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->migrationsTable}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `batch` INT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->db->exec($sql);
    }

    public function getMigrationsPath(): string
    {
        return dirname(__DIR__, 3) . '/database/Migrations';
    }

    public function getMigrationFiles(): array
    {
        $files = glob($this->getMigrationsPath() . '/*.php');
        if ($files === false) {
            return [];
        }
        sort($files);

        return $files;
    }

    public function getRanMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM {$this->migrationsTable} ORDER BY batch, id");

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPendingMigrations(): array
    {
        $files = $this->getMigrationFiles();
        $ran = $this->getRanMigrations();

        return array_filter($files, function ($file) use ($ran) {
            return !in_array(basename($file, '.php'), $ran);
        });
    }

    public function getNextBatchNumber(): int
    {
        $stmt = $this->db->query("SELECT MAX(batch) FROM {$this->migrationsTable}");

        return (int) $stmt->fetchColumn() + 1;
    }

    public function runMigration(string $file): void
    {
        $migration = require $file;

        $migration->up();
        $stmt = $this->db->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([basename($file, '.php'), $this->getNextBatchNumber()]);
    }

    public function rollbackMigration(string $file): void
    {
        $migration = require $file;

        $migration->down();
        $stmt = $this->db->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([basename($file, '.php')]);
    }
}
