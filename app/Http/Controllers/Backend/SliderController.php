<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Cms\Slider\CreateRequest;
use App\Http\Requests\Backend\Shared\ChangeStatusRequest;
use App\Models\Attribute;
use App\Models\Slider;
use App\Traits\ButtonTrait;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use phpseclib3\File\ASN1\Maps\UniqueIdentifier;

class SliderController extends Controller
{
    use DatatableTrait;
    use ButtonTrait;

    #region index
    public function index()
    {
        if (!permission_can('show sliders', 'admin')) {
            return abort(403);
        }
        $datatable_route = route('backend.cms.sliders.datatable');
        $delete_all_route = route('backend.cms.sliders.delete-selected');
        #region data table columns
        $datatable_columns = [];
        $datatable_columns['placeholder'] = '';
        $datatable_columns['id'] = 'id';
        $datatable_columns['link'] = 'link';
        $datatable_columns['image'] = 'image';
        $datatable_columns['type'] = 'type';
        $datatable_columns['created_at'] = 'created_at';
        $datatable_columns['updated_at'] = 'updated_at';
        $datatable_columns['status'] = 'status';
        $datatable_columns['actions'] = 'actions';
        #endregion
        $datatable_script = $this->create_script_datatable($datatable_route, $datatable_columns, $delete_all_route);
        $switch_script = null;
        $switch_route = route('backend.cms.sliders.change.status');
        $switch_class = 'status';
        $switch_script = $this->status_switch_script($switch_route, $switch_class);
        $create_button = '';
        if (permission_can('create slider', 'admin')) {
            $create_button = $this->create_button(route('backend.cms.sliders.create'), trans('backend.slider.create_new_slider'));
        }
        return view('backend.slider.index', compact('datatable_script', 'create_button', 'switch_script'));
    }

    public function datatable()
    {
        if (!permission_can('show sliders', 'admin')) {
            return abort(403);
        }

        $model = Slider::query();
        $default_images = media_file(get_setting('default_images'));

        return datatables()->make($model)
            ->addColumn('placeholder', function ($q){
                return '';
            })
            ->editColumn('status', function ($q) {
                $bool = !permission_can('change status slider', 'admin');
                return $this->status_switch($q->id, $q->status, 'status', $bool);
            })
            ->editColumn('type', function ($q) {
                return trans('backend.slider.'.$q->type);
            })
            ->editColumn('image', function ($q)use ($default_images) {
                return '<img width="75px" onerror="this.src='."'".$default_images."'".'" src="'  . media_file($q->getTranslation('image', app()->getLocale())) .'">';
            })
            ->editColumn('link', function ($q){
                return $q->link;
            })
            ->addColumn('actions', function ($q) {
                $actions = '';
                if (permission_can('edit slider', 'admin')) {
                    $actions .= $this->edit_button(route('backend.cms.sliders.edit', ['slider' => $q->id]));
                }
                if (permission_can('delete slider', 'admin')) {
                    $actions .= $this->delete_button(route('backend.cms.sliders.destroy', ['slider' => $q->id]), $q->name);
                }
                return $actions;
            })
            ->rawColumns(['actions', 'image','status'])->toJson();
    }
    #endregion

    #region create
    public function create()
    {
        $types = [
            'main' => trans('backend.slider.main'),
            'banner' => trans('backend.slider.banner'),
            ];
        return view('backend.slider.create', compact('types'));
    }

    public function store(CreateRequest $request)
    {
        foreach (get_languages() as $language) {
            $image [$language->code] = $request->has('image_' . $language->code) && !empty($request->get('image_' . $language->code)) ? $request->get('image_' . $language->code) : $request->get('image_' . get_languages()[0]->code);
            $link [$language->code] = $request->has('link_' . $language->code) && !empty($request->get('link_' . $language->code)) ? $request->get('link_' . $language->code) : $request->get('link_' . get_languages()[0]->code);
        }
        Slider::create([
            'image' => $image,
            'link' => $link,
            'type' => $request->type,
            'status'=> $request->status?1:0]);

        return redirect()->route('backend.cms.sliders.index')->with('success', trans('backend.global.success_message.created_successfully'));
    }
    #endregion

    #region edit
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        $types = [
            'main' => trans('backend.slider.main'),
            'banner' => trans('backend.slider.banner'),
        ];
        return view('backend.slider.edit', compact('slider', 'types'));
    }

    public function update(CreateRequest $request, $id)
    {
        $slider = Slider::findOrFail($id);

        foreach (get_languages() as $language) {
            $image [$language->code] = $request->get('image_' . $language->code) ;
            $link [$language->code] = $request->get('link_' . $language->code);
        }
        $slider->update([
            'image' => $image,
            'link' => $link,
            'type' => $request->type,
            'status'=> $request->status?1:0]);

        return redirect()->route('backend.cms.sliders.index')->with('success', trans('backend.global.success_message.updated_successfully'));
    }

    #endregion

    #region destroy
    public function destroy($id){
        if (!permission_can('delete slider', 'admin')) {
            return abort(403);
        }

        if (Slider::destroy($id)) {
            return response()->data(['message' => trans('backend.global.success_message.deleted_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.error_on_deleted'));
    }
    #endregion

    #region change status
    function change_status(ChangeStatusRequest $request)
    {
        if (!permission_can('change status attribute', 'admin')) {
            return abort(403);
        }
        $id = $request->id;
        $slider = Slider::find($id);
        if ($slider->status == 1) {
            $slider->status = 0;
        } else {
            $slider->status = 1;
        }
        if ($slider->save()) {
            return response()->data(['message' => trans('backend.global.success_message.changed_status_successfully')]);
        }
        return response()->error(trans('backend.global.error_message.cant_updated'));
    }
    #endregion
}
