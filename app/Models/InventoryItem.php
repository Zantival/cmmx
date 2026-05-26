<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'stock',
        'unit_price',
        'category',
        'min_stock',
    ];

    protected $casts = [
        'stock' => 'integer',
        'unit_price' => 'decimal:2',
        'min_stock' => 'integer',
    ];

    /**
     * Get stock status label.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Out of Stock';
        }
        if ($this->stock <= $this->min_stock) {
            return 'Low Stock';
        }
        return 'In Stock';
    }
    public function maintenances()
    {
        return $this->belongsToMany(Maintenance::class, 'inventory_item_maintenance')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }
}
