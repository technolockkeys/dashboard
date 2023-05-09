<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.device_category')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

                <div class="row gx-9 gy-6">
                    <canvas id="kt_chartjs_3" class="mh-400px"></canvas>
                </div>
            </div>


        </div>
    </div>
</div>
<script>

    // var date_array = [];
    var labels = [];
    var values = [];
    @foreach($results??[] as $index => $item)
    labels.push("{{$index}}");
    values.push("{{$item}}");
    @endforeach

    var ctx = document.getElementById('kt_chartjs_3');
    // Define colors
    var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
    var dangerColor = KTUtil.getCssVariableValue('--kt-danger');
    var successColor = KTUtil.getCssVariableValue('--kt-success');
    var warningColor = KTUtil.getCssVariableValue('--kt-warning');
    var infoColor = KTUtil.getCssVariableValue('--kt-info');

    // Define fonts
    var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

    // Chart labels

    // Chart data
    var data_sets = {
        labels: labels,
        datasets: [{
            label: '{{trans('backend.statistic.devices')}}',
            data: values,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                'rgb(201, 203, 207)',
                'rgb(54, 162, 235)'
            ]

        }
        ]
    };

    // Chart configuration
    var configuration = {
        type: 'polarArea',
        data: data_sets,
        options: {
            plugins: {
                title: {
                    display: false,
                }
            },
            responsive: true,
        },
        defaults: {
            global: {
                defaultFont: fontFamily
            }
        }
    };

    // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
    var myChart = new Chart(ctx, configuration);
</script>
