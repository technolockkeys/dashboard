<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersProducts extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    function product(){
        return $this->belongsTo(Product::class ,'product_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id', 'id');
    }
}
