@if($order->seller != null)
    <div class="  col-12   ">
        <div class="card card-flush  ">
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder text-dark">{{__('backend.order.seller')}}</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                {{--            seller name--}}
                <div class="d-flex flex-stack">
                    <!--begin::Section-->
                    <div class="text-gray-700 fw-bold fs-6 me-2"> {{__('backend.order.seller')}}</div>
                    <!--end::Section-->
                    <!--begin::Statistics-->
                    <div class="d-flex align-items-senter">


                        <span class="text-gray-900 fw-boldest fs-6">{{$order->seller->name}}</span>


                    </div>
                    <!--end::Statistics-->
                </div>
                <div class="separator separator-dashed my-3"></div>
                {{--            seller comission--}}
                <div class="d-flex flex-stack">
                    <!--begin::Section-->
                    <div class="text-gray-700 fw-bold fs-6 me-2">{{__('backend.order.seller_commission')}}</div>
                    <!--end::Section-->
                    <!--begin::Statistics-->
                    <div class="d-flex align-items-senter">


                        <span class="text-gray-900 fw-boldest fs-6">{{currency($order->seller_commission)}}</span>


                    </div>
                    <!--end::Statistics-->
                </div>
                <div class="separator separator-dashed my-3"></div>
                @if($order->seller_manager != null)
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-bold fs-6 me-2">{{__('backend.order.seller_manager')}}</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-boldest fs-6">{{$order->seller_manager->name}}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>

                    <div class="d-flex flex-stack">
                        <div
                            class="text-gray-700 fw-bold fs-6 me-2">{{__('backend.order.seller_manager_commission')}}</div>
                        <div class="d-flex align-items-senter">
                            <span
                                class="text-gray-900 fw-boldest fs-6">{{currency($order->seller_manager_commission)}}</span>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
@endif

@if(!empty($order->feedback))
    <div class="col-md-12 col-12 mb-3">
        <div class="card card-flush mt-2">
            <div class="card-header">
                <h2 class="card-title">           {{trans('backend.order.feedback')}}</h2>
                <div class="card-toolbar">
                    <h2 class='badge btn-primary'>{{$order->feedback_date}}</h2>
                </div>
            </div>
            <div class="card-body mt-0">

                <a style="font-size:  x-small"
                   class="text-gray-600   text-hover-primary">{{$order->feedback}}</a>

            </div>
        </div>
    </div>
@endif
