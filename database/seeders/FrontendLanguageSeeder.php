<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class FrontendLanguageSeeder extends Seeder
{
    function translations($array, $local)
    {

        foreach ($array as $item) {
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', 'frontend')
                ->where('locale', $local)
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = 0;
            $translation->locale = $local;
            $translation->group = 'frontend';
            $translation->value = $item['value'];
            $translation->save();

        }
    }

    public function run()
    {
        #region frontend
        $frontend_en = array(
            array('key' => 'title', 'value' => 'Techno Lock'),

        );
        $frontend_ar = array(
            array('key' => 'title', 'value' => 'تكنو لوك'),

        );
        $this->translations($frontend_en, 'en');
        $this->translations($frontend_ar, 'ar');
        #endregion

        #region menu
        $menu_en = array(
            array('key' => 'menu.categories', 'value' => 'Categories'),
            array('key' => 'menu.manufacturers', 'value' => 'Manufacturers'),
            array('key' => 'menu.colors', 'value' => 'Colors'),
            array('key' => 'menu.brands', 'value' => 'Brands'),
            array('key' => 'menu.cars', 'value' => 'cars'),
            array('key' => 'menu.models', 'value' => 'Models'),
            array('key' => 'menu.years', 'value' => 'Years'),
            array('key' => 'menu.price_range', 'value' => 'Price'),
            array('key' => 'menu.types', 'value' => 'Types'),
            array('key' => 'menu.others_filter', 'value' => 'others filter'),
            array('key' => 'menu.tokens_software', 'value' => 'tokens & software'),
            array('key' => 'menu.device_machines', 'value' => 'Device & Machines'),

        );
        $menu_ar = array(
            array('key' => 'menu.categories', 'value' => 'التصنيفات'),
            array('key' => 'menu.manufacturers', 'value' => 'المعامل'),
            array('key' => 'menu.colors', 'value' => 'الألوان'),
            array('key' => 'menu.brands', 'value' => 'الماركات'),
            array('key' => 'menu.models', 'value' => 'الموديلات'),
            array('key' => 'menu.cars', 'value' => 'سيارات'),
            array('key' => 'menu.years', 'value' => 'السنوات'),
            array('key' => 'menu.price_range', 'value' => 'السعر'),
            array('key' => 'menu.types', 'value' => 'الأنواع'),
            array('key' => 'menu.others_filter', 'value' => 'اخرى'),
            array('key' => 'menu.tokens_software', 'value' => 'tokens & software'),
            array('key' => 'menu.device_machines', 'value' => 'Device & Machines'),


        );
        $this->translations($menu_en, 'en');
        $this->translations($menu_ar, 'ar');
        #endregion

        #region product
        $product_en = array(
            array('key' => 'product.physical', 'value' => 'Physical'),
            array('key' => 'product.software', 'value' => 'Software'),
            array('key' => 'product.is_best_seller', 'value' => 'Best seller'),
            array('key' => 'product.is_saudi_branch', 'value' => 'Saudi branch'),
            array('key' => 'product.is_new_arrival', 'value' => 'New Arrived'),
            array('key' => 'product.is_free_shipping', 'value' => 'Free shipping'),
            array('key' => 'product.is_bundled', 'value' => 'Bundled'),
            array('key' => 'product.has_discount', 'value' => 'Has discount'),
            array('key' => 'product.product_blocked_in_this_country', 'value' => 'This product is not available in this country'),

        );
        $product_ar = array(
            array('key' => 'product.physical', 'value' => 'هاردوير'),
            array('key' => 'product.software', 'value' => 'سوفتوير'),
            array('key' => 'product.is_best_seller', 'value' => 'الأكثر مبيعا'),
            array('key' => 'product.is_saudi_branch', 'value' => 'الفرع السعودي'),
            array('key' => 'product.is_new_arrival', 'value' => 'وصل حديثا'),
            array('key' => 'product.is_free_shipping', 'value' => 'شحن مجاني'),
            array('key' => 'product.is_bundled', 'value' => 'bundled'),
            array('key' => 'product.has_discount', 'value' => 'عليه خصم'),
            array('key' => 'product.product_blocked_in_this_country', 'value' => 'هذا المنتج غير متوفر في هذا البلد'),
        );
        $this->translations($product_en, 'en');
        $this->translations($product_ar, 'ar');
        #endregion

        #region auth
        $auth_ar = array(
            array('key' => 'auth.credentials_not_matched', 'value' => 'الإيميل او كلمة السرّ غير صحيحة'),
            array('key' => 'auth.success_login', 'value' => 'تم تسجيل الدخول'),
            array('key' => 'auth.registered_successfully', 'value' => 'تم التسجيل بالموقع ,الرجاء تحقق من البريد الإلكتروني'),
            array('key' => 'auth.please_verify_your_mail', 'value' => 'الرجاء تحقق من البريد الإلكتروني'),
            array('key' => 'auth.password_changed', 'value' => 'تم تغيير كلمة المرور'),
            array('key' => 'auth.logout_successfully', 'value' => 'تم تسجيل الخروج'),
            array('key' => 'auth.cannt_login', 'value' => 'لم يتم تسجيل الدخول'),
            array('key' => 'auth.the_password_is_incorrect', 'value' => 'كلمة المرور غير صحيحة'),

        );
        $auth_en = array(
            array('key' => 'auth.credentials_not_matched', 'value' => 'Email or Password is invalid'),
            array('key' => 'auth.success_login', 'value' => 'You successfully logged in'),
            array('key' => 'auth.registered_successfully', 'value' => 'Registered successfully,please verify your mail'),
            array('key' => 'auth.please_verify_your_mail', 'value' => 'please verify your mail'),
            array('key' => 'auth.password_changed', 'value' => 'Password changed'),
            array('key' => 'auth.logout_successfully', 'value' => 'logout_successfully'),
            array('key' => 'auth.cannt_login', 'value' => 'Can not login'),
            array('key' => 'auth.the_password_is_incorrect', 'value' => 'the password is incorrect'),

        );
        $this->translations($auth_en, 'en');
        $this->translations($auth_ar, 'ar');
        #endregion

        #region compare
        $compare_ar = array(
            array('key' => 'compare.added_successfully', 'value' => 'أُضيف المنتج بنجاح إلى المقارنات'),
            array('key' => 'compare.removed_successfully', 'value' => 'أُزيل بنجاح من المقارنات'),

        );
        $compare_en = array(
            array('key' => 'compare.added_successfully', 'value' => 'product Added to compare successfully'),
            array('key' => 'compare.removed_successfully', 'value' => 'Product Removed from compare successfully'),

        );
        $this->translations($compare_ar, 'ar');
        $this->translations($compare_en, 'en');
        #endregion

        #region ticket
        $ticket_ar = array(
            array('key' => 'ticket.ticket_created', 'value' => 'تم استلام طلبك! سيتم الرد بأسرع وقت'),
            array('key' => 'ticket.ticket_not_found', 'value' => 'لم يتم العثور على الطلب! هل أنت متأكد من الرابط؟'),
            array('key' => 'ticket.reply_created', 'value' => 'تم تسجيل الرد! شكرًا لتفاعلكم'),
            array('key' => 'ticket.ticket_is_solved', 'value' => 'لا يمكنك الرد على الطلب، تم تحديد الطلب الحالي على أنه مغلق. '),

        );
        $ticket_en = array(
            array('key' => 'ticket.ticket_created', 'value' => 'Ticket created successfully!'),
            array('key' => 'ticket.ticket_not_found', 'value' => 'Ticket not found! we couldn\'t find the '),
            array('key' => 'ticket.reply_created', 'value' => 'Reply has been created'),
            array('key' => 'ticket.ticket_is_solved', 'value' => 'This ticket is solved, you can not write a reply'),

        );
        $this->translations($ticket_ar, 'ar');
        $this->translations($ticket_en, 'en');
        #endregion

        #region order
        $order_ar = array(
            array('key' => 'order.invoice_sent', 'value' => 'تم إرسال الفاتورة إلى بريدكم المسجل'),
        );
        $order_en = array(
            array('key' => 'order.invoice_sent', 'value' => 'We have mailed you with you invoice!'),
        );
        $this->translations($order_ar, 'ar');
        $this->translations($order_en, 'en');
        #endregion

        #region contact
        $contact_ar = array(
            array('key' => 'contact.added_successfully', 'value' => 'تم استلام طلبك، سيتم التواصل معكم بأقرب وقت. شكرا لكم'),
        );
        $contact_en = array(
            array('key' => 'contact.added_successfully', 'value' => 'Your request sent successfully, our team will contact you asap'),
        );
        $this->translations($contact_ar, 'ar');
        $this->translations($contact_en, 'en');
        #endregion


    }
}
