<?php

namespace Database\Migrations;

use App\Core\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $this->createTable('activities', [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_session_id' => 'INT NOT NULL',
            'action' => "ENUM('login', 'logout', 'registration', 'page-view-a', 'page-view-b', 'click-buy-cow', 'click-download') NOT NULL",
            'page' => 'VARCHAR(100) NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey('activities', 'user_session_id', 'user_sessions');

        $this->addIndex('activities', ['user_session_id']);
        $this->addIndex('activities', ['action']);
        $this->addIndex('activities', ['created_at']);
    }

    public function down(): void
    {
        $this->dropTable('activities');
    }
};
