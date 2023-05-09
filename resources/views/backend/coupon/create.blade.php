@extends('backend.layout.app')
@section('title',trans('backend.menu.coupons').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.coupons.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.coupon.create_new_coupon')}}</h3>
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
                                        name='code' value="{{old('code', $code)}}"/>
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
                                               value="{{old('max_use')}}"/>
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
                                               value="{{old('per_user')}}"/>
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
                                               id="discount" name="discount" type="number" step="0.001" min="1"
                                               value="{{old('discount')}}"/>
                                        @error('discount') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                                    </div>

                                    <div class="mb-0 col-2">
                                        <select class="form-control" id="discount_type" name="discount_type" required
                                                data-control="select2"
                                                data-placeholder="Type">
                                            <option selected value="{{null}}"></option>
                                            @foreach($discount_types as $type)
                                                <option value="{{$type}}"
                                                        {{old('discount_type') == $type? "selected":"" }}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                        @error('discount_type') <b class="text-danger"><i
                                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

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
                                <select class="form-control" id="type" name="type" data-control="select2" required
                                        data-placeholder="Select an option">
                                    <option value="{{null}}"></option>
                                    @foreach($types as $type)
                                        <option value="{{$type}}" {{old('type') == $type? "selected":""}}>{{$type}}</option>
                                    @endforeach
                                </select>
                                @error('type') <b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>

                    </div>

                    <div id="product" class=" flex-row-fluid mb-2 @if(old('type') != 'Product')d-none @endif ">

                        <div class="col">
                            <div class="col">
                                <div class="row mb-10">
                                    <label for="products"
                                           class="col-lg-4 col-form-label required">{{trans('backend.coupon.products')}}</label>
                                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                        <select class="form-control" id="products" name="products_ids[]"
                                                data-control="select2"
                                                data-placeholder="Select an option" multiple="multiple">
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}" @if(old('type') == 'Product') {{in_array($product->id, old('products_ids'))? "selected":""}} @endif>{{$product->sku}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('products_ids') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="order" class=" flex-row-fluid mb-2 @if(old('type') != 'Order')d-none @endif">

                        <div class="col">
                            <div class="row mb-10">
                                <label for="minimum_shopping"
                                       class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.minimum_shopping')}}</label>
                                <div class="col-lg-8 fv-row fv-plugins-icon-container">

                                    <input autocomplete="off" type="number" class="form-control"
                                           id="minimum_shopping"
                                           name="minimum_shopping" step="0.01" value="{{old('minimum_shopping')}}"
                                    />
                                    @error('minimum_shopping') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col">
                        <div class="row mb-10">
                            <label for="date-picker "
                                   class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.dates')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container ">
                                <div class="mb-0">
                                    <input class="form-control date-picker form-control"
                                           id="date-picker" name="dates-picker" value="{{old('dates-picker')}}"/>
                                </div>
                            </div>
                            <input type="hidden" id="start_date" name="start_date" value="{{date("Y-m-d H:i:s")}}">
                            <input type="hidden" id="end_date" name="end_date" value="{{date("Y-m-d H:i:s")}}">
                        </div>
                    </div>
                    <div class="col">
                        <div class=" mb-10">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px" @if(old('status')==1) checked @endif type="checkbox" value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.coupon.status')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        $(document).on("change", '#type', function () {
            var type = $(this).val();
            if (type === "Product") {
                $('#product').removeClass('d-none');
                $('#products').attr("required", "required");
                $('#order').addClass('d-none');
                console.log($('#Product'));

            }
            if (type === "Order") {
                $('#order').removeClass('d-none');
                $('#product').addClass('d-none');
                $('#products').removeAttr('required');

            }
        });

        $('.date-picker').daterangepicker({
            "maxYear": null,

            "locale": {
                "format": "YYYY-MM-DD hh:mm A",
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": "{{old('start_date' ,date('Y-m-d H:i:s'))}}",
            "endDate": "{{old('end_date' ,date('Y-m-d H:i:s'))}}",
        }, function (start, end, label) {
            $('#start_date').val(start.format('YYYY-MM-DD hh:mm A'));
            $('#end_date').val(end.format('YYYY-MM-DD hh:mm A'));
        });

    </script>
@endsection
