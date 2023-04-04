<?php

namespace App\Core;

abstract class ProductModel extends DbModel
{
    private string $skuCode;
    private string $name;
    private float $price=0.00;

    public function parentTableName(): string
    {
        return 'product';
    }
    public function parentSchemaName(): string
    {
        return 'onlineStore';
    }
    public function parentPrimaryKey(): string
    {
        return 'skuCode';
    }

    public function parentPrimaryKeyValue():string
    {
        $getterMethod=$this->constructGetterName($this->parentPrimaryKey())['getterMethodName']?? '';
        return $this->$getterMethod();
    }
    public function parentColumnNames():array
    {
        return [
            'skuCode' => 'skuCode',
            'name' => 'name',
            'price' => 'price',
        ];
    }
    public function getSkuCode(): string
    {
        return $this->skuCode;
    }
    public function setSkuCode(string $skuCode): void
    {
        $this->skuCode = $skuCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price): void
    {
        $this->price = floatval($price);
    }
}