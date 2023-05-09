<div class=" col-12 flex-row-fluid   ">
    <div class="card card-flush  ">
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
                <div class="text-gray-700 fw-bold fs-6 me-2">  {{trans('backend.order.user')}}</div>
                <div class="d-flex align-items-center">


                        @if($user->deleted_at != null) <span class="badge badge-lg badge-warning">
                                            {{$user->uuid}} </span> @else <span class="text-gray-900 fw-boldest fs-6">{{$user->uuid}}</span> @endif
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2">  {{trans('backend.global.created_at')}}</div>
                <div class="d-flex align-items-center">

                    <span class="text-gray-900 fw-boldest fs-6">{{$order->created_at}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2">  {{trans('backend.product.weight')}}</div>
                <div class="d-flex align-items-center">

                    <span class="text-gray-900 fw-boldest fs-6">{{$order->weight}} KG</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            {{-- type --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2"> {{trans('backend.order.type')}}</div>
                <div class="d-flex align-items-center">

                    <span
                            class="text-gray-900 fw-boldest fs-6">
                        @php $class = $order->type == \App\Models\Order::$proforma? 'primary':'success' @endphp
                        <span class="badge-light-{{$class}} py-1 px-2 rounded-2">    {{$order->type}}</span>
                </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            {{-- shipping method --}}
            @if($order->shipping_method != null)

            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2"> {{trans('backend.order.shipping_method')}}</div>
                <div class="d-flex align-items-center">

                    <span
                            class="text-gray-900 fw-boldest fs-6">

                        <span class="badge-light-warning py-1 px-2 rounded-2">    {{$order->shipping_method}}</span>
                </span>
                </div>
            </div>

            <div class="separator separator-dashed my-3"></div>
            @endif
                {{--payment method--}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2"> {{trans('backend.order.payment_method')}}</div>
                <div class="d-flex align-items-center">

                    <span
                            class="text-gray-900 fw-boldest fs-6 text-end">
                        @if($order->payment_method == \App\Models\Order::$stripe)
                            <span class="m-2">**** **** **** {{$order->card_information()?->last_four}}     </span>
                            <br>
                            <span
                                    class="badge-light-primary py-1 px-2 rounded-2 w-100 ">    {{$order->card_information()?->brand}}</span>
                        @elseif($order->payment_method == \App\Models\Order::$paypal)
                            <img src="{{asset('backend/media/svg/payment-methods/paypal.svg')}}"
                                 class="w-50px ms-2"></td>
                        @elseif($order->payment_method == \App\Models\Order::$stripe_link)
                            <span
                                    class="badge-light-dark py-1 px-2 rounded-2">   {{trans('seller.orders.stripe_link')}}</span>
                        @elseif($order->payment_method == \App\Models\Order::$transfer)
                            <span
                                    class="badge-light-info py-1 px-2 rounded-2">   {{trans('seller.orders.transfer')}}</span>

                        @endif
                </span>
                </div>
            </div>

            {{--payment method--}}
            @if(!empty($order->note))
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex flex-stack">
                    <div class="text-gray-700 fw-bold fs-6 me-2"> {{trans('backend.order.note')}}</div>
                </div>
                <div class="d-flex flex-stack">
                    <div class="d-flex align-items-center">
                        {{$order->note}}
                    </div>
                </div>

            @endif

        </div>
    </div>


</div>

