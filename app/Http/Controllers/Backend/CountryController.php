<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index page

    public function index()
    {
        if (!permission_can('show countries', 'admin')) {
            return abort(403);
        }

        $filters[] = 'status';

        $datatable_route = route('backend.countries.datatable');
        $delete_all_route = route('backend.countries.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['iso3'] = 'iso3';
        $datatable_columns['capital'] = 'capital';
        $datatable_columns['zone_id'] = 'zone_id';
        $datatable_columns['phonecode'] = 'phonecode';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null,$filters);
        $switch_script = null;
        $switch_route = route('backend.countries.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);

        return view('backend.location.country.index', compact('datatable_script', 'switch_script'));
    }

    public function datatable(Request $request)
    {
        $model = Country::query();
        if ($request->has('status') && $request->status !=-1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)

            ->editColumn('name', function ($q) {
                return $q->name;
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status country', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->addColumn('actions', function ($q){
                $html = '<button id="edit-name-'.$q->id.'" data-href="'.route("backend.countries.edit_name", [ $q->id]).'"
                                    class="btn  btn-info btn-sm  edit-name ">
                                '.trans('backend.global.edit').'
                            </button>';
                  $html .= ' <button id="edit-zone-'.$q->id.'" data-href="'.route("backend.countries.zone", [ $q->id]).'"
                                    class="btn  btn-info btn-sm  edit-zone ">
                                '.trans('backend.country.edit_zone').'
                            </button>';


                  return $html;
            })
            ->rawColumns([ 'status','actions'])
            ->toJson();
    }
    #endregion

    #regioin edit name
    public function edit_name($country)
    {
        $country = Country::findOrFail($country);
        $view = view('backend.location.name.edit', compact('country'))->render();
        return response()->data(['view' => $view]);
    }

    public function update_name(Request $request, $country){
        $validated = $request->validate([
            'name_en' => 'required',
        ]);

        $country = Country::findOrFail($country);

        foreach (get_languages() as $language) {
            $name[ $language->code] = $request->get('name_'.$language->code);
        }
        $country->name = $name;
        $country->save();

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }
    #end region

    public function zone($country)
    {
        $country = Country::findOrFail($country);
        $view = view('backend.location.country.timezone', compact('country'))->render();
        return response()->data(['view' => $view]);
    }

    public function change_timezone(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $country->zone_id = $request->zone_id;
        $country->save();

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status country', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $country = Country::find($id);
        if ($country->status == 1) {
            $country->status = 0;
        } else {
            $country->status = 1;
        }
        if ($country->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete country', 'admin')){
            return  abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            Category::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion
}
