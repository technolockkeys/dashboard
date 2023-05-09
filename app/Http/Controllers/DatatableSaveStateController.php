<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DatatableSaveState;
use App\Models\Seller;
use Illuminate\Http\Request;

class DatatableSaveStateController extends Controller
{
    function get(Request $request)
    {
        $request->validate([
            'page' => 'string|url|required'
        ]);
        $user = auth('seller')->check() ? Seller::class : (auth('admin')->check() ? Admin::class : "-");
        $user_id = auth('seller')->check() ? auth('seller')->id() : (auth('admin')->check() ? auth('admin')->id() : "-");
        $page = $request->page;
        $data = DatatableSaveState::query()->where('module', $user)->where('module_id', $user_id)->where('page', $page)->first();
        if (empty($data)) {
            $data = json_encode(['time' => time(), 'start' => 0, 'length' => 10]);
        } else {
            $data = $data->data;
            $data = json_decode($data ,true);
//            $data['draw'] =intval($data['draw']);
            $data['length'] =intval($data['length']);
            $data['start'] =intval($data['start']);
        }
        return response()->data($data);

    }

    function set(Request $request)
    {
        $request->validate([
            'page' => 'string|url|required',
            'data' => 'required'
        ]);
        $user = auth('seller')->check() ? Seller::class : (auth('admin')->check() ? Admin::class : "-");
        $user_id = auth('seller')->check() ? auth('seller')->id() : (auth('admin')->check() ? auth('admin')->id() : "-");
        $page = $request->page;
        $data = $request->data;
        if ($data['length'] == 0) {
            $data['length'] = 10;
        }


        $item = DatatableSaveState::query()->where('module', $user)->where('module_id', $user_id)->where('page', $page)->first();
        if (!empty($item)) {
            $item->data = json_encode($data);
        } else {
            $item = new DatatableSaveState();
            $item->module = $user;
            $item->module_id = $user_id;
            $item->page = $request->page;
            $item->data = json_encode($data);

        }
        $item->save();
        return response()->data();
    }
}
