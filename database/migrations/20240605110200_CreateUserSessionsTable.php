<?php

namespace Database\Migrations;

use App\Core\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $this->createTable('user_sessions', [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'INT NULL',
            'session_id' => 'VARCHAR(128) NOT NULL UNIQUE',
            'ip_address' => 'VARCHAR(45) NOT NULL',
            'user_agent' => 'TEXT NULL',
            'started_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'last_activity' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'is_active' => 'BOOLEAN DEFAULT TRUE',
        ]);

        $this->addForeignKey('user_sessions', 'user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->addIndex('user_sessions', ['session_id', 'is_active']);
        $this->addIndex('user_sessions', ['user_id', 'is_active']);
        $this->addIndex('user_sessions', ['last_activity', 'is_active']);
    }

    public function down(): void
    {
        $this->dropTable('user_sessions');
    }
};
