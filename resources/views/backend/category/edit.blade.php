@extends('backend.layout.app')
@section('title',trans('backend.menu.categories').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($category, array('method' => 'PATCH', 'route' => array('backend.categories.update', $category->id))) }}

        @csrf
        <div class="card flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.category.edit_category',['name'=>$category->name])}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.categories.index')}}" class="btn btn-info"><i
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
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label required"
                                               for="name_{{$item->code}}">{{trans('backend.category.name')}}</label>
                                        <input type="text" class="form-control" id="name_{{$item->code}}"
                                               name="name_{{$item->code}}"
                                               value="{{old('name_'.$item->code , $category->getTranslation( 'name',$item->code))}}">
                                        @error('name_'.$item->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row  ">
                                <div class="col form-group ">
                                    <div class="form-group">
                                        <label class="form-label required "
                                               for="description_{{$item->code}}">{{trans('backend.category.description')}}</label>
                                        <textarea type="text" class="form-control" id="description_{{$item->code}}"
                                                  name="description_{{$item->code}}"> {{old('description_'.$item->code ,$category->getTranslation( 'description',$item->code))}}</textarea>
                                        @error('description_'.$item->code)<b class="text-danger"> <i
                                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                                    </div>
                                </div>
                            </div>
                            {!! form_seo($item->code , $key ,old('meta_title_'.$item->code,$category->getTranslation('meta_title', $item->code)),old('meta_description_'.$item->code,$category->getTranslation('meta_description', $item->code))) !!}

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col form-group ">
                        <label class="form-label required"
                               for="slug">{{trans('backend.category.slug')}}</label>
                        <input type="text" class="form-control" id="slug" data-id="{{$category->id}}"
                               name="slug" value="{{old('slug', $category->slug)}}">
                        <b class="text-danger" id="message_slug">     @error('slug') <i
                                    class="las la-exclamation-triangle"></i> {{$message}} @enderror</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group ">
                        <label class="form-label"
                               for="parent">{{trans('backend.category.parent')}}</label>
                        <select class="form-control  " data-control="select2" name="parent" id="parent">
                            <option @if( old('parent',$category->parent_id) == 0 ) selected
                                    @endif value="0">{{trans('backend.category.parent')}}</option>
                            {!! \App\Models\Category::select2(old('parent',$category->parent_id) ,0,0,[ $category->id]) !!}

                        </select>
                        @error('parent')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                    </div>
                    <div class="col form-group " id="category_type">
                        <label class="form-label" for="type">{{trans('backend.category.type')}}</label>
                        <select class="form-control  " data-control="select2" name="type" id="type">

                            <option @if(old('type', $category->type) == "physical") selected
                                    @endif  value="physical">{{trans('backend.category.physical')}}</option>
                            <option @if( old('type', $category->type) == "software") selected
                                    @endif  value="software">{{trans('backend.category.software')}}</option>

                        </select>
                        @error('parent')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                    </div>
                </div>
                <div class="row">
                    <div class="col form-group ">
                        <label class="form-label" for="banner">{{trans('backend.category.form_banner')}}</label>
                        <br>
                        {!! single_image('banner' , media_file(old('banner', $category->banner)) , old('banner', $category->banner),'image',[  'width'=>1200 , 'height'=>300]   ) !!}
                        <br>
                        @error('banner')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                    </div>
                    <div class="col form-group ">
                        <label class="form-label" for="icon">{{trans('backend.category.form_icon')}}</label>
                        <br>
                        {!! single_image('icon' , media_file(old('icon', $category->icon)) , old('icon', $category->icon)  ) !!}
                        <br>
                        @error('icon')<b class="text-danger"> <i
                                    class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col  align-items-center">
                        <div class="form-group  align-items-center">
                            <br>
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px"
                                       {{$category->status == 1 ? "checked" : "" }} type="checkbox" value="1"
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
        </form>
    </div>
@endsection
@section('script')
    {!! editor_script() !!}

    @foreach(get_languages() as $key=> $item)
        <script>
            CKEDITOR.replace(document.querySelector('#description_{{$item->code}}'));

        </script>
        {!! script_check_slug(route('backend.categories.check.slug' , 'slug')) !!}
    @endforeach
    <script>
        $(document).on('change','#parent', function(){
            var parent = $("#parent").val();
            if(parent !== '0'){
                $('#category_type').addClass('d-none')
            }else{
                $('#category_type').removeClass('d-none')
            }

            $("#city").children().remove()
        });

        $(document).ready(function (){
            $('#parent').change();
        });
    </script>
    @include('backend.shared.seo.script')

@endsection
