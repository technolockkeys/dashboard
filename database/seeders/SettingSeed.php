<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type' => 'system_name',
                'value' => 'ESG | TLK'
            ],
            [
                'type'=>'Logo',
                'value'=>''
            ],
            [
                'type'=>'system_logo_icon',
                'value'=>''
            ],
            [
                'type'=>'timezone',
                'value'=>'Asia/Damascus'
            ],
            [
                'type'=>'smtp_type',
                'value'=>'mail_host'
            ],
            [
                'type'=>'mail_port',
                'value'=>'2525'
            ],
            [
                'type'=>'mail_username',
                'value'=>'b123123123c'
            ],
            [
                'type'=>'mail_password',
                'value'=>'abcdef123e12'
            ],
            [
                'type'=>'mail_encryption',
                'value'=>'tls'
            ],
            [
                'type'=>'mail_from_address',
                'value'=>'admin@esg.com'
            ],
            [
                'type'=>'mail_from_name',
                'value'=>'Admin'
            ],
            [
                'type'=>'mail_host',
                'value'=>'smtp.mailtrap.io'
            ],
            [
                'type'=>'admin_background',
                'value'=>''
            ],
            [
                'type'=>'meta_title',
                'value'=>'Esg | tlk'
            ],
            [
                'type'=>'meta_description',
                'value'=>'A new website from ESG'
            ],
            [
                'type'=>'keywords',
                'value'=>'[{"value":"ecommerce"},{"value":"store"},{"value":"selling"}]'
            ],
            [
                'type'=>'meta_image',
                'value'=>''
            ],

            [
                'type'=>'social_email',
                'value'=>'info@esg.com'
            ],
            [
                'type'=>'social_facebook',
                'value'=>'www.facebook.com'
            ],
            [
                'type'=>'social_twitter',
                'value'=>'www.twitter.com'
            ],
            [
                'type'=>'social_telegram',
                'value'=>'www.telegram.com'
            ],
            [
                'type'=>'social_whatsapp',
                'value'=>'www.whatsapp.com'
            ],
            [
                'type'=>'social_phone',
                'value'=>'537123456'
            ],
            [
                'type'=>'social_tiktok',
                'value'=>'tiktok.com'
            ],

            [
                'type'=>'contact_email',
                'value'=>'info@esg.com'
            ],
            [
                'type'=>'contact_messenger',
                'value'=>'www.messenger.com'
            ],
            [
                'type'=>'contact_twitter',
                'value'=>'www.twitter.com'
            ],
            [
                'type'=>'contact_telegram',
                'value'=>'www.telegram.com'
            ],
            [
                'type'=>'contact_whatsapp',
                'value'=>'www.whatsapp.com'
            ],
            [
                'type'=>'contact_phone',
                'value'=>'537123456'
            ],
            [
                'type'=>'contact_tiktok',
                'value'=>'tiktok.com'
            ],
            [
                'type'=>'paypal_client_id',
                'value'=>''
            ],
            [
                'type'=>'paypal_client_id_test',
                'value'=>''
            ],
            [
                'type'=>'paypal_client_secret',
                'value'=>''
            ],
            [
                'type'=>'paypal_client_secret_test',
                'value'=>'tiktok.com'
            ],
            [
                'type'=>'paypal_sandbox_mode',
                'value'=>''
            ],
            [
                'type'=>'paypal_status',
                'value'=>''
            ],
            [
                'type'=>'strip_key',
                'value'=>''
            ],
            [
                'type'=>'strip_key_test',
                'value'=>''
            ],
            [
                'type'=>'strip_secret',
                'value'=>''
            ],
            [
                'type'=>'strip_secret_test',
                'value'=>''
            ],
            [
                'type'=>'strip_sandbox_mode',
                'value'=>''
            ],
            [
                'type'=>'strip_status',
                'value'=>''
            ],
            [
                'type'=>'create_review_notification',
                'value'=> 1
            ],
            [
                'type'=>'product_notifications',
                'value'=> 1
            ],
            [
                'type'=>'create_order_notification',
                'value'=> 1
            ],
            [
                'type'=>'order_notifications',
                'value'=> 1
            ],
            [
                'type'=>'competitors_prices_notifications',
                'value'=> 1
            ],
            [
                'type'=>'ticket_notifications',
                'value'=> 1
            ],
        ];

        foreach ($data as $item){
            $check = Setting::query()->where('type', $item['type'])->count();
            if ($check == 0 ){
                $setting = new Setting();
                $setting->type = $item['type'];
                $setting->value = $item['value'];
                $setting->save();
            }
        }
    }
}
