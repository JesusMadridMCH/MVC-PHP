<?php

class  m0002_add_productType {
    public function up()
    {
        $db= \App\Core\Application::$app->database;
        $dbName=$db->getDbName();
        $SQL="CREATE TABLE IF NOT EXISTS $dbName.productType (
        id INT AUTO_INCREMENT PRIMARY KEY,
        measurement_type VARCHAR(255) NOT NULL,
        skuCode_fk VARCHAR(255)  NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $db= \App\Core\Application::$app->database;
        $dbName=$db->getDbName();
        $SQL="DROP TABLE $dbName.productType;";
        $db->pdo->exec($SQL);
    }
}