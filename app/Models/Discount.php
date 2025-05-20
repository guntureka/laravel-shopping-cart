<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'product_id', 'min_price', 'day', 'start_time', 'end_time',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
