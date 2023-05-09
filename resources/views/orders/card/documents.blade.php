
{{--                @dd(json_decode($payment_files->files, true))--}}
@foreach($order->order_payment as $payment)
    @if($payment->payment_method == 'transfer')
        @php $payment_files = $payment @endphp
    @endif
@endforeach

@if(!empty($payment_files->files) && !empty(json_decode($payment_files->files, true)??[]))
    <div class=" col-12 mb-3">
        <div class="card card-flush  ">
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder text-dark">{{__('backend.order.transfer_details')}}</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                @foreach(json_decode($payment_files->files, true)??[] as $index => $file)
                    <div class="d-flex align-items-center">
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
                    </div>
                    <div class="separator separator-dashed my-3"></div>

                @endforeach

            </div>
        </div>
    </div>
@endif
