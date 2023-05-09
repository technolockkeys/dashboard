<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionGroup extends Model
{
    use HasFactory;
    protected $table='permissions_groups';
    function permissions(){
        return $this->hasOne(Permission::class ,'group_id','id')->get();
    }
}
