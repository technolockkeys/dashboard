<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\SubAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttrbuteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(get_setting('token_sender_email'))) {
            set_setting('token_sender_email', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjI0MjUzNjUwYzc0MDBjMjNjYTQxMGZhMDUzYzMwMTVlMmViZGUzMTU3NDJmNWRiYjE3OWI4YWQ1YmFkOTk4MWJmZDdjYjQxYjU2YjNkOGYiLCJpYXQiOjE2NjIyMDA4NzEuMzI4MTMyLCJuYmYiOjE2NjIyMDA4NzEuMzI4MTQyLCJleHAiOjQ4MTU4MDQ0NzEuMzI2LCJzdWIiOiI3NTA1NzUiLCJzY29wZXMiOltdfQ.ZIjE_nxq5-Mtu7ckbDIala-WodwtatkuWcNocfUT3gSmg9WghlRuFZgjZ_Am-a6o-XZuW28C38j7XUIYfvO5cA');
        }

        #region id 1

        $attribute = Attribute::withTrashed()->find(1);
        if (empty($attribute)) {
            $attribute = new Attribute();
            $attribute->id = 1;
        }
        $attribute->name = array('ar' => 'قياسات', 'en' => 'Sizes');
        $attribute->status = 1;
        $attribute->save();
        #region add  sub attribute..
        //id 1
        $sub_attribute = SubAttribute::withTrashed()->find(1);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 1;
        }
        $sub_attribute->value = (array('ar' => 'S', 'en' => 'S'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 2

        $sub_attribute = SubAttribute::withTrashed()->find(2);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 2;
        }
        $sub_attribute->value = (array('ar' => 'M', 'en' => 'M'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 3
        $sub_attribute = SubAttribute::withTrashed()->find(3);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 3;
        }
        $sub_attribute->value = (array('ar' => 'L', 'en' => 'L'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 4
        $sub_attribute = SubAttribute::withTrashed()->find(4);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 4;
        }
        $sub_attribute->value = (array('ar' => 'XL', 'en' => 'XL'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();

        #endregion
        #endregion
        #region id 2

        $attribute = Attribute::withTrashed()->find(2);
        if (empty($attribute)) {
            $attribute = new Attribute();
            $attribute->id = 2;
        }
        $attribute->name = (array('ar' => 'طول', 'en' => 'length'));
        $attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $attribute->save();
        #region add  sub attribute..
        //id 5
        $sub_attribute = SubAttribute::withTrashed()->find(5);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 5;
        }
        $sub_attribute->value = (array('ar' => '100', 'en' => '100'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 6
        $sub_attribute = SubAttribute::withTrashed()->find(6);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 6;
        }
        $sub_attribute->value = (array('ar' => '110', 'en' => '110'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 7
        $sub_attribute = SubAttribute::withTrashed()->find(7);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 7;
        }
        $sub_attribute->value = (array('ar' => '120', 'en' => '120'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();
        //id 8
        $sub_attribute = SubAttribute::withTrashed()->find(8);
        if (empty($sub_attribute)) {
            $sub_attribute = new SubAttribute();
            $sub_attribute->id = 8;
        }
        $sub_attribute->value = (array('ar' => '130', 'en' => '130'));
        $sub_attribute->status = 1;
        $sub_attribute->attribute_id = $attribute->id;
        $sub_attribute->save();


        #endregion

        #endregion

    }
}
