<?php

namespace App\Rules\Seller\Order;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckUserHasTheSameSeller implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $count = User::query()->where('uuid', $value)->where('seller_id', auth('seller')->id())->count();
        return $count != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('seller.orders.not_found_user');
    }
}
