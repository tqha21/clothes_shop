<?php

namespace App\Exceptions;

class InsufficientStockException extends BusinessException
{
    public int $productId;
    public int $requested;
    public int $available;

    public function __construct(int $productId, string $productName, int $requested, int $available, ?string $storeName = null)
    {
        $this->productId = $productId;
        $this->requested = $requested;
        $this->available = $available;

        $where = $storeName ? " tại {$storeName}" : '';
        parent::__construct("Sản phẩm \"{$productName}\" chỉ còn {$available} sản phẩm{$where}, không đủ {$requested} sản phẩm yêu cầu.");
    }
}

