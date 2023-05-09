@extends('backend.layout.app')
@section('title',trans('backend.menu.products').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <form id="kt_stepper_example_basic_form" method="post"
          action="{{route('backend.products.store')}}">
        @csrf

        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                     data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                     class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                        {{trans('backend.product.create_new_product')}}
                    </h1>
                    <!--end::Title-->
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">


                    <button type="submit" class="btn btn-sm btn-primary">{{trans('backend.global.save')}} </button>
                    <a href="{{route('backend.products.index')}}"
                       class="btn btn-sm btn-info">{{trans('backend.global.back')}} </a>


                </div>
                <!--end::Actions-->
            </div>


        </div>
        <div class="row">
            <div class="col-12 col-lg-8 col-md-8">
                @include('backend.product.create.information.name_and_description')
                @include('backend.product.create.attribute.values')
                @include('backend.product.create.attribute.brand')
                @include('backend.product.create.price.price_renge')

                @include('backend.product.create.media.videos')
                @include('backend.product.create.media.pdf')

            </div>
            <div class="col-12 col-lg-3 col-md-3">
                @include('backend.product.create.information.more_information')

                @include('backend.product.create.attribute.checkbox')

                @include('backend.product.create.media.defualt_image')
                @include('backend.product.create.media.secondary_image')
                @include('backend.product.create.media.gallery')
                @include('backend.product.create.media.twitter_image')

                @include('backend.product.create.price.price')
                @include('backend.product.create.accessories_and_bundles.accessories')
                @include('backend.product.create.accessories_and_bundles.bundles')
            </div>
        </div>


    </form>
@endsection
@section('script')
    {!! editor_script() !!}
    @foreach(get_languages() as $key=> $item)
        <script>

            CKEDITOR.replace('description_{{$item->code}}');
            CKEDITOR.replace('faq_{{$item->code}}');


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
            "video_type": "{{trans('backend.product.videos_type')}}",
            "video_url": "{{trans('backend.product.videos_value')}}",
            "videos_value": "{{trans('backend.product.videos_value')}}",

        };
        var products_routes = {
            'check_slug': "{{route('backend.products.check.slug')}}",
            'check_sku': "{{route('backend.products.check.sku')}}",
            'get_product': "{{route('backend.products.get.product')}}",
            'brands': "{{route('backend.products.brands')}}",
            'create_attribute': "{{route('backend.products.attribute.create')}}",
            'create_sub_attribute': "{{route('backend.products.attribute.sub.create')}}",
            'store_sub_attribute': "{{route('backend.products.attribute.sub.store')}}",
            'manufacturer': "{{route('backend.products.check_manufacturer_type')}}"
        };
        var brands = {!! $brands !!}
        $(document).ready(function () {
            var slug = "";
            var value = $('#category').val()
            $.ajax({
                url: '{{route('backend.products.get-category-type')}}',
                method: 'post',
                data: {
                    '_token': '{{csrf_token()}}',
                    'category': value,
                }, success: function (response) {
                    var weight = $('#weight');
                    if (response.data.category_type === 'software') {
                        weight.attr('disabled', true);
                        weight.val(null)
                    } else {
                        weight.attr('disabled', false);
                    }
                }
            })
        });
        var attributes = {!! $attributes !!};

        $(document).on('change', '#category', function () {
            var category = $('#category')
            var value = category.val();

            $.ajax({
                url: '{{route('backend.products.get-category-type')}}',
                method: 'post',
                data: {
                    '_token': '{{csrf_token()}}',
                    'category': value,
                }, success: function (response) {
                    var weight = $('#weight');
                    if (response.data.category_type === 'software') {
                        weight.attr('disabled', true);
                        weight.val(null)
                    } else {
                        weight.attr('disabled', false);
                    }
                }
            })
        });
    </script>

    <script src='{{asset("backend/js/product.js"  )}}'></script>

    <script>

            @if(empty(old('brand',[]) ))
        var uuid = $("#uuid_brand").val();
        var brandItme = $("select[data-brand='" + uuid + "']").val();
        getBrand('models_' + uuid, brandItme);
        @endif

        $(document).ready(function () {
            @foreach(old('brand',[]) as $key =>$old_item)
                @if(!empty(old('models')[$key]) && !empty(old('years_from')[$key]) && !empty(old('years_to')[$key]) )
                    reloadBrand({{$key}}, {{$old_item}}, {{old('models')[$key]}}, {{old('years_from')[$key]}},{{old('years_to')[$key]}});
                @elseif(!empty(old('models')[$key]))
                    reloadBrand({{$key}}, {{$old_item}}, {{old('models')[$key]}},);
                @else
                    reloadBrand({{$key}}, {{$old_item }});
                @endif
            @endforeach


            var slug = "";
            @foreach(get_languages() as $item)
            $("#title_{{$item->code}}").maxlength({
                threshold: 20,
                warningClass: "badge badge-primary",
                limitReachedClass: "badge badge-success"
            });
            $("#short_title_{{$item->code}}").maxlength({
                threshold: 20,
                warningClass: "badge badge-primary",
                limitReachedClass: "badge badge-success"
            });

            $("#short_title_{{$item->code}}").keyup(function () {

                var text = $("#short_title_{{$item->code}}").val();

                @if($item->code == 'en')
                {{--var replaced = '{{convertToKebabCase(text)}}'--}}
                // var replaced = text.

                // .map(x => x.toLowerCase())
                // .join('-')
                var replaced = text.split(' ').join('-');
                replaced = replaced.split('/').join('-');
                replaced = replaced.split('_').join('-');
                replaced = replaced.split(':').join('-');
                replaced = replaced.split('.').join('-');
                replaced = replaced.split('--').join('-');
                var value = '{{\App\Models\Product::withTrashed()->max('id')+1}}';

                $("#slug").val(replaced + '-' + value).change();
                // $('#slug');
                @endif
                if (text.length >= 70) {
                    $("#summary_name_{{$item->code}}").focus();
                }
            });


            @endforeach

            $('#main_attribute').change();
        });
    </script>
    @php
        $languages = get_languages();
    @endphp


    {{--    {!! script_check_slug(route('backend.products.check.slug') ,'slug','short_title_'.$languages[0]->code  ) !!}--}}

    @include('backend.shared.seo.script')

@endsection
