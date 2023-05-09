<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UrlRedirectRequest;
use App\Models\City;
use App\Models\UrlRedirect;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UrlRedirectController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;


    #region index
    public function index()
    {
        if (!permission_can('show redirects', 'admin')) {
            return abort(403);
        }

        $filters[] = 'start_date';
        $filters[] = 'end_date';

        $datatable_route = route('backend.redirects.datatable');
        $delete_all_selected = route('backend.redirects.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = 'id';
        $datatable_columns['id'] = 'id';
        $datatable_columns['old_url'] = 'old_url';
        $datatable_columns['new_url'] = 'new_url';
        $datatable_columns['clicks_count'] = 'clicks_count';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['actions'] = 'actions';

        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_selected, $filters);
        $create_button = null;
        if (permission_can('create redirect', 'admin')) {
            $create_button = $this->create_button(route('backend.redirects.create'), trans('backend.redirect.create_new_redirect'));
        }
        return view('backend.redirects.index', compact('datatable_script', 'create_button'));
    }

    public function datatable(Request $request)
    {
        if (!permission_can('show redirects', 'admin')) {
            return abort(403);
        }
        $model = UrlRedirect::query();

        if ($request->start_date != null) {
            $model = $model->whereBetween('updated_at', [Carbon::parse($request->start_date), Carbon::parse($request->end_date)]);
        }
        $has_delete_permission = permission_can('delete redirect', 'admin');
        $has_edit_permission = permission_can('edit redirect', 'admin');
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return '';
            })
            ->addColumn('actions', function ($q) use ($has_delete_permission,$has_edit_permission) {
                $actions = '';
                if ($has_edit_permission) {
                    $actions .= ' <button id="edit-redirect-' . $q->id . '" data-href="' . route('backend.redirects.edit', ['redirect' => $q->id]) . '"
                                    class="btn btn-sm btn-info btn-icon edit-redirect">
                                <i class="las la-highlighter"></i>
                            </button>';
                }
                if ($has_delete_permission) {
                    $actions .= $this->delete_button(route('backend.redirects.destroy', $q->id), $q->name);
                }
                return $actions;
            })
            ->rawColumns(['actions', 'roles'])
            ->toJson();
    }
    #endregion

    #region create

    public function store(UrlRedirectRequest $request)
    {

        $redirect = UrlRedirect::create($request->except('token'));

        return response()->data(['message' => trans('backend.global.success_message.created_successfully')]);

    }

    #endregion

    public function edit($id)
    {
        $redirect = UrlRedirect::findOrFail($id);

        $view = view('backend.redirects.edit', compact('redirect'))->render();

        return response()->data(['view' => $view]);
    }

    public function update(UrlRedirectRequest $request, $id)
    {
        $redirect = UrlRedirect::findOrFail($id);

        $redirect->update($request->except('token'));

        return response()->data(['message' => trans('backend.global.success_message.updated_successfully')]);

    }

    public function destroy($id)
    {
        if (!permission_can('delete redirect', 'admin')) {
            return abort(403);
        }

        if (UrlRedirect::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.something'));
    }

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete redirect', 'admin')) {
            return abort(403);
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            UrlRedirect::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

}
