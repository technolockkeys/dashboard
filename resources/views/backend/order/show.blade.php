@extends('backend.layout.app')
@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')

    <div class="d-flex   row mb-3 mt-3  ">
        @include('orders.card.order_details')
        @include('orders.card.customer')
        @if($address != null)
            @include('orders.card.address')
        @endif
    </div>

    <div class="d-flex row mb-3">
        @include('orders.card.products')
        @if(!empty($payment_files->files)|| $order->seller != null)
            <div class="col-lg-6  col-xl-3 col-md-12 col-12">
                @include('orders.card.documents')
                @include('orders.card.seller')
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        var user = {
            user_id: '{{$order->user_id}}',
            order_id: '{{$order->id}}',
        };
        var token = '{{csrf_token()}}';
        var overview_error = {
            'name': "{{old('name' ,"")}}",
        };
        var user_routes = {
            payment_info:"{{route('backend.users.wallet.payment.info')}}",
            payment_change:"{{route('backend.users.wallet.payment.change.status')}}",
            payment_get:"{{route('backend.users.wallet.payment.get')}}",
            payment_set:"{{route('backend.users.wallet.payment.set')}}"
        };
    </script>
    @include('backend.user_wallet.script')
    <script src="{{asset('backend/js/user.js')}}"></script>
    <script>
        $('.copy_text').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            success_message('copied text: ' + copyText);
        });
        $(document).ready(function () {
            @foreach($products as $product)
                @if($product->is_bundle == 1)
                    @foreach($product->bundle_products as $bproduct)
                    $("#serial_numbers_{{$bproduct->pivot->id}}").select2({
                        maximumSelectionLength:{{$bproduct->pivot->quantity}}
                    });
                    @endforeach

                @else
                    $("#serial_numbers_{{$product->pivot->id}}").select2({
                        maximumSelectionLength:{{$product->pivot->quantity}}
                    });
                @endif
            @endforeach
        });
        $(document).on('change', '.save_order_products', function () {
            var order_product = $(this).data('orderproductid');
            var item_id = $(this).attr('id');
            var serials_numbers = $("#" + item_id).val();

            $.ajax({
                url: "{{route('backend.orders.order.update.serial.numbers')}}",
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}",
                    order_product_id: order_product,
                    serials_numbers: serials_numbers
                }
            })
        })
    </script>

@endsection
