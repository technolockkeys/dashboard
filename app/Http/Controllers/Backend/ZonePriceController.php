<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ZoneRequest;
use App\Models\Brand;
use App\Models\ZonePrice;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class ZonePriceController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    public function index()
    {
        if (!permission_can('show zones', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.zones.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['zone_id'] = 'zone_id';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, null,$filters);
        $switch_script = null;
        $create_button = '';
        if (permission_can('create category', 'admin')) {
            $create_button = $this->create_button(route('backend.zones.create'), trans('backend.zone.create_new_brand'));
        }
        return view('backend.location.zone.index', compact('datatable_script', 'create_button'));

    }

    public function datatable(Request $request)
    {
        if (!permission_can('show zones', 'admin')) {
            return abort(403);
        }
        $model = ZonePrice::query();

        return datatables()->make($model)
            ->addColumn('actions', function ($q) {
                $actions = '';

                if (permission_can('edit zone', 'admin')) {
                    $actions .= $this->edit_button(route('backend.zones.edit', ['zone' => $q->id]));
                }
                return $actions;
            })
            ->rawColumns(['actions'])->toJson();
    }

    public function edit($id)
    {
        if (!permission_can('edit zone', 'admin')) {
            return abort(403);
        }
        if (!$id > 0 && !$id <= 10) {
            abort(404);
        }

        $zone = [];
        $zone = ZonePrice::where('zone_id', $id)->get();

        return view('backend.location.zone.edit', compact('zone', 'id'));
    }

    public function update(ZoneRequest $request, $id)
    {
        ZonePrice::where('zone_id', $id)->delete();

        if ($request->has('price') && !empty($request->price)) {
            foreach ($request->price as $key => $item) {
                if ($request->weight[$key] != null && $request->price[$key] != null)
                    $zone = ZonePrice::updateOrCreate([
                        'zone_id' => $id,
                        'weight' => $request->weight[$key],
                        'price' => $request->price[$key],
                    ]);
                $zone->save();
            }
        }
        return redirect()->route('backend.zones.index')->with('success', __('backend.global.success_message.updated_successfully'));

    }
}
