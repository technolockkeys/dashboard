@extends('backend.layout.app')
@section('title',trans('backend.menu.products').' | '.get_translatable_setting('system_name', app()->getLocale()))
@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    {{ Form::model($product, array('method' => 'PUT', 'class'=>'form card-body' , 'route' => array('backend.products.update', $product->id))) }}
    @csrf
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                 data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                 class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->

                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                    {{$product->title}}
                    <span class="h-20px border-1 border-gray-200 border-start ms-3 mx-2 me-1"></span>
                    <span class="text-muted fs-7 fw-bold mt-2">#  {{$product->sku}}</span>

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

    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab"
               href="#product_information">{{trans('backend.product.information')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab"
               href="#competitors">{{trans('backend.product.competitors')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab"
               href="#product_google_merchant">{{trans('backend.product.google_merchant')}}</a>
        </li>
        @if(permission_can('show reviews' ,'admin'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab"
                   href="#product_reviews">{{trans('backend.product.reviews')}}</a>
            </li>
        @endif

    </ul>
    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade   show active" id="product_information" role="tabpanel">

            <div class="row">
                <div class="col-12 col-lg-8 col-md-8">
                    @include('backend.product.edit.information.name_and_description')
                    @include('backend.product.edit.attribute.values')
                    @include('backend.product.edit.attribute.brand')
                    @include('backend.product.edit.price.price_renge')
                    @include('backend.product.edit.media.videos')
                    @include('backend.product.edit.media.pdf')
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    @include('backend.product.edit.information.more_information')
                    @include('backend.product.edit.attribute.checkbox')
                    @include('backend.product.edit.media.defualt_image')
                    @include('backend.product.edit.media.secondary_image')
                    @include('backend.product.edit.media.gallery')
                    @include('backend.product.edit.media.twitter_image')
                    @include('backend.product.edit.price.price')
                    @include('backend.product.edit.accessories_and_bundles.accessories')
                    @include('backend.product.edit.accessories_and_bundles.bundles')
                </div>
            </div>
        </div>
        <div class="row tab-pane fade" id="competitors" role="tabpanel">
            @include('backend.product.edit.competitors_prices')
        </div>
        <div class="row tab-pane fade " id="product_google_merchant" role="tabpanel">
            @include('backend.product.edit.google_merchant')
        </div>
        @if(permission_can('show reviews' ,'admin'))
            <div class="row tab-pane fade " id="product_reviews" role="tabpanel">
                @include('backend.product.edit.reviews')
            </div>
        @endif
    </div>
    </form>
 @endsection
@section('script')
    @if(permission_can('show reviews' ,'admin'))
        {!! datatable_script() !!}
        {!! $switch_script !!}
        {!! $datatable_script !!}
    @endif
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
            "competitors_url": "{{trans('backend.product.competitors_url')}}",
            "competitors_tag": "{{trans('backend.product.competitors_tag')}}",
            "competitors_type": "{{trans('backend.product.competitors_type')}}",
            "competitors_html_type": "{{trans('backend.product.competitors_html_type')}}",
            "competitors_price": "{{trans('backend.product.competitors_price')}}",
            "id": "{{trans('backend.global.id')}}",
            "class": "{{trans('backend.global.class')}}",

        };
        var products_routes = {
            'check_slug': "{{route('backend.products.check.slug' , ['id'=>$product->id])}}",
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

                var replaced = text.split(' ').join('-');
                replaced = replaced.split('/').join('-');
                replaced = replaced.split('_').join('-');
                replaced = replaced.split(':').join('-');
                replaced = replaced.split('.').join('-');
                replaced = replaced.split('--').join('-');
                var value = '{{!empty(explode("TL",$product->sku)[1]) ? explode("TL",$product->sku)[1] : " "}}';
                {{--var value = '{{$product->sku}}';--}}

                $("#slug").val(replaced + '-' + value);
                $('#slug').change();
                @endif
                if (text.length >= 70) {
                    $("#summary_name_{{$item->code}}").focus();
                }
            });

                @endforeach
            var value = '{{old('category', $product->category_id)}}'
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
    @include('backend.shared.seo.script')

    <script src='{{asset("backend/js/product.js"  )}}'></script>

    <script>
        @foreach(old('brand',$products_brands) as $key =>$old_item)
        @php
            if(gettype($old_item) == 'string'){
                $old_brand = $old_item;
                $old_model = old('models')[$key];
                $old_year_to = old('years_to')[$key];
                $old_year_from = old('years_from')[$key];
            }else{
                $old_brand = $old_item->brand_id;
                $old_model = $old_item->brand_model_id;
                $old_year_to    = $old_item->to_year_id;
                $old_year_from = $old_item->from_year_id;

            }

        @endphp
        {{--                @dd($old_item->brand_model_id)--}}
        @if(!empty($old_model) && !empty($old_year_from) && !empty($old_year_to) )
        {{--                @dd($old_item->brand_model_id))--}}
        reloadBrand({{$key}}, {{$old_brand}}, {{$old_model}}, {{$old_year_from }}, {{$old_year_to }});
        @elseif(!empty($old_model)  )
        {{--                @dd($old_item->brand_model_id))--}}
        reloadBrand({{$key}}, {{$old_brand}}, {{$old_model}});
        @else
        reloadBrand({{$key}}, {{$old_brand }});
            @endif
            @endforeach
            @if(empty(old('brand',$products_brands) ))
        var uuid = $("#uuid_brand").val();
        var brandItme = $("select[data-brand='" + uuid + "']").val();
        getBrand('models_' + uuid, brandItme);
        @endif
        @if(permission_can('show reviews' ,'admin'))
        $(document).on('click', "a[href='#product_reviews']", function () {
            dt.ajax.reload()
        })
        $(document).on('click', "#apply_filter", function () {
            dt.ajax.reload()
        })
        @endif
    </script>
@endsection
