<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsages extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    protected $table = 'coupon_usages';

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
