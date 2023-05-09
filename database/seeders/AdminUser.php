<?php

namespace Database\Seeders;

use App\Models\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        #region create new admin
        $admin = Admin::query()->where('email', 'admin@esg.com')->first();
        if (empty($admin)) {
            $admin = new Admin();
            $admin->name = 'Super Admin';
            $admin->email = 'admin@esg.com';
            $admin->status = 1;
            $admin->verification_token = "";
            $admin->two_factor_expires_at = date('Y-m-d H:i:s', time());
            $admin->password = \Hash::make('password');
            $admin->remember_token = '';
            $admin->two_factor_code = '';
            $admin->two_factor = 0;

            $admin->save();
            $admin = Admin::query()->where('email', 'admin@esg.com')->first();

        }
        #endregion

        #region create new role
        $admin_role = Role::query()->where(['name' => 'SuperAdmin', 'guard_name' => 'admin'])->first();
        if (empty($admin_role)) {
            $admin_role = new Role();
            $admin_role->name = 'SuperAdmin';
            $admin_role->guard_name = 'admin';
            $admin_role->save();
        }
        $admin->assignRole('SuperAdmin');
        #endregion



    }
}
