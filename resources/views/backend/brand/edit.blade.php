@extends('backend.layout.app')
@section('title',trans('backend.menu.brands').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    {{ Form::model($brand, array('method' => 'PATCH', 'route' => array('backend.brands.update', $brand->id))) }}
    @csrf
    <div class="col">
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.brand.edit_brand',['name'=> $brand->make])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.brands.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    @foreach(get_languages() as $key=> $item)
                        <li class="nav-item">
                            <a class="nav-link  @if($key == 0 ) active @endif" data-bs-toggle="tab"
                               href="#{{$item->code}}">{{$item->language}}</a>
                        </li>
                    @endforeach

                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach(get_languages() as $key=> $item)

                        <div class="tab-pane fade   @if($key == 0 )show active @endif" id="{{$item->code}}"
                             role="tabpanel">
                            <div class="row mb-6">
                                <div class="col-12 col-md-12 mb-10">
                                    <label for="make_{{$item->code}}" class="form-label required">{{trans('backend.brand.make')}}</label>
                                    <input class="form-control" id="make_{{$item->code}}" name="make_{{$item->code}}"
                                           value="{{old('make_'.$item->code, $brand->getTranslation('make', $item->code))}}"
                                           placeholder="Type a brand name"/>

                                    @error('make_'.$item->code) <b class="text-danger"><i
                                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                                </div>
                                <div class="col form-group ">
                                    <div class="form-group">
                                        <label class="form-label"
                                               for="description_{{$item->code}}">{{trans('backend.brand.description')}}</label>
                                        <textarea type="text" class="form-control" id="description_{{$item->code}}"
                                                  name="description_{{$item->code}}"
                                        > {!! old('description_'.$item->code, $brand->getTranslation('description', $item->code)) !!}</textarea>
                                        @error('description_'.$item->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}
                                        </b>@enderror

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="card flex-row-fluid mb-2  ">

            <div class="card-body">
                <div class="row mb-6">


                </div>

                <div class="row flex-center">

                    <div class="col-12 col-md-6 mb-10">
                        <label for="pin_code_price" class="form-label required">{{trans('backend.brand.pin_code_price')}}</label>
                        <input class="form-control" id="pin_code_price" name="pin_code_price"
                               value="{{old('pin_code_price',$brand->pin_code_price)}}"
                               />

                        @error('pin_code_price') <b class="text-danger"><i
                                    class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-10">
                        <div class="form-group  align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px"
                                       @if(old('status', $brand->status) == 1) checked @endif type="checkbox" value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.global.do_you_want_active')}}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label for="system_logo_white"
                               class="col-lg-4 col-form-label fw-bold fs-6">{{trans('backend.brand.image')}} </label>
                        <br>
                        <div class="col-lg-8">


                            {!! single_image('image' , media_file(old('image',$brand->image)) , old('image', $brand->image) ) !!}
                            <br>
                            @error('image') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
            </div>
        </div>
    </div>


    {{Form::close()}}
@endsection

@section('script')
    {!! editor_script() !!}

        @foreach(get_languages() as $key=> $item)
    <script>
        CKEDITOR.replace(document.querySelector('#description_{{$item->code}}'));
    </script>
        @endforeach
    <script>
        $(document).on("change", '#make', function () {
            var make = $(this).val();
            $("#model").children().remove()
            $("#year").children().remove()
            $.ajax({

                url: "{{route('backend.brands.load.models')}}",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "make": make,
                    'type': 'model'
                }
            }).done(function (response) {
                $(response.data.models).each(function () {
                    var old_model = {{empty(old('model' ,-1)) ? -1 : old('model' ,-1)}};
                    var selected = '';
                    if (old_model == this) {
                        selected = 'selected';
                    }
                    $("#model").append("<option  " + selected + " value='" + this + "'> " + this + "</option>");
                })
            });
        });
        $(document).on("change", '#model', function () {
            var model = $("#model").val();
            var make = $("#make").val();

            $("#year").children().remove()
            $.ajax({
                url: "{{route('backend.brands.load.models')}}",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "make": make,
                    "model": model,
                    'type': 'year'
                }
            }).done(function (response) {
                $(response.data.models).each(function () {
                    var old_year = {{empty(old('year' ,-1)) ? -1 :old('year' ,-1)}};
                    var selected = '';
                    if (old_year == this) {
                        selected = 'selected';
                    }
                    $("#year").append("<option  " + selected + " value='" + this + "'> " + this + "</option>");

                })
            });
        });
        $(document).ready(function () {
            var make = $("#make").val();
            @if(!empty(old('make')))
            $("#make").val("{{old('make')}}").change();
            @endif
            @if(!empty(old('model')))
            $("#model").val("{{old('model')}}").change();
            @endif
            // $("#model").children().remove()

        })

        $("#new_make").keyup(function () {
            var value = $(this).val();
            if (value == "") {
                $("#make").removeAttr("disabled");
                $("#model").removeAttr("disabled");
                $("#year").removeAttr("disabled");
            } else {
                $("#make").attr("disabled", 'disabled');
                $("#model").attr("disabled", 'disabled');
                $("#year").attr("disabled", 'disabled');
            }
        })
        $("#new_model").keyup(function () {
            var value = $(this).val();
            if (value == "" && $("#new_make").val() == "") {
                $("#model").removeAttr("disabled");
                $("#year").removeAttr("disabled");
            } else {
                $("#model").attr("disabled", 'disabled');
                $("#year").attr("disabled", 'disabled');
            }
        })
        $("#new_year").keyup(function () {
            var value = $(this).val();
            if (value == "" && $("#new_make").val() == "" && $("#new_model").val() == "") {
                $("#year").removeAttr("disabled");
            } else {
                $("#year").attr("disabled", 'disabled');
            }
        })


    </script>

@endsection
