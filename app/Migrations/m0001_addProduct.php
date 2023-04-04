<?php

class m0001_addProduct {
 public function up()
 {
    $db= \App\Core\Application::$app->database;
    $dbName=$db->getDbName();
    $SQL="CREATE TABLE IF NOT EXISTS $dbName.product (
        id INT AUTO_INCREMENT,
        skuCode VARCHAR(255)  PRIMARY KEY NOT NULL,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(20,2) NOT NULL,
        type INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";
    $db->pdo->exec($SQL);
 }

 public function down()
 {
     $db= \App\Core\Application::$app->database;
     $dbName=$db->getDbName();
     $SQL="DROP TABLE $dbName.product;";
     $db->pdo->exec($SQL);
 }
}?>

