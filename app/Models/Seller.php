<?php

namespace App\Models;

use App\Traits\MediaUploadingTrait;
use App\Traits\SerializeDateTrait;
use Google\Service\Iam\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seller extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use Notifiable;
    use MediaUploadingTrait;
    use HasRoles;
    use LogsActivity;


    protected $guard = 'seller';
    protected $guard_name = "seller";
    public $guarded = 'seller';
    protected $hidden = ['password', 'remember_token'];

    protected $fillable = ['name', 'email', 'password', 'avatar', 'status', 'seller_product_rate', 'seller_manger', 'whatsapp_number', 'phone','skype','facebook'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class, 'seller_manger');
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasMany(SellerWallet::class);
    }

    public function wallet_create_by()
    {
        return $this->morphOne(UserWallet::class, 'create_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function notifications(): MorphOne
    {
        return $this->morphOne(Notification::class, 'receiver');
    }
}
