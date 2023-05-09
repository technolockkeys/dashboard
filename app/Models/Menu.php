<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use HasFactory;
    use HasTranslations;
    use SerializeDateTrait;
    use LogsActivity;


    protected $fillable = ['type', 'link', 'title', 'status', 'icon'];

    public $translatable = ['title'];

    public static function types()
    {
        return [
            'header' => trans('backend.menu.header'),
            'footer_column_1' => trans('backend.menu.footer_column_1'),
            'footer_column_2' => trans('backend.menu.footer_column_2')
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
