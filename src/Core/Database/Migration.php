<?php

namespace App\Core\Database;

use PDO;

abstract class Migration
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    abstract public function up(): void;

    abstract public function down(): void;

    protected function execute(string $sql): void
    {
        $this->db->exec($sql);
    }

    protected function createTable(string $tableName, array $columns): void
    {
        $columnDefinitions = [];
        foreach ($columns as $name => $definition) {
            $columnDefinitions[] = "`$name` $definition";
        }

        $sql = sprintf(
            'CREATE TABLE IF NOT EXISTS `%s` (%s) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
            $tableName,
            implode(', ', $columnDefinitions)
        );

        $this->db->exec($sql);
    }

    protected function dropTable(string $tableName): void
    {
        $sql = sprintf('DROP TABLE IF EXISTS `%s`', $tableName);
        $this->db->exec($sql);
    }

    protected function addForeignKey(
        string $tableName,
        string $columnName,
        string $referencedTable,
        string $referencedColumn = 'id',
        string $onDelete = 'CASCADE',
        string $onUpdate = 'CASCADE',
        ?string $constraintName = null
    ): void {
        $constraintName = $constraintName ?: "fk_{$tableName}_{$columnName}";

        $sql = sprintf(
            'ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`) ON DELETE %s ON UPDATE %s',
            $tableName,
            $constraintName,
            $columnName,
            $referencedTable,
            $referencedColumn,
            $onDelete,
            $onUpdate
        );

        $this->db->exec($sql);
    }

    protected function addIndex(string $tableName, array $columns, ?string $indexName = null): void
    {
        $indexName = $indexName ?: 'idx_' . $tableName . '_' . implode('_', $columns);
        $columnList = '`' . implode('`, `', $columns) . '`';

        $sql = sprintf('ALTER TABLE `%s` ADD INDEX `%s` (%s)', $tableName, $indexName, $columnList);
        $this->db->exec($sql);
    }
}
