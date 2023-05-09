<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class ApiLanguage extends Seeder
{

    function translations($array, $local)
    {

        foreach ($array as $item) {
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', 'api')
                ->where('locale', $local)
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = 0;
            $translation->locale = $local;
            $translation->group = 'api';
            $translation->value = $item['value'];
            $translation->save();

        }
    }

    public function run()
    {
        #region api
        $api_en = array(
            array('key' => 'you_dont_have_permission_to_use_this_api', 'value' => 'you dont have permission to use this api'),
            array('key' => 'please_login', 'value' => 'please login')
        );
        $api_ar = array(
            array('key' => 'you_dont_have_permission_to_use_this_api', 'value' => 'you dont have permission to use this api'),
                        array('key' => 'please_login', 'value' => 'please login')
        );
        $this->translations($api_ar ,'ar');
        $this->translations($api_en ,'en');
        #endregion

        #region cart
        $cart_en = array(
            array('key' => 'cart.product_added_successfully', 'value' => 'product added successfully'),
            array('key' => 'cart.product_deleted_successfully', 'value' => 'product deleted successfully'),
            array('key' => 'cart.product_changed_successfully', 'value' => 'product changed successfully'),
            array('key' => 'cart.can_not_added_product_because_the_minimum_for_order', 'value' => 'can not add product because the minimum for order :num'),
            array('key' => 'cart.can_not_added_product_because_the_number_is_not_available', 'value' => 'can not added product because the number is not available'),
            array('key' => 'cart.can_not_change_quantity_product_because_the_minimum_for_order', 'value' => 'can not change product because  the minimum for order :num'),
            array('key' => 'cart.coupon.coupon_is_not_available', 'value' => 'coupon is not available'),
            array('key' => 'cart.coupon.coupon_is_applied', 'value' => 'coupon is applied'),
            array('key' => 'cart.coupon.deleted_successfully', 'value' => 'Coupon has been removed'),
            array('key' => 'cart.coupon.not_found_product', 'value' => 'Not found product'),
            array('key' => 'cart.coupon.can_not_use_coupon_because_the_minimum_order_is', 'value' => 'can not use coupon because the minimum order is :num'),
            array('key' => 'cart.coupon.can_not_use_coupon_because_the_maximum_discount_is', 'value' => 'can not use coupon because the maximum discount is :num'),
        );
        $cart_ar = array(
            array('key' => 'cart.product_added_successfully', 'value' => 'تم اضافة المنتج بنجاح'),
            array('key' => 'cart.product_deleted_successfully', 'value' => 'تم حذف المنتج بنجاح'),
            array('key' => 'cart.product_changed_successfully', 'value' => 'تم تعديل العدد بنجاح'),
            array('key' => 'cart.can_not_added_product_because_the_minimum_for_order', 'value' => 'الحد الادنى للطلب هو :num'),
            array('key' => 'cart.can_not_added_product_because_the_number_is_not_available', 'value' => 'لا يمكن اضافة منتح بسبب عدم توفر الكمية'),
            array('key' => 'cart.can_not_change_quantity_product_because_the_minimum_for_order', 'value' => 'الحد الادنى للطلب هو :num'),
            array('key' => 'cart.coupon.coupon_is_not_available', 'value' => 'الخصم غير متوفر'),
            array('key' => 'cart.coupon.coupon_is_applied', 'value' => 'تم تطبيق الكوبون'),
            array('key' => 'cart.coupon.deleted_successfully', 'value' => 'تم حذف الكوبون'),
            array('key' => 'cart.coupon.not_found_product', 'value' => 'لم يتم عثور على المنتج'),

            array('key' => 'cart.coupon.can_not_use_coupon_because_the_minimum_order_is', 'value' => 'لايمكن استعمال الخضن اقل طلب بـ :num'),
            array('key' => 'cart.coupon.can_not_use_coupon_because_the_maximum_discount_is', 'value' => 'لا يمكن استعمال الخصم اكبر قيمة خصم هي :num'),

        );
        $this->translations($cart_en, 'en');
        $this->translations($cart_ar, 'ar');
        #endregion

        #region card
        $card_en = array(
            array('key' => 'card.card_added_successfully', 'value' => 'created successfully card. '),
            array('key' => 'card.not_found_card', 'value' => 'not found card'),
            array('key' => 'card.deleted_successfully', 'value' => 'deleted successfully card. '),
            array('key' => 'card.the_product_cannot_be_added_because_the_quantity_is_not_available', 'value' => 'The product cannot be added because the quantity is not available'),
        );
        $card_ar = array(
            array('key' => 'card.card_added_successfully', 'value' => 'تم اضافة بطاقة بنجاح'),
            array('key' => 'card.deleted_successfully', 'value' => 'تم حذف بطاقة بنجاح'),
            array('key' => 'card.not_found_card', 'value' => 'بطاقة غير متاحة'),
            array('key' => 'card.the_product_cannot_be_added_because_the_quantity_is_not_available', 'value' => 'لا يمكن إضافة المنتج لأن الكمية غير متوفرة'),

        );
        $this->translations($card_en, 'en');
        $this->translations($card_ar, 'ar');
        #endregion

        #region orders
        $order_en = array(
            array('key' => 'order.cart_is_empty', 'value' => 'Cart is empty'),
            array('key' => 'order.order_not_found', 'value' => 'Not found order'),
            array('key' => 'order.products_not_found', 'value' => 'Not found products'),
            array('key' => 'order.card_not_found', 'value' => 'Card Not Found '),
            array('key' => 'order.products_not_availability', 'value' => 'products is not availability'),
        );
        $order_ar = array(
            array('key' => 'order.cart_is_empty', 'value' => 'السلة فارغة'),
            array('key' => 'order.order_not_found', 'value' => 'لم يتم عثور على الطلب'),
            array('key' => 'order.products_not_found', 'value' => 'لم يتم عثور على المنتجات'),
            array('key' => 'order.card_not_found', 'value' => 'لم يتم عثور على بطاقة'),
            array('key' => 'order.products_not_availability', 'value' => 'المنتجات غير متوفرة'),

        );
        $this->translations($order_en, 'en');
        $this->translations($order_ar, 'ar');
        #endregion

        #region wishlist
        $wishlist_en = array(
            array('key' => 'wishlist.wishlist_is_empty', 'value' => 'wishlist is empty'),
            array('key' => 'wishlist.added_successfully', 'value' => 'Added successfully'),
            array('key' => 'wishlist.deleted_wishlist', 'value' => 'deleted wishlist'),
        );
        $wishlist_ar = array(
            array('key' => 'wishlist.wishlist_is_empty', 'value' => 'قائمة المفضلة فارغة'),
            array('key' => 'wishlist.added_successfully', 'value' => 'تم اضافة بنتجاج'),
            array('key' => 'wishlist.deleted_wishlist', 'value' => 'تم حذف بنتجاج'),

        );
        $this->translations($wishlist_en, 'en');
        $this->translations($wishlist_ar, 'ar');
        #endregion

        #region pages
        $pages_en = array(
            array('key' => 'page.not_found', 'value' => 'not found the page'),

        );
        $pages_ar = array(

            array('key' => 'page.not_found', 'value' => 'لم يتم عثور على الصفجة'),


        );
        $this->translations($pages_en, 'en');
        $this->translations($pages_ar, 'ar');
        #endregion

        #region review
        $review_en = array(
            array('key' => 'review.added_successfully', 'value' => 'added successfully'),
            array('key' => 'review.deleted_successfully', 'value' => 'deleted successfully'),
            array('key' => 'review.you_cant_review', 'value' => "You can't review this product, because you didn't buy this product"),
            array('key' => 'review.already_reviewed', 'value' => "You can't review this product again!"),
        );
        $review_ar = array(
            array('key' => 'review.added_successfully', 'value' => 'تمت أضافة بنجاح'),
            array('key' => 'review.deleted_successfully', 'value' => 'تمت حذف بنجاح'),
            array('key' => 'review.you_cant_review', 'value' => "لا يمكنك تقييم هذا المنتج ، لأنك لم تشتري هذا المنتج"),
            array('key' => 'review.already_reviewed', 'value' => "لا يمكنت تقييم هذا المنتج مرة أخرى."),

        );
        $this->translations($review_ar, 'ar');
        $this->translations($review_en, 'en');
        #endregion

        #region review
        $review_en = array(
            array('key' => 'brand.added_successfully', 'value' => 'added successfully'),
            array('key' => 'brand.brand_not_found', 'value' => 'Brand not found'),
        );
        $review_ar = array(
            array('key' => 'brand.added_successfully', 'value' => 'تمت الإضافة بنجاح'),
            array('key' => 'brand.brand_not_found', 'value' => 'لم يتم العثور على النوع'),
        );
        $this->translations($review_ar, 'ar');
        $this->translations($review_en, 'en');
        #endregion

        #region page
        $page_ar = array(
            array('key' => 'brand.page.not_found', 'value' => 'الصفحة غير موجودة'),
        );
        $page_en = array(
            array('key' => 'page.not_found', 'value' => 'Page not found'),
        );
        $this->translations($page_ar, 'ar');
        $this->translations($page_en, 'en');
        #endregion

        #region category
        $page_ar = array(
            array('key' => 'category.not_found', 'value' => 'الصفحة غير موجودة'),
        );
        $page_en = array(
            array('key' => 'category.not_found', 'value' => 'Page not found'),
        );
        $this->translations($page_ar, 'ar');
        $this->translations($page_en, 'en');
        #endregion

        #region auth
            $auth_en =[
                array('key'=>'auth.user_not_found' ,'value' => 'not found the user' ),
                array('key'=>'auth.verify_mail' ,'value' => 'thank you for register and verify mail' )
            ];

        $this->translations($auth_en ,'en');
        #endregion


    }
}
