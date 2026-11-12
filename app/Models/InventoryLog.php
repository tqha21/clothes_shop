<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    public const TYPES = [
        'import' => 'Nhập kho',
        'export' => 'Xuất kho',
        'adjustment' => 'Điều chỉnh',
        'stocktake' => 'Kiểm kê',
        'sale' => 'Bán hàng',
        'return' => 'Khách trả hàng',
        'cancel_restore' => 'Hoàn kho do hủy đơn',
        'transfer_in' => 'Nhận chuyển kho',
        'transfer_out' => 'Chuyển kho đi',
    ];

    protected $fillable = [
        'product_id',
        'store_id',
        'order_id',
        'user_id',
        'type',
        'source',
        'reference_type',
        'reference_id',
        'quantity_changed',
        'balance_after',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
