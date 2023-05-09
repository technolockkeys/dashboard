<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!empty(get_setting('api_currency_key'))){

            set_setting('api_currency_key','D60jOFVK5Bg8RhdGXviykAAvVnq3o2X9');
        }
        $currencies = [
            [
                'name' => 'US Dolar',
                'code' => 'USD',
                'symbol' => '$',
                'value' => 1,
                'status' => 1,
                'is_default' => 1
            ], [
                'name' => 'Turkish Lira',
                'code' => 'TRY',
                'symbol' => '₺',
                'value' => 18,
                'status' => 1,
                'is_default' => 0
            ], [
                'name' => 'EURO ',
                'code' => 'EUR',
                'symbol' => '€',
                'value' => 18,
                'status' => 1,
                'is_default' => 0
            ],
        ];

        foreach ($currencies as $currency){
            Currency::updateOrCreate([
                'code' => $currency['code'],
            ], $currency);
        }

    }
}
