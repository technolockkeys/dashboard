@if($order->seller != null)
    <div class="separator separator-dashed my-3"></div>
    <div class="d-flex flex-stack">
        <!--begin::Section-->
        <div class="text-gray-700 fw-boldest fs-6 me-2"> {{__('backend.order.seller')}}</div>
        <!--end::Section-->
        <!--begin::Statistics-->
        <div class="d-flex align-items-senter">


            <span class="text-gray-900 fs-6">{{$order->seller->name}}</span>


        </div>
        <!--end::Statistics-->
    </div>
    <div class="separator separator-dashed my-3"></div>
    {{--            seller comission--}}
    <div class="d-flex flex-stack">
        <!--begin::Section-->
        <div class="text-gray-700 fw-boldest fs-6 me-2">{{__('backend.order.seller_commission')}}</div>
        <!--end::Section-->
        <!--begin::Statistics-->
        <div class="d-flex align-items-senter">


            <span class="text-gray-900 fs-6">{{currency($order->seller_commission)}}</span>


        </div>
        <!--end::Statistics-->
    </div>

    @if($order->seller_manager != null)
        <div class="separator separator-dashed my-3"></div>
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



@endif

@if(!empty($order->feedback))
    <div class="separator separator-dashed my-3"></div>

    <div class="d-flex flex-stack">
        <div
            class="text-gray-700 fw-boldest fs-6 me-2">{{__('backend.order.feedback')}}</div>
        <div class="d-flex align-items-senter">
                            <span
                                class="text-gray-900  fs-6">{{$order->feedback_date}}  </span>
        </div>
    </div>
    <div class="d-flex flex-stack">
        <div class="text-gray-900  fs-6"> {{$order->feedback}}</div>

    </div>

@endif
