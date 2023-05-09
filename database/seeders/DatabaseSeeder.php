<?php

namespace Database\Seeders;

use App\Models\Currency;
use Barryvdh\TranslationManager\Controller;
use Barryvdh\TranslationManager\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([

            ApiLanguage::class,
            SellerLanguage::class,
            BackendLanguage::class,
            SellerPermissionsSeeder::class,
            EmailLanguageSeeder::class,
            FrontendLanguageSeeder::class,
            PermissionSeed::class,

//            AdminUser::class,
//            InvoiceLanguage::class,
//            AttrbuteSeed::class,
//            TicketSeeder::class,
//            SellerSeed::class,
//            CurrencySeeder::class,
//            ContactUsSeed::class,
//            CurrencySeeder::class,
//            LanguageSeed::class,
//            SettingSeed::class,
//            CountrySeeder::class,
//            ColorSeeder::class,
        ]);
    }
}
