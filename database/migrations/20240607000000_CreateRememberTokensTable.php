<?php

namespace Database\Migrations;

use App\Core\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $this->createTable('remember_tokens', [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NOT NULL',
            'token' => 'VARCHAR(64) NOT NULL',
            'expires_at' => 'DATETIME NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey('remember_tokens', 'user_id', 'users');

        $this->addIndex('remember_tokens', ['user_id']);
        $this->addIndex('remember_tokens', ['token', 'expires_at'], 'idx_remember_tokens_token_expires');
    }

    public function down(): void
    {
        $this->dropTable('remember_tokens');
    }
};
