<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = ['country_id', 'state', 'city', 'address', 'street', 'postal_code', 'phone', 'is_default'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function get_country()
    {
        return $this->country?->name;
    }

    public function get_country_with_flag()
    {
        $counrty = $this->country;
        if (!empty($counrty)) {
            return "<img width='20' src='" . asset("backend/assets/media/flags/" . $counrty->name . ".svg") . "'>" . $counrty->name;
        }
        return "-";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
