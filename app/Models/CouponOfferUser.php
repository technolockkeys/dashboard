<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponOfferUser extends Model
{
    use HasFactory;

    protected $table = "coupon_offer_users";
    protected $fillable= ['user_id','coupon_id','offer_id'];
}
