<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.order_count')}}</h3>
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
    var completed = [];
    var failed = [];
    var on_hold = [];
    var pending_payment = [];
    var processing = [];
    var refunded = [];
    @foreach($sales as $data)
        date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");
        completed.push({{$data['completed']}});
        on_hold.push({{$data['on_hold']}});
        pending_payment.push({{$data['pending_payment']}});
        processing.push({{$data['processing']}});
        refunded.push({{$data['refunded']}});
    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [
            {
                label: "{{trans('backend.order.completed')}}",
                data: completed,
                fill: false,
                borderColor: 'rgb(0,158,247)',
                tension: 0.1
            },
            {
                label: "{{trans('backend.order.failed')}}",
                data: failed,
                fill: false,
                borderColor: 'rgb(200, 0, 0)',
                tension: 0.1
            },
            {
                label: "{{trans('backend.order.on_hold')}}",
                data: on_hold,
                fill: false,
                borderColor: 'rgb(255,199,0)',
                tension: 0.1
            },{
                label: "{{trans('backend.order.pending_payment')}}",
                data: pending_payment,
                fill: false,
                borderColor: 'rgb(228,230,239)',
                tension: 0.1
            },{
                label: "{{trans('backend.order.processing')}}",
                data: processing,
                fill: false,
                borderColor: 'rgb(80,205,137)',
                tension: 0.1
            },{
                label: "{{trans('backend.order.refunded')}}",
                data: refunded,
                fill: false,
                borderColor: 'rgb(228,230,239)',
                tension: 0.1
            },]
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
