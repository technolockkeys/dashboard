<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackendLanguage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    function translations($array)
    {

        foreach ($array as $item) {
            $item['group'];
            $translation = Translation::query()
                ->where('key', $item['key'])
                ->where('group', $item['group'])
                ->where('locale', $item['locale'])
                ->first();
            if (empty($translation)) {
                $translation = new Translation();
                $translation->key = $item['key'];
            }
            $translation->status = $item['status'];
            $translation->locale = $item['locale'];
            $translation->group = $item['group'];
            $translation->value = $item['value'];
            $translation->save();

        }
    }


    public function run()
    {

        #region admin
        $admin_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.Email', 'value' => 'Email'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.create_new_admin', 'value' => 'Create New Admin'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.edit_admin', 'value' => 'Edit Admin : :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.password', 'value' => 'Password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.password_confirmation', 'value' => 'Confirm password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.role', 'value' => 'role'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.status', 'value' => 'status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'admin.two_factor', 'value' => 'two factor'),
        );
        $admin_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.Email', 'value' => 'البريد الإلكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.create_new_admin', 'value' => 'إنشاء مسؤول جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.edit_admin', 'value' => 'تحرير المشرف : :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.name', 'value' => 'اسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.password', 'value' => 'كلمه السر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.password_confirmation', 'value' => 'تأكيد كلمة المرور'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.role', 'value' => 'وظيفة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.status', 'value' => 'هل تريد حسابا نشطا؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'admin.two_factor', 'value' => 'Two Factor'),

        );
        $this->translations($admin_en);
        $this->translations($admin_ar);
        #endregion

        #region attribute
        $attribute_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.add_attribute_to', 'value' => 'Add sub attribute to'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.add_new_sub_attribute', 'value' => 'Add Sub Attribute'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.create_new_attribute', 'value' => 'Create New Attribute'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.sub_attributes_page', 'value' => 'Sub Attributes'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.sub_attributes_for', 'value' => 'Sub Attributes For'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.value', 'value' => 'Value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.sub_attribute.edit', 'value' => 'Edit Sub Attribute: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'attribute.edit', 'value' => 'Edit Attribute: :name'),
        );

        $attribute_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.add_attribute_to', 'value' => 'إضافة قيمة فرعية ل:'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.add_new_sub_attribute', 'value' => 'أضف قيمة فرعية جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.create_new_attribute', 'value' => 'أنشئ صفة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.image', 'value' => 'صورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.name', 'value' => 'الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.sub_attributes_page', 'value' => 'القيم الفرعية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.sub_attributes_for', 'value' => 'القيم الفرعية ل:'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.value', 'value' => 'القيمة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.sub_attribute.edit', 'value' => 'نعديل القيمة ل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'attribute.edit', 'value' => 'نعديل القيمة ل :name'),

        );
        $this->translations($attribute_en);
        $this->translations($attribute_ar);
        #endregion

        #region auth
        $auth_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.email', 'value' => 'Email'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.login', 'value' => 'login'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.password', 'value' => 'Password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.sing_in_to', 'value' => 'sing in to tlk'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.admin_not_active', 'value' => 'This Admin is not active'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.seller_not_active', 'value' => 'This Seller is not active'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'auth.credentials_not_matched', 'value' => 'Credentials not matched'),

        );
        $auth_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.email', 'value' => 'البريد الإلكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.login', 'value' => 'تسجيل الدخول'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.password', 'value' => 'password'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.sing_in_to', 'value' => 'تسجيل الدخول إلى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.admin_not_active', 'value' => 'هذا المدير غير مفعل في الوقت الحالي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.seller_not_active', 'value' => 'هذا البائع غير مفعل في الوقت الحالي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'auth.credentials_not_matched', 'value' => 'المعلومات غير متطابقة'),
        );
        $this->translations($auth_en);
        $this->translations($auth_ar);
        #endregion

        #region category
        $categories_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.banner', 'value' => 'icon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.create_new_category', 'value' => 'Create new category'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.description', 'value' => 'description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.edit_category', 'value' => 'Edit Category : :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.form_banner', 'value' => 'banner 1200*300'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.form_icon', 'value' => 'icon 200*200'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.icon', 'value' => 'icon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.meta_description', 'value' => 'meta description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.meta_title', 'value' => 'meta title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.name', 'value' => 'name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.no_parent', 'value' => 'no parent'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.parent', 'value' => 'parent'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.physical', 'value' => 'hardware'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.slug', 'value' => 'slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.software', 'value' => 'software'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.type', 'value' => 'type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'category.products_count', 'value' => 'products count'),
        );
        $categories_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.banner', 'value' => 'banner'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.create_new_category', 'value' => 'انشاء فئة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.description', 'value' => 'الوصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.edit_category', 'value' => 'تعديل الفئة : :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.form_banner', 'value' => 'banner 1200*300'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.form_icon', 'value' => 'icon 200*200'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.icon', 'value' => 'icon'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.meta_description', 'value' => 'meta description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.meta_title', 'value' => 'meta title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.name', 'value' => 'اسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.no_parent', 'value' => 'بدون اب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.parent', 'value' => 'الفئة الاب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.physical', 'value' => 'هارد وير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.slug', 'value' => 'slug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.software', 'value' => 'سوفت وير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'category.products_count', 'value' => 'عدد المنتجات')
        );
        $this->translations($categories_en);
        $this->translations($categories_ar);
        #endregion

        #region city
        $city_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.cost', 'value' => 'cost'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.country_name', 'value' => 'Country Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.create_new_city', 'value' => 'Create New City'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.edit', 'value' => 'Edit City'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.name', 'value' => 'name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'city.status', 'value' => 'Do you want this city to be active?'),
        );
        $city_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.cost', 'value' => 'التكلفة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.country_name', 'value' => 'اسم الدولة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.create_new_city', 'value' => 'أنشئ مدينة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.edit', 'value' => 'تعديل المدينة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.name', 'value' => 'اسم المدينة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'city.status', 'value' => 'هل تريد تفعيل هذه المدينة؟'),

        );
        $this->translations($city_en);
        $this->translations($city_ar);
        #endregion

        #region color
        $color_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'color.code', 'value' => 'Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'color.color_name', 'value' => 'Color Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'color.create_new_color', 'value' => 'Create New Color'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'color.edit', 'value' => 'Edit Color'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'color.status', 'value' => 'Do you want to activate this color?'),
        );
        $color_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'color.code', 'value' => 'كود اللون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'color.color_name', 'value' => 'اسم اللون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'color.create_new_color', 'value' => 'أنشئ لونًا جديدًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'color.edit', 'value' => 'تعديل اللون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'color.status', 'value' => 'هل أنت متأكد من تفعيل هذا اللون؟'),

        );
        $this->translations($color_en);
        $this->translations($color_ar);
        #endregion

        #region country
        $country_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.capital', 'value' => 'Capital'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.code', 'value' => 'Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.phonecode', 'value' => 'Phone Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.zone_id', 'value' => 'Zone Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.edit_zone', 'value' => 'Edit Zone'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'country.edit_name', 'value' => 'Edit name'),

        );
        $country_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.capital', 'value' => 'العاصمة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.code', 'value' => 'كود'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.name', 'value' => 'الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.phonecode', 'value' => 'رمز الهاتف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.zone_id', 'value' => 'زون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.edit_zone', 'value' => 'تعديل الزون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'country.edit_name', 'value' => 'تعديل الاسم'),

        );
        $this->translations($country_en);
        $this->translations($country_ar);
        #endregion

        #region global
        $global_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.actions', 'value' => 'Actions'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.ara_you_sure_to_delete', 'value' => 'Ara you sure to delete :name ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.back', 'value' => 'Back'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.change', 'value' => 'Change'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.click_to_select_files_here', 'value' => 'Click to select files here'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.create_new_item', 'value' => 'Create New Item'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.created_at', 'value' => 'Created At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.delete', 'value' => 'Delete'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.deleted_at', 'value' => 'Deleted At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.do_you_want_active', 'value' => 'do you want active ? '),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.done_deleted', 'value' => 'done deleted :name  '),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.edit', 'value' => 'Edit'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.edit_item', 'value' => 'Edit Item'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.error_message.cant_updated', 'value' => 'cant_updated'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.error_message.error_on_deleted', 'value' => 'error on deleted'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.error_message.image_not_uploaded', 'value' => 'image not uploaded'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.error_message.something', 'value' => 'some thing error'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.error_message.this_country_does_not_have_a_region', 'value' => 'This country does not have a region'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.id', 'value' => '#id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.items', 'value' => 'items'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.loading', 'value' => 'Loading...'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.media', 'value' => 'Media'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.no_items_found', 'value' => 'No items found.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.save', 'value' => 'Save'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.select_an_option', 'value' => 'Select an option'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.select_media', 'value' => 'Select Media'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.status', 'value' => 'Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.success_message.changed_status_successfully', 'value' => 'changed status successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.success_message.created_successfully', 'value' => 'created successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.success_message.deleted_successfully', 'value' => 'deleted successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.success_message.updated_successfully', 'value' => 'updated successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.success_message.sent_successfully', 'value' => 'sent successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.this_item', 'value' => 'this item'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.updated_at', 'value' => 'Updated At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.get_back', 'value' => 'Get Back'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.nothing_here', 'value' => 'Seems there is nothing here'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.system_error', 'value' => 'System Error'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.went_wrong', 'value' => 'Something went wrong!'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.try_again', 'value' => 'Please try again later.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.unauthorized', 'value' => 'Unauthorized.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.not_allowed', 'value' => 'You are no allowed to be here.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.you_need_promotion', 'value' => 'You need to be promoted...'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.logout', 'value' => 'Logout'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.continue', 'value' => 'continue'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.all', 'value' => 'All'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.on', 'value' => 'active'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.off', 'value' => 'not active'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.not_found', 'value' => 'not Found'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.view_columns', 'value' => 'Columns'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.excel', 'value' => 'Excel'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.pdf', 'value' => 'PDF'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.pageLength', 'value' => 'Page Length'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.uuid', 'value' => 'UUID'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.close', 'value' => 'Close'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.filter', 'value' => 'Filter'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.language', 'value' => 'Language'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.datatables.delete_all', 'value' => 'Delete selected'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.active', 'value' => 'Active'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.disabled', 'value' => 'Disabled'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.today', 'value' => 'Today'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.yesterday', 'value' => 'Yesterday'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.last_7_days', 'value' => 'Last 7 days'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.last_30_days', 'value' => 'Last 30 days'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.this_month', 'value' => 'This month'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.last_month', 'value' => 'Last month'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.time', 'value' => 'Time'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.class', 'value' => 'Class'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.show', 'value' => 'Show'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.cut', 'value' => 'Cut'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.download', 'value' => 'Download'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.send_to_user', 'value' => 'Send To User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.sent_successfully', 'value' => 'Sent successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'global.is_default', 'value' => 'is default'),

        );
        $global_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.actions', 'value' => 'العمليات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.ara_you_sure_to_delete', 'value' => 'هل انت متأكد من حذف العنصر :name ?'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.back', 'value' => 'العودة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.click_to_select_files_here', 'value' => 'انقر لأختيار الملفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.create_new_item', 'value' => 'انشاء عنصر جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.created_at', 'value' => 'انشأت في'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.delete', 'value' => 'حذف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.deleted_at', 'value' => 'حذفت في'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.done_deleted', 'value' => 'تم حذف  :name  '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.edit', 'value' => 'تعديل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.edit_item', 'value' => 'تعديل عنصر '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.error_message.cant_updated', 'value' => 'لا يمكن تحديثه'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.error_message.image_not_uploaded', 'value' => 'لم يتم تحميل الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.error_message.something', 'value' => 'خطأ   ما'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.id', 'value' => 'الرقم التعريف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.items', 'value' => 'عناصر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.loading', 'value' => 'الرجاء الانتظار ... '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.no_items_found', 'value' => 'لا يوجد عناصر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.save', 'value' => 'حفظ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.select_an_option', 'value' => 'رجاء اختيار خيار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.status', 'value' => 'الحالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.success_message.changed_status_successfully', 'value' => 'تم تغيير الحالة بنجاح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.success_message.created_successfully', 'value' => 'تم إنشاؤها بنجاح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.success_message.deleted_successfully', 'value' => 'حذف بنجاح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.success_message.updated_successfully', 'value' => 'تم التحديث بنجاح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.this_item', 'value' => 'هذا العنصر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.updated_at', 'value' => 'عدلت في'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.get_back', 'value' => 'عودة إلى الخلف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.nothing_here', 'value' => 'يبدو أنه لا شيء هنا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.system_error', 'value' => 'خطأ في النظام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.went_wrong', 'value' => 'حدث شيء ما!'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.try_again', 'value' => 'الرجاء المحاولة لاحقًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.unauthorized', 'value' => 'غير مصرح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.not_allowed', 'value' => 'غير مصرح لك بالتواجد هنا.'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.you_need_promotion', 'value' => 'يمكنك زيارة هذه الصفحة بعد الترقية...'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.logout', 'value' => 'تسجيل الخروج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.continue', 'value' => 'متابعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.all', 'value' => 'الجميع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.on', 'value' => 'مفعل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.off', 'value' => 'غير مفعل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.not_found', 'value' => 'غير متوفر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.view_columns', 'value' => 'Columns'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.excel', 'value' => 'Excel'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.pdf', 'value' => 'PDF'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.pageLength', 'value' => 'Page Length'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.uuid', 'value' => 'UUID'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.close', 'value' => 'أغلاق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.filter', 'value' => 'فلترا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.language', 'value' => 'اللغة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.datatables.delete_all', 'value' => 'حذف المحدد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.active', 'value' => 'مفعل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.disabled', 'value' => 'غير مفعل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.today', 'value' => 'اليوم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.yesterday', 'value' => 'الأمس'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.last_7_days', 'value' => 'آخر 7 أيام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.last_30_days', 'value' => 'آخر 30 يومًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.this_month', 'value' => 'هذا الشهر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.last_month', 'value' => 'الشهر الماضي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.time', 'value' => 'الوقت'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.class', 'value' => 'Class'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.show', 'value' => 'إظهار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.cut', 'value' => 'قص'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.download', 'value' => 'تنزيل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.send_to_user', 'value' => 'ارسال إلى المستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.sent_successfully', 'value' => 'تم الارسال بنجاح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'global.is_default', 'value' => 'الأفتراضي'),


        );
        $this->translations($global_ar);
        $this->translations($global_en);
        #endregion

        #region language
        $language_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.code', 'value' => 'Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.create_new_language', 'value' => 'Create New Language'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.display_type', 'value' => 'Display Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.edit', 'value' => 'Edit Language'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.flag', 'value' => 'Flag'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.is_default', 'value' => 'Default Language'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.language', 'value' => 'Language'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'language.status', 'value' => 'Status'),
        );

        $language_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.code', 'value' => 'الرمز'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.create_new_language', 'value' => 'انشئ لغة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.display_type', 'value' => 'طريقة العرض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.edit', 'value' => 'تعديل اللغة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.flag', 'value' => 'العلم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.is_default', 'value' => 'اللغة الافتراضية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.language', 'value' => 'اللغة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'language.status', 'value' => 'الحالة'),


        );
        $this->translations($language_ar);
        $this->translations($language_en);
        #endregion

        #region media
        $media_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.alt', 'value' => "alt"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.rel', 'value' => "rel"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.copy_like', 'value' => "copy link"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.description', 'value' => "description"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.details', 'value' => "details"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.drop_files_here_or_click_to', 'value' => "Drop files here or click to upload."),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.extension', 'value' => "extension"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.height', 'value' => "height"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.open_graph', 'value' => "open graph"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.path', 'value' => "path"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.please_select_media', 'value' => "Please select media"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.scale', 'value' => "scale"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.size', 'value' => "size"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.successful_upload', 'value' => "Successful Upload"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.title', 'value' => "title"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.type', 'value' => "type"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.upload_files', 'value' => "Upload Files"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.width', 'value' => "width"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.you_cant_use_this_name', 'value' => "you can't use this name"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.folder_name', 'value' => "folder name"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.create_new_folder', 'value' => "create new folder"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.save_folder', 'value' => "save folder"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.close_create_folder', 'value' => "close create  folder"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'media.already_exists_please_select_other_name', 'value' => "already exists,please select other name"),


        );
        $media_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.alt', 'value' => 'alt'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.rel', 'value' => 'rel'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.copy_like', 'value' => 'نسخ الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.description', 'value' => 'وصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.details', 'value' => 'الخصائص'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.drop_files_here_or_click_to', 'value' => 'قم بإسقاط الملفات هنا أو انقر للتحميل.'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.extension', 'value' => 'اللاحقة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.height', 'value' => 'الطول'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.open_graph', 'value' => 'open graph'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.path', 'value' => 'السمار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.please_select_media', 'value' => 'الرجاء تحديد الوسائط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.scale', 'value' => 'scale'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.size', 'value' => 'الحجم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.successful_upload', 'value' => 'تحميل ناجح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.title', 'value' => 'عنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.upload_files', 'value' => 'تحميل الملفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.width', 'value' => 'العرض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.you_cant_use_this_name', 'value' => 'لا يمكن استعمال هذا الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.folder_name', 'value' => "اسم المجلد"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.save_folder', 'value' => "حفظ المجلد"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.close_create_folder', 'value' => "اغلاق انشاء المجلد"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'media.create_new_folder', 'value' => "انشاء مجلد جديد"),

        );
        $this->translations($media_en);
        $this->translations($media_ar);
        #endregion

        #region menu
        $menu_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.admins', 'value' => 'Admins'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.attributes', 'value' => 'Attributes'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.categories', 'value' => 'Categories'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.cities', 'value' => 'Cities'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.colors', 'value' => 'Colors'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.countries', 'value' => 'Countries'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.dashboard', 'value' => 'Dashboard'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.languages', 'value' => 'Languages'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.location', 'value' => 'location'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.management', 'value' => 'Management'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.media', 'value' => 'Media'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.roles', 'value' => 'Roles'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.setting', 'value' => 'Setting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.attributes', 'value' => 'Attributes'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.pages', 'value' => 'Pages'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.brands', 'value' => 'Cars'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.coupons', 'value' => 'Coupons'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.users', 'value' => 'Users'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.tickets', 'value' => 'Tickets'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.wishlists', 'value' => 'WishLists'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.reviews', 'value' => 'Reviews'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.cms', 'value' => 'CMS'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.statuses', 'value' => 'Statuses'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.products', 'value' => 'Products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.import_product', 'value' => 'Import Products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.years', 'value' => 'Years'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.downloads', 'value' => 'Downloads'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.carts', 'value' => 'Carts'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.zones', 'value' => 'Zones'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.orders', 'value' => 'Orders'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.sellers', 'value' => 'Sellers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.sliders', 'value' => 'Sliders'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.manufacturers', 'value' => 'Manufacturers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.currencies', 'value' => 'Currencies'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.statistics', 'value' => 'statistics'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.redirects', 'value' => 'Redirects'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.sellers_earning', 'value' => 'Sellers Earning'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.offers', 'value' => 'Offers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.cards', 'value' => 'Cards'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.out_of_stock', 'value' => 'Out of stock'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.product_requests', 'value' => 'product request :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.whatsnew', 'value' => 'What`s new'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.user_wallets', 'value' => 'Pending Payment'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.notifications', 'value' => 'Notifications'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.contact_us', 'value' => 'Contact Us'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.compares', 'value' => 'Compares'),


        );
        $menu_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.admins', 'value' => 'مدراء'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.categories', 'value' => 'الفئات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.cities', 'value' => 'المدن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.dashboard', 'value' => 'لوحة القيادة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.languages', 'value' => 'اللغات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.management', 'value' => 'إدارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.media', 'value' => 'الوسائط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.roles', 'value' => 'الأدوار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.setting', 'value' => 'الاعدادات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.attributes', 'value' => 'الخصائص'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.pages', 'value' => 'الصفحات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.brands', 'value' => 'السيارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.coupons', 'value' => 'أكواد الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.users', 'value' => 'المستخدمون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.tickets', 'value' => 'الدعم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.wishlists', 'value' => 'المفضلات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.reviews', 'value' => 'آراء المستخدمين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.cms', 'value' => 'إدارة المحتوى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.statuses', 'value' => 'الحالات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.products', 'value' => 'المنتجات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.import_product', 'value' => 'استرداد المنتجات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.years', 'value' => 'السنوات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.download', 'value' => 'التنزيلات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.carts', 'value' => 'الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.zones', 'value' => 'المناطق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.orders', 'value' => 'الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.sellers', 'value' => 'البائعون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.sliders', 'value' => 'Sliders'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.manufacturers', 'value' => 'المصانع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.currencies', 'value' => 'العملات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.statistics', 'value' => 'الإحصائيات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.redirects', 'value' => 'التحويلات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.sellers_earning', 'value' => 'أرباح البائعين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.offers', 'value' => 'العروض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.cards', 'value' => 'الكروت'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.out_of_stock', 'value' => 'غير متوفر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.product_requests', 'value' => 'الطلبات على المنتج :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.whatsnew', 'value' => 'اخر الأخبار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.user_wallets', 'value' => 'Pending Payment'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.notifications', 'value' => 'الإشعارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.contact_us', 'value' => 'تواصل معنا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.compares', 'value' => 'المقارنات'),

        );
        $this->translations($menu_en);
        $this->translations($menu_ar);
        #endregion

        #region profile
        $profile_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.allowed_types', 'value' => 'Allowed Types'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.avatar', 'value' => 'Avatar'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.details', 'value' => 'Profile Details'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.email', 'value' => 'Email'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.name', 'value' => 'Full Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.password', 'value' => 'Password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'profile.password_confirmation', 'value' => 'Password Confirmation'),

        );
        $profile_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.allowed_types', 'value' => 'اللواحق المسموح بها'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.avatar', 'value' => 'الصورة الشخصية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.email', 'value' => 'البريد الإلكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.name', 'value' => 'الاسم الكامل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.password', 'value' => 'كلمة المرور'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'profile.password_confirmation', 'value' => 'تأكيد كلمة المرور'),
        );
        $this->translations($profile_en);
        $this->translations($profile_ar);
        #endregion

        #region role

        $role_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'role.create_new_role', 'value' => 'Create New Role'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'role.edit_role', 'value' => 'Edit Role : :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'role.guard', 'value' => 'Guard'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'role.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'role.role_permission', 'value' => 'Role Permissions'),
        );
        $role_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'role.create_new_role', 'value' => 'إنشاء دور جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'role.edit_role', 'value' => 'تحرير الدور : :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'role.guard', 'value' => 'Guard'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'role.name', 'value' => 'اسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'role.role_permission', 'value' => 'Role Permissions'),
        );
        $this->translations($role_ar);
        $this->translations($role_en);

        #endregion

        #region setting
        $setting_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.watermark', 'value' => 'watermark'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.watermark_status', 'value' => 'watermark status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.watermark_opacity', 'value' => 'watermark opacity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.translate', 'value' => 'Translate'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.admin_background', 'value' => 'Admin background'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.app_url', 'value' => 'App Url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.global_seo', 'value' => 'Global Seo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.keywords', 'value' => 'Keywords'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.logo', 'value' => 'Logo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_encryption', 'value' => 'Mail Encryption'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_from_address', 'value' => 'Mail From Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_from_name', 'value' => 'Mail From Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_host', 'value' => 'Mail Host'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_password', 'value' => 'Mail Password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_port', 'value' => 'Mail Port'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.mail_username', 'value' => 'Mail Username'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.meta_image', 'value' => 'Meta Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.smtp', 'value' => 'SMTP Setting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.smtp_type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.contact', 'value' => 'Contact'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social', 'value' => 'Social Media'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_email', 'value' => 'Email'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_facebook', 'value' => 'Facebook'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_google+', 'value' => 'Google+'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_messenger', 'value' => 'Messenger'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_phone', 'value' => 'Phone'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_telegram', 'value' => 'Telegram'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_tiktok', 'value' => 'Tiktok'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_twitter', 'value' => 'Twitter'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social.social_whatsapp', 'value' => 'Whatsapp'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.system_logo_black', 'value' => 'Dark Theme Logo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.system_logo_icon', 'value' => 'Logo Icon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.system_logo_white', 'value' => 'Light Theme Logo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.system_name', 'value' => 'System Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.timezone', 'value' => 'Timezone'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.title', 'value' => 'website title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.website', 'value' => 'Website'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_client_id', 'value' => 'Paypal Client Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_client_id_test', 'value' => 'Paypal Client Id Test'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_client_secret', 'value' => 'Paypal Client Secret'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_client_secret_test', 'value' => 'Paypal Client Secret Test'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_sandbox_mode', 'value' => 'Paypal Sandbox Mode'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal_status', 'value' => 'Paypal Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.paypal', 'value' => 'Paypal Setting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe', 'value' => 'Stripe Setting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_key', 'value' => 'Stripe Key'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_key_test', 'value' => 'Stripe Key Test'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_secret', 'value' => 'Stripe Secret'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_secret_test', 'value' => 'Stripe Secret Test'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_sandbox_mode', 'value' => 'Stripe Sandbox Mode'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.stripe_status', 'value' => 'Stripe Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.payment', 'value' => 'Payment Methods'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.product', 'value' => 'Product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.product_default_image', 'value' => 'Product Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_instagram', 'value' => 'Instagram'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_wechat', 'value' => 'WeChat'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_pinterest', 'value' => 'Pinterest'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_reddit', 'value' => 'Reddit'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_quora', 'value' => 'Quora'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_skype', 'value' => 'Skype'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_linkedin', 'value' => 'LinkedIn'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.default_images', 'value' => 'Default Images'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.category_default_image', 'value' => 'Category Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.attribute_default_image', 'value' => 'Attribute Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.download_default_image', 'value' => 'Download Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.status_default_image', 'value' => 'Status Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.brand_default_image', 'value' => 'Car Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.page_default_image', 'value' => 'Page Default Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.dhl', 'value' => 'dhl'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.fedex', 'value' => 'fedex'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.aramex', 'value' => 'aramex'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.ups', 'value' => 'ups'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.shipping_method', 'value' => 'Shipping method'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.shipping_default', 'value' => 'shipping default'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.banner_image', 'value' => 'Banner image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.banner_link', 'value' => 'Banner Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.frontend', 'value' => 'FrontEnd'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.banner_status', 'value' => 'Banner status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.facebook_app_id', 'value' => 'Facebook app id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.facebook_app_secret', 'value' => 'Facebook app secret'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.facebook_status', 'value' => 'Facebook Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.google_client_id', 'value' => 'Google client ID'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.google_client_secret', 'value' => 'Google client Secret'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.google_status', 'value' => 'Google status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.phone_title', 'value' => 'Phone title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.phone_key', 'value' => 'Phone Key'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.phone_status', 'value' => 'Phone status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.low_product_quantity_alert', 'value' => 'Low quantity alert'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.merchant_app_name', 'value' => 'Merchant app name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.merchant_id', 'value' => 'Merchant Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.client_credentials_path', 'value' => 'client credentials path'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.social_login', 'value' => 'Social login setting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.notifications', 'value' => 'Notifications'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_apiKey', 'value' => 'Api key'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_authDomain', 'value' => 'Auth domain'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_projectId', 'value' => 'Project id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_storageBucket', 'value' => 'Storage Bucket '),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_messagingSenderId', 'value' => 'Messaging sender id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_appId', 'value' => 'App Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_measurementId', 'value' => 'Measurement Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.firebase_server_api_key', 'value' => 'Server Api key'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.facebook_version', 'value' => 'Facebook Version'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.reCaptcha_code', 'value' => 'reCaptcha code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.send_reminder_after', 'value' => 'Send reminder after'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.sender_form', 'value' => 'Sender form'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.sender_form_id', 'value' => 'Send form id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.tawk_chat_api', 'value' => 'tawk chat api'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.api_currency_key', 'value' => 'Api currency key'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.sender_email_token', 'value' => 'sender email token'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.user_countries', 'value' => 'User Countries'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.google_map_iframe', 'value' => 'google Map Iframe'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_link', 'value' => 'small bottom banner link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_status', 'value' => 'small bottom banner status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_image', 'value' => 'small bottom banner image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_link', 'value' => 'big bottom banner link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_image', 'value' => 'big bottom banner status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_status', 'value' => 'big bottom banner image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.contact.address', 'value' => 'Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.offers_status', 'value' => 'Send offer status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.order_notifications_receivers', 'value' => 'Order mail receivers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.product_notifications_receivers', 'value' => 'Product mail receivers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.review_notifications_receivers', 'value' => 'Review mail receivers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.ticket_notifications_receivers', 'value' => 'Ticket mail receivers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.free_shipping_cost', 'value' => 'Total order on free shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.contact_us_notifications_receivers', 'value' => 'Contact us mail receivers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.top_banner_1', 'value' => 'top banner 1'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.top_banner_2', 'value' => 'top banner 2'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.sender_form_popup', 'value' => 'sender form popup'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.sender_form_id_popup', 'value' => 'sender form id popup'),

            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_key-remote', 'value' => 'key-remote'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_accessories-tools', 'value' => 'accessories-tools'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_device-machines', 'value' => 'device-machines'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_token-software', 'value' => 'token-software'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_manufacturers', 'value' => 'manufacturers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_cars', 'value' => 'cars'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_download', 'value' => 'download'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'setting.icon_pincode', 'value' => 'pincode'),


        );
        $setting_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.watermark', 'value' => 'watermark'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.watermark_status', 'value' => 'watermark status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.watermark_opacity', 'value' => 'watermark opacity'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.translate', 'value' => 'الترجمة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.admin_background', 'value' => 'خلفية المدير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.app_url', 'value' => 'App Url'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.global_seo', 'value' => 'Global Seo'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.keywords', 'value' => 'Keywords'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.logo', 'value' => 'الشعار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_encryption', 'value' => 'Mail Encryption'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_from_address', 'value' => 'عنوان بريد المرسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_from_name', 'value' => 'اسم مرسل البريد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_host', 'value' => 'Mail Host'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_password', 'value' => 'Mail Password'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_port', 'value' => 'Mail Port'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.mail_username', 'value' => 'Mail Username'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.meta_image', 'value' => 'Meta Image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.smtp', 'value' => 'إعدادات SMTP'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.smtp_type', 'value' => 'نوع SMTP'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.contact', 'value' => 'معلومات التواصل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social', 'value' => 'مواقع التواصل الاجتماعي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_email', 'value' => 'البريد الالكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_facebook', 'value' => 'Facebook'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_google+', 'value' => 'Google+'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_messenger', 'value' => 'Messenger'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_phone', 'value' => 'رقم الهاتف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_telegram', 'value' => 'Telegram'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_tiktok', 'value' => 'Tiktok'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_twitter', 'value' => 'Twitter'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social.social_whatsapp', 'value' => 'Whatsapp'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.system_logo_black', 'value' => 'شعار السمة المعتمة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.system_logo_icon', 'value' => 'أيقونة النظام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.system_logo_white', 'value' => 'شعار السمة المضيئة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.system_name', 'value' => 'اسم النظام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.timezone', 'value' => 'المنطقة الزمنية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.title', 'value' => 'اسم الموقع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.website', 'value' => 'الموقع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.product', 'value' => 'إعدادات المنتح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.product_default_image', 'value' => 'صورة المنتج الافتراضية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_instagram', 'value' => 'انستاغرام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_wechat', 'value' => 'وي تشات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_pinterest', 'value' => 'بينتيريست'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_reddit', 'value' => 'ريد ات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_quora', 'value' => 'كورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_skype', 'value' => 'سكايب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_linkedin', 'value' => 'لينكيد ان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.dhl', 'value' => 'dhl'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.fedex', 'value' => 'fedex'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.aramex', 'value' => 'aramex'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.ups', 'value' => 'ups'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.shipping_method', 'value' => 'طرق الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.shipping_default', 'value' => 'الشحن الافتراضي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.banner_image', 'value' => 'صورة البانر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.banner_link', 'value' => 'رابط البانر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.frontend', 'value' => 'frontend'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.banner_status', 'value' => 'Banner status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.facebook_app_id', 'value' => 'Facebook app id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.facebook_app_secret', 'value' => 'Facebook app secret'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.facebook_status', 'value' => 'Facebook Status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.google_client_id', 'value' => 'Google client ID'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.google_client_secret', 'value' => 'Google client Secret'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.google_status', 'value' => 'Google status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.phone_title', 'value' => 'Phone title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.phone_key', 'value' => 'Phone Key'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.phone_status', 'value' => 'Phone status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.low_product_quantity_alert', 'value' => 'Low quantity alert'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.merchant_app_name', 'value' => 'Merchant app name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.merchant_id', 'value' => 'Merchant Id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.client_credentials_path', 'value' => 'client credentials path'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.social_login', 'value' => 'إعدادات تسجيل الدخول'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.notifications', 'value' => 'إعدادات الإشعارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_apiKey', 'value' => 'Api key'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_authDomain', 'value' => 'Auth domain'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_projectId', 'value' => 'Project id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_storageBucket', 'value' => 'Storage Bucket'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_messagingSenderId', 'value' => 'Messaging sender id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_appId', 'value' => 'App Id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_measurementId', 'value' => 'Measurement Id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.firebase_server_api_key', 'value' => 'Server Api key'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.facebook_version', 'value' => 'Facebook version'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.reCaptcha_code', 'value' => 'reCaptcha code'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.send_reminder_after', 'value' => 'إرسال التذكير بعد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.sender_form', 'value' => 'نموذج المرسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.sender_form_id', 'value' => 'Sender form id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.tawk_chat_api', 'value' => 'Tawk chat api'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.api_currency_key', 'value' => 'Api currency key'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.sender_email_token', 'value' => 'Sender email token'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.user_countries', 'value' => 'دول المستخدمين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.google_map_iframe', 'value' => 'google Map Iframe'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_link', 'value' => 'small bottom banner link'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_status', 'value' => 'small bottom banner status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_small_banner_image', 'value' => 'small bottom banner image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_link', 'value' => 'big bottom banner link'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_image', 'value' => 'big bottom banner status'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.bottom_big_banner_status', 'value' => 'big bottom banner image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.contact.address', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.offers_status', 'value' => 'حالة إرسال العروض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.order_notifications_receivers', 'value' => 'مستقبلو بريد الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.product_notifications_receivers', 'value' => 'مستقبلو بريد المنتجات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.review_notifications_receivers', 'value' => 'مستقبلو بريد التقييمات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.ticket_notifications_receivers', 'value' => 'مستقبو بريد الدعم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.contact_us_notifications_receivers', 'value' => 'مستقبو بريد تواصل معنا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.top_banner_1', 'value' => 'Top banner 1'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'setting.top_banner_2', 'value' => 'Top banner 2'),
        );
        $this->translations($setting_en);
        $this->translations($setting_ar);
        #endregion

        #region Brand
        $brand_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.year', 'value' => 'السنة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.make', 'value' => 'الشركة المصنعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.model', 'value' => 'الموديل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.create_new_brand', 'value' => 'أنشئ سيارة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.description', 'value' => 'الوصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.image', 'value' => 'الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.models', 'value' => 'عرض الموديلات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.models_of', 'value' => 'الموديلات ل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.years_of', 'value' => 'سنوات الموديل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.create_model', 'value' => 'أنشئ موديلا جديدا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.years', 'value' => 'عرض السنوات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.add_model_to', 'value' => 'إضافة موديل ل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.create_year', 'value' => 'أنشئ سنة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.edit_model', 'value' => 'عدل الموديل: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.edit_year', 'value' => 'عدل السنة: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.edit_brand', 'value' => 'عدل السيارة: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'brand.pin_code_price', 'value' => 'سعر الرمز'),
        );

        $brand_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.year', 'value' => 'Year'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.make', 'value' => 'Make'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.model', 'value' => 'Model'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.create_new_brand', 'value' => 'Create New Car'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.description', 'value' => 'Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.models', 'value' => 'View Models'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.models_of', 'value' => 'Models of :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.years_of', 'value' => 'Years of :name model'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.create_model', 'value' => 'Create New Model'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.years', 'value' => 'View Years'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.add_model_to', 'value' => 'Add model to :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.create_year', 'value' => 'Create Year'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.edit_model', 'value' => 'Edit Model: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.edit_year', 'value' => 'Edit Year: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.edit_brand', 'value' => 'Edit Car: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'brand.pin_code_price', 'value' => 'pin Code Price'),
        );

        $this->translations($brand_ar);
        $this->translations($brand_en);
        #endregion

        #region Page
        $page_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.slug', 'value' => 'Slug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.image', 'value' => 'صورة الصفحة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.description', 'value' => 'وصف الصفحة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.create_new_page', 'value' => 'أنشئ صفحة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.meta_image', 'value' => 'Meta image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.meta_description', 'value' => 'Meta description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'page.edit', 'value' => 'تعديل الصفحة'),
        );
        $page_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.slug', 'value' => 'Slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.description', 'value' => 'Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.create_new_page', 'value' => 'Create New Page'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.meta_image', 'value' => 'Meta image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.meta_description', 'value' => 'Meta description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'page.edit', 'value' => 'Edit Page'),
        );

        $this->translations($page_ar);
        $this->translations($page_en);
        #endregion

        #region Coupon
        $coupon_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.code', 'value' => 'كود الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.type', 'value' => 'نوع الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.discount', 'value' => 'قيمة الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.discount_type', 'value' => 'نوع قيمة الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.starts_at', 'value' => 'بداية الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.ends_at', 'value' => 'نهاية الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.create_new_coupon', 'value' => 'أنشئ كود خصم جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.edit', 'value' => 'تعديل كود الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.minimum_shopping', 'value' => 'الحد الأدنى للشراء'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.maximum_discount', 'value' => 'قيمة الخصم الأعلى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.dates', 'value' => 'تواريخ الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.products', 'value' => 'المنتجات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.status', 'value' => 'هل تريد تفعيل هذه القسيمة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.max_use', 'value' => 'أكثر الاستخدام للخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.per_user', 'value' => 'اكثر استخدام للمستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'coupon.times_used', 'value' => 'Times used'),
        );

        $coupon_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.code', 'value' => 'Coupon Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.type', 'value' => 'Coupon Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.discount', 'value' => 'Discount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.discount_type', 'value' => 'Discount Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.starts_at', 'value' => 'Starts At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.ends_at', 'value' => 'Ends At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.create_new_coupon', 'value' => 'Create New Coupon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.edit', 'value' => 'Edit Coupon : :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.minimum_shopping', 'value' => 'Minimum Shopping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.maximum_discount', 'value' => 'Maximum Discount Value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.dates', 'value' => 'Dates'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.products', 'value' => 'Products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.status', 'value' => 'Do you want to activate this coupon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.max_use', 'value' => 'Max use'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.per_user', 'value' => 'Per user'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'coupon.times_used', 'value' => 'Times used'),
        );

        $this->translations($coupon_ar);
        $this->translations($coupon_en);
        #endregion

        #region products
        $product_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.title', 'value' => 'title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.summary_name', 'value' => 'summary name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.slug', 'value' => 'slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.category', 'value' => 'category'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.min_purchase_qty', 'value' => 'min purchase qty'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.description', 'value' => 'description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.priority', 'value' => 'priority'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.accessories', 'value' => 'accessories'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.image', 'value' => 'image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.twitter_image', 'value' => 'twitter image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.gallery', 'value' => 'gallery'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.videos', 'value' => 'videos'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.pdf', 'value' => 'pdf'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.price', 'value' => 'price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.discount_type', 'value' => 'discount type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.fixed', 'value' => 'Fixed'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.percent', 'value' => 'Percent'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.none', 'value' => 'None'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.discount_value', 'value' => 'discount value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.sku', 'value' => 'SKU'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.quantity', 'value' => 'quantity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.stock_visibility', 'value' => 'stock visibility ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_title', 'value' => 'meta title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_description', 'value' => 'meta description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_image', 'value' => 'meta image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_best_seller', 'value' => 'is best seller ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_super_sales', 'value' => 'is best offer ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_visibility', 'value' => 'is visibility ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_saudi_branch', 'value' => 'is saudi branch ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_featured', 'value' => 'is New Arrival ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_today_deal', 'value' => 'is today deal ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_free_shipping', 'value' => 'is free shipping ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_bundle', 'value' => 'is bundle ?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.hide_price', 'value' => 'hide price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.status', 'value' => 'do want active  product? '),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.create_new_product', 'value' => 'create new product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.sale_price', 'value' => 'sale price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.from', 'value' => 'from'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.to', 'value' => 'to'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.brand', 'value' => 'car'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.model', 'value' => 'model'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.year', 'value' => 'year'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.colors', 'value' => 'colors'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.accessories', 'value' => 'Accessories'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.bundles', 'value' => 'Bundles'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.brands', 'value' => 'Cars'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.add_brand', 'value' => 'Add car'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.bttributes', 'value' => 'Attributes'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.bundle', 'value' => 'bundle'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.add_video', 'value' => 'add video'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.meta_image', 'value' => 'Meta Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.check_slug.waiting', 'value' => 'please waiting to check on slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.check_slug.you_can_use_this_slug', 'value' => 'You can use this slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.check_slug.you_can_not_use_this_slug', 'value' => 'You can not use this slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.check_sku.you_can_use_this_sku', 'value' => 'You can use this sku'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.check_sku.you_can_not_use_this_sku', 'value' => 'You can not use this sku'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.please_select_brand', 'value' => 'please select car'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.please_select_option', 'value' => 'please select option'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.add_new_offer', 'value' => 'Add new offer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.serial_numbers', 'value' => 'Serial numbers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.serial_number', 'value' => 'Serial number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.add_new_serial_number', 'value' => 'Add new Serial Number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.discounts_and_offers', 'value' => 'Discounts && Offers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step1', 'value' => 'Step 1'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.information', 'value' => 'Information'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step2', 'value' => 'Step 2'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.media', 'value' => 'media'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.videos_type', 'value' => 'videos type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.videos_value', 'value' => 'videos value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.youtube', 'value' => 'youtube'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.vimeo', 'value' => 'vimeo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step3', 'value' => 'Step 3'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.attributes', 'value' => 'Attributes'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step4', 'value' => 'Step 4'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.stock_and_price', 'value' => 'Stock & Price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step5', 'value' => 'Step 5'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.accessories_and_bundles', 'value' => 'Accessories & Bundles'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.step6', 'value' => 'Step 6'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.seo', 'value' => 'SEO'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.edit', 'value' => 'Edit Product : :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.type', 'value' => 'Product type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.software', 'value' => 'Software'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.physical', 'value' => 'Hardware'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.weight', 'value' => 'weight'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.discount_range', 'value' => 'discount range'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.faq', 'value' => 'FAQ'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.series_number', 'value' => 'Series number(:product)'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.upload_excel_file', 'value' => 'Upload Excel File'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.download_template', 'value' => 'Download Template'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.upload_file', 'value' => 'Upload File'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.total_serial_number_is_duplicated', 'value' => 'total serial number is duplicated'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.total_serial_number_is_added', 'value' => 'total serial number is added'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.total_not_found_product', 'value' => 'total not found product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.back_in_stock', 'value' => 'back in stock'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.details', 'value' => 'Details'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.secondary_image', 'value' => 'Secondary image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.manufacturer', 'value' => 'Manufacturer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.short_title', 'value' => 'Short Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.low_in_store', 'value' => 'Low in the store'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.normal_quantity', 'value' => 'Normal quantity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.not_available', 'value' => 'Not available'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors', 'value' => 'Competitors'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_url', 'value' => 'Competitors Url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_tag', 'value' => 'Selector'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_type', 'value' => 'identifier name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_html_type', 'value' => 'Html Tag'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_price', 'value' => 'Price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.add_competitors', 'value' => 'Add Competitor'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.google_merchant', 'value' => 'Google Merchant'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.link', 'value' => 'Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.GTIN', 'value' => 'GTIN'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.mpn', 'value' => 'mpn'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.in_stock', 'value' => 'in stock'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.new', 'value' => 'New'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.blocked_countries', 'value' => 'blocked countries'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.date_type', 'value' => 'date type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.custom_date', 'value' => 'Custom date'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.for_ever', 'value' => 'For ever'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.create_new_sub_attribute', 'value' => 'Create new sub attribute'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.Create_new_attribute', 'value' => 'Create new attribute'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.request_count', 'value' => 'Request count'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.last_request', 'value' => 'Last Request'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.competitors_have_lower_price', 'value' => 'Competitors have lower price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.added_products', 'value' => 'Added products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.sold_quantity', 'value' => 'Sold quantity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.manufacturer_type', 'value' => 'Manufacturer type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.is_on_sale', 'value' => 'is on sale'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.price_is_hidden', 'value' => 'price is hidden'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.min_price', 'value' => 'Min price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.max_price', 'value' => 'Max price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.has_serial_numbers', 'value' => 'has serial numbers'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.featured', 'value' => 'Featured'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.super_sales', 'value' => 'Super sales'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.best_seller', 'value' => 'Best seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.free_shipping', 'value' => 'Free shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.on_sale', 'value' => 'On sale'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.saudi_branch', 'value' => 'Saudi branch'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.hidden_price', 'value' => 'Hidden price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.bundled', 'value' => 'Bundled'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.discount_offer', 'value' => 'Has discount & offer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'product.reviews', 'value' => 'reviews'),

        );
        $product_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.title', 'value' => 'عنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.summary_name', 'value' => 'ملخص الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.slug', 'value' => 'slug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.category', 'value' => 'الفئة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.min_purchase_qty', 'value' => 'اقل كمية للشراء'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.description', 'value' => 'الوصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.priority', 'value' => 'الأولوية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.accessories', 'value' => 'الاكسسورات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.image', 'value' => 'الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.twitter_image', 'value' => 'صورة التويتر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.gallery', 'value' => 'الاستديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.videos', 'value' => 'الفيديوهات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.pdf', 'value' => 'ملف PDF'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.price', 'value' => 'السعر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.discount_type', 'value' => 'نوع الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.fixed', 'value' => 'ثابت'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.percent', 'value' => 'مئوي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.none', 'value' => 'لايوجد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.discount_value', 'value' => 'قيمة الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.sku', 'value' => 'SKU'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.quantity', 'value' => 'الكمية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.stock_visibility', 'value' => 'هل متاح في المستودع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_title', 'value' => 'meta title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_description', 'value' => 'meta description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_image', 'value' => 'meta image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_best_seller', 'value' => 'افضل البائعين ؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_super_sales', 'value' => 'أفضل عرض ؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_visibility', 'value' => 'هل ممكن ؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_saudi_branch', 'value' => 'ضمن فرع السعودية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_featured', 'value' => 'is New Arrival ?'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_today_deal', 'value' => 'صفقة اليوم ؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_free_shipping', 'value' => 'شحن المجاني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_bundle', 'value' => 'باقة ؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.hide_price', 'value' => 'أخفاء السعر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.status', 'value' => 'هل تريد تفعيل المنتج ؟ '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.create_new_product', 'value' => 'انشاء منتج جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.sale_price', 'value' => 'سعر المبيع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.from', 'value' => 'من'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.to', 'value' => 'إلى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.brand', 'value' => 'السيارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.model', 'value' => 'الموديل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.year', 'value' => 'السنة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.colors', 'value' => 'الألوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.accessories', 'value' => 'أكسسورات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.bundles', 'value' => 'الباقات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.brands', 'value' => 'السيارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.add_brand', 'value' => 'اضافة  سيارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.bttributes', 'value' => 'المواصفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.bundle', 'value' => 'باقة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.add_video', 'value' => 'اضافة فيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.meta_image', 'value' => 'Meta Image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.check_slug.waiting', 'value' => 'الرجاء الأنتظار ..'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.check_slug.you_can_use_this_slug', 'value' => 'يمكن استعمال هذا الslug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.check_slug.you_can_not_use_this_slug', 'value' => 'لا يمكن استعمال هذا الslug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.please_select_brand', 'value' => 'الرجاء اختبار السيارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.please_select_option', 'value' => 'الرجاء اختيار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.add_new_offer', 'value' => 'اضافة عرض جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.serial_numbers', 'value' => ' ارقام تسلسلي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.serial_number', 'value' => 'رقم تسلسلي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.add_new_serial_number', 'value' => 'اضافة رقم تسلسلي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.discounts_and_offers', 'value' => 'عروض والحسومات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step1', 'value' => 'الخطوة 1'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.information', 'value' => 'المعلومات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step2', 'value' => 'الخطوة 2'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.media', 'value' => 'الميديا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.videos_type', 'value' => 'نوع الفيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.videos_value', 'value' => 'الفيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.youtube', 'value' => 'youtube'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.vimeo', 'value' => 'vimeo'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step3', 'value' => 'الخطوة 3'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.attributes', 'value' => 'المواصفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step4', 'value' => 'الخطوة 4'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.stock_and_price', 'value' => 'المستودع والاسعار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step5', 'value' => 'الخطوة 5'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.accessories_and_bundles', 'value' => 'الأكسسورات و الباقات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.step6', 'value' => 'الخطوة 6'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.seo', 'value' => 'SEO'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.edit', 'value' => 'تعديل المنتج :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.type', 'value' => 'نوع المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.software', 'value' => 'سوفت وير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.physical', 'value' => 'هارد وير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.weight', 'value' => 'الوزن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.check_sku.you_can_use_this_sku', 'value' => 'يمكن استعمال هذا الـ SKU'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.check_sku.you_can_not_use_this_sku', 'value' => 'لا يمكن استعمال هذا الـ SKU'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.discount_range', 'value' => 'مدة العرض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.faq', 'value' => 'الأسئلة الشائعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.series_number', 'value' => 'الأرقام المتسلسلة (:product)'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.upload_excel_file', 'value' => 'رفع ملف اكسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.download_template', 'value' => 'تنزيل الملف اكسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.upload_file', 'value' => 'رفع الملف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.total_serial_number_is_duplicated', 'value' => 'عدد الارقام المتكررة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.total_serial_number_is_added', 'value' => 'عدد الارقام الجديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.total_not_found_product', 'value' => 'المنتجات غير متوفرة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.back_in_stock', 'value' => 'back in stock'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.details', 'value' => 'التفاصيل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.secondary_image', 'value' => 'الصورة الثانوية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.manufacturer', 'value' => 'المصنع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.short_title', 'value' => 'العنوان القصير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.low_in_store', 'value' => 'منخفض في المخزون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.normal_quantity', 'value' => 'الكمية غير منخفضة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.not_available', 'value' => 'غير متوفر في المتجر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors', 'value' => 'لمنافسون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors_url', 'value' => 'روابط المنافسين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors_type', 'value' => 'نوع المُعرف للمنافس'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors_html_type', 'value' => 'نوع html tag'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors_price', 'value' => 'السعر المنافس'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.add_competitors', 'value' => 'أضف منافسًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.google_merchant', 'value' => 'Google Merchant'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.link', 'value' => 'الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.GTIN', 'value' => 'GTIN'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.mpn', 'value' => 'mpn'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.new', 'value' => 'جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.blocked_countries', 'value' => 'الدول المحظورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.date_type', 'value' => 'نوع المدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.custom_date', 'value' => 'مدة محددة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.for_ever', 'value' => 'غير محددة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.create_new_sub_attribute', 'value' => 'أضافة صفات فرعية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.Create_new_attribute', 'value' => 'أضافة صفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.request_count', 'value' => 'عدد الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.last_request', 'value' => 'آخر طلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.competitors_have_lower_price', 'value' => 'المنافسون لهذا المنتج لديهم سعر أقل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.added_products', 'value' => 'المنتجات المضافة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.sold_quantity', 'value' => 'الكمية المباعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.manufacturer_type', 'value' => 'أنواع الشركة المصنعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.is_on_sale', 'value' => 'is on sale '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.price_is_hidden', 'value' => 'price is hidden'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.min_price', 'value' => 'min price'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.max_price', 'value' => 'max price'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.has_serial_numbers', 'value' => 'has serial numbers'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.featured', 'value' => 'عنصر مميز'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.super_sales', 'value' => 'Super sales'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.best_seller', 'value' => 'Best seller'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.free_shipping', 'value' => 'Free shipping'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.on_sale', 'value' => 'On sale'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.saudi_branch', 'value' => 'Saudi branch'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.hidden_price', 'value' => 'Hidden price'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.bundled', 'value' => 'Bundled'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.discount_offer', 'value' => 'Has discount & offer'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'product.reviews', 'value' => 'التقيمات'),

        );
        $this->translations($product_en);
        $this->translations($product_ar);
        #endregion

        #region user
        $user_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.name', 'value' => 'الاسم الكامل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.email', 'value' => 'عنوان البريد الإلكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.phone', 'value' => 'رقم الهاتف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.create_new_user', 'value' => 'أنشئ مستخدمًا جديدًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.edit', 'value' => 'تعديل المستخدم: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.city', 'value' => 'المدينة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.address', 'value' => 'العنوان الكامل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.addresses', 'value' => 'عناوين الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.password', 'value' => 'كلمة المرور'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.password_confirmation', 'value' => 'تأكيد كلمة المرور'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.status', 'value' => 'هل تريد تفعيل هذا المستخدم؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.avatar', 'value' => 'الصورة الشخصية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.postal_code', 'value' => 'الصورة الشخصية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.state', 'value' => 'الولاية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.street', 'value' => 'الشارع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.company_name', 'value' => 'اسم الشركة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.website_url', 'value' => 'رابط الموقع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.type_of_business', 'value' => 'نوع العمل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.overview', 'value' => 'تعديل البيانات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.tickets', 'value' => 'الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.wishlists', 'value' => 'المفضلة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.product', 'value' => 'المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.reviews', 'value' => 'المراجعات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.seller', 'value' => 'البائع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.wallet', 'value' => 'المحفظة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.approved', 'value' => 'المؤكدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.orders_count', 'value' => 'عدد الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.purchase_value', 'value' => 'قيمة المشتريات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.avg_purchase_value', 'value' => 'متوسط قيمة الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.send_reminder', 'value' => ' تذكير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.send_account_statement', 'value' => ' كشف الحساب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.send', 'value' => ' إرسال'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.user_doesnot_have_dept', 'value' => ' ليس على هذا الزبون مستحقات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.country', 'value' => ' الدولة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.balance', 'value' => 'الميزانية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.has_dept', 'value' => 'عليه دفعات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.balanced', 'value' => 'صفر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'user.has_deposit', 'value' => 'لديه إيداع'),
        );

        $user_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.name', 'value' => 'Full Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.email', 'value' => 'Email Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.phone', 'value' => 'Phone Number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.create_new_user', 'value' => 'Create New User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.edit', 'value' => 'Edit User: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.city', 'value' => 'City'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.address', 'value' => 'Full Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.addresses', 'value' => 'Addresses'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.password', 'value' => 'Password'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.password_confirmation', 'value' => 'Password Confirmation'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.status', 'value' => 'Do you want to activate this user?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.avatar', 'value' => 'Avatar'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.postal_code', 'value' => 'Postal Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.street', 'value' => 'Street'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.state', 'value' => 'State'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.company_name', 'value' => 'Company Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.website_url', 'value' => 'Website Url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.type_of_business', 'value' => 'Type of business'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.overview', 'value' => 'Update Information'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.tickets', 'value' => 'Tickets'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.wishlists', 'value' => 'Wishlists'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.product', 'value' => 'Product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.reviews', 'value' => 'Reviews'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.seller', 'value' => 'Seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.wallet', 'value' => 'Wallet'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.approved', 'value' => 'Approved'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.orders_count', 'value' => 'Orders'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.purchase_value', 'value' => 'Purchase Value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.avg_purchase_value', 'value' => 'Average order value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.send_reminder', 'value' => 'Reminder'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.send_account_statement', 'value' => 'Account statement'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.send', 'value' => 'Send'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.user_doesnot_have_dept', 'value' => 'User does not have a dept'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.country', 'value' => 'Country'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.balance', 'value' => 'Balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.has_dept', 'value' => 'Has dept'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.balanced', 'value' => 'Balanced'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'user.has_deposit', 'value' => 'Has deposit'),

        );

        $this->translations($user_ar);
        $this->translations($user_en);

        #endregion

        #region ticket

        $ticket_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.ticket_id', 'value' => 'رقم الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.type', 'value' => 'نوع الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.subject', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.user', 'value' => 'المستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.status', 'value' => 'حالة الطللب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.last_reply', 'value' => 'آخر رد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.reply.send', 'value' => 'أرسل الرد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.files', 'value' => 'ارفع ملفاتك هنا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.pending', 'value' => 'معلق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.open', 'value' => 'مفتوح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.solved', 'value' => 'تم حلها'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.order', 'value' => 'الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.product', 'value' => 'المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.support', 'value' => 'الدعم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.shipping', 'value' => 'الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.other', 'value' => 'أخرى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'ticket.model', 'value' => 'العنصر'),

        );
        $ticket_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.ticket_id', 'value' => 'Ticket Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.type', 'value' => 'Ticket Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.subject', 'value' => 'Subject'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.user', 'value' => 'User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.status', 'value' => 'Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.last_reply', 'value' => 'Last Replay'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.reply.send', 'value' => 'Replay'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.files', 'value' => 'Upload your file'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.pending', 'value' => 'Pending'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.open', 'value' => 'Open'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.solved', 'value' => 'Solved'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.order', 'value' => 'Order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.product', 'value' => 'Product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.support', 'value' => 'Support'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.shipping', 'value' => 'Shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.other', 'value' => 'Other'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'ticket.model', 'value' => 'Model'),

        );

        $this->translations($ticket_ar);
        $this->translations($ticket_en);
        #endregion

        #region address
        $address_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.address', 'value' => 'العنوان :number'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.primary', 'value' => 'العنوان الأساسي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.you_can_add_new_address', 'value' => 'بإمكانك إضافة عناوين أخرى!'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.new_address', 'value' => 'أضف عنوانًأ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.city', 'value' => 'المدينة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.postal_code', 'value' => 'الرقم البريدي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.add_as_default_address', 'value' => 'إضافة كعنوان أساسي؟'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.default_address', 'value' => 'العنوان الأساسي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'address.edit :number', 'value' => 'تعديل العنوان :number'),
        );

        $address_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.address', 'value' => 'Address :number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.primary', 'value' => 'Primary'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.you_can_add_new_address', 'value' => 'You Also Can Add New Address!'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.new_address', 'value' => 'Add Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.city', 'value' => 'City'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.postal_code', 'value' => 'Postal Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.add_as_default_address', 'value' => 'Add this address as default address?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.default_address', 'value' => 'Primary address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.edit', 'value' => 'Edit Address :number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.set_default', 'value' => 'Set As Default'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'address.add_new_address', 'value' => 'Add Address'),

        );

        $this->translations($address_ar);
        $this->translations($address_en);
        #endregion

        #region status
        $status_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.order', 'value' => 'الترتيب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.create_new_status', 'value' => 'أنشئ حالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.edit', 'value' => 'تعديل الحالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.video', 'value' => 'رابط فيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.link', 'value' => 'رابط خارجي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'status.image', 'value' => 'صورة'),

        );

        $status_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.order', 'value' => 'Order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.create_new_status', 'value' => 'Create Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.edit', 'value' => 'Edit Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.video', 'value' => 'Video link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.link', 'value' => 'External Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'status.image', 'value' => 'Image'),

        );

        $this->translations($status_ar);
        $this->translations($status_en);
        #endregion

        #region download
        $download_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.description', 'value' => 'الوصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.screen_shot', 'value' => 'لقطة شاشة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.internal_image', 'value' => 'Internal Image'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.name', 'value' => 'الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.add_attribute', 'value' => 'إضافة رابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.add_video', 'value' => 'إضافة فيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.create_new_download', 'value' => 'أنشئ تنزيل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.edit', 'value' => 'تعديل التنزيل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.image', 'value' => 'الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.slug', 'value' => 'Slug'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.gallery', 'value' => 'معرض الصور'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.type', 'value' => 'نوع الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.link', 'value' => 'الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.videos_type', 'value' => 'نوع الفيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.videos_value', 'value' => 'رابط الفيديو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.youtube', 'value' => 'يوتيوب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.vimeo', 'value' => 'فيمو'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'download.download_type', 'value' => 'نوع التنزيل'),
        );

        $download_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.description', 'value' => 'Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.meta_title', 'value' => 'Meta Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.meta_description', 'value' => 'Meta Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.screen_shot', 'value' => 'Screen Shot'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.internal_image', 'value' => 'Internal Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.add_attribute', 'value' => 'Add Attribute'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.add_video', 'value' => 'Add Video'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.create_new_download', 'value' => 'Create Download'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.edit', 'value' => 'Edit: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.slug', 'value' => 'Slug'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.gallery', 'value' => 'Gallery'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.link', 'value' => 'Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.videos_type', 'value' => 'Video Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.videos_value', 'value' => 'Video Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.youtube', 'value' => 'Youtube'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.vimeo', 'value' => 'Vimeo'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'download.download_type', 'value' => 'Download Type'),
        );

        $this->translations($download_ar);
        $this->translations($download_en);

        #endregion

        #region carts
        $carts_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.cart_id', 'value' => 'Id'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.user', 'value' => 'المستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.Image', 'value' => 'صورة المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.product', 'value' => 'المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.price', 'value' => 'السعر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.discount', 'value' => 'الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.quantity', 'value' => 'العدد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'cart.image', 'value' => 'الصورة'),
        );
        $carts_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.cart_id', 'value' => 'Id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.user', 'value' => 'User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.Image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.product', 'value' => 'Product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.price', 'value' => 'Price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.discount', 'value' => 'Discount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.quantity', 'value' => 'Quantity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'cart.image', 'value' => 'Image'),
        );

        $this->translations($carts_ar);
        $this->translations($carts_en);
        #endregion

        #region wallet
        $wallet_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.show', 'value' => 'show wallet'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.amount', 'value' => 'amount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.type', 'value' => 'type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.status', 'value' => 'status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.order', 'value' => 'order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.order_payment', 'value' => 'order payment'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.created_by', 'value' => 'created by'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.refund', 'value' => 'Refund'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.withdraw', 'value' => 'Withdraw'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.approve', 'value' => 'Approve'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.reject', 'value' => 'Reject'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.change_balance', 'value' => 'Change Balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.user', 'value' => 'User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.balance', 'value' => 'Balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.payment_info', 'value' => 'Payment Information'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.files', 'value' => 'Files'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.order_balance', 'value' => 'Order Balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.credit', 'value' => 'unpaid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.part_credit', 'value' => 'part paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.total', 'value' => 'paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'wallet.you_cannot_edit_statement', 'value' => "you can't edit statement"),

        );
        $wallet_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.show', 'value' => 'مشاهدة الرصيد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.amount', 'value' => 'المبلغ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.type', 'value' => 'نوع الدفعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.status', 'value' => 'الحالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.order', 'value' => 'الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.order_payment', 'value' => 'عملية الدفع '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.created_by', 'value' => 'انشاء عن طريق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.refund', 'value' => 'مسترجع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.withdraw', 'value' => 'Withdraw'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.approve', 'value' => 'قبول'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.reject', 'value' => 'رفض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.change_balance', 'value' => 'تعديل الرصسيد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.payment_info', 'value' => 'معلومات الدفعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.files', 'value' => 'ملفات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.order_balance', 'value' => 'Order Balance'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.credit', 'value' => 'دين كامل '),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.part_credit', 'value' => 'دفعة جزيئة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'wallet.total', 'value' => 'دفع كامل '),

        );
        $this->translations($wallet_en);
        $this->translations($wallet_ar);
        #endregion

        #region menu
        $menus_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.menus', 'value' => 'القوائم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.header', 'value' => 'header'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.footer_column_1', 'value' => 'footer column 1'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.footer_column_2', 'value' => 'footer column 2'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.link', 'value' => 'الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.create_new_menu', 'value' => 'أنشئ قائمة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.edit', 'value' => 'تعديل :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'menu.icon', 'value' => 'تعديل :name'),
        );

        $menus_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.menus', 'value' => 'Menus'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.link', 'value' => 'Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.create_new_menu', 'value' => 'Create Menu'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.edit', 'value' => 'Edit: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.header', 'value' => 'header'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.footer_column_1', 'value' => 'footer column 1'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.footer_column_2', 'value' => 'footer column 2'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'menu.icon', 'value' => 'footer column 2'),
        );
        $this->translations($menus_ar);
        $this->translations($menus_en);

        #endregion

        #region menu
        $dashboard = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.visits_last_week', 'value' => 'الزيارات آخر اسبوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.seller_earning', 'value' => 'ارباح المبيعات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.seller_earning_date', 'value' => ':from إلى :to'),

        );
        $dashboard_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.visits_last_week', 'value' => 'Visits last week'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.seller_earning', 'value' => 'Seller Earning'),

            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.seller_earning_date', 'value' => ':from to :to'),
        );
        $this->translations($dashboard);
        $this->translations($dashboard_en);

        #endregion

        #region zone
        $zone_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'zone.edit', 'value' => 'تعديل الزون :number'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'zone.add_price', 'value' => 'إضافة سعر جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'zone.price', 'value' => 'السعر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'zone.weight', 'value' => 'الوزن'),
        );
        $zone_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'zone.edit', 'value' => 'Edit Zone: :number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'zone.add_price', 'value' => 'Add Price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'zone.price', 'value' => 'Price'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'zone.weight', 'value' => 'Weight'),

        );
        $this->translations($zone_ar);
        $this->translations($zone_en);

        #endregion

        #region dashboard
        $dashboard_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.order', 'value' => 'الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.total', 'value' => 'المجموع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.order_completed', 'value' => ' المكتملة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.order_failed', 'value' => ' فشلت'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.pending_payment', 'value' => 'قيد الانتظار للدفع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'dashboard.order_processing', 'value' => 'ضمن عملية المعالجة'),

        );
        $dashboard_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.order', 'value' => 'Order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.total', 'value' => 'Total'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.order_completed', 'value' => 'Completed'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.order_failed', 'value' => 'Order failed'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.pending_payment', 'value' => 'Pending payment'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'dashboard.order_processing', 'value' => 'Processing'),

        );
        $this->translations($dashboard_ar);
        $this->translations($dashboard_en);
        #endregion

        #region order
        $order_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.details', 'value' => 'تفاصيل الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.payment_method', 'value' => 'طريقة الدفع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.shipping_method', 'value' => 'طريق الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.user', 'value' => 'المستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.customer_details', 'value' => 'تفاصيل العميل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.city', 'value' => 'المدينة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.country', 'value' => 'البلد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.total', 'value' => 'مجموع الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.sub_total', 'value' => 'Sub total'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.payment_status', 'value' => 'حالة الدفع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.shipping', 'value' => 'الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.has_coupon', 'value' => 'هل لديه خصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.coupon_value', 'value' => 'قيمة الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.type', 'value' => 'نوع الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.discount', 'value' => 'قيمة الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.canceled', 'value' => 'ملغى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.completed', 'value' => 'مكتمل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.failed', 'value' => 'فشل الطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.on_hold', 'value' => 'معلق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.pending_payment', 'value' => 'تعليق الدفع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.processing', 'value' => 'جاري العمل عليها'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.refunded', 'value' => 'تمت إعادته'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.tracking_number', 'value' => 'رقم التعقب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.status', 'value' => 'الحالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.address', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.coupon', 'value' => 'الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.seller_commission', 'value' => 'عمولة البائع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.seller_manager_commission', 'value' => 'عمولة المدير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.seller', 'value' => 'البائع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.seller_manager', 'value' => 'المدير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.note', 'value' => 'الملاحظات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.payment_history', 'value' => 'الدفعات الماضية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.amount', 'value' => 'المبلغ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.stripe_link', 'value' => 'Stripe Link'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.paypal', 'value' => 'Paypal'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.transfer', 'value' => 'Transfer'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.stripe', 'value' => 'Stripe'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.created', 'value' => 'Created'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.captured', 'value' => 'Approved'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.denied', 'value' => 'رُفض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.refund', 'value' => 'مرتجع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.pending', 'value' => 'معلق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.voided', 'value' => 'Voided'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.file', 'value' => 'الملف :number'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.transfer_details', 'value' => 'تفاصيل التحويل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.amount', 'value' => 'المبلغ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.before_balance', 'value' => 'قبل التحويل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.balance', 'value' => 'الميزانية'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.make_order', 'value' => 'التحويل كطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.make_order_confirmation', 'value' => 'التحويل كطلب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.waiting', 'value' => 'المنتظرة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.wallet', 'value' => 'wallet'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.payment_details  ', 'value' => 'PAYMENT DETAILS'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.payment_details  ', 'value' => 'PAYMENT DETAILS'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.all', 'value' => 'Total'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.order_type', 'value' => 'Order Type'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.pin_code', 'value' => 'Pin Code'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.proforma', 'value' => 'Proforma'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.order', 'value' => 'Order'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.brand', 'value' => 'الماركة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.serial_number', 'value' => 'الرقم التسلسلي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.contact_channel', 'value' => 'طريقة التواصل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.contact_value', 'value' => 'التواصل عبر'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.feedback', 'value' => 'feedback'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.order_number', 'value' => 'Order Number (:num)'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.proforma_number', 'value' => 'Proforma Number (:num)'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.unpaid', 'value' => 'Unpaid'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.paid', 'value' => 'Paid'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.refund_successfully', 'value' => 'refund successfully'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.cant_refund_because_is_not_hold_or_canceled', 'value' => "can't refund because the status is not on hold or canceled"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.is_free_shipping', 'value' => "شحن مجاني"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.no_seller', 'value' => 'No Seller'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'order.cancel', 'value' => 'Cancel'),

        );

        $order_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.uuid', 'value' => 'Order UUID'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.created_at', 'value' => 'Created At'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.details', 'value' => 'Order details'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.payment_method', 'value' => 'Payment Method'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.shipping_method', 'value' => 'Shipping Method'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.user', 'value' => 'User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.customer_details', 'value' => 'Customer Details'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.city', 'value' => 'City'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.country', 'value' => 'Country'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.total', 'value' => 'Total'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.sub_total', 'value' => 'Sub total'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.payment_status', 'value' => 'Payment Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.shipping', 'value' => 'Shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.has_coupon', 'value' => 'Has Coupon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.coupon_value', 'value' => 'Coupon Value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.discount', 'value' => 'Discount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.canceled', 'value' => 'Canceled'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.completed', 'value' => 'Completed'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.failed', 'value' => 'Failed'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.on_hold', 'value' => 'On hold'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.pending_payment', 'value' => 'Pending Payment'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.processing', 'value' => 'Processing'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.refunded', 'value' => 'Refunded'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.tracking_number', 'value' => 'Tracking No '),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.status', 'value' => 'Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.address', 'value' => 'Address'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.coupon', 'value' => 'Coupon'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.seller_commission', 'value' => 'Commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.seller_manager_commission', 'value' => 'Manager Commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.seller', 'value' => 'Seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.seller_manager', 'value' => 'Manager'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.note', 'value' => 'Note'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.payment_history', 'value' => 'Payment History'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.amount', 'value' => 'Amount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.stripe_link', 'value' => 'Stripe link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.paypal', 'value' => 'Paypal'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.transfer', 'value' => 'Transfer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.stripe', 'value' => 'Stripe'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.created', 'value' => 'Created'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.captured', 'value' => 'Approved'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.denied', 'value' => 'Denied'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.refund', 'value' => 'Refund'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.pending', 'value' => 'Pending'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.voided', 'value' => 'Voided'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.file', 'value' => 'File :number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.transfer_details', 'value' => 'Transfer details'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.amount', 'value' => 'Amount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.before_balance', 'value' => 'Before balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.balance', 'value' => 'Balance'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.make_order', 'value' => 'Change As Order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.make_order_confirmation', 'value' => 'Are you sure?'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.waiting', 'value' => 'Waiting'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.wallet', 'value' => 'wallet'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.payment_details', 'value' => 'PAYMENT DETAILS'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.all', 'value' => 'Total'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.order_type', 'value' => 'Order Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.pin_code', 'value' => 'Pin Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.proforma', 'value' => 'Proforma'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.order', 'value' => 'Order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.dhl', 'value' => 'DHL'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.fedex', 'value' => 'FeDex'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.ups', 'value' => 'Ups'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.aramex', 'value' => 'Aramex'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.brand', 'value' => 'Brand'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.serial_number', 'value' => 'Serial number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.contact_channel', 'value' => 'contact channel'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.contact_value', 'value' => 'value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.feedback', 'value' => 'feedback'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.order_number', 'value' => 'Order Number (:num)'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.proforma_number', 'value' => 'Proforma Number (:num)'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.unpaid', 'value' => 'Unpaid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.paid', 'value' => 'Paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.shipment_value', 'value' => 'shipment value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.shipment_description', 'value' => 'shipment description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.paid', 'value' => 'Paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.refund_successfully', 'value' => 'refund successfully'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.cant_refund_because_is_not_hold_or_canceled', 'value' => "can't refund because the status is not on hold or canceled"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.is_free_shipping', 'value' => "free shipping"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.no_seller', 'value' => 'No Seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.cancel', 'value' => 'Cancel'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.than_you_for_your_order', 'value' => 'thank you for your order.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.your_order_is_in_processing_status', 'value' => 'your order is in processing status.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.your_order_is_in_completed_status', 'value' => 'your order is in completed status.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.order_number', 'value' => 'Order Number'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.tracking_number', 'value' => 'Tracking Number :'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.please_go_to_each_product_and_rate_it', 'value' => 'Please go to each product and rate it'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.order_confirmation', 'value' => 'Order #'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.amount', 'value' => "Dear :name, an amount of :amount has been added to the order number :order And the remainder for repayment is :balance"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.withdraw', 'value' => "Dear :name, an amount of :amount has been withdrawn from the order number :order, the current balance of the order is :balance"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.refund', 'value' => "Dear :name, the order has been returned. Please contact the administration to get the money back"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.payment_information', 'value' => "payment information"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.feedback', 'value' => "Feedback"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.statement.feedback_statement', 'value' => "Dear :name , Thank you for dealing with us and we hope you evaluate the products"),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.mail.order_content', 'value' => 'Hi :name' . PHP_EOL . 'Your request No. :order_uuid has been received' . PHP_EOL . 'Thank you for choosing us for your shopping.' . PHP_EOL . 'If you have a problem with the product you received, you can report it here.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.coupons.this_coupon_is_invalidate', 'value' =>'this coupon is invalid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'order.coupons.this_coupon_is_invalid', 'value' => 'this coupon is invalid'),


        );
        $this->translations($order_ar);
        $this->translations($order_en);
        #endregion

        #region seller
        $seller_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.create_new_seller', 'value' => 'أنشئ بائعا جديدا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.commission', 'value' => ': :nameعمولة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.from', 'value' => 'من'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.to', 'value' => 'إلى'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.you_cant_add_commission_because_it_already_exists', 'value' => 'لا يمكنك إضافة عمولة لأنها موجودة بالفعل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.seller_manager', 'value' => 'مدير البائعين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.edit', 'value' => 'تعديل البائع: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.is_manager', 'value' => 'مدير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.orders', 'value' => 'طلبات البائع: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.user', 'value' => 'المستخدم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.seller_product_rate', 'value' => 'Seller Product Rate'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.withdraw', 'value' => 'المبالغ المسحوبة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.refund', 'value' => 'المرتجع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.pending_commission', 'value' => 'العمولة المنتظرة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.waited_commission', 'value' => "بانتظار الستوك"),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.canceled_commission', 'value' => 'العمولة الملغاة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.approved_commission', 'value' => 'العمولة المؤكدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.wallet', 'value' => 'المحفظة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.show_wallet', 'value' => 'محفظة :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.manager', 'value' => 'الإدارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.from_value', 'value' => 'من قيمة المبيعات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.to_value', 'value' => 'إلى قيمة المبيعات'),
        );
        $seller_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.create_new_seller', 'value' => 'Create New Seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.commission', 'value' => 'Commission: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.from', 'value' => 'From'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.to', 'value' => 'To'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.you_cant_add_commission_because_it_already_exists', 'value' => 'You cannot add commission because it already exists'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.seller_manager', 'value' => 'Seller Manager'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.edit', 'value' => 'Edit seller: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.is_manager', 'value' => 'Manager'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.orders', 'value' => 'Orders from: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.user', 'value' => 'User'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.seller_product_rate', 'value' => 'Seller Product Rate'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.withdraw', 'value' => 'Withdraw'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.refund', 'value' => 'refund'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.pending_commission', 'value' => 'pending commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.waited_commission', 'value' => 'waiting Commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.canceled_commission', 'value' => 'Canceled Commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.approved_commission', 'value' => 'Approved Commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.wallet', 'value' => 'Wallet'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.show_wallet', 'value' => ':name Wallet'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.manager', 'value' => 'Management'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.role', 'value' => 'Role'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.from_value', 'value' => 'Sold from'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.to_value', 'value' => 'sold To'),
        );

        $this->translations($seller_ar);
        $this->translations($seller_en);

        #endregion

        #region sliders
        $seller_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.create_new_slider', 'value' => 'أنشئ سلايدًا جديدا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.link', 'value' => 'الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.image', 'value' => 'الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.edit_slider', 'value' => 'تعديل السلايد :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.banner', 'value' => 'banner'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'slider.main', 'value' => 'main'),
        );

        $seller_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.create_new_slider', 'value' => 'Create New Slider'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.create_new_slider', 'value' => 'Create New Slider'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.link', 'value' => 'Link'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.edit_slider', 'value' => 'Edit: :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.banner', 'value' => 'banner'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'slider.main', 'value' => 'main'),
        );

        $this->translations($seller_ar);
        $this->translations($seller_en);
        #endregion

        #region currency
        $currency_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.create_new_currency', 'value' => 'أنشئ عملة جديدا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.name', 'value' => 'الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.code', 'value' => 'رمز العملة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.edit', 'value' => 'تعديل العملة: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.value', 'value' => 'قيمة العملة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.status', 'value' => 'تفعيل العملة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'currency.symbol', 'value' => 'رمز العلمة'),

        );

        $currency_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.create_new_currency', 'value' => 'Create New Currency'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.name', 'value' => 'Name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.code', 'value' => 'Code'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.edit', 'value' => 'Edit :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.value', 'value' => 'Value'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.status', 'value' => 'Activate the currency'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'currency.symbol', 'value' => 'Symbol'),
        );

        $this->translations($currency_ar);
        $this->translations($currency_en);
        #endregion

        #region manufacturer
        $manufacturer_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.create_new_manufacturer', 'value' => 'أنشئ مصنعا جديدًا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.image', 'value' => 'الصورة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.edit', 'value' => 'تعديل المصنع: :name'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.description', 'value' => 'الوصف'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.software', 'value' => 'سوفتوير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'manufacturer.token', 'value' => 'توكين'),

        );

        $manufacturer_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.create_new_manufacturer', 'value' => 'Create New Manufacturer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.image', 'value' => 'Image'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.edit', 'value' => 'Edit Manufacturer :name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.description', 'value' => 'Description'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.software', 'value' => 'software'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'manufacturer.token', 'value' => 'token'),
        );

        $this->translations($manufacturer_ar);
        $this->translations($manufacturer_en);
        #endregion

        #region statistic
        $statistic_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.total_sales', 'value' => 'مجموع المبيعات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.website_visits', 'value' => 'زيارات الموقع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.visits', 'value' => 'الزيارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.most_visited_pages', 'value' => 'الصفحات الأكثر زيارة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.page_title', 'value' => 'عنوان الصفحة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.page_views', 'value' => 'عدد الزيارات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.url', 'value' => 'الرابط'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.reports', 'value' => 'التقارير'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.top_selling_categories', 'value' => 'التصنيفات الأعلى مبيعا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.top_selling_products', 'value' => 'المنتجات الأعلى مبيعا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.sold_quantity', 'value' => 'القيمة المباعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.users', 'value' => 'المستخدمون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.new_users', 'value' => 'المستخدمون الجدد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.session', 'value' => 'الجلسة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.session_per_user', 'value' => 'متوسط الجلسات للمستخدمين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.page_views', 'value' => 'زيارات الصفحات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.page_views_per_session', 'value' => 'زيارات الصفحات في الجلسة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.avg_session_duration', 'value' => 'متوسط الجلسات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.bounce_rate', 'value' => 'Bounce Rate'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.shipping', 'value' => 'الشحن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.coupons', 'value' => 'الخصومات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.order_count', 'value' => 'عدد الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.traffic_source', 'value' => 'مصادر الدخول'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.user_countries', 'value' => 'بلاد المستخدمين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.device_category', 'value' => 'نوع الجهاز'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.operating_system', 'value' => 'نظام التشغيل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.users_orders', 'value' => 'طلبات المستخدمين'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.average', 'value' => 'متوسط سعر الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.google_analytics', 'value' => 'بيانات غوغل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.net_revenue', 'value' => 'صافي الإيرادات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.total_revenue', 'value' => 'المجموع الصافي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'statistic.avg_daily_revenue', 'value' => 'متوسط المجموع الصافي'),
        );

        $statistic_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.total_sales', 'value' => 'Total sales'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.website_visits', 'value' => 'Website Visits'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.visits', 'value' => 'Visits'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.most_visited_pages', 'value' => 'Most visited pages'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.page_title', 'value' => 'Page title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.page_views', 'value' => 'Page views'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.url', 'value' => 'Url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.reports', 'value' => 'Reports'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.top_selling_categories', 'value' => 'Top selling category'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.top_selling_products', 'value' => 'Top selling products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.sold_quantity', 'value' => 'Top selling products'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.users', 'value' => 'Users'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.new_users', 'value' => 'New users'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.session', 'value' => 'Sessions'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.session_per_user', 'value' => 'Session per user'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.page_views', 'value' => 'Page views'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.page_views_per_session', 'value' => 'Pages per session'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.avg_session_duration', 'value' => 'Average duration'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.bounce_rate', 'value' => 'Bounce rate'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.shipping', 'value' => 'Shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.coupons', 'value' => 'Coupons'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.order_count', 'value' => 'Order Count'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.traffic_source', 'value' => 'Traffic source'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.user_countries', 'value' => 'User countries'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.device_category', 'value' => 'device category'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.operating_system', 'value' => 'Operating systems'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.users_orders', 'value' => 'User orders'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.average', 'value' => 'Avrage sales'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.google_analytics', 'value' => 'Google analytics'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.net_revenue', 'value' => 'Net revenue'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.total_revenue', 'value' => 'total revenue'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.sales_chart', 'value' => 'sales chart'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.stock_chart', 'value' => 'stock chart'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'statistic.avg_daily_revenue', 'value' => 'avg daily revenue'),
        );

        $this->translations($statistic_ar);
        $this->translations($statistic_en);
        #endregion

        #region redirect
        $redirect_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'redirect.old_url', 'value' => 'الرابط القديم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'redirect.new_url', 'value' => 'الرابط الجديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'redirect.clicks_count', 'value' => 'عدد الضغطات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'redirect.create_new_redirect', 'value' => 'إنشاء تحويلة جديدة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'redirect.edit', 'value' => 'تعديل الرابط'),
        );

        $redirect_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'redirect.old_url', 'value' => 'Old url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'redirect.new_url', 'value' => 'New Url'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'redirect.clicks_count', 'value' => 'Clicks count'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'redirect.create_new_redirect', 'value' => 'Create new redirect'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'redirect.edit', 'value' => 'Edit redirect'),
        );

        $this->translations($redirect_ar);
        $this->translations($redirect_en);
        #endregion

        #region seller_earning
        $seller_earning_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.date', 'value' => 'التاريخ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.total_orders', 'value' => 'قيمة الطلبات'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.commission_rate', 'value' => 'نسبة العمولة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.earning', 'value' => 'الأرباح'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'seller.months', 'value' => 'الأشهر'),
        );

        $seller_earning_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.date', 'value' => 'Date'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.total_orders', 'value' => 'Total orders'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.commission_rate', 'value' => 'Commission rate'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.earning', 'value' => 'Earning'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'seller.months', 'value' => 'Months'),
        );

        $this->translations($seller_earning_ar);
        $this->translations($seller_earning_en);
        #endregion

        #region offer
        $offer_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.from', 'value' => 'من'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.to', 'value' => 'إلى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.days', 'value' => 'عدد الأيام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.discount', 'value' => 'الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.discount_type', 'value' => 'نوع الخصم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.type', 'value' => 'النوع'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.free_shipping', 'value' => 'الشحن مجاني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.create_new_offer', 'value' => 'أنشى عرضا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.values_can_not_be_intersected', 'value' => 'القيم لا يمكن أن تكون متقاطعة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.dates', 'value' => 'التاريخ'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.status', 'value' => 'حالة العرض'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'offer.the_values_can_not_be_intersected', 'value' => 'لا يمكن أن تتقاطع القيم'),
        );

        $offer_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.from', 'value' => 'From'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.to', 'value' => 'To'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.days', 'value' => 'Days'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.discount', 'value' => 'Discount'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.discount_type', 'value' => 'Discount Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.type', 'value' => 'Type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.free_shipping', 'value' => 'Free Shipping'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.create_new_offer', 'value' => 'Create Offer'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.values_can_not_be_intersected', 'value' => 'Values Can not be Intersected'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.dates', 'value' => 'Dates'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.status', 'value' => 'Offer status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'offer.the_values_can_not_be_intersected', 'value' => 'the values can not be intersected'),

        );

        $this->translations($offer_ar);
        $this->translations($offer_en);
        #endregion

        #region card
        $card_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'card.last_four', 'value' => 'اخر اربع ارقام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'card.brand', 'value' => 'الماركة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'card.is_default', 'value' => 'الافتراضي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'card.default', 'value' => 'الافتراضي'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'card.not_default', 'value' => 'ليس افتراضي'),
        );

        $card_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'card.last_four', 'value' => 'last four'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'card.brand', 'value' => 'Brand'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'card.is_default', 'value' => 'is default'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'card.default', 'value' => 'default'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'card.not_default', 'value' => 'not default'),


        );

        $this->translations($card_ar);
        $this->translations($card_en);
        #endregion

        #region whatsnew
        $whatsnew_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.create_new_whatnew', 'value' => 'إنشاء جديد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.content', 'value' => 'المحتوى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.country', 'value' => 'البلد'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.user', 'value' => 'المستخدمون'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.message_id', 'value' => 'معرف الرسالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.read', 'value' => 'قُرأت'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.unread', 'value' => 'غير مقروءة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'whatnew.show_users', 'value' => 'عرض المستخدمين'),

        );

        $whatsnew_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.create_new_whatnew', 'value' => 'Create New'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.content', 'value' => 'Content'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.country', 'value' => 'Country'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.user', 'value' => 'Users'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.message_id', 'value' => 'message id'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.read', 'value' => 'read'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.unread', 'value' => 'unread'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'whatnew.show_users', 'value' => 'show users'),

        );

        $this->translations($whatsnew_ar);
        $this->translations($whatsnew_en);
        #endregion

        #region review
        $review_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'review.comment', 'value' => 'التعليق'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'review.rating', 'value' => 'التقييم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'review.status', 'value' => 'الحالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'review.order', 'value' => 'ترتيب'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'review.select_icon', 'value' => 'اختر الأيقونة'),
        );

        $review_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'review.comment', 'value' => 'Comment'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'review.rating', 'value' => 'Rating'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'review.status', 'value' => 'Status'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'review.order', 'value' => 'order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'review.select_icon', 'value' => 'Select Icon'),
        );

        $this->translations($review_ar);
        $this->translations($review_en);
        #endregion

        #region notification
        $notification_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.title', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.visit_website', 'value' => 'visit website'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.content', 'value' => 'المحتوى'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.sender_type', 'value' => 'نوع المرسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.sender', 'value' => 'المرسل'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.notification_type', 'value' => 'نوع الإشعار'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.read', 'value' => 'مقروءة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.read_all', 'value' => 'حدد الجميع كمقروءة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.unread', 'value' => 'غير مقروءة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.system', 'value' => 'النظام'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.thanks_for_review', 'value' => 'شكرًا لتقيمك'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.thanks_for_contactus', 'value' => 'شكرًا لتواصل معنا'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.replay_review', 'value' => 'تم الرد على تقييمك'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.visit_product', 'value' => 'زيارة صفحة المنتج'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.out_of_stock', 'value' => 'المنتج غير متواجد في المخزن'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.product_out_of_stock', 'value' => 'المنتج غير متواجد في المخزن، سيتم توفيره بأقرب وقت.'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'notifications.message', 'value' => 'يسعدنا اشتركك  في TLK بأن أتقدم لك شخصيا بالشكر على اشتراكك في خدمات موقعنا.'),

        );

        $notification_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.visit_website', 'value' => 'visit website'),

            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.title', 'value' => 'Title'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.content', 'value' => 'content'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.sender_type', 'value' => 'sender type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.sender', 'value' => 'sender'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.notification_type', 'value' => 'notification type'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.read', 'value' => 'read'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.read_all', 'value' => 'read all'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.unread', 'value' => 'unread'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.system', 'value' => 'System'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.thanks_for_review', 'value' => 'Thank you for your review'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.thanks_for_contactus', 'value' => 'Thank you for contact us'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.visit_product', 'value' => 'Visit product'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.out_of_stock', 'value' => 'Out of stock'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.product_out_of_stock', 'value' => 'The product :sku is out of stock. We will notify you when the product becomes available again.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.welcome', 'value' => 'Welcome To TLKEYs'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.message', 'value' => 'We are pleased to have you subscribe to TLK to personally thank you for your subscription to our website services.'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.replay_review', 'value' => 'Your review has been answered'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.low_quantity', 'value' => 'low quantity'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.review_added', 'value' => 'review added'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.new_order', 'value' => 'new order'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.order_delivered', 'value' => 'order delivered'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.order_canceled', 'value' => 'order canceled'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.order_refunded', 'value' => 'order refunded'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.admin', 'value' => 'admin'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.seller', 'value' => 'seller'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.user', 'value' => 'user'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.order_is_paid', 'value' => 'Order is paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.seller_order_is_paid', 'value' => 'The Order :number is paid and the commission is :commission'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.the_order_is_paid', 'value' => 'The order :number is paid'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.stock_increase_order', 'value' => 'stock increase because the order :number is refunded (sku : :sku)'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.stock_increase', 'value' => 'stock increase'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.contact_us', 'value' => 'Contact Us'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.contact_us_body', 'value' => 'message is : :body'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.thanks_for_contact_us', 'value' => 'thanks for contact us'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'notifications.we_will_contact_you_as_soon_as_possible', 'value' => 'We will contact you as soon as possible'),
        );

        $this->translations($notification_ar);
        $this->translations($notification_en);
        #endregion

        #region contact us
        $contact_us_ar = array(
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'contact_us.name', 'value' => 'الاسم'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'contact_us.message', 'value' => 'الرسالة'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'contact_us.email', 'value' => 'البريد الالكتروني'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'contact_us.subject', 'value' => 'العنوان'),
            array('status' => '0', 'locale' => 'ar', 'group' => 'backend', 'key' => 'contact_us.product', 'value' => 'المنتج'),

        );
        $contact_us_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'contact_us.name', 'value' => 'name'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'contact_us.message', 'value' => 'message'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'contact_us.email', 'value' => 'email'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'contact_us.subject', 'value' => 'subject'),
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'contact_us.product', 'value' => 'product'),

        );
        $this->translations($contact_us_en);
        $this->translations($contact_us_ar);
        #endregion
        #region contact us

        $contact_us_en = array(
            array('status' => '0', 'locale' => 'en', 'group' => 'backend', 'key' => 'validation.year_is_small', 'value' => 'Please check the years, you have a `year to`  younger `year from`'),


        );
        $this->translations($contact_us_en);

        #endregion

    }
}
