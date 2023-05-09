<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Offer extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use LogsActivity;

    protected $casts = [
        'products_ids' => 'array'
    ];

    protected $fillable = ['from', 'to', 'days', 'discount', 'discount_type', 'type', 'minimum_shopping', 'products_ids', 'free_shipping', 'status'];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
