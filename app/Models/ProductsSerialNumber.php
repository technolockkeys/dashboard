<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsSerialNumber extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    protected $table = 'products_serial_numbers';

    public function product():BelongsTo
    {
        return  $this->belongsTo(Product::class);
    }
}
