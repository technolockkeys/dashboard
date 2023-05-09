<?php

namespace App\Traits;

use App;
use Config;

trait SetMailConfigurations
{
    public function setMailConfigurations(array $options = null)
    {

        $config = [

            'transport' => 'smtp',
            'MAIL_MAILER' => 'smtp',
            'host' => get_setting('mail_host'),
            'port' => get_setting('mail_port'),
            'encryption' => get_setting('mail_encryption'),
            'username' => get_setting('mail_username'),
            'password' => get_setting('mail_password'),
            'timeout' => null,
            'auth_mode' => null,

            'from' => [
                'address' =>!empty($options['from_mail']) ? $options['from_mail'] : get_setting('mail_from_address'),
                'name' => get_setting('mail_from_name'),
            ],
        ];
        Config::set('mail.mailers.smtp', $config);
        Config::set('mail.from', [
            'address' => !empty($options['from_mail']) ? $options['from_mail'] : get_setting('mail_from_address'),
            'name' => get_setting('mail_from_name'),
        ]);

        Config::set('mail.mailers.smtp.encryption', get_setting('mail_encryption'));

        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');

    }


}
