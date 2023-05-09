<div class="card card-flush">
    <div class="card-body">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
            @foreach(get_languages() as $key => $item)
                <li class="nav-item">
                    <a class="nav-link  {{$key == 0 ? "active" : ""}}" data-bs-toggle="tab"
                       href="#lang_{{$item->code}}">{{$item->language}}</a>
                </li>
            @endforeach

        </ul>
        <div class="tab-content" id="information_tabs">
            @foreach(get_languages() as $key => $item)
                <div class="tab-pane fade show  {{$key == 0 ? "active" : ""}}"
                     id="lang_{{$item->code}}" role="tabpane{{$key}}">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="title_{{$item->code}}"
                                       class="form-label @if($key == 0 ) required @endif">{{trans('backend.product.title')}}</label>
                                <input type="text" class="form-control has-error" name="title_{{$item->code}}"
                                       value="{{old('title_'.$item->code , $product->getTranslation('title' , $item->code))}}"
                                       id="title_{{$item->code}}" maxlength="70">
                                <b id="error_title_{{$item->code}}" class="text-danger"></b>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="short_title_{{$item->code}}"
                                       class="form-label @if($key == 0 ) required @endif">{{trans('backend.product.short_title')}}</label>
                                <input type="text" class="form-control has-error" name="short_title_{{$item->code}}"
                                       value="{{old('short_title_'.$item->code , $product->getTranslation('short_title' , $item->code))}}"
                                       id="short_title_{{$item->code}}" maxlength="70">
                                <b id="error_short_title_{{$item->code}}" class="text-danger"></b>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="summary_name_{{$item->code}}"
                                       class="form-label  ">{{trans('backend.product.summary_name')}}</label>
                                <textarea type="text" class="form-control" name="summary_name_{{$item->code}}"
                                          id="summary_name_{{$item->code}}">{{old('summary_name_'.$item->code, $product->getTranslation('summary_name' , $item->code))}}</textarea>
                                <b id="error_summary_name_{{$item->code}}" class="text-danger"></b>

                            </div>

                        <div class="col-12 col-md-12 form-group ">
                            <div class="form-group">
                                <label class="form-label  @if($key == 0 ) required @endif"
                                       for="description_{{$item->code}}"> {{trans('backend.category.description')}}</label>
                                <textarea type="text" class="form-control" id="description_{{$item->code}}"
                                          name="description_{{$item->code}}"
                                > {{old('description_'.$item->code , $product->getTranslation('description' , $item->code))}}</textarea>
                                <b class="text-danger" id="error_description_{{$item->code}}">
                                    @error('description_'.$item->code)<i
                                        class="las la-exclamation-triangle"></i> {{$message}} @enderror</b>
                            </div>
                        </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="faq_{{$item->code}}"
                                       class="form-label  ">{{trans('backend.product.faq')}}</label>
                                <textarea type="text" class="form-control" name="faq_{{$item->code}}"
                                          id="faq_{{$item->code}}">{{old('faq_'.$item->code, $product->getTranslation('faq' , $item->code))}}</textarea>
                                <b id="error_faq_{{$item->code}}" class="text-danger"></b>

                            </div>
                        </div>
                        {!! form_seo(  $item->code , $key,$product->getTranslation('meta_title' , $item->code) , $product->getTranslation('meta_description' , $item->code)) !!}

                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
