<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database\Connection;
use App\Core\Environment\EnvironmentService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\UserSeeder;

try {
    echo "Starting database seeding...\n\n";

    $environmentService = new EnvironmentService();
    Connection::setEnvironmentService($environmentService);

    $pdo = Connection::getInstance();

    $adminSeeder = new AdminSeeder($pdo);
    $adminSeeder->run();

    echo "\n";

    $userSeeder = new UserSeeder($pdo);
    $userSeeder->run();

    echo "\n✅ Database seeding completed successfully!\n";
} catch (Exception $e) {
    echo "\n❌ Seeding failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
