<div class="card card-flush col-md-4 col-12 py-4 flex-row-fluid">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{__('backend.order.details')}}</h2>

            <h6>#{{$order->uuid}}</h6>
        </div>
        <div class="card-toolbar">
            {!! $qr !!}
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">

        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                <!--begin::Date-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="20" height="21" viewBox="0 0 20 21"
                                                                             fill="none">
																			<path opacity="0.3"
                                                                                  d="M19 3.40002C18.4 3.40002 18 3.80002 18 4.40002V8.40002H14V4.40002C14 3.80002 13.6 3.40002 13 3.40002C12.4 3.40002 12 3.80002 12 4.40002V8.40002H8V4.40002C8 3.80002 7.6 3.40002 7 3.40002C6.4 3.40002 6 3.80002 6 4.40002V8.40002H2V4.40002C2 3.80002 1.6 3.40002 1 3.40002C0.4 3.40002 0 3.80002 0 4.40002V19.4C0 20 0.4 20.4 1 20.4H19C19.6 20.4 20 20 20 19.4V4.40002C20 3.80002 19.6 3.40002 19 3.40002ZM18 10.4V13.4H14V10.4H18ZM12 10.4V13.4H8V10.4H12ZM12 15.4V18.4H8V15.4H12ZM6 10.4V13.4H2V10.4H6ZM2 15.4H6V18.4H2V15.4ZM14 18.4V15.4H18V18.4H14Z"
                                                                                  fill="currentColor"></path>
																			<path d="M19 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V4.40002C0 5.00002 0.4 5.40002 1 5.40002H19C19.6 5.40002 20 5.00002 20 4.40002V1.40002C20 0.800024 19.6 0.400024 19 0.400024Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            <!--end::Svg Icon-->{{trans('backend.global.created_at')}}</div>
                    </td>
                    <td class="fw-bolder text-end">{{$order->created_at}}</td>
                </tr>
                <!--end::Date-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path opacity="0.3"
                                                                                  d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z"
                                                                                  fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z"
                                                                                  fill="currentColor"></path>
																			<path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            <!--end::Svg Icon-->{{__('backend.order.type')}}</div>
                    </td>
                    <td class="fw-bolder text-end">
                        {{--                        //TODO add images--}}
                        @php $class = $order->type == \App\Models\Order::$proforma? 'primary':'success' @endphp
                        <span class="badge-light-{{$class}} py-1 px-2 rounded-2">    {{$order->type}}</span>

                </tr>
                <!--begin::Payment method-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path opacity="0.3"
                                                                                  d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z"
                                                                                  fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z"
                                                                                  fill="currentColor"></path>
																			<path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            <!--end::Svg Icon-->{{__('backend.order.payment_method')}}</div>
                    </td>
                    <td class="fw-bolder text-end">
                        {{--                        //TODO add images--}}
                        @if($order->payment_method == 'stripe')
                            <span class="mx-2">{{$order->card_information()?->last_four}}     </span>
                            <span class="badge-light-primary py-1 px-2 rounded-2">    {{$order->card_information()?->brand}}</span>
                        @elseif($order->payment_method == 'paypal')
                            <img src="{{asset('backend/media/svg/payment-methods/paypal.svg')}}"
                                 class="w-50px ms-2"></td>
                    @endif

                </tr>

                <!--end::Payment method-->
                <!--begin::Date-->
                @if($order->shipping_method != null)
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm006.svg-->
                                <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z"
                                                                                  fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                                <!--end::Svg Icon-->{{__('backend.order.shipping_method')}}</div>
                        </td>
                        <td class="fw-bolder text-end">{{$order->shipping_method}}</td>
                    </tr>
                @endif
                <!--end::Date--><!--begin::Date-->
                @if($order->seller != null)
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <!--begin::Svg Icon | path: assets/media/icons/duotune/ecommerce/ecm004.svg-->
                                <span class="svg-icon svg-icon-muted me-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z" fill="currentColor"/>
<path opacity="0.3" d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z" fill="currentColor"/>
<path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z" fill="currentColor"/>
<path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z" fill="currentColor"/>
<path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor"/>
<path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor"/>
<path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor"/>
<path d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z"
      fill="currentColor"/>
</svg></span>
                                <!--end::Svg Icon-->
                                {{__('backend.order.seller')}}/{{__('backend.order.seller_commission')}}
                            </div>
                        </td>
                        <td class="fw-bolder text-end">{{$order->seller->name}}
                            /{{currency($order->seller_commission)}}</td>
                    </tr>
                @endif
                @if($order->seller_manager != null)
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <!--begin::Svg Icon | path: assets/media/icons/duotune/ecommerce/ecm004.svg-->
                                <span class="svg-icon svg-icon-muted me-2"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z" fill="currentColor"/>
<path opacity="0.3" d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z" fill="currentColor"/>
<path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z" fill="currentColor"/>
<path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z" fill="currentColor"/>
<path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor"/>
<path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor"/>
<path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor"/>
<path d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z"
      fill="currentColor"/>
</svg></span>
                                <!--end::Svg Icon-->
                                {{__('backend.order.seller_manager')}}
                                /{{__('backend.order.seller_manager_commission')}}</div>
                        </td>
                        <td class="fw-bolder text-end">{{$order->seller_manager->name}}
                            /{{currency($order->seller_manager_commission)}}</td>
                    </tr>
                @endif

                <!--end::Date-->
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
    </div>
    <!--end::Card body-->
</div>
