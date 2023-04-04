<?php

ini_set('display_errors','on');
error_reporting(E_ALL & ~E_DEPRECATED);

use App\Controller\AuthController;
use App\Controller\ProductController;
use App\Core\Application;
use App\Controller\SiteController;

require_once "../vendor/autoload.php";
$dotenv=Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$path=str_replace("\\","/",dirname(__DIR__)."/app");

$config = [
    'db' => [
        'domainServiceName' => $_ENV['DB_DSN'],
        'dbName' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application($path, $config);

$app->router->post("/addproduct", [ProductController::class, 'addProduct']);
$app->router->get("/products", [ProductController::class, 'getProducts']);
$app->router->get("/deleteproduct", [ProductController::class, 'deleteProduct']);


$app->router->get("/", [SiteController::class, 'productList']);
$app->router->get("/addProduct", [SiteController::class, 'addProduct']);
$app->router->get("/contact", [SiteController::class, 'contact']);
$app->router->post("/contact", [SiteController::class, "handleContact"]);

$app->run();
