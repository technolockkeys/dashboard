@extends('backend.layout.app')
@section('title',trans('backend.menu.products').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card  flex-row-fluid mb-2">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.products')}}</h3>
                <div class="card-toolbar">
                    {!! @$create_button !!}
                </div>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-row-bordered gy-5 gs-7">
                    <div class="row">
                        {!! select_status() !!}
                        {{--                        {!! multi_select_2('type', $types,'backend.product.type') !!}--}}
                        {!! select_bool('is_featured'       ,'backend.product.is_featured') !!}
                        {!! select_bool('is_super_sales'    ,'backend.product.is_super_sales') !!}
                        {!! select_bool('is_best_seller'    ,'backend.product.is_best_seller') !!}
                        {!! select_bool('is_free_shipping'  ,'backend.product.is_free_shipping') !!}
                        {!! select_bool('is_on_sale'  ,'backend.product.is_on_sale') !!}
                        {!! select_bool('discount_offer'  ,'backend.product.discount_offer') !!}
                        {!! select_bool('is_saudi_branch'  ,'backend.product.is_saudi_branch') !!}
                        {!! select_bool('price_is_hidden'  ,'backend.product.price_is_hidden') !!}
                        {!! select_bool('is_bundle'  ,'backend.product.is_bundle') !!}
                        {!! select_bool('has_serial_numbers'  ,'backend.product.has_serial_numbers') !!}
                        {!! multi_select_2('category', $categories, 'backend.menu.categories') !!}
                        {!! multi_select_2('manufacturer', $manufacturers, 'backend.menu.manufacturers') !!}
                        {!! multi_select_2('manufacturer_type', $manufacturer_types, 'backend.product.manufacturer_type') !!}
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="brand"
                                   class="col-form-label form-label">{{trans('backend.product.brand')}}</label>
                            <select class="form-control form-control-sm" name="brand" data-control="select2" id="brand">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                @foreach($brands as $key=> $value)
                                    <option value="{{$value->id}}">{{ $value->make}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="model"
                                   class="col-form-label form-label">{{trans('backend.product.model')}}</label>
                            <select class="form-control form-control-sm" name="model" data-control="select2" id="model">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                @foreach($models as $key=> $value)
                                    <option value="{{$value->id}}">{{ $value->model}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="year"
                                   class="col-form-label form-label">{{trans('backend.product.year')}}</label>
                            <select class="form-control form-control-sm" name="year" data-control="select2" id="year">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>

                            </select>
                        </div>

                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="quantity"
                                   class="col-form-label form-label">{{trans('backend.product.quantity')}}</label>
                            <select class="form-control form-control-sm" name="quantity" data-control="select2"
                                    id="quantity">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                <option value="normal">{{trans('backend.product.normal_quantity')}}</option>
                                <option value="low">{{trans('backend.product.low_in_store')}}</option>
                                <option value="empty">{{trans('backend.product.not_available')}}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="type"
                                   class="col-form-label form-label">{{trans('backend.product.type')}}</label>
                            <select class="form-control form-control-sm" name="type" data-control="select2"
                                    id="type">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                <option value="software">{{trans('backend.product.software')}}</option>
                                <option value="physical">{{trans('backend.product.physical')}}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="min_price"
                                   class="col-form-label form-label-sm">{{trans('backend.product.min_price')}}</label>
                            <input type="number" id="min_price" name="min_price" class="form-control form-control-sm">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="max_price"
                                   class="col-form-label form-label-sm">{{trans('backend.product.max_price')}}</label>
                            <input type="number" id="max_price" name="max_price" class="form-control form-control-sm">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2  form-group">
                            <label class="col-form-label form-label">{{trans('backend.global.time')}}</label>
                            <input class="form-control form-control-sm" name="date" placeholder="{{trans('backend.global.select_time_range')}}" id="date"/>
                            <input type="hidden" name="start_date" placeholder="{{trans('backend.global.select_time_range')}}" id="start_date"/>
                            <input type="hidden" name="end_date" placeholder="{{trans('backend.global.select_time_range')}}" id="end_date"/>
                        </div>

                        {!! apply_filter_button() !!}
                    </div>


                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th style="text-align: center"><input type="checkbox" id="select_all"/></th>
                        <th class="text-start">{{trans('backend.global.id')}}</th>
                        <th class="text-start min-w-150px">{{trans('backend.product.sku')}}</th>
                        <th class="text-start">{{trans('backend.product.title')}}</th>
                        <th class="text-start">{{trans('backend.product.short_title')}}</th>
                        <th class="text-start">{{trans('backend.product.colors')}}</th>
                        <th class="text-start">{{trans('backend.product.manufacturer')}}</th>
                        <th class="text-start">{{trans('backend.product.type')}}</th>
                        <th class="text-start">{{trans('backend.product.image')}}</th>
                        <th class="text-start">{{trans('backend.product.price')}}</th>
                        <th class="text-start">{{trans('backend.product.sale_price')}}</th>
                        <th class="text-start">{{trans('backend.product.in_stock')}}</th>
                        <th class="text-start">{{trans('backend.product.priority')}}</th>
                        <th class="text-start">{{trans('backend.product.category')}}</th>
                        <th class="text-start">{{trans('backend.global.created_at')}}</th>
                        <th class="text-start">{{trans('backend.global.updated_at')}}</th>
                        <th class="text-start">{{trans('backend.global.status')}}</th>
                        <th class="text-start">{{trans('backend.product.featured')}}</th>
                        <th class="text-start">{{trans('backend.product.super_sales')}}</th>
                        <th class="text-start">{{trans('backend.product.best_seller')}}</th>
                        <th class="text-start">{{trans('backend.product.free_shipping')}}</th>
                        <th class="text-start min-w-250px">{{trans('backend.global.actions')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $switch_script !!}
    {!!  $switch_script_featured !!}
    {!!  $switch_script_free_shipping !!}
    {!!  $switch_script_super_sales !!}
    {!!  $switch_script_best_seller !!}

    <script>
        var start = moment().subtract(29, "days");
        var end = moment();

        function cb(start, end) {
            $("#date").html(start.format("dd/mm/yyyy") + " - " + end.format("dd/mm/yyyy"));
        }

        $("#date").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "{{trans('backend.global.today')}}": [moment().subtract(1, "days"), moment().endOf("day")],
                "{{trans('backend.global.yesterday')}}": [moment().subtract(1, "days"), moment().subtract(1, "days").endOf("day")],
                "{{trans('backend.global.last_7_days')}}": [moment().subtract(6, "days"), moment()],
                "{{trans('backend.global.last_30_days')}}": [moment().subtract(29, "days"), moment()],
                "{{trans('backend.global.this_month')}}": [moment().startOf("month"), moment().endOf("month")],
                "{{trans('backend.global.last_month')}}": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            },
            locale: {
                format: 'DD-MM-YYYY'
            }

        }, function (start, end, label) {
            $('#start_date').val(start.format('DD-MM-YYYY'));
            $('#end_date').val(end.format('DD-MM-YYYY'));
        });
        cb(start, end);

        $(document).on('change', '#brand', function () {
            var brand = $(this).val();
            var url = "{{route('backend.products.brands')}}";

            $.ajax({
                url: url,
                method: "post",
                data: {
                    '_token': "{{csrf_token()}}",
                    brand: brand,
                    model: null,
                }, success(response) {
                    $("#model").empty();
                    $("#model").append("<option selected value=''>All</option>");
                    $.each(response.data.models, function (key, value) {
                        $("#model").append("<option  value='" + key + "'>" + value + "</option>");
                    });
                }
            })
        });
        $(document).on('change', '#model', function () {
            var brand = $('#brand').val();
            var model = $(this).val();
            var url = "{{route('backend.products.brands')}}";

            $.ajax({
                url: url,
                method: "post",
                data: {
                    '_token': "{{csrf_token()}}",
                    brand: brand,
                    model: model,
                }, success(response) {
                    $("#year").empty();
                    $("#year").append("<option selected value=''>All</option>");
                    $.each(response.data.years, function (key, value) {
                        $("#year").append("<option  value='" + key + "'>" + value + "</option>");
                    });
                }
            })
        });
    </script>
@endsection
