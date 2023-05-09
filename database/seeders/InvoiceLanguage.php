<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceLanguage extends Seeder
{

    function translations($array, $local)
    {

        foreach ($array as $item) {
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', 'invoice')
                ->where('locale', $local)
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = 0;
            $translation->locale = $local;
            $translation->group = 'invoice';
            $translation->value = $item['value'];
            $translation->save();

        }
    }

    public function run()
    {
        #region auth
        $auth_en = array(
            array('key' => 'title', 'value' => 'Techno Lock'),
            array('key' => 'proforma', 'value' => 'Proforma'),
            array('key' => 'order', 'value' => 'Order'),
            array('key' => 'date', 'value' => 'Date'),
            array('key' => 'order_number', 'value' => 'Order No:'),
            array('key' => 'no', 'value' => 'No:'),
            array('key' => 'sku', 'value' => 'Sku'),
            array('key' => 'product_name', 'value' => 'Product Name'),
            array('key' => 'image', 'value' => 'Image'),
            array('key' => 'price', 'value' => 'Price'),
            array('key' => 'QTY', 'value' => 'Quantity'),
            array('key' => 'total', 'value' => 'Total'),
            array('key' => 'terms_and_conditions', 'value' => 'Terms and conditions'),
            array('key' => 'shipment_description', 'value' => 'Shipment description'),
            array('key' => 'shipment_value', 'value' => 'Shipment value'),
            array('key' => 'subtotal', 'value' => 'Subtotal'),
            array('key' => 'discount', 'value' => 'Discount'),
            array('key' => 'total_after_discount', 'value' => 'Total after discount'),
            array('key' => 'shipping', 'value' => 'Shipping'),
            array('key' => 'other', 'value' => 'Other'),
            array('key' => 'footer', 'value' => 'For questions concerning this quote, please contact'),
            array('key' => 'seller_info', 'value' => 'Seller Info'),
            array('key' => 'email', 'value' => 'Email'),
            array('key' => 'phone', 'value' => 'Phone'),
            array('key' => 'thank_you', 'value' => 'Thank you'),
            array('key' => 'account_statement', 'value' => 'Account statement'),
            array('key' => 'type', 'value' => 'Type'),
            array('key' => 'amount', 'value' => 'Amount'),
        );
        $auth_ar = array(
            array('key' => 'title', 'value' => 'تكنو لوك'),
            array('key' => 'proforma', 'value' => 'عرض سعر'),
            array('key' => 'order', 'value' => 'طلب'),
            array('key' => 'date', 'value' => 'التاريخ'),
            array('key' => 'order_number', 'value' => 'رقم الطلب'),
            array('key' => 'no', 'value' => 'الرقم'),
            array('key' => 'sku', 'value' => 'sku'),
            array('key' => 'product_name', 'value' => 'اسم المنتج'),
            array('key' => 'image', 'value' => 'صورة المنتج'),
            array('key' => 'price', 'value' => 'السعر'),
            array('key' => 'QTY', 'value' => 'العدد'),
            array('key' => 'total', 'value' => 'السعر الكامل'),
            array('key' => 'terms_and_conditions', 'value' => 'الشروط والأحكام'),
            array('key' => 'shipment_description', 'value' => 'وصف الشحنة'),
            array('key' => 'shipment_value', 'value' => 'قيمة الشحنة'),
            array('key' => 'subtotal', 'value' => 'السعر الأساسي'),
            array('key' => 'discount', 'value' => 'الخصم'),
            array('key' => 'total_after_discount', 'value' => 'السعر بعد الخصم'),
            array('key' => 'shipping', 'value' => 'الشحن'),
            array('key' => 'other', 'value' => 'أخرى'),
            array('key' => 'footer', 'value' => 'للاستفسار والتواصل، اضغط هنا'),
            array('key' => 'seller_info', 'value' => 'معلومات البائع'),
            array('key' => 'email', 'value' => 'البريد الإلكتروني'),
            array('key' => 'phone', 'value' => 'رقم الهاتف'),
            array('key' => 'thank_you', 'value' => 'شكرا لك'),
            array('key' => 'account_statement', 'value' => 'كشف حساب'),
            array('key' => 'type', 'value' => 'النوع'),
            array('key' => 'amount', 'value' => 'المقدار'),
        );
        $this->translations($auth_en, 'en');
        $this->translations($auth_ar, 'ar');
        #endregion

    }
}
