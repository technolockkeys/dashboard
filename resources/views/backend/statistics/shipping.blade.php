<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.shipping')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class=" col-2 col-md-2 col-xl-2 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true" data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">${{$total_dhl}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.order.dhl')}}</div>
                    <!--end::Label-->
                </div>
            </div>
            <div class=" col-2 col-md-2 col-xl-2 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true" data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">${{$total_fedex}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.order.fedex')}}</div>
                    <!--end::Label-->
                </div>
            </div>
            <div class=" col-2 col-md-2 col-xl-2 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true" data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">${{$total_aramex}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.order.aramex')}}</div>
                    <!--end::Label-->
                </div>
            </div>
            <div class=" col-2 col-md-2 col-xl-2 mb-6">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                    <!--begin::Number-->
                    <div class="d-flex align-items-center ">
                        <img style='width: 20%' src="{{asset('backend/media/icons/duotune/finance/fin003.svg')}}"/>
                        <!--end::Svg Icon-->
                        <div class="fs-2 fw-bold counted px-3 text-primary" data-kt-countup="true" data-kt-countup-value="4500"
                             data-kt-countup-prefix="$" data-kt-initialized="1">${{$total_ups}}</div>
                    </div>
                    <!--end::Number-->
                    <!--begin::Label-->
                    <div class="fw-semibold fs-4 ">{{trans('backend.order.ups')}}</div>
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
    var dhl = [];
    var fedex = [];
    var aramex = [];
    var ups = [];
    @foreach($shipping as $data)
    date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");
    dhl.push({{$data['dhl']}});
    fedex.push({{$data['fedex']}});
    aramex.push({{$data['aramex']}});
    ups.push({{$data['ups']}});
    @endforeach

    var data = {
        labels: date_array,
        datasets: [
            {
                label: "{{trans('backend.order.dhl')}}",
                data: dhl,
                fill: false,
                borderColor: 'rgb(0, 192, 0)',
                tension: 0.1
            },
            {
                label: "{{trans('backend.order.fedex')}}",
                data: fedex,
                fill: false,
                borderColor: 'rgb(200, 0, 0)',
                tension: 0.1
            },
            {
                label: "{{trans('backend.order.aramex')}}",
                data: aramex,
                fill: false,
                borderColor: 'rgb(0, 192, 255)',
                tension: 0.1
            }, {
                label: "{{trans('backend.order.ups')}}",
                data: ups,
                fill: false,
                borderColor: 'rgb(128, 128, 128)',
                tension: 0.1
            },
        ]
    };
    var config = {
        type: 'line',
        data: data,
        options: {
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px'
                    },

                    formatter: function (val) {
                        return "$" + val
                    }
                }


            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$" + val
                    }
                }
            },
        }
    };
    var myChart = new Chart(
        $('#sales_chart'),
        config
    );

</script>
