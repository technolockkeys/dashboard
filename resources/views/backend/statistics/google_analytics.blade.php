<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.google_analytics')}}</h3>
        </div>
    </div>
    <div class="card-body">

        <div class="row">

            @foreach($statistics as $element)
                @php
                    $svg = $element['svg'] ;
                    $number= $element['number'];
                    $name=$element['name'];
                @endphp
                <div class=" col-2 col-md-2 col-xl-2 mb-6">
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 ps-1 me-6 mb-3">
                        <!--begin::Number-->
                        <div class="d-flex align-items-center ">
                            <img style='width: 10%' src="{{$svg}}"/>
                            <!--end::Svg Icon-->
                            <div class="fs-2 fw-bold counted px-3 text-{{$element['color']}}" data-kt-countup="true" data-kt-countup-value="4500"
                                 data-kt-countup-prefix="$" data-kt-initialized="1">{{$number}}</div>
                        </div>
                        <!--end::Number-->
                        <!--begin::Label-->
                        <div class="fw-semibold fs-4 ">{{$name}}</div>
                        <!--end::Label-->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
