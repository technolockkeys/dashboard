@extends('backend.layout.app')
@section('title',trans('backend.menu.roles').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($role, array('method' => 'PATCH', 'route' => array('backend.roles.update', $role->id))) }}

            @csrf
            <div class="card flex-row-fluid mb-2">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.role.edit_role',['name'=>$role->name])}}</h3>
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
                                       name="name" value="{{old('name' , $role->name)}}"
                                       placeholder="{{trans('backend.admin.name')}}"/>
                                @error('name') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="guard_name" class="required form-label">{{trans('backend.role.guard')}}</label>
                                <select class="form-control" disabled name="guard_name" data-control="select2" id="guard_name"
                                        data-placeholder="Select an option">
                                    <option   disabled>{{trans('backend.global.select_an_option')}}</option>
                                    @foreach($guards as $item)
                                        <option value="{{$item}}"  @if($item == $role->guard_name) selected    @endif>{{$item}}</option>
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
                    @if( count($PermissionsGroup))
                        <div class="card">

                            <div class="card-body">

                                <div class="fv-row">

                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-bold">
                                            <!--begin::Table row-->
                                            <tr>
                                                <td class="text-gray-800">
                                                    <label class="fs-5 fw-bolder form-label mb-2">{{trans('backend.role.role_permission')}}</label>


                                                </td>
                                                <td >
                                                    <!--begin::Checkbox-->
                                                {{--                            <label class="form-check form-check-custom form-check-solid me-9">--}}
                                                {{--                                <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all">--}}
                                                {{--                                <span class="form-check-label" for="kt_roles_select_all">Select all</span>--}}
                                                {{--                            </label>--}}
                                                <!--end::Checkbox-->
                                                </td>
                                            </tr>
                                            <!--end::Table row-->
                                            @foreach($PermissionsGroup as  $key => $item)
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Label-->
                                                    <td class="text-gray-800 w-250px"><b>{{$key+1}}. </b> {{$item->name}}</td>
                                                    <!--end::Label-->
                                                    <!--begin::Options-->
                                                    <td>
                                                        <!--begin::Wrapper-->
                                                        <div class="d-flex">
                                                            @foreach($item->permission as $permission)

                                                                <label
                                                                    class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                    <input class="form-check-input" type="checkbox" value="{{$permission->id}}" @if($permission->active) checked @endif
                                                                           name="permission[]">
                                                                    <span class="form-check-label">{{$permission->name}}</span>
                                                                </label>
                                                            @endforeach

                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </td>
                                                    <!--end::Options-->
                                                </tr>
                                                <!--end::Table row-->
                                            @endforeach

                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                            </div>
                        </div>
                    @else
                        {{--@include('backend.shared.not_found_items')--}}
                    @endif

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
        {{ Form::close() }}
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
                    'guard': guard_name,
                    'role':"{{$role->id}}"
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
