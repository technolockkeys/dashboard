<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.net_revenue')}}</h3>
        </div>
    </div>

    <div class="card-body">
        <div class="row">

            <div class=" col-3 col-md-3 col-xl-3 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true"
                             data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">
                            ${{number_format($total_revenue,2,'.')}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.statistic.total_revenue')}}</div>
                    <!--end::Label-->
                </div>
            </div>
            <div class=" col-3 col-md-3 col-xl-3 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true"
                             data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">
                            ${{number_format($avg_daily_revenue,2,'.')}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.statistic.avg_daily_revenue')}}</div>
                    <!--end::Label-->
                </div>
            </div>
        </div>
        <div class="row gx-9 gy-6">
            <canvas id="sales_chart"></canvas>
        </div>
    </div>


</div>

<script>

    var date_array = [];
    var average_sales = [];
    var total_sales = [];
    @foreach($sales as $data)
    date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");
    total_sales.push({{$data['sum']}});
    average_sales.push({{$data['average']}});
    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [{
            label: "{{trans('backend.statistic.total_sales')}}",
            data: total_sales,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }
            , {
                label: "{{trans('backend.statistic.average')}}",
                data: average_sales,
                fill: false,
                borderColor: 'rgb(75, 192, 0)',
                tension: 0.1
            }]
    };
    var config = {
        type: 'line',
        data: data,
        options: {}
    };
    var myChart = new Chart(
        $('#sales_chart'),
        config
    );

</script>
