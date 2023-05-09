<?php

namespace App\Traits;


trait GeneratedCodeTrait
{
    function user_code($id)
    {
        return "TLKC" . date('y') . "00" . ($id + 500);
    }
    static function generateUUID($length) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }
    function order_code()
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
    }
}
