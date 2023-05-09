@extends('backend.layout.app')
@section('title',trans('backend.menu.roles').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.roles.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.role.create_new_role')}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.roles.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="required form-label">{{trans('backend.role.name')}}</label>
                                <input required autocomplete="off" type="text" class="form-control " id="name"
                                       name="name" value="{{old('name')}}"
                                       placeholder="{{trans('backend.admin.name')}}"/>
                                @error('name') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="guard_name" class="required form-label">{{trans('backend.role.guard')}}</label>
                                <select class="form-control" name="guard_name" data-control="select2" id="guard_name"
                                        data-placeholder="Select an option">
                                    <option selected disabled>{{trans('backend.global.select_an_option')}}</option>
                                     @foreach($guards as $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                @error('guard_name') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col" id="permissions">

                </div>
            </div>
            <div class="row mt-2">
                <div class="col" >
                    <div class="card">
                        <div class="card-header">
                            <div class="card-toolbar">
                                <button class="btn btn-primary" type="submit">{{trans('backend.global.save')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('change', 'select[name=guard_name]', () => {
            var guard_name = $('select[name=guard_name]').val();
            $.ajax({
                url: "{{route('backend.roles.get.permission')}}",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'guard': guard_name
                },
                success: (response) => {
                    if(response.code = 200){
                        $("#permissions").html(response.data.view);
                    }
                }
            })
        });
    </script>
@endsection
