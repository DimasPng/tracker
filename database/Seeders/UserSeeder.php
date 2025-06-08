<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use PDO;

class UserSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $email = 'testuser@buycow.com';

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $userCount = $stmt->fetchColumn();

        if ($userCount > 0) {
            echo "Test user already exists. Skipping seeder.\n";

            return;
        }

        $password = password_hash('password123', PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('
            INSERT INTO users (email, password, role, created_at) 
            VALUES (?, ?, ?, NOW())
        ');

        if ($stmt->execute([$email, $password, UserRole::USER->value])) {
            echo "✅ Test user created successfully!\n";
            echo "   Email: {$email}\n";
            echo "   Password: password123\n";
            echo '   Role: ' . UserRole::USER->value . "\n";
        } else {
            echo "❌ Failed to create test user.\n";
        }
    }
}
