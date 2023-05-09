<div class="  col-md-6 col-lg-4 col-12   mt-3 ">
    <div class="card card-flush  h-100 ">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.order.customer_details')}}</span>
            </h3>
        </div>
        <div class="card-body pt-5">
            {{-- user  --}}
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-bold fs-6 me-2">

                    {{trans('backend.order.user')}}
                </div>
                <div class="d-flex align-items-senter">

                    <span class="text-gray-900 fw-boldest fs-6"> <div
                            class="symbol symbol-circle symbol-25px overflow-hidden me-3">

                                <div class="symbol-label">
                                    <img src="{{$user->avatar}}" alt="{{$user->name}}"
                                         onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                         class="w-100">
                                </div>
                            </div>
                                    @if($user->deleted_at != null) <span class="badge badge-warning">
                                            {{$user->name}} </span> @else
                            <span class="text-gray-600 text-hover-primary">{{$user->name}}</span>@endif
                    </span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>

            {{-- email  --}}
            @if(!empty($user->email))
                <div class="d-flex flex-stack">
                    <div class="text-gray-700 fw-bold fs-6 me-2">

                        {{trans('backend.auth.email')}}
                    </div>
                    <div class="d-flex align-items-senter">

                    <span class="text-gray-900 fw-boldest fs-6">
                                <a href="mailto:{{$user->email}}" style="font-size:  x-small"
                                   class="text-gray-600   text-hover-primary">{{$user->email}}</a>

                    </span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            @endif
            {{-- phone  --}}
            @if(!empty($user->phone))
                <div class="d-flex flex-stack">
                    <div class="text-gray-700 fw-bold fs-6 me-2">

                        {{trans('backend.user.phone')}}
                    </div>
                    <div class="d-flex align-items-senter">

                    <span class="text-gray-900 fw-boldest fs-6">
                                <a href="mailto:{{$user->phone}}" style="font-size:  x-small"
                                   class="text-gray-600   text-hover-primary">{{$user->phone}}</a>

                    </span>
                    </div>
                </div>


            @endif

        </div>
    </div>
</div>



