@extends('backend.layout.app')
@section('title',trans('backend.menu.offers').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.offers.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.offer.create_new_offer')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.offers.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col">
                        <div class="row mb-10">
                            <label for="from"
                                   class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.offer.from')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">

                                <input autocomplete="off" type="number" step="0.01" class="form-control values" id="from"
                                       name='from' value="{{old('from')}}"/>
                                <b class="text-danger" id="value-from">@error('from') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror</b>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row mb-10">
                            <label for="to"
                                   class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.offer.to')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="row">

                                    <div class="mb-0 col-12">
                                        <input class="form-control values"
                                               id="to" name="to" type="number" step="0.01" min="1"
                                               value="{{old('to')}}"/>
                                        <b class="text-danger" id="value-to">@error('to') <b class="text-danger"><i
                                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row mb-10">
                            <label for="days"
                                   class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.offer.days')}}</label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <div class="row">

                                    <div class="mb-0 col-12">
                                        <input class="form-control form-control"
                                               id="days" name="days" type="number" min="1"
                                               value="{{old('days')}}"/>
                                        @error('days') <b class="text-danger"><i
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
                                                <option value="{{$product->id}}"@if(old('type') == 'Product')
                                                    {{in_array($product->id, old('products_ids'))? "selected":""}}
                                                        @endif>{{$product->sku}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('products_ids') <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                                </div>

                            </div>
                        </div>
                    </div>
{{--                    <div id="order" class=" flex-row-fluid mb-2 @if(old('type') != 'Order')d-none @endif">--}}

{{--                        <div class="col">--}}
{{--                            <div class="row mb-10">--}}
{{--                                <label for="minimum_shopping"--}}
{{--                                       class="col-lg-4 col-md-6 col-form-label form-label required">{{trans('backend.coupon.minimum_shopping')}}</label>--}}
{{--                                <div class="col-lg-8 fv-row fv-plugins-icon-container">--}}

{{--                                    <input autocomplete="off" type="number" class="form-control"--}}
{{--                                           id="minimum_shopping"--}}
{{--                                           name="minimum_shopping" step="0.01" value="{{old('minimum_shopping')}}"--}}
{{--                                    />--}}
{{--                                    @error('minimum_shopping') <b class="text-danger"><i--}}
{{--                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}


                    <div class="col">
                        <div class="mb-10">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px" @if(old('free_shipping')==1) checked
                                       @endif type="checkbox" value="1"
                                       name="free_shipping" id="free_shipping"/>
                                <label class="form-check-label" for="free_shipping">
                                    {{trans('backend.offer.free_shipping')}}
                                </label>
                            </div>
                        </div>
                        <div class=" mb-10">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px" @if(old('status')==1) checked
                                       @endif type="checkbox" value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.offer.status')}}
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
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

            }
            if (type === "Order") {
                $('#order').removeClass('d-none');
                $('#product').addClass('d-none');
                $('#products').removeAttr('required');

            }
        });

        $('.values').on('change', function () {
            // console.log()
            var input = $(this).attr('id')
            $.ajax({
                url: '{{route('backend.offers.check_values')}}',
                method: 'post',
                data:{
                    _token : "{{ csrf_token() }}",
                    value: $(this).val()
                },
                success: function (response) {
                    $('#value-'+input).text('');

                }.bind(input),
                error: function (response )  {
                    $('#value-'+ input).text(response.responseJSON.message);

                }.bind(input)
            });
        });
    </script>
@endsection
