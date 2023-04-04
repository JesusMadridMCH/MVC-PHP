<?php

namespace App\Core;

class Database
{

    public \PDO $pdo;
    private string $domainServiceName;
    private string $dbName;
    private string $user;
    private string $password;

    public function __construct(array $config)
    {
        $this->domainServiceName=$config['domainServiceName']??'';
        $this->dbName=$config['dbName']??'';
        $this->user=$config['user']??'';
        $this->password=$config['password']??'';
        $this->pdo = new \PDO($this->domainServiceName, $this->user, $this->password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigration()
    {
        $this->createMigrationsTable();
        $appliedMigrations=$this->getAppliedMigrations();
        $newMigrations=[];
        $files=scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations=array_diff($files,$appliedMigrations);
        foreach ($toApplyMigrations as $migration)
        {
            if(in_array($migration, array('.', '..')))
                continue;

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className=pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            echo "Applying migration $migration".PHP_EOL;
            $instance->up();
            echo "Applied migration $migration".PHP_EOL;
            array_push($newMigrations, $migration);
        }
        if(!empty($newMigrations))
            $this->saveMigrations($newMigrations);
        else
            echo "All migrations were applied";
    }
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;");
    }
    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    public function saveMigrations(array $newMigrations)
    {
        foreach ($newMigrations as $newMigration){
            $statement = $this->pdo->prepare("INSERT INTO {$this->dbName}.migrations (migration) VALUES ('$newMigration')");
            $statement->execute();
        }
    }
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * @return string
     */
    public function getDomainServiceName(): string
    {
        return $this->domainServiceName;
    }

    /**
     * @param string $domainServiceName
     */
    public function setDomainServiceName(string $domainServiceName): void
    {
        $this->domainServiceName = $domainServiceName;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}