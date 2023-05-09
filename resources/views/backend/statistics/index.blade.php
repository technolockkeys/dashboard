@extends('backend.layout.app')
@section('title',trans('backend.menu.statistics').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link href="{{asset('backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.statistics')}}</h3>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">

                    <div class="col-6 col-md-4 col-lg-3 col-xl-3 form-group">
                        <label for="reports"
                               class="col-form-label form-label">{{trans('backend.statistic.reports')}}</label>
                        <select class="form-control change_statistic" name="reports" data-control="select2"
                                id="reports">
                            <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                            @foreach($reports as $key =>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3  form-group">
                        <label class="col-form-label form-label">{{trans('backend.global.time')}}</label>
                        <input class="form-control change_statistic" name="date"
                               placeholder="{{trans('backend.global.select_time_range')}}" id="date"/>
                        <input type="hidden" name="start_date"
                               placeholder="{{trans('backend.global.select_time_range')}}" id="start_date"/>
                        <input type="hidden" name="end_date" placeholder="{{trans('backend.global.select_time_range')}}"
                               id="end_date"/>
                    </div>
                </div>


            </div>
            <!--end::Card Body-->
        </div>

        {{--        <div class="row gx-9 gy-6">--}}
        {{--            <canvas id="sales_chart"></canvas>--}}
        {{--        </div>--}}
        <div class="card mb-5 mb-xl-10" id="content">


            <div class="card-body">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('backend/plugins/global/plugins.bundle.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var csrf_token = '{{csrf_token()}}'

        var routes = {
            get_category : '{{route('backend.statistics.show_categories_chart')}}',
            get_product_sales : '{{route('backend.statistics.show_categories_chart')}}',
            get_product_stock : '{{route('backend.statistics.show_categories_chart')}}'
        }
        var start = moment().subtract(29, "days");
        var end = moment();

        cb(start, end);
        $("#date").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "{{trans('backend.global.today')}}": [moment().subtract(1, "days"), moment()],
                "{{trans('backend.global.yesterday')}}": [moment().subtract(1, "days"), moment().subtract(1, "days")],
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

        function cb(start, end) {
            $("#date").html(start.format("dd/mm/yyyy") + " - " + end.format("dd/mm/yyyy"));
        }

    </script>

    <script src="{{asset('backend/js/statistics.js')}}"></script>
    {{--    sales region script start--}}
    {{--    sales region script ends--}}

@endsection
