@extends('backend.layout.app')
@section('title',trans('backend.menu.coupons').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($coupon, array('method' => 'PUT', 'route' => array('backend.coupons.update', $coupon->id))) }}
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.coupon.edit' , ['name'=>$coupon->code])}} </h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.coupons.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="col">
                    <div class="row mb-10">
                        <label for="code"
                               class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.code')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">

                            <input autocomplete="off" type="text" class="form-control" id="code" readonly
                                   name="code" value="{{old('code',$coupon->code)}}"/>
                            @error('code') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-10">
                        <label for="max_use"
                               class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.max_use')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="row">

                                <div class="mb-0 col-12">
                                    <input class="form-control form-control"
                                           id="max_use" name="max_use" type="number" min="1"
                                           value="{{old('max_use', $coupon->max_use)}}"/>
                                    @error('max_use') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-10">
                        <label for="per_user"
                               class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.per_user')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="row">

                                <div class="mb-0 col-12">
                                    <input class="form-control form-control"
                                           id="per_user" name="per_user" type="number" min="1"
                                           value="{{old('per_user',$coupon->per_user)}}"/>
                                    @error('per_user') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-10">
                        <label for="discount"
                               class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.discount')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="row">

                                <div class="mb-0 col-10">
                                    <input class="form-control form-control"
                                           id="discount" name="discount" type="number" step="0.01" min="1" value="{{old('discount', $coupon->discount)}}"/>
                                    @error('discount') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                                </div>
                                <div class="mb-0 col-2">
                                    <select class="form-control" id="discount_type" name="discount_type"
                                            data-control="select2"
                                            data-placeholder="Type">
                                        <option selected value="{{null}}"></option>
                                        @foreach($discount_types as $type)
                                            <option value="{{$type}}" {{old('discount_type', $coupon->discount_type) == $type? 'selected':'' }} >{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-10">
                        <label for="type"
                               class="col-lg-4 col-form-label required">{{trans('backend.coupon.type')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <select class="form-control" id="type" name="type" data-control="select2"
                                    data-placeholder="Select an option">
                                <option selected value="{{null}}"></option>

                                @foreach($types as $type)
                                    <option value="{{$type}}" {{old('type',$coupon->type ) == $type? "selected":""}}>{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div id="product" class=" flex-row-fluid mb-2 ">

                </div>
                <div class="col">
                    <div class="row mb-10">
                        <label for="product_daterangepicker"
                               class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.dates')}}</label>
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <div class="mb-0">
                                <input class="form-control date-picker form-control"
                                       id="date-picker" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class=" mb-10">
                        <div class="form-check form-switch form-check-custom form-check-solid me-10">
                            <input class="form-check-input h-20px w-30px" @if(old('status',$coupon->status == 1)) checked @endif type="checkbox" value="1"
                                   name="status" id="status"/>
                            <label class="form-check-label" for="status">
                                {{trans('backend.coupon.status')}}
                            </label>
                        </div>
                    </div>
                </div>
                                <input type="hidden" id="start_date" name="start_date" value="{{old('starts_at',$coupon->starts_at)}}">
                                <input type="hidden" id="end_date" name="end_date" value="{{old('ends_at',$coupon->ends_at)}}">
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
@section('script')
    <script>
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        $(document).on("change", '#type', function () {
            var type = $(this).val();

            if (type === "Product") {
                var html = '<div class="col"> <div class="col"> <div class="row mb-10"><label for="products"'+
                    'class="col-lg-4 col-form-label required">{{trans('backend.coupon.products')}}</label>'+
                '<div class="col-lg-8 fv-row fv-plugins-icon-container">'+
                    '<select class="form-control" id="products" name="products_ids[]" data-control="select2" data-placeholder="Select an option" multiple="multiple">'+
                        '<option value="{{null}}"></option>'+
                        @foreach($products as $product)
                       ' <option value="{{$product->id}}" @if($coupon->type == 'Product'){{in_array( $product->id, old('products_ids', $coupon->products_ids))? "selected":"" }} @endif>{{$product->sku}} </option>'+
                        @endforeach
                   ' </select> </div> </div> </div></div>'
                $('#product').html(html)
                $('#products').select2()
            }

            if (type === "Order") {
                var html = '<div class="col"> <div class="row mb-10"> <label for="minimum_shopping"class="col-lg-4 col-md-6 col-form-label form-label required">'+
                '{{trans('backend.coupon.minimum_shopping')}}'+
                '</label> <div class="col-lg-8 fv-row fv-plugins-icon-container"> <input autocomplete="off" type="number"  class="form-control" id="minimum_shopping"'+
                         ' name="minimum_shopping" step="0.01" value="{{old('minimum_shopping', $coupon->minimum_shopping)}}"/>'+
                    '@error('minimum_shopping') <b class="text-danger"><iclass="las la-exclamation-triangle"></i> {{$message}} </b> @enderror</div></div> </div>'
                $('#product').html(html)
            }
        });

        $('.date-picker').daterangepicker({
            "maxYear": null,


            "locale": {
                "format": "YYYY-MM-DD hh:mm A",
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate":"{{old('start_date' ,$coupon->starts_at)}}",
            "endDate": "{{old('end_date' ,$coupon->ends_at)}}",
        }, function (start, end, label) {
            // $star_date = start.format('YYYY-MM-DD hh:mm A')
            $('#start_date').val(start.format('YYYY-MM-DD hh:mm A'));
            $('#end_date').val(end.format('YYYY-MM-DD hh:mm A'));
        });
        $(document).ready(function () {
            var type = $("#type").val();
            $('#type').val(type).change();


        })
    </script>
@endsection
