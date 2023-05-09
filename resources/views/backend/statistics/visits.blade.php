<link href="{{asset('backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>


<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.visits')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row gx-9 gy-6">
            <div class="statistics-widget-3-chart card-rounded-bottom" id="visits_chart" data-kt-chart-color="success"
                 style="height: 500px"></div>
        </div>
    </div>


</div>

<script>
    var date_array = [];
    var visits_array = [];
    var views_array = [];
    @foreach($analyticsData as $data)
    console.log("{{$data['date']}}", {{$data['visitors']}})
    date_array.push("{{Carbon\Carbon::parse($data['date'])->format('d/m')}}");
    visits_array.push({{$data['visitors']}});
    views_array.push({{$data['pageViews']}});
    @endforeach
    var element = document.getElementById('visits_chart');

    var height = parseInt(KTUtil.css(element, 'height'));
    var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
    var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
    var baseColor = KTUtil.getCssVariableValue('--bs-success');
    var baseLightColor = KTUtil.getCssVariableValue('--bs-light-success');
    var secondaryColor = KTUtil.getCssVariableValue('--bs-warning');
    var secondaryLightColor = KTUtil.getCssVariableValue('--bs-light-warning');

    var fun = function () {
        var options = {
            chart: {
                fontFamily: "inherit",
                type: "area",
                height: height,
                toolbar: false,
                zoom: false,
                sparkline: false,
            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 0.1
            },
            series: [
                {
                    name: 'views',
                    data: views_array
                },
                {
                    name: 'visits',
                    data: visits_array
                }
            ],
            xaxis: {
                labels: {show: !1},
                tooltip: {enabled: !0, formatter: void 0, offsetY: 0, style: {fontSize: "12px"}},
                categories: date_array
            },
            colors: [baseColor, secondaryColor],
            yaxis: {min: 0, labels: {show: 0}},
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false
            },
            grid: {
                strokeDashArray: 4,
                padding: {top: 0, right: -20, bottom: -20, left: -20},
                yaxis: {lines: {show: false}}
            },
            markers: {
                colors: [baseLightColor, secondaryLightColor],
                strokeColor: [baseLightColor, secondaryLightColor],
                strokeWidth: 3
            }
        }
        var chart = new ApexCharts(document.querySelector("#visits_chart"), options);
        chart.render();
    }
    fun();
</script>