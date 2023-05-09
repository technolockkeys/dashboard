<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cart extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use LogsActivity;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function api_product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with(['category', 'brands']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function sub_attributes()
    {
        $attributes = SubAttribute::whereIn('id', $this->attributes)->with('attribute')->groupBy('attribute_id')->get();

        $data = [];
        foreach ($attributes as $attribute) {
            $data[$attribute->attribute->name][] = $attribute->value;
        }

        return $data;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
