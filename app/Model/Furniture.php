<?php

namespace App\Model;

use App\Core\ProductModel;

class Furniture extends ProductModel {
    private string $dimensions;
    private string $productId;
    private int $type=1;

    public function tableName(): string
    {
        return 'productType';
    }

    public function schemaName(): string
    {
       return 'onlineStore';
    }
    public function columnNames(): array
    {
        return [
            'type' => 'type',
            'dimensions' => 'measurement_type',
            'productId' => 'skuCode'
        ];
    }

    public function primaryKey(): string
    {
       return 'id';
    }
    public function foreignKey(): string
    {
        return 'skuCode';
    }
    public function rules()
    {
        // TODO: Implement rules() method.
    }

    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
    public function setProductId(string $productId): void
    {
        $this->productId = $this->getSkuCode();
    }

     public function getType(): int
    {
        return $this->type;
    }
}