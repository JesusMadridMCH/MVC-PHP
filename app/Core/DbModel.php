<?php

namespace App\Core;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;
    abstract public function schemaName(): string;
    abstract public function columnNames(): array;
    abstract public function primaryKey(): string;
    abstract public function foreignKey(): string;

    public function save($schemaName,$tableName,$columnNames): array
    {
//        print_r($columnNames);
        /* This is for access to tables columns names associated to object attributes */
        $tableColumnNames=array_values($columnNames);
        $attributes=array_keys($columnNames);
        /* This is for access to object attributes */

        $params = array_map(fn($attribute) => ":$attribute", $attributes);

        $statement = self::prepare("INSERT INTO $schemaName.$tableName (".implode(',',$tableColumnNames).") 
                                        VALUES (".implode(",", $params).")");
        foreach ($attributes as $attribute){
            if($this->constructGetterName($attribute)['exists']){
                $getterName=$this->constructGetterName($attribute)['getterMethodName'];
                $statement->bindValue(":$attribute", $this->$getterName());
            }
        }
        try {
            $statement->execute();
            return ["success" => true, "productId" => self::lastIdInserted()];
        }catch (\Exception $e){
            return ["success"=> false, "message" => $e->getMessage()];
        }
    }
    public function checkIfRecordExists($schemaName,$tableName,$values)
    {
        $column=$values['column'];
        $value=$values['value'];
        $statement=self::prepare("SELECT COUNT(1) AS total FROM $schemaName.$tableName WHERE  $column=:$column");
        $statement->bindValue(":$column", $value);
        try {
            $statement->execute();
            $responseDb=$statement->fetch();
            return $responseDb['total']?? false;
        } catch (\Exception $exception){
            echo "<br>  checkIfRecordExists ERROR  ".$exception->getMessage();
        }
    }
    public function getRecordByColumn($schemaName,$tableName,$data = [])
    {
        $statement=self::prepare("SELECT * FROM $schemaName.$tableName WHERE {$data['column']}=:{$data['column']}");
        try {
            $statement->bindValue(":{$data['column']}", $data['value']);
            $statement->execute();
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $exception){
            echo "<br> getRecordByColumn ERROR  ".$exception->getMessage();
        }
    }
    public function getRecordById($schemaName,$tableName,$id)
    {
        $statement=self::prepare("SELECT * FROM $schemaName.$tableName WHERE id=:id");
        try {
            $statement->bindValue(":id", $id);
            $statement->execute();
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $exception){
            echo "<br> getRecordById ERROR  ".$exception->getMessage();
        }
    }

    public static function deleteByColumn($schemaName,$tableName,$data = [])
    {
        $statement=self::prepare("DELETE FROM $schemaName.$tableName WHERE {$data['column']}=:{$data['column']}");
        $statement->bindValue(":{$data['column']}", $data['value']);
        try {
            return $statement->execute();
        } catch (\Exception $exception){
            echo "<br> deleteByColumn ERROR  ".$exception->getMessage();
        }
    }
    public function deleteRecordByColumn($schemaName,$tableName,$data = [])
    {
        $statement=self::prepare("DELETE FROM $schemaName.$tableName WHERE {$data['column']}={$data['value']}");
        try {
            return $statement->execute();
        } catch (\Exception $exception){
            echo "<br>  deleteRecordByColumn ERROR  ".$exception->getMessage();
        }
    }

    public function getAllByType($type)
    {
        $schemaName=$this->schemaName();
        $tableName=$this->tableName();
        $statement=self::prepare("SELECT * FROM $schemaName.$tableName WHERE type=$type");
        try {
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $exception){
            echo "<br> getAllByType ERROR  ".$exception->getMessage();
        }
    }
    public static function prepare($sql)
    {
        return Application::$app->database->pdo->prepare($sql);
    }
    public static function lastIdInserted()
    {
        return Application::$app->database->pdo->lastInsertId();
    }
}