{{--<div class="card mb-5 mb-xl-10">--}}
{{--    <div class="card-header">--}}
{{--        <div class="card-title">--}}
{{--            <h3>{{trans('backend.statistic.top_selling_products')}}</h3>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="card-body">--}}
{{--        <div class="table-responsive">--}}
{{--            <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">--}}
{{--                <thead>--}}
{{--                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">--}}
{{--                    <th>{{trans('backend.global.id')}}</th>--}}
{{--                    <th>{{trans('backend.product.sku')}}</th>--}}
{{--                    <th>{{trans('backend.product.title')}}</th>--}}
{{--                    <th>{{trans('backend.product.price')}}</th>--}}
{{--                    <th>{{trans('backend.product.slug')}}</th>--}}
{{--                    <th>{{trans('backend.statistic.sold_quantity')}}</th>--}}
{{--                    <th>{{trans('backend.product.in_stock')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($products_quantity as $key => $product )--}}
{{--                    <tr>--}}
{{--                        <td>{{$key}}</td>--}}
{{--                        <td>--}}
{{--                            <div class="d-flex align-items-center">--}}
{{--                                <a href="{{route('backend.products.edit', ['product'=>$product->product?->id])}}"--}}
{{--                                   class="symbol    symbol-50px  ">--}}
{{--                                    <img class="symbol-label"--}}
{{--                                         onerror="this.src='{{media_file(get_setting('default_images'))}}'"--}}
{{--                                         src=" {{media_file($product->product?->image)}}">--}}
{{--                                </a>--}}
{{--                                <div class="ms-5">--}}
{{--                                    <span class="badge badge-lg badge-light-primary">--}}
{{--                                    <a href="{{route('backend.products.edit', ['product'=>$product->product?->id])}}"--}}
{{--                                       class="fw-bolder  text-hover-primary">{{$product->product?->sku}}</a>--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        <td>{{$product->product?->title}}</td>--}}
{{--                        <td>{{$product->product?->price}}</td>--}}
{{--                        <td>{{$product->product?->slug}}</td>--}}
{{--                        <td>{{$product->sold_quantity}}</td>--}}
{{--                        <td>{{$product->product?->quantity}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="row gx-9 gy-6">
    <canvas id="product_sales_chart"></canvas>
</div>


<script>

    var date_array = [];
    var average_sales = [];
    var sold_quantity = [];
    var name = [];

    @foreach($products_quantity as $data)
    date_array.push("{{Carbon\Carbon::parse($data['days'])->format('d/m')}}");

    sold_quantity.push({{$data['quantity']}});

    @endforeach
    var element = document.getElementById('chart');
    var data = {
        labels: date_array,
        datasets: [
            {
                label: "safd",
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
        $('#product_sales_chart'),
        config
    );

</script>
