<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.users')}}</h3>
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
    var users = [];
    var new_users = [];
    @foreach($result['date'] as $index=> $data)
    date_array.push("{{$data}}");
    users.push({{$result['users'][$index]}});
    new_users.push({{$result['new_users'][$index]}});
    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [{
            label: "{{trans('backend.statistic.users')}}",
            data: users,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }
            ,{
                label: "{{trans('backend.statistic.new_users')}}",
                data: new_users,
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
