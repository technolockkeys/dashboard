<div class="card card-flush  ">


    <div class="card-body pt-5">
        <div class="row">
            @foreach($statistics as $element)
                @php
                    $svg = $element['svg'] ;
                    $number= $element['number'];
                    $name=$element['name'];
                    $sum=$element['sum'];
                @endphp
                {{--                    <div class="d-flex flex-stack">--}}
                {{--                        <!--begin::Section-->--}}
                {{--                        <div class="text-gray-700 fw-bold fs-6 me-2">--}}
                {{--                            <img src="{{$svg}}" width="20" alt="">--}}
                {{--                            {{$name}}</div>--}}
                {{--                        <!--end::Section-->--}}
                {{--                        <!--begin::Statistics-->--}}
                {{--                        <div class="d-flex align-items-senter">--}}
                {{--                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr094.svg-->--}}


                {{--                            <!--end::Svg Icon-->--}}
                {{--                            <!--begin::Number-->--}}
                {{--                            <span class="text-gray-900 fw-boldest fs-6">{{$sum}}</span>--}}
                {{--                            <!--end::Number-->--}}
                {{--                            <span class="text-gray-400 fw-bolder fs-6">/{{$number}}</span>--}}
                {{--                        </div>--}}
                {{--                        <!--end::Statistics-->--}}
                {{--                    </div>--}}
                {{--                    <div class="separator separator-dashed my-3"></div>--}}
                <div class="   col-2 col-lg  col-xl   col-md  ">

                    <div class=" mt-2 ">
                        {{--                                <div class=" d-flex justify-content-between align-items-start flex-column">--}}
                        <div class="w-75 h-50px">
                            <img style='width: 50%; height: 100%' src="{{$svg}}"/>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold fs-m text-gray-800 lh-1 badge badge-lg  ls-n2">{{$sum}}</span>
                            <div class="my-2">
                                <span class="fw-semibold fs-s badge badge-light-primary text-gray-400">{{$name}}</span>
                            </div>
                        </div>
                        <span class="badge badge-light-success fs-base">
                            {{$number}}</span>
                    </div>
                    {{--                            </div>--}}
                </div>
                {{--                </div>--}}
            @endforeach
        </div>
    </div>
</div>
{{--<div class="card ">--}}

{{--    <div class="card-body pt-6">--}}

{{--        @foreach($statistics as $element)--}}
{{--            @php--}}
{{--                $svg = $element['svg'] ;--}}
{{--                $number= $element['number'];--}}
{{--                $name=$element['name'];--}}
{{--                $sum=$element['sum'];--}}
{{--            @endphp--}}
{{--         <div class="d-flex flex-stack">--}}
{{--            <!--begin::Symbol-->--}}
{{--            <div class="symbol symbol-40px me-4">--}}
{{--                <img src="{{$svg}}" width="100" alt="">--}}
{{--            </div>--}}
{{--            <!--end::Symbol-->--}}
{{--            <!--begin::Section-->--}}
{{--            <div class="d-flex align-items-center flex-row-fluid flex-wrap">--}}
{{--                <!--begin:Author-->--}}
{{--                <div class="flex-grow-1 me-2">--}}
{{--                    <a  class="text-gray-800 text-hover-primary fs-6 fw-bolder">{{$name}}</a>--}}
{{--                    <span class="text-muted fw-bold d-block fs-7">{{$sum}}  / {{$number}}</span>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--            <!--end::Section-->--}}
{{--        </div>--}}

{{--        <div class="separator separator-dashed my-4"></div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--    <!--end::Body-->--}}
{{--</div>--}}
