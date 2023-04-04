<?php

use App\Core\Application;


require_once "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$path = str_replace("\\", "/", dirname(__DIR__) . "/app");

$config = [
    'db' => [
        'domainServiceName' => $_ENV['DB_DSN'],
        'dbName' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application($path, $config);
$app->database->applyMigration();
