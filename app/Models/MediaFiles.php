<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class MediaFiles extends Model
{
    use HasFactory;
    use LogsActivity;
    use HasTranslations;
    public $translatable = [
        'description',
        'alt',
        'rel',
        'scale',
        'open_graph',


    ];
    public $fillable=[

        'title',
        'height',
        'description',
        'extension',
        'size',
        'type',
        'rel',
        'width',
        'alt',
        'path',
        'related_images_ids',
        'open_graph',
        'scale'

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
