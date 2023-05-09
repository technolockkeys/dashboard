@extends('seller.layout.app')
@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('seller.order.data.orders_details')
        </div>

        <div class="col-12 mt-3 col-md-12">
            <div class="card   flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.menu.orders')}}</h3>
                    <div class="card-toolbar">
                        @if(permission_can('create order', 'seller'))
                            {!! @$create_button !!}
                        @endif
                    </div>
                </div>
                <!--begin::Card Body-->
                <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

                    <div class="row">
                        {!! multi_select_2('user', $users, trans('backend.order.user')) !!}
                        {!! multi_select_2('type', $types, trans('backend.order.type')) !!}

                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="status" class="col-form-label form-label">{{trans('backend.order.status')}}</label>
                            <select class="form-control form-control-sm" name="status" data-control="select2" id="status">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                @foreach($statuses as $key=> $status)
                                    <option value="{{$key}}" @if(request()->status_filter == $key)selected @endif>{{ $status}}</option>
                                @endforeach
                            </select>
                        </div>
                        {!! multi_select_2('payment_method', $payment_methods, trans('backend.order.payment_method')) !!}
                        {!! multi_select_2('payment_status', $payment_statuses, trans('backend.order.payment_status')) !!}
                        {!! multi_select_2('shipping_method', $shipping_methods, 'backend.order.shipping_method') !!}
                        {!! multi_select_2('product', $products, 'backend.menu.products') !!}
                        {!! select_bool('coupon', trans('backend.order.coupon')) !!}
                        {!! select_bool('is_free_shipping', trans('backend.order.is_free_shipping')) !!}
                        <div class="col-6 col-md-3 col-lg-2  form-group">
                            <label class="col-form-label form-label">{{trans('backend.global.time')}}</label>
                            <input class="form-control form-control-sm" name="date"
                                   placeholder="{{trans('backend.global.select_time_range')}}" id="date"/>
                            <input type="hidden" name="start_date"
                                   placeholder="{{trans('backend.global.select_time_range')}}" id="start_date"/>
                            <input type="hidden" name="end_date" placeholder="{{trans('backend.global.select_time_range')}}"
                                   id="end_date"/>
                        </div>
                        {!! multi_select_2('currency', $currencies, trans('backend.menu.currencies')) !!}
                    </div>
                    {!! apply_filter_button() !!}

                    <div class="table-responsive">
                        <table id="datatable" class="table  table-striped border gy-7 w-100 gs-7">
                            <thead>
                            <tr class=" text-center fw-bold fs-6 text-gray-800">
                                <th>{{trans('backend.global.id')}}</th>
                                <th  class="text-center">{{trans('backend.global.created_at')}}</th>
                                <th  class="text-center">{{trans('backend.global.uuid')}}</th>
                                <th  class="text-center">{{trans('seller.orders.user')}}</th>
                                <th  class="text-center">{{trans('seller.orders.type')}}</th>
                                <th  class="text-center min-w-150px">{{trans('seller.orders.payment_method')}}</th>
                                <th  class="text-center min-w-150px">{{trans('seller.orders.payment_status')}}</th>
                                <th  class="text-center ">{{trans('backend.order.status')}}</th>
                                <th  class="text-center min-w-150px">{{trans('seller.orders.total')}}</th>
                                <th  class="text-center min-w-150px">{{trans('backend.order.balance')}}</th>
                                <th  class="text-center min-w-150px">{{trans('seller.orders.shipping')}}</th>
                                <th  class="text-center  min-w-150px">{{trans('backend.order.seller_commission')}}</th>
                                {{--                                <th>{{trans('backend.global.status')}}</th>--}}
                                    <th class="w-250px min-w-300px">{{trans('backend.global.actions')}}</th>

                            </tr>

                            </thead>

                        </table>
                    </div>
                </div>
                <!--end::Card Body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
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

    </script>
@endsection
