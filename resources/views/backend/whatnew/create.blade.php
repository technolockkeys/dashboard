@extends('backend.layout.app')
@section('title','whats new | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.whatsnew.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.whatnew.create_new_whatnew')}}</h3>
                    {{--                    <div class="card-toolbar">--}}
                    {{--                        <a href="{{route('backend.whatnews.index')}}" class="btn btn-info"><i--}}
                    {{--                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>--}}
                    {{--                    </div>--}}
                </div>
                <div class="card-body">

                    <div class="row mb-6">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-label"
                                       for="subject">{{trans('backend.whatnew.title')}}</label>
                                <input type="text" class="form-control" id="title"
                                       required
                                       name="title" value="{{old('title')}}">
                                @error('title')<b class="text-danger"> <i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col form-group ">
                            <div class="form-group">
                                <label class="form-label "
                                       for="content">{{trans('backend.whatnew.content')}}</label>
                                <textarea type="text" class="form-control" id="content"
                                          name="content"
                                > {{old('content')}}</textarea>
                                @error('content')<b class="text-danger"> <i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror

                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-6 form-group">
                            <label for="country" class="form-label">{{trans('backend.whatnew.country')}}</label>
                            <select name="country[]" multiple class="form-control" data-control="select2"
                                    id="country" >
                                <option value=""   >{{trans('backend.global.all')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}"
                                            @if($country->id == old('country')) selected @endif>{{$country->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-6 form-group">
                            <label for="users" class="form-label">{{trans('backend.whatnew.user')}}</label>
                            <select name="users[]" class="form-control" multiple data-control="select2" id="users">
                                        @foreach($users as $user)
                                    <option value="{{$user->id}}"
                                       >{{$user->name}}</option>
                                        @endforeach
                            </select>
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

    <script>
        CKEDITOR.replace(
            document.querySelector('#content'));


        $(document).on('change', '#country', function(){
            var country = $(this).val();
            var users = $('#users').val()
            $("#users" ).empty();

            $.ajax({
                url: '{{route('backend.whatsnew.get-users')}}',
                method: "post",
                data: {
                    "_token": '{{csrf_token()}}',
                    'country':country,
                },
                success: function (response) {
                    $("#users").append("<option selected value=''>" + '{{trans('backend.global.all')}}'  + "</option>");
                    console.log(users);
                    $.each(response.data.users, function (key, user) {
                        var select = users.indexOf(user.id.toString()) !== -1?'selected':'';
                        console.log(user.id,select, user.id,user.id.toString())
                        $("#users").append("<option value='" + user.id +"'"+ select+">" + user.name  + "</option>");

                    });
                }
            })
        });


    </script>

@endsection
