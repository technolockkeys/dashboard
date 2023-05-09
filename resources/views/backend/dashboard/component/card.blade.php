<div class="col-12 col-md-6 col-xl-3  col-lg-4 ">
    <div class="card card-flush">
        <div class="card-body pt-4">
            @foreach($statistics as $element)

                @php
                    $class=              $element['class'];
                    $route=              $element['route'];
                    $svg=                  $element['svg'] ;
                    $number=             $element['number'];
                    $name=               $element['name'];
                    $text_color=         $element['text-color'];
                @endphp
            <div class="d-flex flex-stack">
                <!--begin::Section-->
                <div class="d-flex align-items-center me-5">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40px me-4 {{$class}}">
															<span class="symbol-label {{$class}}">
															<img src="{{$svg}}" alt="">
															</span>
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Content-->
                    <div class="me-5">
                        <!--begin::Title-->
                        <a href="{{$route}}" class="text-gray-800 fw-bolder text-hover-primary fs-6">{{$name}}</a>
                        <!--end::Title-->
                        <!--begin::Desc-->
                         <!--end::Desc-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Section-->
                <!--begin::Wrapper-->
                <div class="text-gray-400 fw-bolder fs-7 text-end">
                    <!--begin::Number-->
                    <span class="text-gray-800 fw-bolder fs-6 d-block">{{$number}}</span>
                    <!--end::Number-->
                </div>
                <!--end::Wrapper-->
            </div>
                @if (!$loop->last)
                    <div class="separator separator-dashed my-5"></div>
                @endif




            @endforeach
        </div>
    </div>

</div>
