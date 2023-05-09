<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_role = Role::query()->where(['name' => 'SuperAdmin', 'guard_name' => 'admin'])->first();
        if (empty($super_role)) {
            $super_role = new Role();
            $super_role->name = 'SuperAdmin';
            $super_role->guard_name = 'admin';
            $super_role->save();
        }

        //region remove permissions
        $removed_permissions = [
            'add sub attribute', 'change visibility product',
            'change super sales product', 'change today deal product', 'show product series number', 'change download download',
            'create cart', 'edit cart', 'delete cart', 'change status cart',
            'show cities', 'create city', 'edit city', 'delete city', 'change status city',
            'create zone', 'create wallet','create wishlist', 'edit wishlist','create review', 'edit review',
            'delete zone', 'change status zone', 'delete',
            'create statistic', 'edit statistic', 'delete statistic', 'change status statistic', 'change status redirect',
            'create card', 'edit card', 'delete card', 'change status card',
            'create compare', 'edit compare', 'delete compare', 'change status compare', 'create wallet', 'change status wallet',
            'show downloads', 'create download', 'edit download', 'delete download', 'change status download', 'delete order', 'change to order'
        ];
        foreach ($removed_permissions as $permission) {
            $permission = Permission::query()->where('name', $permission)->with('roles')->first();
            $permission ?->roles()->detach();
            $permission ?->forceDelete();
        }
        $removed_groups = ['user downloads'];
        foreach ($removed_groups as $group) {
            PermissionGroup::where('name', $group)->first() ?->forceDelete();
        }
        //endregion

        $groups = [];

        #region management user
        $groups[] = [
            'title' => 'management user',
            'guard' => 'admin',
            'permission' => ['show admins', 'create admin', 'edit admin', 'delete admin', 'change status admin']
        ];
        #endregion

        #region management user
        $groups[] = [
            'title' => 'user',
            'guard' => 'admin',
            'permission' => ['show users', 'create user', 'edit user', 'delete user', 'change status user']
        ];
        #endregion

        #region management role
        $groups[] = [
            'title' => 'management role',
            'guard' => 'admin',
            'permission' => ['show roles', 'create role', 'edit role', 'delete role']
        ];
        #endregion

        #region media
        $groups[] = [
            'title' => 'media',
            'guard' => 'admin',
            'permission' => ['show media', 'upload media', 'edit media', 'delete media', 'create new folder']
        ];
        #endregion

        #region setting
        $groups[] = [
            'title' => 'setting',
            'guard' => 'admin',
            'permission' => ['setting website', 'setting smtp', 'setting social', 'setting global_seo',
                'setting contact', 'setting translate', 'setting shipping', 'setting default_images', 'setting frontend',
                'setting notifications'
            ]
        ];
        #endregion

        #region setting payment
        $groups[] = [
            'title' => 'setting payment',
            'guard' => 'admin',
            'permission' => ['setting payment', 'paypal update', 'strip update']
        ];
        #endregion

        #region manage location
        $groups[] = [
            'title' => 'location countries',
            'guard' => 'admin',
            'permission' => ['show countries', 'change status country']
        ];
//        $groups[] = [
//            'title' => 'location cities',
//            'guard' => 'admin',
//            'permission' => ['show cities', 'create city', 'edit city', 'delete city', 'change status city',]
//        ];
        #endregion

        #region manage color
        $groups[] = [
            'title' => 'color',
            'guard' => 'admin',
            'permission' => ['show colors', 'create color', 'edit color', 'delete color', 'change status color']
        ];
        #endregion

        #region categories
        $groups[] = [
            'title' => 'categories',
            'guard' => 'admin',
            'permission' => ['show category', 'create category', 'edit category', 'delete category', 'change status category']
        ];
        #endregion

        #region languages
        $groups[] = [
            'title' => 'languages',
            'guard' => 'admin',
            'permission' => ['show languages', 'create language', 'edit language', 'delete language', 'change status language']
        ];
        #endregion

        #region attributes
        $groups[] = [
            'title' => 'attributes',
            'guard' => 'admin',
            'permission' => ['show attributes', 'create attribute', 'edit attribute', 'delete attribute', 'change status attribute']
        ];
        #endregion

        #region sub attributes
        $groups[] = [
            'title' => 'sub attributes',
            'guard' => 'admin',
            'permission' => ['show sub attributes', 'create sub attribute', 'edit sub attribute', 'delete sub attribute', 'change status sub attribute']
        ];
        #endregion

        #region Pages
        $groups[] = [
            'title' => 'pages',
            'guard' => 'admin',
            'permission' => ['show pages', 'create page', 'edit page', 'delete page', 'change status page']
        ];
        #endregion

        #region brands
        $groups[] = [
            'title' => 'brands (cars)',
            'guard' => 'admin',
            'permission' => ['show brands', 'create brand', 'edit brand', 'delete brand', 'change status brand']
        ];
        #endregion

        #region models
        $groups[] = [
            'title' => 'models',
            'guard' => 'admin',
            'permission' => ['show models', 'create model', 'edit model', 'delete model', 'change status model']
        ];
        #endregion

        #region years
        $groups[] = [
            'title' => 'years',
            'guard' => 'admin',
            'permission' => ['show years', 'create year', 'edit year', 'delete year', 'change status year']
        ];
        #endregion

        #region coupons
        $groups[] = [
            'title' => 'coupons',
            'guard' => 'admin',
            'permission' => ['show coupons', 'create coupon', 'edit coupon', 'delete coupon', 'change status coupon']
        ];
        #endregion

        #region tickets
        $groups[] = [
            'title' => 'tickets',
            'guard' => 'admin',
            'permission' => ['show tickets', 'edit ticket', 'delete ticket']
        ];
        #endregion

        #region ticket replies
        $groups[] = [
            'title' => 'replies',
            'guard' => 'admin',
            'permission' => ['show replies', 'create reply', 'edit reply', 'delete reply']
        ];
        #endregion

        #region user address
        $groups[] = [
            'title' => 'user address',
            'guard' => 'admin',
            'permission' => ['show user addresses', 'create user address', 'edit user address', 'delete user address']
        ];
        #endregion

        #region product
        $groups[] = [
            'title' => 'products',
            'guard' => 'admin',
            'permission' => ['show product', 'import product', 'show product serial number', 'create product', 'edit product', 'delete product', 'change status product', 'change feature product', 'change best seller product', 'change free shipping product']
        ];
        #endregion

        #region wishlists
        $groups[] = [
            'title' => 'user wishlists',
            'guard' => 'admin',
            'permission' => ['show wishlists',  'delete wishlist']
        ];
        #endregion

        #region reviews
        $groups[] = [
            'title' => 'user reviews',
            'guard' => 'admin',
            'permission' => ['show reviews',  'delete review' ,'change status review' ]
        ];
        #endregion

        #region statuses
        $groups[] = [
            'title' => 'user statuses',
            'guard' => 'admin',
            'permission' => ['show statuses', 'create status', 'edit status', 'delete status', 'change status status']
        ];
        #endregion

        #region carts
        $groups[] = [
            'title' => 'user carts',
            'guard' => 'admin',
            'permission' => ['show carts']
        ];
        #endregion

        #region cards
        $groups[] = [
            'title' => 'user cards',
            'guard' => 'admin',
            'permission' => ['show cards']
        ];
        #endregion

        #region compares
        $groups[] = [
            'title' => 'user compares',
            'guard' => 'admin',
            'permission' => ['show compares']
        ];
        #endregion

        #region user wallet
        $groups[] = [
            'title' => 'user wallet',
            'guard' => 'admin',
            'permission' => ['show wallets']
        ];
        #endregion

        #region menus
        $groups[] = [
            'title' => 'menus',
            'guard' => 'admin',
            'permission' => ['show menus', 'create menu', 'edit menu', 'delete menu', 'change status menu']
        ];
        #endregion

        #region zone
        $groups[] = [
            'title' => 'zone',
            'guard' => 'admin',
            'permission' => ['show zones', 'edit zone']
        ];
        #endregion

        #region order
        $groups[] = [
            'title' => 'order',
            'guard' => 'admin',
            'permission' => ['show orders', 'create order', 'edit order', 'send pdf order', 'change status order',  'refund order']
        ];
        #endregion

        #region seller
        $groups[] = [
            'title' => 'seller',
            'guard' => 'admin',
            'permission' => ['show sellers', 'create seller', 'edit seller', 'delete seller', 'change status seller', 'show commission seller', 'add commission seller', 'delete commission seller']
        ];
        //slider
        $groups[] = [
            'title' => 'slider',
            'guard' => 'admin',
            'permission' => ['show sliders', 'create slider', 'edit slider', 'delete slider', 'change status slider']
        ];
        #endregion

        #region currency
        $groups[] = [
            'title' => 'currency',
            'guard' => 'admin',
            'permission' => ['show currencies', 'create currency', 'edit currency', 'delete currency', 'change status currency']
        ];
        #endregion

        #region manufacturer
        $groups[] = [
            'title' => 'manufacturer',
            'guard' => 'admin',
            'permission' => ['show manufacturers', 'create manufacturer', 'edit manufacturer', 'delete manufacturer', 'change status manufacturer']
        ];
        #endregion

        #region statistic
        $groups[] = [
            'title' => 'statistic',
            'guard' => 'admin',
            'permission' => ['show statistics']
        ];
        #endregion

        #region redirect
        $groups[] = [
            'title' => 'redirect',
            'guard' => 'admin',
            'permission' => ['show redirects', 'create redirect', 'edit redirect', 'delete redirect']
        ];
        #endregion

        #region seller earning
        $groups[] = [
            'title' => 'seller earning',
            'guard' => 'admin',
            'permission' => ['show seller earnings']
        ];
        #endregion

        #region offers
        $groups[] = [
            'title' => 'offers',
            'guard' => 'admin',
            'permission' => ['show offers', 'create offer', 'edit offer', 'delete offer', 'change status offer']
        ];
        #endregion

        #region contact us
        $groups[] = [
            'title' => 'contact us',
            'guard' => 'admin',
            'permission' => ['show contact us']
        ];
        #endregion

        #region create whatsnew
        $groups[] = [
            'title' => 'whatsnew',
            'guard' => 'admin',
            'permission' => ['create whatsnew', 'show whatsnews']
        ];
        #endregion

        #region notifications
        $groups[] = [
            'title' => 'notifications',
            'guard' => 'admin',
            'permission' => ['show notifications']
        ];
        #endregion

        #region user wallet
        $groups[] = [
            'title' => 'user_wallet',
            'guard' => 'admin',
            'permission' => ['show user wallet', 'approve user wallet', 'change user balance']
        ];
        #endregion

        #region downloads
        $groups[] = [
            'title' => 'downloads',
            'guard' => 'admin',
            'permission' => ['show downloads', 'create download', 'edit download', 'delete download', 'change status download']
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
