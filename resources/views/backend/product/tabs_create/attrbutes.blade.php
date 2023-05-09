<div class="row">

    <div class="col-12">
        <div class="row">
            @foreach([
'is_best_seller',
'is_super_sales',
'is_visibility',
'is_saudi_branch',
'is_featured',
'is_today_deal',
'is_free_shipping',
'status',
] as $item)
                <div class="col-12 col-md-6">
                    <div class="form-group  align-items-center">
                        <br>
                        <div class="form-check form-switch form-check-custom form-check-solid me-10">
                            <input class="form-check-input h-20px w-30px" @if(old($item, 0) == 1 ) checked
                                   @endif type="checkbox"
                                   value="1"
                                   name="{{$item}}" id="{{$item}}"/>
                            <label class="form-check-label" for="{{$item}}">
                                {{trans('backend.product.'.$item)}}
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>
    <div class="separator separator-content border-dark my-10"><span
            class="w-250px fw-bolder">{{trans('backend.product.attributes')}}</span></div>

    <div class="col-12 ">
        <div class="row">
            @foreach($attributes as $attribute)
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="attr_{{$attribute->id}}">{{$attribute->name}}</label>
                        <select multiple data-control="select2" name="attribute[]" class="form-control"
                                id="attr_{{$attribute->id}}">
                            @foreach($attribute->sub_attributes  as $sub_attribute)
                                <option
                                    @if(!empty(old('attribute' ,[]) ) && in_array($sub_attribute->id , old('attribute'))) selected
                                    @endif value="{{$sub_attribute->id}}">{{$sub_attribute->value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="separator separator-content border-dark my-10"><span
            class="w-250px fw-bolder">{{trans('backend.product.brands')}}</span></div>
@if(empty(old('brand',[]) ))
    @php $time = 0; @endphp
    {{--    brand --}}

    <div class="col-12 col-md-3   ">
        <div class="form-group">
            <label for="brand">{{trans('backend.product.brand')}}</label>
            <input type="hidden" id="uuid_brand" value="{{$time}}">
            <select data-control="select2" class="form-control brands" name="brand[]" id="brand_{{$time}}"
                    data-brand="{{$time}}" data-uuid="{{$time}}">
                <option value="" disabled>{{trans('backend.product.please_select_option')}}</option>
                @foreach($brands as $brand)
                    <option
                        @if(!empty(old('attribute' ,[]) ) && in_array($sub_attribute->id , old('attribute'))) selected
                        @endif  value="{{$brand->id}}">{{$brand->make}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12 col-md-3 ">
        <label for="default_models">{{trans('backend.product.model')}}</label>

        <select name="models[]" id="models_{{$time}}" data-uuid="{{$time}}" class="form-control models"
                data-control="select2">
            <option disabled value="">{{trans('backend.product.please_select_brand')}}</option>
        </select>
    </div>
    <div class="col-12 col-md-6 ">
        <label for="year_{{$time}}">{{trans('backend.product.year')}}</label>
        <select name="years[]" id="year_{{$time}}" multiple data-uuid="{{$time}}" class="form-control years"
                data-control="select2"></select>
    </div>
    @endif
    <div class="col-12 col-md-12 mt-4">
        <div class="row" id="brands_models">
            {{--            {!! json_encode(old('brand',[]))  !!}--}}
            <div class="col-12">
                @foreach(old('brand',[]) as $key =>$old_item)

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="brand">{{trans('backend.product.brand')}}</label>
                                    <input type="hidden" id="uuid_brand" name="uuid_brand[]" value="{{$key}}">
                                    <select data-control="select2" class="form-control brands" name="brand[]"
                                            id="brand_{{$key}}"
                                            data-brand="{{$key}}" data-uuid="{{$key}}">
                                        <option value="" disabled>{{trans('backend.product.please_select_option')}}</option>
                                        @foreach($brands as $brand)
                                            <option @if($brand->id == $old_item ) selected @endif  value="{{$brand->id}}">{{$brand->make}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 ">
                                <label for="default_models">{{trans('backend.product.model')}}</label>

                                <select name="models[]" id="models_{{$key}}" data-uuid="{{$key}}"
                                        class="form-control models"
                                        data-control="select2">


                                </select>
                            </div>
                            <div class="col-12 col-md-6 ">
                                <label for="year_{{$key}}">{{trans('backend.product.year')}}</label>
                                <select name="years[]" id="year_{{$key}}" multiple data-uuid="{{$key}}"
                                        class="form-control years"
                                        data-control="select2"></select>
                            </div>
                        </div>

                @endforeach
            </div>


        </div>

        <div class="row mt-3">
            <div class="col-12 col-md-12">
                <button class="btn btn-primary" type="button"
                        onclick="add_new_brand()">{{trans('backend.product.add_brand')}}</button>
            </div>
        </div>
    </div>

    {{--    end brand--}}


</div>
