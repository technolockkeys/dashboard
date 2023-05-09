@extends('backend.layout.app')
@section('title',trans('backend.menu.downloads').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($download, array('method' => 'PUT', 'class'=>'form   card-body' , 'route' => array('backend.downloads.update', $download->id))) }}
        @csrf
        <div class="card flex-row-fluid mb-6  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.download.edit',['name' => $download->title])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.downloads.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        @foreach(get_languages() as $key => $item)
                            <li class="nav-item">
                                <a class="nav-link  {{$key == 0 ? "active" : ""}}" data-bs-toggle="tab"
                                   href="#lang_{{$item->code}}">{{$item->language}}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="information_tabs">
                        @foreach(get_languages() as $key => $item)
                            <div class="tab-pane fade show  {{$key == 0 ? "active" : ""}}"
                                 id="lang_{{$item->code}}" role="tabpane{{$key}}">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                            <label for="title_{{$item->code}}"
                                                   class="label @if($item->is_default)required @endif">{{trans('backend.download.title')}}</label>
                                            <input type="text" class="form-control has-error"
                                                   name="title_{{$item->code}}" @if($item->is_default)required @endif
                                                   value="{{old('title_'.$item->code, $download->getTranslation('title', $item->code))}}"
                                                   id="title_{{$item->code}}" maxlength="70">
                                            <b id="error_title_{{$item->code}}" class="text-danger"></b>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 form-group ">
                                        <div class="form-group">
                                            <label class="form-label @if($item->is_default)required @endif"
                                                   for="description_{{$item->code}}">{{trans('backend.category.description')}}</label>
                                            <textarea type="text" class="form-control" @if($item->is_default)required @endif
                                                      id="description_{{$item->code}}"
                                                      name="description_{{$item->code}}"
                                            > {{old('description_'.$item->code, $download->getTranslation('description', $item->code))}}</textarea>
                                            <b class="text-danger" id="error_description_{{$item->code}}">
                                                @error('description_'.$item->code)<i
                                                        class="las la-exclamation-triangle"></i> {{$message}} @enderror
                                            </b>
                                        </div>
                                    </div>
                                    {!! form_seo($item->code , $key ,old('meta_title_'.$item->code, $download->getTranslation('meta_title', $item->code)),old('meta_description_'.$item->code,$download->getTranslation('meta_description', $item->code) )) !!}

                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="card flex-row-fluid mt-6">
            <div class="card-body">

                <div class="col-12 col-md-12 mt-4">
                    <div class="form-group mb-4">
                        <label for="slug" class="required mb-4">{{trans('backend.download.slug')}}</label>
                        <input type="text" class="form-control" required id="slug" data-id="{{$download->id}}"
                               value="{{old('slug', $download->slug)}}"
                               name="slug">

                        <b class="text-danger" id="message_slug"> @error('slug')<i
                                    class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="form-group text-left">
                            <label for="gallery" class=" mb-4">{{trans('backend.download.gallery')}}</label>
                            <br>
                            {!! multi_images('gallery' , old('gallery',$download->gallery) , old('gallery',$download->gallery)) !!}
                            <br>
                            @error('image')<b class="text-danger">{{ $message }}</b> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-left">
                        <div class="form-group">
                            <label for="screen_shots"
                                   class="mb-4">{{trans('backend.download.screen_shot')}}</label>
                            <br>
                            {!! multi_images('screen_shot' , media_file(old('screen_shot', $download->screen_shot) ), old('screen_shot', $download->screen_shot)) !!}
                            <br>
                            @error('screen_shot')<b class="text-danger">{{ $message }}</b> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-4  text-left">
                        <div class="form-group">
                            <label for="image" class="required mb-4">{{trans('backend.download.image')}}</label>
                            <br>
                            {!! single_image('image' , media_file(old('image',$download->image) ), old('image',$download->image), 'image',['watermark'=>'no' ]) !!}
                            <br>
                            {{--                                <b class="text-danger" id="error_image">    @error('image'){{ $message }}@enderror</b>--}}
                        </div>
                    </div>

                    <div class="col-12 col-md-4  text-left">
                        <div class="form-group">
                            <label for="twitter_image"
                                   class="mb-4">{{trans('backend.download.internal_image')}}</label>
                            <br>
                            {!! single_image('internal_image' , media_file(old('internal_image',$download->internal_image) ), old('internal_image',$download->internal_image), 'image',['watermark'=>'no' ]) !!}
                            <br>
                            {{--                                @error('internal_image')<b class="text-danger">{{ $message }}</b> @enderror--}}
                        </div>
                    </div>

                </div>
                <div>
                    <hr>
                </div>
                <div class="row">

                    <div id="attributes_table">

                        @if(!empty(old('types', $attributes)))
                            @php
                                $attributes  =json_decode($attributes);
                                $type =  [] ;
                                $name =  [] ;
                                $link =  [] ;


                                foreach ($attributes as $attribute ){
                                    $attr_type[]= $attribute->type ;
                                    $attr_name[]= $attribute->name ;
                                    $attr_link[]= $attribute->link ;
                                }
                            @endphp
                            @foreach(old('types', $attributes ) as  $key=>$name)
                                <div class="row mb-4" data-row="{{$key.'_attributes'}}">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <select  name='types[]' class='form-control' id=''>
                                                @foreach($types as $type)
                                                    <option

                                                            @if(!empty(old('type', $attr_type)[$key]) && old('type',$attr_type)[$key]== $type  ) selected
                                                            @endif value='{{$type}}'>  {{$type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <input  type='text'
                                                   @if(!empty(old('name[]', $attr_name)[$key])) value="{{old('name[]', $attr_name)[$key]}}"
                                                   @endif  class='form-control ' name='name[]'>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <input  type='text'
                                                   @if(!empty(old('link[]', $attr_link)[$key])) value="{{old('link[]', $attr_link)[$key]}}"
                                                   @endif  class='form-control ' name='link[]'></div>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <div class="form-group">
                                            <button type='button' data-uuid='{{$key.'_attributes'}}'
                                                    class='btn btn-danger btn-icon btn-sm remove-row'><i
                                                        class='fa fa-times'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-12">
                            <button class="btn btn-primary" type="button"
                                    id="add_new_attribute">{{trans('backend.download.add_attribute')}}</button>
                        </div>
                    </div>
                </div>

                <div>
                    <hr>
                </div>
                <div class="col-12 col-md-12 mt-3">
                    @php
                        $videos  =json_decode($download->video);
                        $video_providers =  [] ;
                        $video_url =  [] ;
                        if(!empty($videos))
                            foreach ($videos as $video ){
                                $video_providers[]= $video->provider ;
                                $video_url[]= $video->link ;
                            }
                    @endphp
                    <div id="add_videos" @if(empty(old('videos_provider',$videos)) ) class="d-none" @endif>
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <b>{{trans('backend.download.videos_value')}}</b>
                            </div>
                        </div>


                        @if(!empty(old('videos_provider[]',$video_providers)) )
                            @foreach(old('videos_provider[]',$video_providers) as $key=>$item)
                                <div class="row mt-3" data-row="{{$key}}">

                                    <div class="col-12 col-md-11">
                                        <input name="video_url[]"
                                               value="{{!empty(old('video_url[]',$video_url)[$key]) ? old('video_url[]',$video_url)[$key] :""}}"
                                               type="text" class="form-control" >
                                    </div>
                                    <div class="col-12 col-md-1 ">
                                        <button type="button" data-uuid="{{$key}}"
                                                class="btn btn-icon btn-danger remove-row"><i class="fa fa-exclamation-triangle"></i></button>
                                    </div>
                                </div>

                            @endforeach
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <button type="button" onclick="add_video()"
                                    class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                                <i
                                        class="fa fa-plus"></i> {{trans('backend.download.add_video')}}
                            </button>

                        </div>
                    </div>

                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
            </div>
        </div>
        {{Form::close()}}
    </div>

@endsection


@section('script')
    <script src="https://cdn.ckeditor.com/4.19.0/full/ckeditor.js"></script>
    @foreach(get_languages() as $key=> $item)
        <script>

            CKEDITOR.replace('description_{{$item->code}}');

        </script>
    @endforeach

    <script>

        function uuidv4() {
            return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }

        $(document).on('click', '#add_new_attribute', function () {

            var uuid = uuidv4();
            var html = "<div class='row mb-4' data-row='"+ uuid +"'>" +
                "<div class='col-12 col-md-3'><div class='form-group'>" +
                "<select   name='types[]' class='form-control' id=''>" +

                    @foreach($types as $type)
                        "<option value='{{$type}}'>{{$type}}</option>" +
                    @endforeach
                        "</select></div></div>" +
                "<div class='col-12 col-md-4'><div class='form-group'><input type='text'  class='form-control' name='name[]'></div></div>" +
                "<div class='col-12 col-md-4'><div class='form-group'><input type='text'  class='form-control' name='link[]'></div></div>" +
                "<div class='col-12 col-md-1'><div class='form-group'><button type='button' data-uuid='" + uuid + "' class='btn btn-danger btn-icon btn-sm remove-row'><i class='fa fa-times'></i></button></div></div>" +
                "</div>";

            $("#attributes_table").append(html)
            $('select[name="types[]"]').select2();
        });

        $(document).on('click', ".remove-row", function () {
            var uuid = $(this).data('uuid');
            $("div[data-row='" + uuid + "']").remove();
        });


        function add_video() {
            var uuid = uuidv4();
            var add_videos = '  <div class="row mt-3" data-row="' + uuid + '" >' +

                '<div class="col-11 col-md-11">' +
                '<input name="video_url[]" type="text" class="form-control"  >' +
                '</div>' +
                '<div class="col-1 col-md-1 ">' +
                '<button type="button" data-uuid="' + uuid + '"  class="btn btn-icon btn-danger remove-row"> <i class="fa fa-times"></i> </button>' +
                '</div>' +
                '</div>';
            $("#add_videos").removeClass('d-none');
            $("#add_videos").append(add_videos);
            $(".videos_provider").select2()
        }

    </script>
    {!! script_check_slug(route('backend.downloads.check_slug'),'slug') !!}
    @include('backend.shared.seo.script')

@endsection
