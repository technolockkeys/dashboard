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

class Attribute extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use HasTranslations;
    use LogsActivity;

    public $translatable = ['name', 'image'];

    public function sub_attributes(): HasMany
    {
        return $this->hasMany(SubAttribute::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
