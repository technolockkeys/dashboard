<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = Seller::withTrashed()->find(1);
        if (empty($seller)) {
            $seller = new Seller();

        }
        $seller->name = "ESG Seller";
        $seller->email = "seller@esg.com";
        $seller->password = \Hash::make("password");
        $seller->status = 1;
        $seller->deleted_at = null;
        $seller->save();
    }
}
