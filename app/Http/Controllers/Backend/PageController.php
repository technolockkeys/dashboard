<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Page\CheckSlugRequest;
use App\Http\Requests\Backend\Page\CreateRequest;
use App\Http\Requests\Backend\Page\EditRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Page;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    function index()
    {
        if (!permission_can('show pages', 'admin')) {
            return abort(403);
        }
        $filters[] = 'status';
        $datatable_route = route('backend.pages.datatable');
        $delete_all_route = route('backend.pages.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = 'placeholder';
        $datatable_columns['id'] = 'id';
        $datatable_columns['title'] = 'title';
        $datatable_columns['slug'] = 'slug';
        $datatable_columns['image'] = 'meta_image';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns,$delete_all_route, $filters);
        $switch_script = null;
        $switch_route = route('backend.pages.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create page', 'admin')) {
            $create_button = $this->create_button(route('backend.pages.create'), trans('backend.page.create_new_page'));
        }
        return view('backend.page.index', compact('datatable_script', 'switch_script', 'create_button'));

    }

    function datatable(Request $request)
    {
        if (!permission_can('show pages', 'admin')) {
            return abort(403);
        }
        $model = Page::query();
        if ($request->has('status') && $request->status != -1) {
            $model = $model->where('status', $request->status);
        }
        return datatables()->make($model)
            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->editColumn('status', function ($q) {
                return $this->status_switch($q->id, $q->status, 'status');
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit page', 'admin')) {
                    $actions .= $this->edit_button(route('backend.pages.edit', ['page' => $q->id]));
                }
                if (permission_can('delete page', 'admin')) {
                    $actions .= $this->delete_button(route('backend.pages.destroy', ['page' => $q->id]), $q->name);
                }
                return $actions;
            })->editColumn('image', function ($q) {
                $html = "<div class='w-40px h-40px overflow-hidden'> <img class='w-100 h-100 ' style='object-fit: cover' src='" . media_file($q->meta_image) . "'> </div>";
                return $html;

            })
            ->editColumn('title', function ($q) {
                return $q->title;
            })
            ->rawColumns(['actions', 'status', 'image', 'title'])->toJson();
    }
    #endregion

    #region create
    function create()
    {
        if (!permission_can('create page', 'admin')) {
            return abort(403);
        }
        $pages = Page::all();
        return view('backend.page.create', compact('pages'));
    }

    function store(CreateRequest $request)
    {
        $page = new Page();
        $title = [];
        $description = [];
        $meta_description = [];
        $meta_title = [];
        foreach (get_languages() as $item) {
            $title[$item->code] = $request->get('title_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
            $meta_title[$item->code] = $request->get('meta_title_' . $item->code);
            $meta_description[$item->code] = $request->get('meta_description_' . $item->code);

        }
        $page->title = $title;
        $page->description = $description;
        $page->meta_title = $meta_title;
        $page->meta_description = $meta_description;
        $page->slug = $request->slug;
        $page->meta_image = $request->meta_image;
        $page->status = $request->has('status') ? 1 : 0;
        $page->save();
        return redirect()->route('backend.pages.create')->with('success', trans('backend.global.success_message.created_successfully'));
    }

    #endregion

    #region edit
    function edit($id)
    {
        if (!permission_can('edit page', 'admin')) {
            return abort(403);
        }
        $page = Page::findOrFail($id);
        return view('backend.page.edit', compact('page'));
    }

    function update(EditRequest $request, $id)
    {

        $page = Page::findOrFail($id);
        $title = [];
        $description = [];
        $meta_description = [];
        $meta_title = [];
        foreach (get_languages() as $item) {
            $title[$item->code] = $request->get('title_' . $item->code);
            $description[$item->code] = $request->get('description_' . $item->code);
            $meta_title[$item->code] = $request->get('meta_title_' . $item->code);
            $meta_description[$item->code] = $request->get('meta_description_' . $item->code);
        }
        $page->title = $title;
        $page->description = $description;
        if(!in_array($page->slug, $page->default_pages)){
            $page->slug = $request->slug;
        }
        $page->meta_title = $meta_title;
        $page->meta_image = $request->meta_image;
        $page->meta_description = $meta_description;
        $page->status = $request->has('status') ? 1 : 0;
        $page->save();
        return redirect()->route('backend.pages.edit', $page)->with('success', trans('backend.global.success_message.updated_successfully'));
    }
    #endregion

    #region delete
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        if(in_array($page->slug, $page->default_pages )){
            return response()->error(trans('backend.pages.can_not_delete_default_page'));
        }
        if (Page::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));

    }
    #endregion
    #region delete all
    function delete_selected_items(Request $request)
    {
        if(!permission_can('delete page', 'admin')){
            return abort(403);
        }

        $ids = $request->ids;
        foreach ($ids as $id) {
            $page = Page::findOrFail($id);
            if(!in_array($page->slug, $page->default_pages )){
                Page::destroy($id);
            }
        }
        return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status page', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $page = Page::find($id);
        if ($page->status == 1) {
            $page->status = 0;
        } else {
            $page->status = 1;
        }
        if ($page->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion

    #region check slug
    function check_slug(CheckSlugRequest $request)
    {

        $category = Page::query()->where('slug', $request->slug);
        if ($request->has('id') && !empty($request->id)) {
            $category->whereNot('id', $request->id);
        }
        if ($category->count() == 0) {
            return response()->data(['message' => trans('backend.product.check_slug.you_can_use_this_slug')]);
        }
        return response()->error(trans('backend.product.check_slug.you_can_not_use_this_slug'), []);

    }
    #endregion
}
