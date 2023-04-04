<?php

namespace App\Model;

use App\Core\ProductModel;

class DvdDisk extends ProductModel
{
    private float $size;
    private string $productId;
    private int $type=3;

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
            'size' => 'measurement_type',
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

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
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