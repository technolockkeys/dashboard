<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsPackages extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    protected $table = "products_packages";

    function Product()
    {
        $this->belongsTo(Product::class);
    }
}
