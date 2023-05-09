<div class="image-input image-input-outline mt-3" data-kt-image-input="true"
     style="background-image: url('{{media_file()}} ;') ;background-size: 100% 100%;">
    <div class="image-input-wrapper w-125px h-125px FileManager" id="FileManager_{{$name}}"
         style="background-image: url('{{ $old_image }}');background-size: 100% 100%;"></div>
    <button data-auto-close="1" data-input-name="{{$name}}" id="button_single_{{$name}}" data-count-image="1"
            type="button"
            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow single_media"
            data-id="logo" data-type="{{$type}}" data-watermark="{{$water_mark}}" data-wa2termark="{{$water_mark}}"
            data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
            data-width="{{$width}}"
            data-height="{{$height}}"
            @if(!empty($value)) data-path="{{media_file($value , true)?->path}}" @else data-path="/" @endif
            data-bs-original-title="{{trans('backend.media.please_select_media')}}"><i
            class="bi bi-pencil-fill fs-7"></i>
    </button>
    <input type="hidden" data-watermark="{{$water_mark}}" id="{{$name}}" name="{{$name}}"
           value="{{empty($value) ?"":$value}}">
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow remove_media_single"
          data-id="{{$name}}" id="remove_item_single_{{$name}}"
          data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove">
																<i class="bi bi-x fs-2"></i>
															</span>
</div>
