<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Status extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use HasTranslations;
    use LogsActivity;

    protected $fillable = ['name', 'image', 'order', 'value', 'type'];
    public $translatable = ['name', 'image', 'type', 'value'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
