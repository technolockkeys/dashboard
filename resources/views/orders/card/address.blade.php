<div class=" col-md-6 col-lg-4 col-12   mt-3 ">
    <div class="card card-flush    h-100   ">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.user.address')}}</span>
            </h3>
        </div>
        <div class="card-body pt-5">
            {{-- country  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2">

                    {{trans('backend.order.country')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900 fs-6">
                            <span class="text-gray-900 text-hover-primary">{{$address->get_country()}}</span>
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
          {{-- city  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2">

                    {{trans('backend.order.city')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900    fs-6">
                            <span class="text-gray-900 text-hover-primary">{{$address->city}}</span>
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
         {{-- address  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2">

                    {{trans('backend.order.address')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900   fs-6">
                            <span class="text-gray-900 text-hover-primary">{{$address->address}}</span>
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
         {{-- postal_code  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2">

                    {{trans('backend.address.postal_code')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900  fs-6">
                            <span class="text-gray-900 text-hover-primary">{{$address->postal_code}}</span>
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
         {{-- postal_code  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-boldest fs-6 me-2">

                    {{trans('backend.user.phone')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900   fs-6">
                         <a  class="text-gray-900 text-hover-primary" href="tel:{{$address->phone}}">{{$address->phone}}</a>

                    </span>
                </div>
            </div>



        </div>
    </div>


</div>



