<?php

namespace App\Traits;


trait ButtonTrait
{


    function status_switch($id, $check = false, $class = null, $disabled = false)
    {
        $html = '<label class="form-check form-switch-sm form-switch form-check-custom form-check-solid">
                    <input ' . ($disabled ? 'disabled' : '') . '  class="form-check-input ' . $class . ' " type="checkbox" value="' . $id . '" ' . ($check ? ' checked="checked"' : "") . '  />
                    <span class="form-check-label fw-bold text-muted"></span>
                    </label>';
        return $html;
    }

    function status_switch_script($url, $class = 'status')
    {
        return view('backend.shared.status_script', compact('url', 'class'))->render();
    }

    function change_column_switch_script($url, $column = 'status')
    {
        return view('backend.shared.switch_column_value_script', compact('url', 'column'))->render();
    }

    function btn($route = null, $title, $icon = null, $class = 'btn-primary', $data = [], $id = null)
    {
        if (empty($route)) {
            $button = '<button  id="' . (empty($id) ? "" : $id) . '"  href="' . $route . '" class="btn btn-sm ' . $class . ' btn-hover-rise  me-1"';
            foreach ($data as $key => $value) {
                $button .= ' data-' . $key . '="' . $value . '" ';
            }
            $button .= ' data-token="' . csrf_token() . '" ';
            $button .= '  > ' . (!empty($icon) ? ' <i class="' . $icon . '"></i> ' : "") . $title . '</button>';
            return $button;
        }
        return '<a href="' . $route . '" class="btn btn-sm ' . $class . ' btn-hover-rise  me-1"> ' . (!empty($icon) ? ' <i class="' . $icon . '"></i> ' : "") . $title . '</a>';
    }

    function edit_button($route)
    {
        return $this->btn($route, '', 'las la-highlighter', 'btn-info btn-icon');
    }

    function delete_button($route, $name = null)
    {

        $message = '';
        $done_deleted = '';
        if (!empty($name)) {
            $message = trans('backend.global.ara_you_sure_to_delete', ['name' => $name]);
            $done_deleted = trans('backend.global.done_deleted', ['name' => $name]);
        } else {
            $message = trans('backend.global.ara_you_sure_to_delete', ['name' => trans('backend.global.this_item')]);
            $done_deleted = trans('backend.global.done_deleted', ['name' => trans('backend.global.this_item')]);
        }
        $data['deleted'] = $done_deleted;
        $data['message'] = $message;
        $data['route'] = $route;
        return $this->btn(null, '', 'las la-trash', 'btn-danger btn-delete btn-icon', $data);
    }

    function create_button($url, $title = null)
    {
        if (!isset($title)) {
            $title = trans('backend.global.create_new_item');
        }
        return ' <a href="' . $url . '"  class="btn btn-primary" >
                     <span class="svg-icon svg-icon-2">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
														<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
													</svg>
												</span>
                   ' . $title . '
                </a>';
    }
}
