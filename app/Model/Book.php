<?php

namespace App\Model;

use App\Core\ProductModel;

class Book extends ProductModel
{
    private float $weight;
    private string $productId;
    private int $type=2;
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
            'weight' => 'measurement_type',
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

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
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