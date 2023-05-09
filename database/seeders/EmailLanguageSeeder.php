<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailLanguageSeeder extends Seeder
{
    function translations($array, $local)
    {

        foreach ($array as $item) {
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', 'email')
                ->where('locale', $local)
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = 0;
            $translation->locale = $local;
            $translation->group = 'email';
            $translation->value = $item['value'];
            $translation->save();

        }
    }

    public function run()
    {
        #region auth

        $auth_ar = array(
            array('key' => 'you_have_wishlists', 'value' => 'لديك منتجات مفضلة تنتظرك'),
            array('key' => 'you_have_carts', 'value' => 'المنتجات في السلة تنتظرك'),
            array('key' => 'you_have_compares', 'value' => 'لقد قارنت بين المنتجات.. ماذا تنتظر'),
            array('key' => 'wishlists_reminder', 'value' => 'تذكير بالمفضلة'),
            array('key' => 'carts_reminder', 'value' => 'نذكير بالمنتجات في السلة'),
            array('key' => 'compares_reminder', 'value' => 'تذكير بمقارناتك'),
            array('key' => 'visit_website', 'value' => 'زُر الموقع'),
            array('key' => 'new_coupon', 'value' => 'خصم جديد'),
            array('key' => 'new_coupon_for_you', 'value' => 'خصم جديد خصيصا لك'),
            array('key' => 'new_coupon_matches_your_cart', 'value' => 'خصم جديد يناسب المنتجات في سلتك'),
            array('key' => 'new_coupon_matches_your_wishlists', 'value' => 'خصم جديد يناسب المنتجات في المفضلة'),
            array('key' => 'thank_you_for_choosing_us', 'value' => 'شكرا لاختيارك لنا!'),
            array('key' => 'payment_reminder', 'value' => 'ادفع اللي عليك يا :name'),
            array('key' => 'payment_reminder_title', 'value' => 'This is reminder about our $ :amount '),

        );
        $auth_en = array(
            array('key' => 'you_have_wishlists', 'value' => 'Your wishlist waiting for you'),
            array('key' => 'you_have_carts', 'value' => 'Your carts are waiting'),
            array('key' => 'you_have_compares', 'value' => 'You compared products'),
            array('key' => 'wishlists_reminder', 'value' => 'Wishlist reminder'),
            array('key' => 'carts_reminder', 'value' => 'Cart Reminder'),
            array('key' => 'compares_reminder', 'value' => 'compares reminder'),
            array('key' => 'visit_website', 'value' => 'Visit website'),
            array('key' => 'new_coupon', 'value' => 'New coupon'),
            array('key' => 'new_coupon_for_you', 'value' => 'New coupon FOR YOU'),
            array('key' => 'new_coupon_matches_your_cart', 'value' => 'New coupon matches your cart'),
            array('key' => 'new_coupon_matches_your_wishlists', 'value' => 'New coupon matches your wishlist'),
            array('key' => 'new_coupon_matches_your_compares', 'value' => 'New coupon matches your compares'),
            array('key' => 'thank_you_for_choosing_us', 'value' => 'Thank you for choosing Us'),
            array('key' => 'payment_reminder', 'value' => 'Dear :name, please pay dues '),
            array('key' => 'payment_reminder_title', 'value' => 'This is reminder about our $ :amount'),

            array('key' => 'reminder.carts', 'value' => "Dear :name ,We thank you for dealing with us. We remind you what's in your shopping cart"),
            array('key' => 'reminder.compared_products', 'value' => "Dear :name ,We thank you for dealing with us. We remind you what's in your compare"),
            array('key' => 'reminder.wishlists', 'value' => "Dear :name ,We thank you for dealing with us. We remind you what's in your wishlists"),
            array('key' => 'reminder.out_of_stock', 'value' => "Dear customer, the product is not in stock. When it is available, we will contact you"),
            array('key' => 'payment_reminder_title', 'value' => 'This is reminder about our $ :amount'),
            array('key' => 'verifyYourUser', 'value' => 'Please click the button below to verify your email address.'),
            array('key' => 'clickHereToVerify', 'value' => 'Verify Email Address'),
            array('key' => 'thankYouForUsingOurApplication', 'value' => 'If you did not create an account, no further action is required.'),
        );
        $this->translations($auth_ar, 'ar');
        $this->translations($auth_en, 'en');
        #endregion

    }
}
