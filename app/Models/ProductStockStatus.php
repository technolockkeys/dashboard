<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStockStatus extends Model
{
    use HasFactory;

    protected $fillable = ['quantity'];

    public function Product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
