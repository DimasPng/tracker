<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use PDO;

class AdminSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE role = ?');
        $stmt->execute([UserRole::ADMIN->value]);
        $adminCount = $stmt->fetchColumn();

        if ($adminCount > 0) {
            echo "Admin user already exists. Skipping seeder.\n";

            return;
        }

        $email = 'admin@buycow.com';
        $password = password_hash('admin123', PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('
            INSERT INTO users (email, password, role, created_at) 
            VALUES (?, ?, ?, NOW())
        ');

        if ($stmt->execute([$email, $password, UserRole::ADMIN->value])) {
            echo "✅ Admin user created successfully!\n";
            echo "   Email: {$email}\n";
            echo "   Password: admin123\n";
            echo '   Role: ' . UserRole::ADMIN->value . "\n";
        } else {
            echo "❌ Failed to create admin user.\n";
        }
    }
}
