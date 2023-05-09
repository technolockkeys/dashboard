<?php

namespace App\Http\Controllers\Api\Frontend\User\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Card\CreateRequest;
use App\Http\Requests\Api\User\Card\DeleteRequest;
use App\Models\Card;
use App\Traits\StripeTrait;
use Google\Service\Docs\Request;

class CardController extends Controller
{
    use StripeTrait;

    function create(CreateRequest $request)
    {
        $user_id = auth('api')->id();
        $card_number = $request->card_number;
        $card_name = $request->card_name;
        $exp_month = $request->expiry_month;
        $exp_year = $request->expiry_year;
        $card_cvc = $request->card_cvc;
        $response = $this->create_card($user_id, $card_name, $card_number, $exp_month, $exp_year, $card_cvc);
        if ($response['success'] == true) {
            return response()->data($response['message']);
        } else {
            return response()->error($response['message']);
        }
    }

    function delete(DeleteRequest $request)
    {

        $card_id = $request->id;
        $user_id = auth('api')->id();
        $response = $this->delete_card($user_id, $card_id);
        if ($response['success'] == true) {
            return response()->data(trans('api.card.deleted_successfully'));
        } else {
            return response()->data($response['message']);

        }
    }

    function get(Request $request)
    {
        $length = 12;
        $page = 1;

        if ($request->length >= 1) {
            $length = $request->length;
        }
        if ($request->page >= 1) {
            $page = $request->page;
        }
        $user_id = auth('api')->id();
        $cards = Card::where('user_id', $user_id);
        $total = $cards->count();
        $cards = $cards->skip(($page - 1) * $length)->limit($length)->get();

        $response = [];
        foreach ($cards as $card) {
            $response[] = [
                'id' => $card->id,
                'card_id' => $card->card_id,
                'is_default' => $card->is_default,
                'brand' => $card->brand,
                'last4' => $card->last_four
            ];
        }

        return response()->api_data(['cards' => $response, 'total'=>$total, 'page'=> $page,  'total_pages' => ceil($total/$length),'length' => sizeof($response)]);
    }
}
