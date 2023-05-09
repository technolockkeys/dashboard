<div class="row">
    <div class="col-12">
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
                                       class="label required">{{trans('backend.product.title')}}</label>
                                <input type="text" class="form-control has-error" name="title_{{$item->code}}"
                                       value="{{old('title_'.$item->code)}}"
                                       id="title_{{$item->code}}" maxlength="70">
                                <b id="error_title_{{$item->code}}" class="text-danger"></b>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="summary_name_{{$item->code}}"
                                       class="label  ">{{trans('backend.product.summary_name')}}</label>
                                <textarea type="text" class="form-control" name="summary_name_{{$item->code}}"
                                          id="summary_name_{{$item->code}}">{{old('summary_name_'.$item->code)}}</textarea>
                                <b id="error_summary_name_{{$item->code}}" class="text-danger"></b>

                            </div>
                        </div>
                        <div class="col-12 col-md-12 form-group ">
                            <div class="form-group">
                                <label class="form-label required"
                                       for="description_{{$item->code}}">{{trans('backend.category.description')}}</label>
                                <textarea type="text" class="form-control" id="description_{{$item->code}}"
                                          name="description_{{$item->code}}"
                                > {{old('description_'.$item->code)}}</textarea>
                                <b class="text-danger" id="error_description_{{$item->code}}">
                                    @error('description_'.$item->code)<i
                                        class="las la-exclamation-triangle"></i> {{$message}} @enderror</b>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    <div class="col-12 col-md-12">
        <hr>
    </div>
    <div class="col-12 col-md-12 mt-4">
        <div class="form-group">
            <label for="slug" class="required">{{trans('backend.product.slug')}}</label>
            <input type="text" class="form-control" required id="slug" value="{{old('slug')}}" name="slug">
            <span id="message_slug"></span>
            <b class="text-danger" id="error_slug"> @error('sku')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
        </div>
    </div>
    <div class="col-12 col-md-6 ">
        <div class="form-group">
            <label for="sku" class="required">{{trans('backend.product.sku')}}</label>
            <input type="text" class="form-control" required id="sku" name="sku" value="{{old('sku')}}">
            <b class="text-danger" id="error_sku"> @error('sku')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
        </div>
    </div>
    <div class="col-12 col-md-6 ">
        <div class="form-group">
            <label for="weight" class="required">{{trans('backend.product.weight')}}</label>
            <input type="number" step="2" class="form-control" required id="weight" name="weight"
                   value="{{old('weight')}}">
            <b class="text-danger" id="error_weight"> @error('weight')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror
            </b>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <label for="priority" class="required">{{trans('backend.product.priority')}}</label>
        <input type="number" max="1" min="1" value="1" class="form-control" required id="priority" name="priority">
        <b class="text-danger" id="error_priority">     @error('priority')
            <i class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
    </div>
    <div class="col-12 col-md-6">
        <label for="category" class="required">{{trans('backend.product.category')}}</label>
        <select data-control="select2" class="form-control" required id="category" name="category">
            {!! \App\Models\Category::select2([],0,0) !!}
        </select>
        <b class="text-danger" id="error_category"> @error('category') <i
                class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
    </div>
    <div class="col-12 col-md-6">
        <label for="type" class="required">{{trans('backend.product.type')}}</label>
        <select name="type" id="type" class="form-control" data-control="select2">
            <option @if(old('software' ) == 'software') selected
                    @endif value="software">{{trans('backend.product.software')}}</option>
            <option @if(old('physical' ) == 'physical') selected
                    @endif value="physical">{{trans('backend.product.physical')}}</option>
        </select>
    </div>
    <div class="col-12 col-md-6">
        <label for="colors">{{trans('backend.product.colors')}}</label>
        <select class="form-control" id="color" name="color">
            <option @if(old('colors' ) == "") selected @endif value="">{{trans('backend.global.not_found')}}</option>
            @foreach($colors as $item)
                <option data-color="{{$item->code}}" @if($item->id ==  old('color',[] ) ) selected
                        @endif  value="{{$item->id}}">   {{$item->name}}</option>
            @endforeach
        </select>
        <b class="text-danger" id="error_color">  @error('color')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
    </div>


</div>
