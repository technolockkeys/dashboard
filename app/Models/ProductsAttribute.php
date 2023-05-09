<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsAttribute extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    protected $table = 'products_attributes';

    function sub_attribute()
    {
        return $this->belongsTo(SubAttribute::class)->with('attribute');


    }
}
