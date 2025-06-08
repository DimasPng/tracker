<?php

namespace App\Repository;

use App\Enum\UserRole;
use PDO;

class UserRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function create(string $email, string $hashedPassword, UserRole $role): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO users (email, password, role, created_at) 
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ');

        $stmt->execute([$email, $hashedPassword, $role->value]);

        return (int)$this->pdo->lastInsertId();
    }

    public function existsByEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);

        return $stmt->fetch() !== false;
    }

    public function getAllForFilter(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT u.id, u.email, u.role
            FROM users u 
            ORDER BY u.role DESC, u.email
        ');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
