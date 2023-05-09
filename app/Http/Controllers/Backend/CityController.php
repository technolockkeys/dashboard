<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\City\UpdateRequest;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Http\Requests\Backend\City\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;



    #region index
    public function index()
    {
        if (!permission_can('show cities', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $filters[] = 'country';

        $datatable_route = route('backend.cities.datatable');
        $delete_all_selected = route('backend.cities.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = 'id';
        $datatable_columns['id'] = 'id';
        $datatable_columns['country_id'] = 'country_id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        $countries = Country::where('status', 1)->get();

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns,$delete_all_selected, $filters);
        $switch_script = null;
        $switch_route = route('backend.cities.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = $this->create_button(route('backend.cities.create'), trans('backend.city.create_new_city'));
        return view('backend.location.city.index', compact('datatable_script', 'switch_script', 'create_button','countries' ));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show cities', 'admin')) {
            return abort(403);
        }
        $model = City::query();
        if ($request->has('status') && $request->status !=-1) {
            $model = $model->where('status', $request->status);
        }
        if ($request->has('country') && $request->country != null) {
//            dd('asdasd');
            $model = $model->where('country_id', $request->country);
        }
        return datatables()->make($model)

            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit city', 'admin')) {
                    $actions .= $this->edit_button(route('backend.cities.edit', ['city' => $q->id]));
                }
                if (permission_can('delete city', 'admin')) {
                    $actions .= $this->delete_button(route('backend.cities.destroy', ['city' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('country_id',function ($q){
                $country = $q->country;
                return $country->name;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status city', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->rawColumns(['actions', 'status', 'country', 'roles'])
            ->toJson();
    }
    #endregion
    public function create()
    {
        if (!permission_can('create city', 'admin')) {
            return abort(403);
        }
        $countries = Country::where('status', 1)->get();
        return view('backend.location.city.create', compact('countries'));
    }

    public function store(CreateRequest $request)
    {

        $city = new City();

        $city->name = $request->name;
        $city->status = $request->has('status') ? 1 : 0;;
        $country = Country::find($request->country);
        $city->country()->associate($country);

        $city->save();
        return redirect()->route('backend.cities.create')->with('success' ,trans('backend.global.success_message.created_successfully'));
    }

    public function edit($id)
    {
        if (!permission_can('edit city', 'admin')) {
            return abort(403);
        }

        $city = City::find($id);
        $countries = Country::where('status', 1)->get();
        return view('backend.location.city.edit', compact('countries','city'));
    }

    public function update(UpdateRequest $request, $id)
    {

        $city = City::find($id);

        $city->name = $request->name;

        $city->status = $request->has('status') ? 1 : 0;

        $country = Country::find($request->country);
        $city->country()->associate($country);

        $city->save();
        return redirect()->route('backend.cities.index')->with('success' ,trans('backend.global.success_message.updated_successfully'));
    }

    public function destroy($id)
    {
        if (!permission_can('delete city', 'admin')) {
            return abort(403);
        }

        if (City::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete city' , 'admin')){
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            City::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status city', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $city = City::find($id);
        if ($city->status == 1) {
            $city->status = 0;
        } else {
            $city->status = 1;
        }
        if ($city->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion
}
