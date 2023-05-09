<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UrlRedirect extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = ['old_url', 'new_url', 'clicks_count'];

    public static function check($path)
    {
        $redirect = UrlRedirect::where('old_url', $path)->first();
        if ($redirect) {
            $redirect->update([
                'clicks_count' => $redirect->clicks_count + 1
            ]);
            return $redirect->new_url;
        }
        return false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
