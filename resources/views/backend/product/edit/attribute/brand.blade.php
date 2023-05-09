<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2><label for="image" >{{trans('backend.product.brands')}}</label></h2>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12 col-md-12 mt-4">
                <div class="row" id="brands_models">
                    {{--            {!! json_encode(old('brand',[]))  !!}--}}
                    <div class="col-12">
{{--                        @dd(old('brand'))--}}
                        @foreach(old('brand',$products_brands) as $key =>$old_item)
                            <div class="row" id='row_brand_{{$key}}'>
                                <div class="col-12 col-md-2">
                                    <div class="form-group">
                                        <label for="brand">{{trans('backend.product.brand')}}</label>
                                        <input type="hidden" id="uuid_brand" name="uuid_brand[]" value="{{$key}}">
                                        <select data-control="select2" class="form-control brands" name="brand[]"
                                                id="brand_{{$key}}"
                                                data-brand="{{$key}}" data-uuid="{{$key}}">
                                            <option value="" disabled>{{trans('backend.product.please_select_option')}}</option>
                                            @php
                                                $old_brand = gettype($old_item) == 'string'? $old_item : $old_item->brand_id;
                                            @endphp
                                            @foreach($brands as $brand)

                                                <option @if($brand->id == $old_brand ) selected @endif  value="{{$brand->id}}">{{$brand->make}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 ">
                                    <label for="default_models">{{trans('backend.product.model')}}</label>

                                    <select name="models[]" id="models_{{$key}}" data-uuid="{{$key}}"
                                            class="form-control models"
                                            data-control="select2">
                                        <option  readonly  selected value=''>All</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 ">
                                    <label for="year_from_{{$key}}">{{trans('backend.product.year')}}</label>
                                    <select name="years_from[]" id="year_from_{{$key}}" data-uuid="{{$key}}"
                                            class="form-control years"
                                            data-control="select2">
                                        <option  readonly  selected value=''>All</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 ">
                                    <label for="year_to_{{$key}}">{{trans('backend.product.year')}}</label>
                                    <select name="years_to[]" id="year_to_{{$key}}" data-uuid="{{$key}}"
                                            class="form-control years"
                                            data-control="select2">
                                        <option  readonly  selected value=''>All</option>

                                    </select>
                                </div>
                                <div class='col-1 col-md-1'>
                                    <button type='button' onclick=$('#row_brand_{{$key}}').remove()
                                            class='btn btn mt-5 btn-icon btn-danger btn-hover-scale   me-5'><i
                                                class='fas fa-trash fs-4'></i></button>
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
        </div>
        <div class="card-header">
            <div class="card-title">
                <h2><label for="image">{{trans('backend.product.manufacturer')}}</label></h2>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12 col-md-12 mt-6">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="date_type">{{trans('backend.product.manufacturer')}}</label>

                            <select data-control="select2" class="form-control brands" name="manufacturer"
                                    id="manufacturer">
                                <option value="">{{trans('backend.product.please_select_option')}}</option>
                                @foreach($manufacturers as $manufacturer)
                                    <option @if($manufacturer->id == old('manufacturer', $product->manufacturer?->id) ) selected
                                            @endif  value="{{$manufacturer->id}}">{{$manufacturer->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 @if(!($product->manufacturer?->software && $product->manufacturer?->software))d-none @endif" id="manufacturer_type_wrapper">
                        <div class="form-group">
                            <label class="form-label"
                                   for="manufacturer_type">{{trans('backend.product.manufacturer_type')}}</label>

                            <select data-control="select2" class="form-control brands" name="manufacturer_type"
                                    id="manufacturer_type">
                                <option value="">{{trans('backend.product.please_select_option')}}</option>
                                <option @if('software' == old('manufacturer_type',$product->manufacturer_type) ) selected
                                        @endif  value="software">{{trans('backend.manufacturer.software')}}</option>
                                <option @if('token' == old('manufacturer_type',$product->manufacturer_type) ) selected
                                        @endif  value="token">{{trans('backend.manufacturer.token')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
