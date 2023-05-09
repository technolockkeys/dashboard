<div class="w-100  p-3 pt-5 mt-3">
    <form id="media_edit_form" method="post">
        @csrf
        <input type="hidden" name="id" id="model_media_id" value="{{$mediaFile->id}}">
        {{--title--}}

        <div class="form-group">
            <label class="form-label" for="media_title">{{trans('backend.media.title')}}</label>
            <input type="text" id="media_title" name="title" class="form-control"
                   value="{!! strtr($mediaFile->title,['.'.$mediaFile->extension =>'']) !!}" placeholder="">
            <b id="error_media_form_title" class="text-danger"></b>
        </div>
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            @foreach(get_languages() as $key => $item)
                <li class="nav-item">
                    <a class="nav-link  {{$key == 0 ? "active" : ""}}" data-bs-toggle="tab"
                       href="#lang_media_{{$item->code}}">{{$item->language}}</a>
                </li>
            @endforeach

        </ul>
        <div class="tab-content" id="information_tabs">
            @foreach(get_languages() as $key => $item)
                <div class="tab-pane fade show  {{$key == 0 ? "active" : ""}}"
                     id="lang_media_{{$item->code}}" role="tabpane{{$key}}">
                    <div class="row">
                        {{--description--}}

                        <div class="form-group">
                            <label class="form-label"
                                   for="media_description_{{$item->code}}">{{trans('backend.media.description')}}</label>
                            <textarea name="description_{{$item->code}}" class="form-control" id="media_description_{{$item->code}}"
                                   style="resize: none"   rows="3">{{$mediaFile->getTranslation('description', $item->code)  }}</textarea>
                            <b id="error_media_form_description" class="text-danger"></b>
                        </div>

                        {{--open_graph--}}

                        <div class="form-group">
                            <label class="form-label"
                                   for="media_open_graph_{{$item->code}}">{{trans('backend.media.open_graph')}}</label>
                            <textarea name="open_graph_{{$item->code}}" class="form-control" id="media_open_graph_{{$item->code}}"
                                  style="resize: none"     rows="3">{{$mediaFile->getTranslation('open_graph', $item->code)  }}</textarea>
                            <b id="error_media_form_open_graph" class="text-danger"></b>
                        </div>
                        {{--scale--}}

                        <div class="form-group">
                            <label class="form-label" for="media_scale_{{$item->code}}">{{trans('backend.media.scale')}}</label>
                            <input type="text" id="media_scale_{{$item->code}}" name="scale_{{$item->code}}" value="{{$mediaFile->getTranslation('scale', $item->code)  }}"
                                   class="form-control" placeholder="">
                            <b id="error_media_form_scale   " class="text-danger"></b>
                        </div>
                        {{--alt--}}

                        <div class="form-group">
                            <label class="form-label" for="media_alt_{{$item->code}}">{{trans('backend.media.alt')}}</label>
                            <input type="text" id="media_alt_{{$item->code}}" value="{{$mediaFile->getTranslation('alt', $item->code)  }}" name="alt_{{$item->code}}"
                                   class="form-control" placeholder="">
                            <b id="error_media_form_alt" class="text-danger"></b>
                        </div>

                        {{--rel--}}

                        <div class="form-group">
                            <label class="form-label" for="media_rel_{{$item->code}}">{{trans('backend.media.rel')}}</label>
                            <input type="text" id="media_rel_{{$item->code}}" value="{{$mediaFile->getTranslation('rel', $item->code)  }}" name="rel_{{$item->code}}"
                                   class="form-control" placeholder="">
                            <b id="error_media_form_rel" class="text-danger"></b>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{--extension--}}

        <div class="form-group">
            <label class="form-label" for="media_extension">{{trans('backend.media.extension')}}</label>
            <input type="text" id="media_extension" value="{{$mediaFile->extension}}" name="extension" readonly
                   disabled class="form-control bg-secondary" placeholder="">
            <b id="error_media_form_extension" class="text-danger"></b>
        </div>

        {{--type--}}

        <div class="form-group">
            <label class="form-label" for="media_type">{{trans('backend.media.type')}}</label>
            <input type="text" id="media_type" value="{{$mediaFile->type}}" name="type" readonly disabled
                   class="form-control bg-secondary" placeholder="">
            <b id="error_media_form_type" class="text-danger"></b>
        </div>


        {{--width--}}

        <div class="form-group">
            <label class="form-label" for="media_width">{{trans('backend.media.width')}}</label>
            <input type="text" id="media_width" value="{{$mediaFile->width}}" name="width" readonly disabled
                   class="form-control  bg-secondary" placeholder="">
            <b id="error_media_form_width" class="text-danger"></b>
        </div>

        {{--height--}}

        <div class="form-group">
            <label class="form-label" for="media_height">{{trans('backend.media.height')}}</label>
            <input type="text" id="media_height" value="{{$mediaFile->height}}" name="height" readonly disabled
                   class="form-control bg-secondary" placeholder="">
            <b id="error_media_form_height" class="text-danger"></b>
        </div>

        {{--created at--}}
        <div class="form-group">
            <label class="form-label" for="created_at">{{trans('backend.global.created_at')}}</label>
            <input type="text" id="created_at" value="{{$mediaFile->created_at}}" name="created_at" readonly
                   disabled class="form-control bg-secondary" placeholder="">
        </div>
        {{--updated at--}}
        <div class="form-group">
            <label class="form-label" for="updated_at">{{trans('backend.global.updated_at')}}</label>
            <input type="text" id="updated_at" value="{{$mediaFile->updated_at}}" name="updated_at" readonly
                   disabled class="form-control bg-secondary" placeholder="">
        </div>
        {{--size--}}
        <div class="form-group">
            <label class="form-label" for="size">{{trans('backend.media.size')}}</label>
            <input type="text" id="media_height" value="{{$mediaFile->size}} KB" name="size" readonly disabled
                   class="form-control bg-secondary" placeholder="">

        </div>

        {{--buuton submit--}}

        <div class="form-group">
            <br>
            <button id="model_save_btn_model"
                    class="btn btn-primary w-100">{{trans('backend.global.save')}}</button>
        </div>

    </form>
</div>
