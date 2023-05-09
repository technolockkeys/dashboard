{{--<div class="card mb-5 mb-xl-10">--}}
{{--    <div class="card-header">--}}
{{--        <div class="card-title">--}}
{{--            <h3>{{trans('backend.statistic.top_selling_categories')}}</h3>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="card-body">--}}
{{--        <div class="table-responsive">--}}
{{--            <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">--}}
{{--                <thead>--}}
{{--                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">--}}
{{--                    <th>{{trans('backend.global.id')}}</th>--}}
{{--                    <th>{{trans('backend.category.name')}}</th>--}}
{{--                    <th>{{trans('backend.menu.products')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                @foreach($categories as $key => $category )--}}
{{--                    <tr>--}}
{{--                        <td>{{$key + 1}}</td>--}}
{{--                        <td>--}}
{{--                            <a href="{{route('backend.categories.edit', ['category'=>$category->id])}}"--}}
{{--                               class="fw-bolder text-primary text-hover-primary">{{json_decode($category->name,true)[app()->getLocale()]}}</a>--}}
{{--                            </td>--}}
{{--                        <td>{{$category->sold_quantity}} {{$category->days}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="card mb-5 mb-xl-10">--}}
{{--    <div class="card-header">--}}
{{--        <div class="card-title">--}}
{{--            <h3>{{trans('backend.statistic.order_count')}}</h3>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="card-body">--}}
        <div class="row gx-9 gy-6">
            <canvas id="sales_chart"></canvas>
        </div>
{{--    </div>--}}


{{--</div>--}}

<script>

    var date_array = [];
    var average_sales = [];
    var sold_quantity = [];
    var name = [];

    @foreach($categories as $data)
    date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");

    sold_quantity.push({{$data['sold_quantity']}});

    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [
            {
                label: "{{trans('backend.product.sold_quantity')}}",
                data: sold_quantity,
                fill: false,
                borderColor: 'rgb(0, 192, 0)',
                tension: 0.1
            },
            {{--{--}}
            {{--    label: "{{trans('backend.order.failed')}}",--}}
            {{--    data: name,--}}
            {{--    fill: false,--}}
            {{--    borderColor: 'rgb(200, 0, 0)',--}}
            {{--    tension: 0.1--}}
            {{--},--}}
            ]
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

