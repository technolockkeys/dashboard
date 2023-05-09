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

class Download extends Model
{
    use HasFactory;
    use HasTranslations;
    use SerializeDateTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $hidden = ['id'];

    protected $fillable = ['slug', 'title', 'description', 'meta_title', 'meta_description', 'image', 'internal_image', 'screen_shot', 'gallery', 'video'];

    public $translatable = ['title', 'description', 'meta_title', 'meta_description'];


    public function attributes(): HasMany
    {
        return $this->hasMany(DownloadAttribute::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
