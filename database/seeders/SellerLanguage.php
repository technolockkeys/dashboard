<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerLanguage extends Seeder
{
    function translations($array, $local)
    {

        foreach ($array as $item) {
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', 'seller')
                ->where('locale', $local)
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = 0;
            $translation->locale = $local;
            $translation->group = 'seller';
            $translation->value = $item['value'];
            $translation->save();

        }
    }

    public function run()
    {
        #region auth
        $auth_en = array(
            array('key' => 'auth.sing_in_to', 'value' => 'Sing in to Seller'),
            array('key' => 'auth.email', 'value' => 'Email'),
            array('key' => 'auth.password', 'value' => 'Password'),
        );
        $auth_ar = array(
            array('key' => 'auth.sing_in_to', 'value' => 'تسجيل بحساب بائع'),
            array('key' => 'auth.email', 'value' => 'البريد الإلكتروني'),
            array('key' => 'auth.password', 'value' => 'كلمة المرور'),
        );
        $this->translations($auth_en, 'en');
        $this->translations($auth_ar, 'ar');
        #endregion

        #region menu
        $menu_ar = array(
            array('key' => 'menu.dashboard', 'value' => 'لوحة التحكم'),
            array('key' => 'menu.orders', 'value' => 'الطلبات'),
            array('key' => 'menu.after_sales', 'value' => 'عملية بعد الطلب'),
        );
        $menu_en = array(
            array('key' => 'menu.dashboard', 'value' => 'Dashboard'),
            array('key' => 'menu.orders', 'value' => 'Orders'),
            array('key' => 'menu.after_sales', 'value' => 'After Sales'),

        );
        $this->translations($menu_ar, "ar");
        $this->translations($menu_en, "en");
        #endregion

        #region orders
        $order_en = array(
            array('key' => 'orders.user', 'value' => 'User'),
            array('key' => 'orders.payment_method', 'value' => 'Payment Method'),
            array('key' => 'orders.payment_status', 'value' => 'Payment Status'),
            array('key' => 'orders.please_select_option', 'value' => 'Please Select Option'),
            array('key' => 'orders.shipping', 'value' => 'Shipping'),
            array('key' => 'orders.create_new_order', 'value' => 'Create new order'),
            array('key' => 'orders.create_new_address', 'value' => 'Create new address'),
            array('key' => 'orders.not_found_user', 'value' => 'Not Found User'),
            array('key' => 'orders.this_field_is_required', 'value' => 'This field is required'),
            array('key' => 'orders.country', 'value' => 'country'),
            array('key' => 'orders.city', 'value' => 'city'),
            array('key' => 'orders.address', 'value' => 'address'),
            array('key' => 'orders.phone', 'value' => 'phone'),
            array('key' => 'orders.postal_code', 'value' => 'postal code'),
            array('key' => 'orders.carts', 'value' => 'Carts'),
            array('key' => 'orders.product', 'value' => 'Product'),
            array('key' => 'orders.quantity', 'value' => 'Quantity'),
            array('key' => 'orders.attributes', 'value' => 'Attributes'),
            array('key' => 'orders.cost', 'value' => 'Cost'),
            array('key' => 'orders.type', 'value' => 'Type'),
            array('key' => 'orders.proforma', 'value' => 'proforma'),
            array('key' => 'orders.order', 'value' => 'order'),
            array('key' => 'orders.add_product', 'value' => 'add product'),
            array('key' => 'orders.product_not_found', 'value' => 'Product not found'),
            array('key' => 'orders.shipping_method', 'value' => 'Shipping Method'),
            array('key' => 'orders.coupon', 'value' => 'Coupon'),
            array('key' => 'orders.apply_coupon', 'value' => 'Apply Coupon'),
            array('key' => 'orders.order_details', 'value' => 'Order Details'),
            array('key' => 'orders.sub_total', 'value' => 'Sub total'),
            array('key' => 'orders.shipping_total', 'value' => 'Shipping total'),
            array('key' => 'orders.total', 'value' => 'total'),
            array('key' => 'orders.payment_method', 'value' => 'Payment Method'),
            array('key' => 'orders.payment_files', 'value' => 'Payment Files'),
            array('key' => 'orders.stripe_link', 'value' => 'Stripe Link'),
            array('key' => 'orders.transfer', 'value' => 'Transfer'),
            array('key' => 'orders.item', 'value' => 'Item'),
            array('key' => 'orders.quantity', 'value' => 'Quantity'),
            array('key' => 'orders.price', 'value' => 'Price'),
            array('key' => 'orders.total', 'value' => 'Total'),
            array('key' => 'orders.shipping_price', 'value' => 'Shipping price'),
            array('key' => 'orders.total_price', 'value' => 'price'),
            array('key' => 'orders.min_total_price', 'value' => 'Min Total Price'),
            array('key' => 'orders.unit_price', 'value' => 'unit price'),
            array('key' => 'orders.min_unit_price', 'value' => 'min unit price'),
            array('key' => 'orders.the_quantity_available_is', 'value' => 'quantity available is : '),
            array('key' => 'orders.note', 'value' => 'note'),
            array('key' => 'orders.paypal', 'value' => 'PayPal'),
            array('key' => 'orders.stripe', 'value' => 'Stripe'),
            array('key' => 'orders.stripe_link', 'value' => 'Stripe Link'),
            array('key' => 'orders.transfer', 'value' => 'Transfer'),
            array('key' => 'orders.successfully_order', 'value' => 'Successfully order'),
            array('key' => 'orders.waiting_order', 'value' => 'Waiting order'),
            array('key' => 'orders.coupon_is_not_available', 'value' => 'Coupon is not available'),
            array('key' => 'orders.this_coupon_has_expired', 'value' => 'This coupon has expired'),
            array('key' => 'orders.seller', 'value' => 'Seller'),
            array('key' => 'orders.show', 'value' => 'Show'),
            array('key' => 'orders.open_new_order', 'value' => 'Open new order'),
            array('key' => 'orders.edit', 'value' => 'Edit Order (# :uuid)'),
            array('key' => 'orders.you_cant_edit_this_order', 'value' => "You can't edit this order"),
        );
        $order_ar = array(
            array('key' => 'orders.user', 'value' => 'المستخدم'),
            array('key' => 'orders.payment_method', 'value' => 'طريقة الدفع'),
            array('key' => 'orders.payment_status', 'value' => 'حالة الدفع'),
            array('key' => 'orders.total', 'value' => 'المجموع'),
            array('key' => 'orders.shipping', 'value' => 'الشحن'),
            array('key' => 'orders.create_new_order', 'value' => 'انشاء طلب جديد'),
            array('key' => 'orders.please_select_option', 'value' => 'الرجاء اختيار'),
            array('key' => 'orders.create_new_address', 'value' => 'انشاء عنوان جديد'),
            array('key' => 'orders.not_found_user', 'value' => 'لم يتم عثور على مستخدم'),
            array('key' => 'orders.this_field_is_required', 'value' => 'هذه الخانة مطلوبه.'),
            array('key' => 'orders.country', 'value' => 'الدولة'),
            array('key' => 'orders.city', 'value' => 'المدينة'),
            array('key' => 'orders.address', 'value' => 'العنوان'),
            array('key' => 'orders.phone', 'value' => 'رقم الهاتف'),
            array('key' => 'orders.postal_code', 'value' => 'postal code'),
            array('key' => 'orders.carts', 'value' => 'سلة المشتريات'),
            array('key' => 'orders.product', 'value' => 'المنتج'),
            array('key' => 'orders.quantity', 'value' => 'الكمية'),
            array('key' => 'orders.attributes', 'value' => 'خصائص'),
            array('key' => 'orders.cost', 'value' => 'الكلفة'),
            array('key' => 'orders.type', 'value' => 'النوع'),
            array('key' => 'orders.proforma', 'value' => 'proforma'),
            array('key' => 'orders.order', 'value' => 'order'),
            array('key' => 'orders.add_product', 'value' => 'اضافة منتج'),
            array('key' => 'orders.product_not_found', 'value' => 'لم يتم عثور على المنتج'),
            array('key' => 'orders.shipping_method', 'value' => 'طرق الشحن'),
            array('key' => 'orders.coupon', 'value' => 'الخصم'),
            array('key' => 'orders.apply_coupon', 'value' => 'تطبيق الخصم'),
            array('key' => 'orders.order_details', 'value' => 'معلومات الطلب'),
            array('key' => 'orders.sub_total', 'value' => 'المجموع الفرعي'),
            array('key' => 'orders.shipping_total', 'value' => 'تكلفة الشحن'),
            array('key' => 'orders.total', 'value' => 'المجموع'),
            array('key' => 'orders.payment_method', 'value' => 'طرق الدفع'),
            array('key' => 'orders.payment_files', 'value' => 'ملفات الدفع'),
            array('key' => 'orders.stripe_link', 'value' => ' انشاء رابط سترايب '),
            array('key' => 'orders.transfer', 'value' => 'تحويل'),
            array('key' => 'orders.item', 'value' => 'عنصر'),
            array('key' => 'orders.quantity', 'value' => 'الكمية'),
            array('key' => 'orders.price', 'value' => 'السعر'),
            array('key' => 'orders.total', 'value' => 'الأجمالي'),
            array('key' => 'orders.shipping_price', 'value' => 'تكلفة الشحن'),
            array('key' => 'orders.total_price', 'value' => 'السعر'),
            array('key' => 'orders.min_total_price', 'value' => 'اقل سعر للطلب'),
            array('key' => 'orders.unit_price', 'value' => 'سعر الواحدة'),
            array('key' => 'orders.min_unit_price', 'value' => 'اقل سعر للواحدة'),
            array('key' => 'orders.the_quantity_available_is', 'value' => 'الكمية المتوفرة هي'),
            array('key' => 'orders.note', 'value' => 'ملاحظة'),
            array('key' => 'orders.paypal', 'value' => 'PayPal'),
            array('key' => 'orders.stripe', 'value' => 'Stripe'),
            array('key' => 'orders.stripe_link', 'value' => 'Stripe Link'),
            array('key' => 'orders.transfer', 'value' => 'حوالة مالية'),
            array('key' => 'orders.seller', 'value' => 'البائع'),
            array('key' => 'orders.show', 'value' => 'إظهار'),
            array('key' => 'orders.open_new_order', 'value' => 'Open new order'),

        );
        $this->translations($order_en, 'en');
        $this->translations($order_ar, 'ar');
        #endregion

        ##region users
        $user_en = array(
            array('key' => 'user.orders', 'value' => 'Orders'),
            array('key' => 'user.full_name', 'value' => 'Full Name'),
            array('key' => 'user.email_address', 'value' => 'Email Address'),
            array('key' => 'user.phone_number', 'value' => 'Phone Number'),
            array('key' => 'user.type_of_business', 'value' => 'Type Of Business'),
            array('key' => 'user.company_name', 'value' => 'Company Name'),
            array('key' => 'user.website_url', 'value' => 'Website Url'),
            array('key' => 'user.avatar', 'value' => 'avatar'),
            array('key' => 'user.create_new_client', 'value' => 'Create New Client'),
            array('key' => 'user.country', 'value' => 'country'),
            array('key' => 'user.state', 'value' => 'state'),
            array('key' => 'user.city', 'value' => 'city'),
            array('key' => 'user.street', 'value' => 'street'),
            array('key' => 'user.full_address', 'value' => 'full address'),
            array('key' => 'user.edit', 'value' => 'Edit User :'),
            array('key' => 'user.account_id', 'value' => 'Account ID'),
            array('key' => 'user.details', 'value' => 'Details'),
            array('key' => 'user.addresses', 'value' => 'Addresses'),
            array('key' => 'user.postal_code', 'value' => 'Postal Code'),
            array('key' => 'user.phone', 'value' => 'phone'),
            array('key' => 'user.payment_recodes', 'value' => 'Payment records'),
            array('key' => 'user.withdraw', 'value' => 'Withdraw'),
            array('key' => 'user.order', 'value' => 'Order'),
            array('key' => 'user.refund', 'value' => 'Refund'),
            array('key' => 'user.amount', 'value' => 'Amount'),
            array('key' => 'user.approve', 'value' => 'Approve'),
            array('key' => 'user.pending', 'value' => 'Pending'),
            array('key' => 'user.cancelled', 'value' => 'Cancelled'),
            array('key' => 'user.create_new_payment_recode', 'value' => 'Create Payment Records'),
            array('key' => 'user.amount', 'value' => 'Amount'),
            array('key' => 'user.type', 'value' => 'Type'),
            array('key' => 'user.status', 'value' => 'Status'),
            array('key' => 'user.created_at', 'value' => 'Created At'),
            array('key' => 'user.notes', 'value' => 'Notes'),
            array('key' => 'user.files', 'value' => 'Files'),
            array('key' => 'user.balance', 'value' => 'Balance'),
            array('key' => 'user.edit_address', 'value' => 'Edit address'),
        );
        $user_ar = array(
            array('key' => 'user.orders', 'value' => 'الطلبات'),
            array('key' => 'user.full_name', 'value' => 'اسم الكامل'),
            array('key' => 'user.email_address', 'value' => 'البريد الإلكتروني'),
            array('key' => 'user.phone_number', 'value' => 'رقم الهاتف'),
            array('key' => 'user.type_of_business', 'value' => 'نوع العمل'),
            array('key' => 'user.company_name', 'value' => 'اسم الشركة'),
            array('key' => 'user.website_url', 'value' => 'رابط الشركة'),
            array('key' => 'user.avatar', 'value' => 'صورة الشخصية'),
            array('key' => 'user.create_new_client', 'value' => 'أنشاء عميل جديد'),
            array('key' => 'user.country', 'value' => 'الدولة'),
            array('key' => 'user.state', 'value' => 'الولاية'),
            array('key' => 'user.city', 'value' => 'المدينة'),
            array('key' => 'user.street', 'value' => 'الشارع'),
            array('key' => 'user.full_address', 'value' => 'عنوان كامل'),
            array('key' => 'user.edit', 'value' => 'تعديل الزبون : '),
            array('key' => 'user.details', 'value' => 'معلومات الزبون'),
            array('key' => 'user.addresses', 'value' => 'عناوين'),
            array('key' => 'user.postal_code', 'value' => 'Postal Code'),
            array('key' => 'user.phone', 'value' => 'رقم الهاتف'),
            array('key' => 'user.payment_recodes', 'value' => 'الدفعات المالية'),
            array('key' => 'user.withdraw', 'value' => 'Withdraw'),
            array('key' => 'user.order', 'value' => 'Order'),
            array('key' => 'user.refund', 'value' => 'Refund'),
            array('key' => 'user.amount', 'value' => 'Amount'),
            array('key' => 'user.type', 'value' => 'Type'),
            array('key' => 'user.status', 'value' => 'Status'),
            array('key' => 'user.created_at', 'value' => 'Created At'),
            array('key' => 'user.notes', 'value' => 'Notes'),
            array('key' => 'user.approve', 'value' => 'Approve'),
            array('key' => 'user.pending', 'value' => 'Pending'),
            array('key' => 'user.cancelled', 'value' => 'Cancelled'),
            array('key' => 'user.create_new_payment_recode', 'value' => 'Create Payment Records'),
            array('key' => 'user.files', 'value' => 'Files'),
            array('key' => 'user.balance', 'value' => 'Balance'),
            array('key' => 'user.edit_address', 'value' => 'تعديل العنوان'),

        );

        $this->translations($user_en, 'en');
        $this->translations($user_ar, 'ar');
        #endregion

        #region global
        $global_ar = array(
            array('key' => 'get_quantity', 'value' => 'عرض الكمية'),
            array('key' => 'calculate_shipping_cost', 'value' => 'كلفة الشحن'),
            array('key' => 'get_shipping_price', 'value' => 'عرض كلفة الشحن'),
        );
        $global_en = array(
            array('key' => 'get_quantity', 'value' => 'Get quantity'),
            array('key' => 'calculate_shipping_cost', 'value' => 'Shipping Cost'),
            array('key' => 'get_shipping_price', 'value' => 'Get Shipping Price'),
        );
        $this->translations($global_ar, "ar");
        $this->translations($global_en, "en");
        #endregion

        #region product
        $product_ar = array(
            array('key' => 'product.sku', 'value' => 'sku'),
            array('key' => 'product.countries', 'value' => 'الدول'),
            array('key' => 'product.weight', 'value' => 'الوزن'),
        );
        $product_en = array(
            array('key' => 'product.sku', 'value' => 'sku'),
            array('key' => 'product.countries', 'value' => 'countries'),
            array('key' => 'product.weight', 'value' => 'weight'),
        );
        $this->translations($product_ar, "ar");
        $this->translations($product_en, "en");
        #endregion

        #region after sale
        $after_sales_ar = array(
            array('key' => 'after_sales.order_number', 'value' => 'رقم الاوردر'),
            array('key' => 'after_sales.order_date', 'value' => 'تاريخ الاوردر'),
            array('key' => 'after_sales.user', 'value' => 'اسم العميل'),
            array('key' => 'after_sales.send_email', 'value' => 'ارسال أيميل'),
            array('key' => 'after_sales.feedback', 'value' => 'تقييمات'),
            array('key' => 'after_sales.feedback_date', 'value' => 'تاريخ التقييمات'),
            array('key' => 'after_sales.set_feedback', 'value' => 'وضع تقييم'),
            array('key' => 'after_sales.send_email', 'value' => 'Send Email'),
            array('key' => 'after_sales.last_orders', 'value' => 'Last Orders'),
            array('key' => 'after_sales.resend_email', 'value' => 'reSend Email'),
            array('key' => 'after_sales.please_send_feedback', 'value' => 'Please Send Feedback'),
            array('key' => 'after_sales.please_send_feedback_for_order', 'value' => 'Please Send Feedback For Order :num'),

        );
        $after_sales_en = array(
            array('key' => 'after_sales.order_number', 'value' => 'Order Number'),
            array('key' => 'after_sales.order_date', 'value' => 'Order Date'),
            array('key' => 'after_sales.user', 'value' => 'User'),
            array('key' => 'after_sales.send_email', 'value' => 'Send Email'),
            array('key' => 'after_sales.feedback', 'value' => 'Feedback'),
            array('key' => 'after_sales.feedback_date', 'value' => 'Feedback Date'),
            array('key' => 'after_sales.set_feedback', 'value' => 'Set Feedback'),
            array('key' => 'after_sales.send_email', 'value' => 'Send Email'),
            array('key' => 'after_sales.last_orders', 'value' => 'Last Orders'),
            array('key' => 'after_sales.resend_email', 'value' => 'reSend Email'),
            array('key' => 'after_sales.please_send_feedback', 'value' => 'Please Send Feedback'),
            array('key' => 'after_sales.please_send_feedback_for_order', 'value' => 'Please Send Feedback For Order :num'),
            array('key' => 'after_sales.user_black_list', 'value' => 'Users Black List'),

        );
        $this->translations($after_sales_ar, "ar");
        $this->translations($after_sales_en, "en");
        #endregion


        #region profile
        $profile_ar = array(
            array('key' => 'profile.phone', 'value' => 'الهاتف',),
            array('key' => 'profile.whatsapp_number', 'value' => 'رقم الواتس ',),
            array('key' => 'profile.facebook', 'value' => 'رابط الفيس',),
            array('key' => 'profile.skype', 'value' => 'سكايب',),
        );
        $profile_en = array(
            array('key' => 'profile.phone', 'value' => 'phone',),
            array('key' => 'profile.whatsapp_number', 'value' => 'whatsapp number',),
            array('key' => 'profile.facebook', 'value' => 'facebook',),
            array('key' => 'profile.skype', 'value' => 'skype',),
        );
        $this->translations($profile_ar, "ar");
        $this->translations($profile_en, "en");
        #endregion

    }
}
