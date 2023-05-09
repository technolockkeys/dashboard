@extends('backend.layout.app')
@section('title',trans('backend.menu.menus').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{Form::model($menu,array('method' => 'PATCH', 'route' => array('backend.cms.menus.update', $menu->id)) )}}
        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.edit',['name'=> $menu->getTranslation('title', app()->getLocale())])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.cms.menus.index')}}" class="btn btn-info"><i
                            class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    @foreach(get_languages() as $key=> $language)
                        <li class="nav-item">
                            <a class="nav-link  @if($key == 0 ) active @endif" data-bs-toggle="tab"
                               href="#{{$language->code}}">{{$language->language}}</a>
                        </li>
                    @endforeach

                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach(get_languages() as $key=> $language)
                        <div class="tab-pane fade   @if($key == 0 )show active @endif" id="{{$language->code}}"
                             role="tabpanel">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label @if($key == 0 ) required @endif"
                                               for="title_{{$language->code}}">{{trans('backend.menu.title')}}</label>
                                        <input type="text" class="form-control" id="title_{{$language->code}}"
                                               @if($key == 0 ) required @endif
                                               name="title_{{$language->code}}"
                                               value="{{old('title_'.$language->code, $menu->getTranslation('title', $language->code))}}">
                                        @error('title_'.$language->code)<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                </div>
                            </div>


                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="card flex-row-fluid mb-2 mt-5  ">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col form-group ">
                        <label class="form-label required"
                               for="type">{{trans('backend.status.type')}}</label>

                        <select class="form-control type" id="type"
                                name="type" required
                                data-control="select2"
                                data-placeholder="Type">
                            <option selected value="{{null}}"></option>
                            @foreach($types as $type)
                                <option value="{{$type}}"
                                    {{old('type', $menu->type) == $type? "selected":"" }}>{{trans('backend.menu.'.$type)}}</option>
                            @endforeach
                        </select>
                        @error('type') <b class="text-danger"><i
                                class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                    </div>
                </div>
                <div class="row  mb-4">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label"
                                   for="link">{{trans('backend.menu.link')}}</label>
                            <input type="text" class="form-control" id="link"
                                   name="link" value="{{old('link',$menu->link)}}">
                            @error('link')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                        </div>
                    </div>
                </div>
                <div class="row  mb-4 @if(old('type', $menu->type) != 'header') d-none @endif" id="icon-wrapper">
                    <div class="col ">
                        <div class="form-group">
                            {!! single_image('icon' , media_file(old('icon',$menu->icon)) , $menu->icon, 'image',['watermark'=>'no' ,'width'=>70 , 'height'=>70] ) !!}


                            @error('icon')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col  align-items-center">
                        <div class="form-group  align-items-center">
                            <br>
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px"
                                       @if(old('status', $menu->status)== 1) checked @endif type="checkbox" value="1"
                                       name="status" id="status"/>
                                <label class="form-check-label" for="status">
                                    {{trans('backend.global.do_you_want_active')}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
            </div>
        </div>
        {{Form::close()}}
    </div>
@endsection

@section('script')

    <script>
        $(document).on('change', '#type', function () {
            var value = $(this).val();
            if (value === 'header') {
                $('#icon-wrapper').removeClass('d-none');
            } else {
                $('#icon-wrapper').addClass('d-none');
            }
        });
    </script>
@endsection
