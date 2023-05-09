<div class=" col-md-6 col-lg-4 col-12 mt-3   ">
    <div class="card card-flush  h-100  ">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.order.details')}}</span>
                <span class="text-gray-400 mt-1 fw-bold fs-6">#{{$order->uuid}}</span>

            </h3>

            <div class="card-toolbar">
                {!! $qr !!}
            </div>
        </div>
        <div class="card-body pt-5">
            {{-- created at --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fw-bold fs-6 me-2">  {{trans('backend.global.created_at')}}</div>
                <div class="d-flex align-items-senter">

                    <span class="    text-gray-900   fs-6    fs-6">{{$order->created_at}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            {{-- type --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.type')}}</div>
                <div class="d-flex align-items-senter">

                    <span
                        class="text-gray-900 fw-boldest fs-6   ">
                        @php $class = $order->type == \App\Models\Order::$proforma? 'primary':'success' @endphp
                        <span class="badge-light-{{$class}} py-1 px-2 rounded-2">    {{$order->type}}

                            @if( $order->type != \App\Models\Order::$proforma)
                                @php $class = $order->payment_status == \App\Models\Order::$payment_status_unpaid || $order->payment_status == \App\Models\Order::$payment_status_failed  ? 'danger':'success' @endphp
                                <span class="text-secondary">/</span>

                                <span class="text-{{$class}} ">
                                    {{  $order->payment_status == \App\Models\Order::$payment_status_unpaid?  trans('backend.order.unpaid') : ($order->payment_status == \App\Models\Order::$payment_status_paid? trans('backend.order.paid')  : trans('backend.order.failed')) }}
                                    <span class="text-secondary">/</span>
                                    {{trans('backend.order.'.$order->status)}}
                                </span>

                            @endif

                        </span>
      @if($order->payment_status == \App\Models\Order::$payment_status_failed)

                            <span class="badge-light-danger mt-1 py-1 px-2 rounded-2">
                            {{  \App\Models\OrderPayment::where('order_id' , $order->id)->first()?->payment_details}}
                            </span>
                        @endif

                </span>
                </div>
            </div>


            <div class="separator separator-dashed my-3"></div>
            {{-- shipping method --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.shipping_method')}}</div>
                <div class="d-flex align-items-senter">

                    <span
                        class="text-gray-900 fw-boldest fs-6">

                        <span class="badge-light-warning py-1 px-2 rounded-2">    {{    $order->shipping_method}}</span>
                </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>

            {{-- shipment_value --}}
            @if($order->shipment_value)
                <div class="d-flex flex-stack">
                    <div class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.shipment_value')}}</div>
                    <div class="d-flex align-items-senter">
                        <span class="  text-gray-900  fs-6 me-2  ">{{$order->shipment_value}}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            @endif

            @if($order->shipment_description)

                {{-- shipment_description --}}
                <div class="d-flex flex-stack">
                    <div
                        class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.shipment_description')}}</div>
                    <div class="d-flex align-items-senter">

                    <span
                        class="  text-gray-900   fs-6 me-2   fs-6">

                           {{$order->shipment_description}}
                </span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            @endif
            @if(!empty($coupon))
                <div class="d-flex flex-stack">
                    <div
                        class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.coupon')}}</div>
                    <div class="d-flex align-items-senter">

                    <span
                        class="  text-gray-900   fs-6 me-2   fs-6">
                        <span
                            class="badge badge-primary">#{{$coupon->code}} /{{$coupon->type}} / {{ $coupon->discount_type == \App\Models\Coupon::$Amount ? currency($coupon->discount)  : $coupon->discount_type." %" }} </span>
                </span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            @endif

            @if($order->weight)

                {{-- shipment_description --}}
                <div class="d-flex flex-stack">
                    <div class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.product.weight')}} </div>
                    <div class="d-flex align-items-senter">

                    <span
                        class="  text-gray-900   fs-6 me-2   fs-6">

                           {{$order->weight}}KG
                </span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            @endif

            {{--payment method--}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2"> {{trans('backend.order.payment_method')}}</div>
                <div class="d-flex align-items-senter">

                    <span
                        class="text-gray-900 fw-boldest fs-6">
                        @if($order->payment_method == \App\Models\Order::$stripe)

                            @if(!empty($order->card_information()?->last_four))
                                <span
                                    class="mx-2">**** **** ****  {{$order->card_information()?->last_four}}

                                    {{--                                    @if(!empty($order->card_information()->brand) &&   ($order->card_information()?->brand == 'MasterCard' || $order->card_information()?->brand  == 'Visa') )--}}
                                    {{--                                        <img alt="{{$order->card_information()?->brand}}"--}}
                                    {{--                                             src="{{asset('backend/assets/media/svg/card-logos/'.strtolower($order->card_information()?->brand).'.svg')}}"--}}
                                    {{--                                             width="25" alt="">--}}
                                    {{--                                    @else--}}
                                        <span
                                            class=" badge badge-light-dark"> {{$order->card_information()?->brand }} </span>
{{--                                    @endif --}}
                                </span>
                            @endif
                        @elseif($order->payment_method == \App\Models\Order::$paypal)
                            <span
                                class="  badge badge-light-dark py-1 px-2 "> <i
                                    class="la text-primary fs-1 la-cc-paypal"></i>  {{trans('seller.orders.paypal')}}</span>

                        @elseif($order->payment_method == \App\Models\Order::$stripe_link)
                            <span
                                class=" badge badge-light-dark py-1 px-2 "> <i class="la fs-1 la-cc-stripe"></i>  {{trans('seller.orders.stripe_link')}}</span>
                        @elseif($order->payment_method == \App\Models\Order::$transfer)
                            <span
                                class="badge badge-light-dark py-1 px-2 rounded-2">  <i
                                    class="la fs-1 la-money-bill-wave-alt"></i>  {{trans('seller.orders.transfer')}}</span>

                        @endif
                </span>
                </div>
            </div>


        </div>
    </div>


</div>

