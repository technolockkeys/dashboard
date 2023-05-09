<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\BrandModelYear;
use Illuminate\Console\Command;

class GetBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:brand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://parseapi.back4app.com/classes/Car_Model_List?count=1');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'X-Parse-Application-Id: hlhoNKjOvEhqzcVAJ1lxjicJLZNVv36GdbboZj3Z', // This is the fake app's application id
            'X-Parse-Master-Key: SNMJJF0CZZhTPhLDIqGhTlUNV9r60M2Z5spyWfXW' // This is the fake app's readonly master key
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data2 = json_decode(curl_exec($curl)); // Here you have the data that you need
//dd($data2);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://parseapi.back4app.com/classes/Car_Model_List?limit=' . $data2->count);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'X-Parse-Application-Id: hlhoNKjOvEhqzcVAJ1lxjicJLZNVv36GdbboZj3Z', // This is the fake app's application id
            'X-Parse-Master-Key: SNMJJF0CZZhTPhLDIqGhTlUNV9r60M2Z5spyWfXW' // This is the fake app's readonly master key
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = json_decode(curl_exec($curl)); // Here you have the data that you need
        foreach ($data->results as $item) {
//            $x = Brand::where('object_id', $item->objectId)->first();
//            if (empty($x)) {
//
//                $new_brand = new Brand();
//                $new_brand->object_id = $item->objectId;
//                $new_brand->year = $item->Year;
//                $new_brand->make = $item->Make;
//                $new_brand->model = $item->Model;
//                $new_brand->category = $item->Category;
//                $new_brand->status = 1;
//
//                $new_brand->save();
//            }

            if (!BrandModelYear::where('object_id', $item->objectId)->first()) {

                $brand = Brand::updateOrCreate([
                    'slug' =>   check_slug(Brand::query(), convertToKebabCase( $item->Make))
                ],
                    [
                        'make' => $item->Make,
                        'slug' =>check_slug(Brand::query(), convertToKebabCase( $item->Make))

                    ]);
                $model = BrandModel::where('brand_id', $brand->id)
                    ->updateOrCreate([
                        'slug' => check_slug(BrandModel::query(), convertToKebabCase( $item->Make))

                    ], [
                        'model' => $item->Model." ",
                        'slug' => check_slug(BrandModel::query(), convertToKebabCase( $item->Make)),
                        'brand_id' => $brand->id,
                    ]);
                $year = BrandModelYear::where('brand_model_id', $model->id)
                    ->firstOrCreate(
                        [
                            'brand_id' => $brand->id,
                            'object_id' => $item->objectId,
                            'brand_model_id' => $model->id,
                            'year' => $item->Year,
                        ]);
            }

        }
        return 0;
    }
}
