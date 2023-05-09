<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Category\CheckSlugRequest;
use App\Http\Requests\Backend\Category\CreateRequest;
use App\Http\Requests\Backend\Category\EditRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Admin;
use App\Models\Category;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use phpDocumentor\Reflection\Types\Parent_;
use function Doctrine\Common\Cache\Psr6\get;
use function Symfony\Component\HttpFoundation\getLanguages;

class CategoyController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show category', 'admin')) {
            return abort(403);
        }
        $filters = [];
        $filters[] = 'type';
        $filters[] = 'status';
        $filters[] = 'parent';
        $datatable_route = route('backend.categories.datatable');
        $delete_all_route = route('backend.categories.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['name'] = 'name';
        $datatable_columns['parent_id'] = 'parent_id';
        $datatable_columns['type'] = 'type';
        $datatable_columns['products_count'] = 'products_count';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route, $filters,[],'post','datatable',true , true , true ,'' ,['products_count']);
        $switch_script = null;
        $switch_route = route('backend.categories.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create category', 'admin')) {
            $create_button = $this->create_button(route('backend.categories.create'), trans('backend.category.create_new_category'));
        }
        return view('backend.category.index', compact('datatable_script', 'switch_script', 'create_button', 'filters'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show category', 'admin')) {
            return abort(403);
        }
        $model = Category::query()->select('categories.*', DB::raw('COUNT(products.id) as products_count'))
            ->leftJoin('products', 'products.category_id', 'categories.id')
            ->whereNull('products.deleted_at')
            ->groupBy('categories.id');
        if ($request->has('type') && $request->type != null) {
            $model = $model->where('type', $request->type);
        }
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('categories.status', $request->status);
        }
        if ($request->has('parent') && $request->parent != null) {
            $model = $model->where('parent_id', $request->parent);
        }

        if ($request->order[0]['column'] == 0) {
            $model->orderByDesc('id');
        }

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q) {
                return "";
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status category', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit category', 'admin')) {
                    $actions .= $this->edit_button(route('backend.categories.edit', ['category' => $q->id]));
                }
                if (permission_can('delete category', 'admin')) {
                    $actions .= $this->delete_button(route('backend.categories.destroy', ['category' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->editColumn('parent_id', function ($q) {
                if ($q->parent_id != null && $q->parent_id > 0) {
                    return '<a href="'.route('backend.categories.edit', ['category'=>$q->parent_id]).'"
                                                       class="symbol    symbol-50px  ">
                                                       '. $q->parent?->name.'
                                                    </a>';
                }
                return "-";
            })
            ->editColumn('products_count', function ($q) {
                return $q->products_count;
            })
            ->editColumn('name', function ($q) {
                return $q->name;
            })
            ->editColumn('type', function ($q) {
                if ($q->type == 'software') {
                    return '<span class="badge badge-primary">' . trans('backend.category.software') . '</span>';

                } else {
                    return '<span class="badge badge-secondary">' . trans('backend.category.physical') . '</span>';
                }
            })
            ->rawColumns(['actions', 'status', 'type', 'roles', 'placeholder', 'parent_id'])->toJson();
    }
    #endregion

    #region create
    function create()
    {
        if (!permission_can('create category', 'admin')) {
            return abort(403);
        }
        $categories = Category::all();
        return view('backend.category.create', compact('categories'));
    }

    function store(CreateRequest $request)
    {

        $category = new Category();
        $name = [];
        $description = [];
        $meta_title = [];
        $meta_description = [];
        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
            $meta_title[$item->code] = $request->get('meta_title_' . $item->code);
            $meta_description[$item->code] = $request->get('meta_description_' . $item->code);
        }
        $category->name = $name;
        $category->description = $description;
        $category->slug = $request->slug;

        if ($request->parent != 0) {
            $category->parent_id = $request->parent;
            $category->type = Category::find($request->parent)->type;
        } else {
            $category->type = $request->type;
        }

        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $meta_title;
        $category->meta_description = $meta_description;
        $category->status = $request->has('status') ? 1 : 0;
        $category->save();
        return redirect()->route('backend.categories.create')->with('success', trans('backend.global.success_message.created_successfully'));

    }

    #endregion

    #region edit
    function edit($id)
    {
        if (!permission_can('edit category', 'admin')) {
            return abort(403);
        }
        $categories = Category::all();
        $category = Category::findOrFail($id);
        return view('backend.category.edit', compact('category', 'categories'));
    }

    function update(EditRequest $request, $id)
    {

        $category = Category::findOrFail($id);
        $name = [];
        $description = [];
        $meta_title = [];
        $meta_description = [];

        foreach (get_languages() as $item) {
            $name[$item->code] = $request->get('name_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
            $meta_title[$item->code] = $request->get('meta_title_' . $item->code);
            $meta_description[$item->code] = $request->get('meta_description_' . $item->code);
        }
        $category->name = $name;
        $category->description = $description;
        $category->slug = $request->slug;

        if ($request->parent != 0) {
            $category->parent_id = $request->parent;
            $category->type = Category::find($request->parent)->type;
        } else {
            $category->parent_id = null;
            $category->type = $request->type;
        }
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $meta_title;
        $category->meta_description = $meta_description;
        $category->status = $request->has('status') ? 1 : 0;
        $category->save();
        return redirect()->route('backend.categories.edit', $category)->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region delete
    public function destroy($id)
    {

        if (Category::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion

    #region delete all
    function delete_selected_items(Request $request)
    {
        if (!permission_can('delete attribute', 'admin')) {
            return abort(403);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            Category::destroy($id);
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status category', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $admin = Category::find($id);
        if ($admin->status == 1) {
            $admin->status = 0;
        } else {
            $admin->status = 1;
        }
        if ($admin->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

    #region check slug
    function check_slug(CheckSlugRequest $request)
    {
        $category = Category::query()->where('slug', $request->slug);
        if ($request->has('id') && !empty($request->id)) {
            $category->whereNot('id', $request->id);
        }
        if ($category->count() == 0) {
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);
        }
        return response()->error(trans('backend.product.check_slug.you_can_not_use_this_slug'), []);
    }
    #endregion

    #region load parents
    public function load_parents(Request $request)
    {


        $models = [];
        if ($request->type != [])
            $models = Category::whereNotIn('type', [$request->type]  )->where('status', 1)
                ->pluck('id');
        $models = Category::select2(null, 0, 0, $models);
        return response()->data(['models' => $models]);
    }

}
