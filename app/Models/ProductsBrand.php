<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsBrand extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    protected $table = 'products_brands';

    protected $fillable = ['brand_id', 'brand_model_id', 'brand_model_year_id', 'product_id'];
    public function brand_model(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class);
    }

    public function brand_year(): BelongsTo
    {
        return $this->belongsTo(BrandModelYear::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class,'id');
    }
}
