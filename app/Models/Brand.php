<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['make', 'description', 'status', 'image', 'pin_code_price','slug'];
    public $translatable = ['description', 'make'];

    public function models(): HasMany
    {
        return $this->hasMany(BrandModel::class);
    }

    public function years(): HasMany
    {
        return $this->hasMany(BrandModelYear::class);

    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
