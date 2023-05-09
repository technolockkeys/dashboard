@if(auth('admin')->check())
    @php $layout= 'backend.layout.app' ;  @endphp
@else
    @php $layout= 'seller.layout.app' ;  @endphp
@endif
@extends($layout)
@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')

    <div class="d-flex   row mb-3 mt-3  ">
        @include('orders.card.order_details')
        @include('orders.card.customer')
        {{--        @include('orders.card.seller')--}}
        @if($address != null)
            @include('orders.card.address')
        @endif
    </div>

    <div class="d-flex row mb-3">
        @include('orders.card.products')
        @if(!empty($payment_files->files) )
            <div class="col-lg-12  col-xl-12 col-md-12 col-12">
                @include('orders.card.documents')

            </div>
        @endif
    </div>
@endsection

@section('script')
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
        $('#stripe_link_payment_btn').click(function (e) {
            e.preventDefault();
            var copyText = $("#stripe_link_value").val();

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
                maximumSelectionLength: {{$bproduct->pivot->quantity}}
            });
            @endforeach

            @else
            $("#serial_numbers_{{$product->pivot->id}}").select2({
                maximumSelectionLength: {{$product->pivot->quantity}}
            });
            @endif
            @endforeach

            @if(  in_array($order->status,[\App\Models\Order::$processing ,\App\Models\Order::$completed] ))
            $("#tracking_number_dev").show();
            @else
            $("#tracking_number_dev").hide();
            @endif
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
    <script>

        $(document).on('change', "#order_statuses", function () {
            check_status();
        });
        $(document).on('submit', '#proforma_to_order', function (event) {
            event.preventDefault();

            var button = $(this).find(":submit");
            button.attr('disabled', true);
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "post",
                data: $(this).serialize(),
                success: function (response) {
                    button.removeAttr('disabled', false)
                    success_message(response.data.message);

                    if (response.data.reload == 1) {
                        $("#kt_modal_1").modal('hide');
                        location.reload();
                    } else {
                        $("#stripe_link_dev").css('display', 'block')
                        $("#stripe_link_value").val(response.data.stripe_link);
                    }
                    $("#submit_save_convert").hide();
                    $("#submit_save_convert").remove();
                },
                error: function (response) {
                    button.attr('disabled', false);
                }
            })
        })
        check_status();

        function check_status() {
            var status = $("#order_statuses").val();
            var payment_status = $("#payment_status").val();
            @if($order->payment_status == \App\Models\Order::$payment_status_unpaid)
            if (status == "{{\App\Models\Order::$processing}}" || status == "{{\App\Models\Order::$completed}}") {
                $("#div_payment_status").show()

            } else {
                $("#payment_status").val("{{\App\Models\Order::$payment_status_unpaid}}").trigger('change');
                $("#div_payment_status").hide();

            }
            @endif
            if (status == "{{\App\Models\Order::$processing}}" || status == "{{\App\Models\Order::$completed}}") {
                $("#tracking_number_dev").show()
            } else {
                $("#tracking_number_dev").hide();
            }
        }


    </script>

@endsection
