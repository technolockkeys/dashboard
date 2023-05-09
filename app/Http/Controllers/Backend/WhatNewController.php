<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SendNewsRequest;
use App\Mail\WelcomeMail;
use App\Models\Color;
use App\Models\Country;
use App\Models\User;
use App\Models\WhatNew;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Traits\RandomCodeGeneratorTrait;
use Illuminate\Http\Request;

class WhatNewController extends Controller
{

    use DatatableTrait;
    use ButtonTrait;
    use RandomCodeGeneratorTrait;

    #region index
    public function index()
    {
        if (!permission_can('show whatsnews', 'admin')) {
            return abort(403);
        }

        $datatable_route = route('backend.whatsnew.datatable');
//        $delete_all_route = route('backend.whatsnews.delete-selected');

        #region data table columns
        $datatable_columns = [];
        $datatable_columns['message_id'] = 'message_id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $switch_script = null;

        $create_button = $this->create_button(route('backend.whatsnew.create'), trans('backend.whatnew.create_new_whatnew'));
        return view('backend.whatsnew.index', compact('datatable_script', 'switch_script', 'create_button'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show whatsnews', 'admin')) {
            return abort(403);
        }
        $model = WhatNew::query()->groupBy('message_id');

        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';

                $actions .= $this->btn(
                    route('backend.whatsnew.show-users',
                        ['message_id' => $q->message_id]),
                    trans('backend.whatnew.show_users'),
                    'fa fa-eys', 'btn-info',
                    ['message_id' => $q->message_id]);
                return $actions;
            })
            ->rawColumns(['actions', 'status', 'placeholder'])
            ->toJson();
    }
    #endregion

    #region show users
    public function show_users($message_id)
    {
        $datatable_route = route('backend.whatsnew.user_datatable', ['message_id' => $message_id]);

        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['message_id'] = 'message_id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['user_name'] = 'user_name';
        $datatable_columns['read'] = 'read';
        $datatable_columns['created_at'] = 'created_at';

        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        $whatnew = WhatNew::query()->where('message_id', $message_id)->first();
        return view('backend.whatsnew.show_users', compact('datatable_script','whatnew'));
    }

    public function user_datatable(Request $request)
    {
        $model = WhatNew::query()
            ->select('users.name as user_name', 'what_news.*')
            ->join('users', 'what_news.user_id', 'users.id')
            ->where('message_id', $request->message_id);

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('user_name', function ($q) {
                return '<a href="' . route('backend.users.show', ['user' => $q->user_id]) . '" class="text-gray-800 fw-bolder text-hover-primary small fs-6">
                 <span class="badge badge-light-primary badge-lg">' . $q->user_name . '</span></a>';
            })
            ->editColumn('read', function ($q) {
                $class = $q->read ? 'success' : 'danger';
                $text = $q->read ? trans('backend.notifications.read') : trans('backend.notifications.unread');
                return '<span class="badge badge-light-' . $class . ' badge-lg">' . $text . '</span>';
            })
            ->rawColumns(['actions', 'user_name', 'read', 'placeholder'])
            ->toJson();

    }

    #endreguion

    #region create

    public function create()
    {
        $countries = Country::where('status', 1)->get();
        $users = User::where('status', 1)->get();
        return view('backend.whatnew.create', compact('countries', 'users'));
    }

    public function store(SendNewsRequest $request)
    {


        if ((count($request->country) == 1 && empty($request->country[0])) || $request->country == []) {
            $users = User::where('status', 1)->get();
        } else if ((count($request->users) == 1 && empty($request->users[0])) || $request->users == []) {
            $countries = Country::whereIn('id', $request->country)->pluck('id');
            $users = User::where('status', 1)->with('addresses')
                ->whereHas('addresses', function ($q) use ($countries) {
                    $q->whereIn('country_id', $countries);
                })->get();
        } else {
            $users = User::where('status', 1)
                ->whereIn('id', $request->users)->get();
        }


        $uuid = time();
        foreach ($users as $user) {
            $news = WhatNew::make([
                'title' => $request->title,
                'content' => $request->get('content'),
                'message_id' => $uuid
            ]);
            $news->user()->associate($user);
            $news->save();
            $details = [
                'title' => $request->title,
                'content' => $request->get('content'),
                'user' => $user,
                'button' => 'visit website'
            ];
            \Mail::to($user)->queue(new WelcomeMail($request->title, $details));
        }

        return redirect()->back()->with('success', trans('backend.global.success_message.created_successfully'));
    }

    function generate_code()
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);
    }

    #endregion

    public function get_users(Request $request)
    {
        if (empty($request->country)) {
            $users = User::where('status', 1)->get();
            return response()->data(['users' => $users]);
        }
        $request->validate([
            'country' => 'required',
            'country.*' => 'required|exists:countries,id'
        ]);

        $countries = Country::whereIn('id', $request->country)->pluck('id');

        $users = User::where('status', 1)
            ->with('addresses')
            ->whereHas('addresses', function ($q) use ($countries) {
                $q->whereIn('country_id', $countries);
            })->get();

        return response()->data(['users' => $users]);
    }
}
