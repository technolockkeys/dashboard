<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserWallet extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'user_wallet';
    //type
    public static $refund = "refund";
    public static $order = "order";
    public static $withdraw = "withdraw";
    //status
    public static $amount = "amount";
    public static $approve = "approve";
    public static $pending = "pending";
    public static $cancelled = "cancelled";
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'type',
        'status',
        'files',
        'create_by_type',
        'create_by_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function create_by()
    {
        return $this->morphTo('create_by');
    }
  public function order_payment()
    {
        return $this->belongsTo(OrderPayment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
