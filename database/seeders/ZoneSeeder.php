<?php

namespace Database\Seeders;

use App\Models\ZonePrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i <=10; $i++){
            ZonePrice::firstOrCreate([
                'id' => $i,
                'zone_id' => $i,
                'prices' => json_encode([['weight'=>'0.5' , 'price'=>'0']]),
            ]);
        }

    }
}
