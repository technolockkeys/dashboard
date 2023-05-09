<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.coupons')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row gx-9 gy-6">
            <canvas id="sales_chart"></canvas>
        </div>
    </div>


</div>

<script>

    var date_array = [];
    var average_sales = [];
    var coupons = [];
    var fedex = [];
    var aramex = [];
    var ups = [];
    @foreach($coupons as $data)
    date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");
    coupons.push({{$data['discount']}});
    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [
            {
                label: "{{trans('backend.order.discount')}}",
                data: coupons,
                fill: false,
                borderColor: 'rgb(0, 192, 0)',
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
