<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerEarning extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    protected $table= 'seller_earning';

}
