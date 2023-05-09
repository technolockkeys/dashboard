<input type="hidden" id="{{$name}}" name="{{$name}}" value="{{empty($value) ?"[]":$value}}"   @if(!empty($value) && is_array(json_decode($value)) && !empty(json_decode($value)[0])) data-path="{{media_file(json_decode($value)[0] ,true)?->path}}" @endif>
<div data-input-name="{{$name}}"  @if(!empty($value) && is_array(json_decode($value) ) && !empty(json_decode($value)[0])) data-path="{{media_file(json_decode($value)[0] ,true)?->path}}" @else data-path="/" @endif data-type="{{$type}}" @if($small)  data-size="small" @else data-size="large" @endif
class="card    flex-center bg-light-primary border-primary  border border-dashed p-4  "
     id="FileManager_{{$name}}">
    @if(!empty(json_decode($value)))
        <div class="row draggable-zone w-100"   data-name="{{$name}}">
            @php
                $class_style = 'col-1';
                 if ($small ) {
                        $class_style = 'col-4';
                  }
            @endphp

            @foreach(json_decode($value) as $item)
                @php
                    $media_file= media_file($item);
                    $url = "";
                    if(!empty($media_file->title)){
                        $url = asset('backend/media/svg/files/pdf.svg');
                    }else{
                        $url = $media_file;
                    }

                @endphp
                <div class="{{$class_style}} mt-9  draggable draggable_{{$name}}  " data-id="{{$item}}" id="card_media_{{$item}}_{{$name}}">

                    <div class="image-input image-input-outline w-100 mh-100"
                         data-kt-image-input="true"
                         style=" aspect-ratio : 1 / 1 !important;    background-size: cover;background-position: center;background-image: url('{{$url}}')">
                        <a data-auto-close="1"   data-count-image="1" type="button" class=" draggable-handle btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow single_media" data-id="logo" data-type="image" data-watermark="true" data-wa2termark="true" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" ><i class="bi bi-arrows-move"></i></a>

                        <div class="image-input-wrapper w-100 mh-100" style=" height:unset !important;  aspect-ratio : 1 / 1 !important;    background-size: cover;  background-position: center;background-image: url('{{$url}}')">
                        </div>
                        <button style="z-index: 21" data-input-name="{{$name}}" data-type="{{$type}}"
                                class="btn btn-icon btn-circle btn-active-color-primary remove-media w-25px h-25px bg-body shadow"
                                data-id="{{$item}}" data-kt-image-input-action="remove"
                                title=""
                        ><i class="bi bi-x fs-2"></i></button>


                    </div>
                </div>
            @endforeach

            <div class="{{$class_style}} mt-9 MediaFileMangerMultiImage"  data-type="{{$type}}" data-input-name="{{$name}}">
                <label class="btn      d-flex align-items-center" style="padding: 0">
                    <div class="card w-100 " style="width: 100% !important;background-color: rgba(255,255,255,0);padding: 0">
                        <div class="card-body p-0 d-flex justify-content-center text-center flex-column ">
                                <span class="text-gray-800 text-hover-primary d-flex flex-column">
                                    <div class="mb-5">
                                        <img width="100%" src="{{asset('backend/media/icons/duotune/files/fil005.svg')}}" alt="">
                                    </div>
                                    <div style="font-size: small" class="fw-bolder mb-2">
{{--                                        {{trans('backend.global.select_media')}}--}}
                                    </div>
                                </span>
                        </div>
                    </div>
                </label>
            </div>


        </div>
    @else
        <div class="row MediaFileMangerMultiImage" data-type="{{$type}}" data-input-name="{{$name}}">
            <div class="col-12"><img src="{{asset('backend/media/svg/files/upload.svg')}}" class="mb-5" alt="">
                <a href="#"
                   class="text-hover-primary fs-5 fw-bolder mb-2">
{{--                    {{trans('backend.global.select_media')}}--}}
                </a>
                <div
                    class="fs-7 fw-bold text-gray-400">{{trans('backend.global.click_to_select_files_here')}}</div>
            </div>
        </div>
    @endif
</div>
