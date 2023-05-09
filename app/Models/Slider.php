<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Slider extends Model
{
    use HasFactory;
    use HasTranslations;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = ['image', 'link', 'status', 'type'];
    public $translatable = ['image', 'link'];
//    public $main = 'main';
    public $banner = 'banner';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
