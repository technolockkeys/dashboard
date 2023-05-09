@extends('backend.layout.app')
@section('content')
    {{--    @dd(session()->getOldInput())--}}
    <div class="col">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.product.create_new_product')}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.products.index')}}" class="btn btn-info"><i
                            class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
        </div>


        <!--begin::Card body-->

        <!--begin::Stepper-->
        <div class="stepper stepper-pills card" id="kt_stepper_example_clickable">

            <div class="stepper-nav flex-center flex-wrap mb-10 border-gray-300 border-bottom-dashed card-header">

                <div class="stepper-item col mx-2 my-4 current " data-kt-stepper-element="nav"
                     data-kt-stepper-action="step">

                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">1</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.information')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step1')}}

                        </div>
                    </div>
                </div>

                <div class="stepper-item col mx-2 my-4 " data-kt-stepper-element="nav"
                     data-kt-stepper-action="step">
                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">2</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.media')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step2')}}

                        </div>
                    </div>
                </div>

                <div class="stepper-item col mx-2 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">3</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.attributes')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step3')}}

                        </div>
                    </div>
                </div>

                <div class="stepper-item col mx-2 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">4</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.stock_and_price')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step4')}}

                        </div>
                    </div>
                </div>

                <div class="stepper-item col mx-2 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">5</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.accessories_and_bundles')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step5')}}
                        </div>
                    </div>
                </div>

                <div class="stepper-item col mx-2 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                    <div class="stepper-line w-40px"></div>

                    <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">6</span>
                    </div>

                    <div class="stepper-label">
                        <h3 class="stepper-title">
                            {{trans('backend.product.seo')}}

                        </h3>

                        <div class="stepper-desc">
                            {{trans('backend.product.step6')}}
                        </div>
                    </div>
                </div>

            </div>

            <form class="form   card-body" novalidate="novalidate" id="kt_stepper_example_basic_form" method="post"
                  action="{{route('backend.products.store')}}">
                @csrf
                <div class="mb-5">

                    <div class="flex-column   current" data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.information')
                    </div>

                    <div class="flex-column " data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.media')

                    </div>

                    <div class="flex-column " data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.attrbutes')
                    </div>

                    <div class="flex-column " data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.price_and_stock')

                    </div>
                    <div class="flex-column " data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.accessories_and_bundles')
                    </div>
                    <div class="flex-column " data-kt-stepper-element="content">
                        @include('backend.product.tabs_create.seo')

                    </div>

                </div>

                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="me-2">
                        <button type="button" class="btn btn-light btn-active-light-primary"
                                data-kt-stepper-action="previous">
                            {{trans('backend.global.back')}}
                        </button>
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Wrapper-->
                    <div>
                        <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                    <span class="indicator-label">
                        {{trans('backend.global.save')}}
                    </span>
                            <span class="indicator-progress">
                       {{trans('backend.global.please_wait')}}  <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                        </button>

                        <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                            {{trans('backend.global.continue')}}
                        </button>
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Actions-->
            </form>


        </div>

    </div>
@endsection

@section('script')
    {!! editor_script() !!}
    @foreach(get_languages() as $key=> $item)
        <script>

            CKEDITOR.replace('description_{{$item->code}}');


        </script>
    @endforeach
    <script>
        var brand_model = [];
        var brand_id = null;
        var languages = {!! get_languages()  !!};
        var keys = {
            "slug_waiting": "{{trans('backend.product.check_slug.waiting')}}",
            "you_can_use_this_slug": "{{trans('backend.product.check_slug.you_can_use_this_slug')}}",
            "you_can_not_use_this_slug": "{{trans('backend.product.check_slug.you_can_not_use_this_slug')}}",
            "youtube": "{{trans('backend.product.youtube')}}",
            "vimeo": "{{trans('backend.product.vimeo')}}",
            "fixed": "{{trans('backend.product.fixed')}}",
            "percent": "{{trans('backend.product.percent')}}",
            "model": "{{trans('backend.product.model')}}",
            "brand": "{{trans('backend.product.brand')}}",
            "year": "{{trans('backend.product.year')}}",

        };
        var products_routes = {
            'check_slug': "{{route('backend.products.check.slug')}}",
            'check_sku': "{{route('backend.products.check.sku')}}",
            'get_product': "{{route('backend.products.get.product')}}",
            'brands': "{{route('backend.products.brands')}}"
        };
        var brands = {!! $brands !!}
        $(document).ready(function () {
            var slug = "";
            @foreach(get_languages() as $item)
            $("#title_{{$item->code}}").maxlength({
                threshold: 20,
                warningClass: "badge badge-primary",
                limitReachedClass: "badge badge-success"
            });
            $("#title_{{$item->code}}").keyup(function () {

                var text = $("#title_{{$item->code}}").val();
                    @if($item->code == 'en')
                var replaced = text.replace(/ /g, '_');

                $("#slug").val(replaced);
                $('#slug').change();
                @endif
                if (text.length >= 70) {
                    $("#summary_name_{{$item->code}}").focus();
                }
            });

            @endforeach

        });


    </script>
    <script src='{{asset("backend/js/product.js"  )}}'></script>

    <script>
        @foreach(old('brand',[]) as $key =>$old_item)
            @if(!empty(old('models')[$key]))
                reloadBrand({{$key}} ,{{$old_item}} ,  {{old('models')[$key]}}  , {!! json_encode(old('years',[])) !!});
            @else
                reloadBrand({{$key}} ,{{$old_item }} );
            @endif
        @endforeach
            @if(empty(old('brand',[]) ))
            var uuid = $("#uuid_brand").val();
        var brandItme = $("select[data-brand='" + uuid + "']").val();
        getBrand('models_' + uuid, brandItme);
        @endif

    </script>
@endsection
