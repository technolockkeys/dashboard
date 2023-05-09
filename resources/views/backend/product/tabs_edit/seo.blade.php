<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            @foreach(get_languages() as $key => $item)
                <li class="nav-item">
                    <a class="nav-link  {{$key == 0 ? "active" : ""}}" data-bs-toggle="tab"
                       href="#lang_seo_{{$item->code}}">{{$item->language}}</a>
                </li>
            @endforeach

        </ul>
        <div class="tab-content" id="seo_tabs">
            @foreach(get_languages() as $key => $item)
                <div class="tab-pane fade show  {{$key == 0 ? "active" : ""}}"
                     id="lang_seo_{{$item->code}}" role="tabpane_seo_{{$key}}">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="title_meta_{{$item->code}}"
                                       class="label required">{{trans('backend.product.title')}}</label>
                                <input type="text" class="form-control has-error" name="title_meta_{{$item->code}}" value=" {{old('title_meta_'.$item->code , $product->getTranslation('meta_title' , $item->code))}}"
                                       id="title_meta_{{$item->code}}" maxlength="70">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 form-group ">
                            <div class="form-group">
                                <label class="form-label required"
                                       for="description_meta_{{$item->code}}">{{trans('backend.category.description')}}</label>
                                <textarea type="text" class="form-control" id="description_meta_{{$item->code}}"
                                          name="description_meta_{{$item->code}}"
                                > {{old('description_meta_'.$item->code , $product->getTranslation('meta_description' , $item->code))}}</textarea>
                                @error('description_meta_'.$item->code)<b class="text-danger"> <i
                                        class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
{{--<div class="row">--}}
{{--    <div class="col-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="image_meta">{{trans('backend.product.image')}}</label>--}}
{{--            <br>--}}
{{--            {!! single_image('meta_image' ,media_file( old('meta_image' , $product->meta_image)), old('meta_image', $product->meta_image)) !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
