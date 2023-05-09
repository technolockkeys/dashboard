<?php

namespace App\Traits;


use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductsPackages;
use function App\Http\Controllers\Backend\RandomString;

trait RandomCodeGeneratorTrait
{

    static function  RandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            if($i % 3 ==0 ){
                $randstring .= '-';
            }
            $randstring .= $characters[rand(0, (strlen($characters) -1) )];
        }
        return $randstring;
    }
}
