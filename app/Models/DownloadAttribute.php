<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DownloadAttribute extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = ['download_id', 'name', 'link', 'type'];

    public function download(): BelongsTo
    {
        return $this->belongsTo(Download::class);
    }

    public static function types()
    {
        return ['software', 'maker', 'driver', 'extra', 'user_manual', 'configuration'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
