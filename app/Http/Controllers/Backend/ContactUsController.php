<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    use DatatableTrait;

    function index()
    {
        if (!permission_can('show contact us', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.contact_us.datatable');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['email'] = 'email';
        $datatable_columns['product'] = 'product';
        $datatable_columns['subject'] = 'subject';
        $datatable_columns['message'] = 'message';
        $datatable_columns['created_at'] = 'created_at';
        #endregion


        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns);
        return view('backend.contact_us.index', compact('datatable_script'));
    }

    function datatable(Request $request)
    {
        $query = ContactUs::query();
        return datatables()->of($query)
            ->addColumn('product', function ($q) {
                $html = '';
                if ($q->model_id != null)
                    $html = '<a href="' . route('backend.products.edit', ['product' => $q->model?->id]) . '" class="btn btn-sm  btn-hover-rise  me-1"> <span class="badge badge-light-danger"> ' . $q->model?->short_title . '</span></a>';
                return $html;
            })
            ->rawColumns(['product'])
            ->toJson();
    }
}
