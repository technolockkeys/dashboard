<div class="table-responsive">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
        <!--begin::Table head-->
        <thead>
        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
            <th class="min-w-100px ">{{__('backend.order.payment_method')}}</th>
            <th class="min-w-70px ">{{__('backend.order.status')}}</th>
            <th class="min-w-70px ">{{__('backend.order.payment_details')}}</th>
            <th class="min-w-50px text-end">{{__('backend.order.amount')}}</th>
        </tr>
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody class="fw-bold text-gray-600">
        @foreach($order->order_payment as $index => $payment)
            <tr>

                <td>{{trans('backend.order.'.$payment->payment_method)}}</td>
                <td>{{trans('backend.order.'.$payment->status)}}</td>
                <td>
                    @if($payment->payment_method == \App\Models\Order::$stripe_link)
                        <a class="copy_text" href="{{$payment->stripe_url}}"><span
                                class="badge-info badge">{{__('backend.media.copy_like')}}</span></a>
                    @elseif($payment->payment_method == \App\Models\Order::$transfer)
                        @foreach(json_decode($payment->files, true)??[] as $index => $file)
                            <span class="">
                                            <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3"
                                                                          d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                                          fill="currentColor"></path>
																	<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                          fill="currentColor"></path>
																</svg>
															</span>
                                <a href="{{asset('storage/'.$file)}}"
                                   class="text-gray-800 text-hover-primary">{{__('backend.order.file', ['number'=> $index+1])}} </a>
                            </span>

                        @endforeach
                    @elseif($payment->payment_method == \App\Models\Order::$stripe)
                        @if(!empty($payment->card) && !empty($payment->card->last_four))
                               <span class="badge badge-info">{{$payment->card->last_four}}</span>
                        @endif
                    @endif
                </td>
                <td class="text-end">{{currency($payment->amount)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

