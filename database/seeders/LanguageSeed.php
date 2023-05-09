<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeed extends Seeder
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
                'language' => 'English',
                'code' => 'en',
                'display_type' => 'LTR',
                'is_default' => 1,
                'flag' => '',
                'status' => 1,

            ],
            [
                'language' => 'عربي',
                'code' => 'ar',
                'display_type' => 'RTL',
                'is_default' => 0,
                'flag' => '',
                'status' => 1,
            ]
        ];
        foreach ($data as $item) {
            $lang = Language::query()->where('code', $item['code'])->count();
            if ($lang == 0 ){
                $lang = new Language();
                $lang->language = $item['language'];
                $lang->code = $item['code'];
                $lang->display_type = $item['display_type'];
                $lang->is_default = $item['is_default'];
                $lang->flag = $item['flag'];
                $lang->status = $item['status'];
                $lang->save();
            }
        }
    }
}
