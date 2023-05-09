<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Manufacturer extends Model
{
    use HasFactory;
    use HasTranslations;
    use SerializeDateTrait;
    use SoftDeletes;
    use LogsActivity;

    public $translatable = ['title', 'description', 'meta_title', 'meta_description'];
    protected $fillable = ['title', 'meta_title', 'description', 'meta_description', 'image', 'status', 'slug', 'software', 'token'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
