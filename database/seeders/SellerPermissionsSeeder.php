<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SellerPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_role = Role::query()->where(['name' => 'Super Seller', 'guard_name' => 'seller'])->first();
        $normal_role = Role::query()->where(['name' => 'Normal Seller', 'guard_name' => 'seller'])->first();

        if (empty($super_role)) {
            $super_role = new Role();
            $super_role->name = 'Super Seller';
            $super_role->guard_name = 'seller';
            $super_role->save();
        }
        if (empty($normal_role)) {
            $normal_role = new Role();
            $normal_role->name = 'Normal Seller';
            $normal_role->guard_name = 'seller';
            $normal_role->save();
        }
        $groups = [];

        #region management user
        $groups[] = [
            'title' => 'management user',
            'guard' => 'seller',
            'permission' => ['show seller users', 'create seller user', 'show seller user',
                'edit seller user' , 'edit address seller user' , 'show address seller user',
                'show payment recodes seller user' , 'edit payment recodes seller user' , 'show orders seller user' ]
        ];
        #endregion

        #region management order
        $groups[] = [
            'title' => 'management order',
            'guard' => 'seller',
            'permission' => ['show orders', 'create order','refund order','send pdf order', 'edit order']
        ];
        #endregion

        #region management wallet
        $groups[] = [
            'title' => 'management wallet',
            'guard' => 'seller',
            'permission' => ['show wallet']
        ];
        #endregion

        #region management seller
        $groups[] = [
            'title' => 'management seller',
            'guard' => 'seller',
            'permission' => ['show sellers']
        ];
        #endregion

        #region commission
        $groups[] = [
            'title' => 'management commission',
            'guard' => 'seller',
            'permission' => ['show commission']
        ];
        #endregion

        #region after sale
        $groups[] = [
            'title' => 'management after sales',
            'guard' => 'seller',
            'permission' => ['show after sales', 'send after sales', 'resend after sales', 'set feedback after sales']
        ];
        #endregion

        if (!empty($groups)) {
            foreach ($groups as $group) {
                $check_group = PermissionGroup::query()->where(['guard_name' => $group['guard'], 'name' => $group['title']])->first();

                if (empty($check_group)) {
                    $check_group = new PermissionGroup();
                    $check_group->guard_name = $group['guard'];
                    $check_group->name = $group['title'];
                    $check_group->save();
                }

                if (!empty($group['permission'])) {
                    foreach ($group['permission'] as $perm) {

                        $permission = Permission::query()
                            ->where('name', $perm)
                            ->where("guard_name", $check_group->guard_name)
                            ->where("group_id", $check_group->id)->first();

                        if (empty($permission)) {
                            $permission = new Permission();
                            $permission->name = $perm;
                            $permission->guard_name = $check_group->guard_name;
                            $permission->group_id = $check_group->id;
                            $permission->save();
                        }
                        $super_role->givePermissionTo($permission);
                    }
                }


            }
        }
    }
}
