<?php

namespace App\Core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public function loadData($data)
    {
        foreach ($data as $key=>$value)
        {
            $key=ucwords($key);
            $callMethod="set".$key;
            if(method_exists($this, $callMethod) && is_callable([$this, $callMethod]))
            {
                $this->{$callMethod}($value);
            }
        }
    }
    public function constructGetterName($attribute):array
    {
        $attribute=ucwords($attribute);
        $getterMethod="get".$attribute;
        if(method_exists($this, $getterMethod) && is_callable([$this, $getterMethod]))
        {
            return ["exists" => true, "getterMethodName" => $getterMethod];
        }
        return ["exists" => false];
    }
    public function setRealKeys($array, $realKeys)
    {
        $oldKeys=array_keys($array);
        $newKeys=[];
        for($i=0;$i<count($oldKeys); $i++){
            for($j=0;$j<count($realKeys); $j++){
                if(strpos($oldKeys[$i], $realKeys[$j])!==false){
                    $newKeys[$oldKeys[$i]]=substr($oldKeys[$i], strpos($oldKeys[$i], $realKeys[$j]));
                }
            }
        }
        foreach($newKeys as $key => $value){
            $array[$value]=$array[$key];
            unset($array[$key]);
        }
        return $array;
    }
    abstract public function rules();
    public array $errors=[];
    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules)
        {
            $value=$this->{$attribute};
            foreach($rules as $rule)
            {
                $ruleName=$rule;
                if(!is_string($ruleName)){
                    $ruleName=$rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value)<$rule['min']){
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value)>$rule['max']){
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName === self::RULE_MATCH && $value!==$this->{$rule['match']}){
                    $rule['match']=$this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $className=$rule['class'];
                    $uniqueAttribute=$rule['attribute']?? $attribute;
                    $schemaName=$className::schemaName();
                    $tableName=$className::tableName();
                    $statement = Application::$app->database->pdo->prepare("SELECT * FROM $schemaName.$tableName WHERE $uniqueAttribute= :attribute");
                    $statement->bindValue(":attribute", $value);
                    $statement->execute();
                    $record=$statement->fetchObject();
                    if($record)
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                }
            }
        }
        return empty($this->errors);
    }
    public function labels(): array
    {
        return [];
    }
    public function getLabel($attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
    private function addErrorForRule(string $attribute, string $rule, $params=[])
    {
        $message = $this->errorMessages()[$rule]??'';
        foreach ($params as $key=>$value){
            $message=str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][]=$message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][]=$message;
    }
    public function errorMessages(){
        return [
            self::RULE_REQUIRED => "This field is required",
            self::RULE_EMAIL => "This field must be a valid email address",
            self::RULE_MIN => "Min length of this field must be {min}",
            self::RULE_MAX => "Min length of this field must be {max}",
            self::RULE_MATCH => "This field must be the same as {match}",
            self::RULE_UNIQUE => "Record with this {field} already exists",
        ];
    }

    public function hashError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}