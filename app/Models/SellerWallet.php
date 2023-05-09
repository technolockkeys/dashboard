<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SellerWallet extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use LogsActivity;


    protected $table = 'sellers_wallet';
    //type
    public static $refund = 'refund';
    public static $withdraw = 'withdraw';
    public static $commission = 'commission';
    //status
    public static $approve = 'approve';
    public static $pending = 'pending';
    public static $waiting = 'waiting';
    public static $cancelled = 'cancelled';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

}
