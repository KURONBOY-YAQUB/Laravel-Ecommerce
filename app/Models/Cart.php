<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_color_id',
        'quantity'
    ];

    /**
     * Get the product that owns the Cart
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the productColor that owns the Cart
     *
     * @return BelongsTo
     */
    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id', 'id');
    }

}
