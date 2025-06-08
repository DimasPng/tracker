<?php

namespace Database\Migrations;

use App\Core\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $this->createTable('users', [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'email' => 'VARCHAR(255) NOT NULL UNIQUE',
            'password' => 'VARCHAR(255) NOT NULL',
            'role' => "ENUM('user', 'admin') DEFAULT 'user'",
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->addIndex('users', ['email']);
        $this->addIndex('users', ['role']);
    }

    public function down(): void
    {
        $this->dropTable('users');
    }
};
