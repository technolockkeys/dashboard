@extends('backend.layout.app')
@section('title',trans('backend.menu.admins').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        {{ Form::model($admin, array('method' => 'PATCH', 'route' => array('backend.admins.update', $admin->id), 'enctype' => "multipart/form-data")) ,   }}

            @csrf
            <div class="card   flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.admin.edit_admin' ,['name'=>$admin->name])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.admins.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-10">
                                <label for="name" class="required form-label">{{trans('backend.admin.name')}}</label>
                                <input required autocomplete="off" type="text" class="form-control " id="name" name="name" value="{{old('name' , $admin->name)}}"
                                       placeholder="{{trans('backend.admin.name')}}"/>
                                @error('name') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                            </div>

                        </div>

                        <div class="col">
                            <div class="  mb-10">
                                <label for="email" class="required form-label">{{trans('backend.admin.Email')}}</label>
                                <input required autocomplete="off" type="email" class="form-control" id="email" name="email" value="{{old('email', $admin->email)}}"
                                       placeholder="{{trans('backend.admin.Email')}}"/>
                                @error('email') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="  mb-10">
                                <label for="password"
                                       class="  form-label">{{trans('backend.admin.password')}}</label>
                                <input autocomplete="off" type="password" class="form-control " id="password" name="password"
                                       placeholder="{{trans('backend.admin.password')}}"/>
                                @error('password') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col">
                            <div class=" mb-10">
                                <label for="password_confirmation"
                                       class="  form-label">{{trans('backend.admin.password_confirmation')}}</label>
                                <input autocomplete="off" type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                       placeholder="{{trans('backend.admin.password_confirmation')}}"/>
                                @error('password_confirmation') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="role"
                                       class="required form-label">{{trans('backend.admin.role')}}</label>
                                <select class="form-control" name="role" data-placeholder="Select an option">
                                    @foreach($roles as $role)
                                        <option   {{ (old('role')== $role->id ) ||  $admin->hasRole($role->name) ?  'selected' : ""  }}    value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @error('role') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror

                            </div>
                        </div>
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if($admin->status == 1) checked  @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.admin.status')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-lg-6 ">
                            <div class="form-group ">
                                <div class="d-flex align-items-start me-10">

                                    <label class="col-lg-6 form-label fw-bold  mx-auto">{{trans('backend.profile.avatar')}}</label>
                                    <div class="col-lg-6 mx-auto">
                                        <div class="image-input image-input-outline @if($admin->avatar==null) image-input-empty @endif" data-kt-image-input="true"
                                             style="background-image: url({{ asset('backend/media/avatars/blank.png')}})">
                                            <div class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                                 style="background-image:  url({{$admin->avatar}})">
                                            </div>
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                   data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                                                   data-bs-original-title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="avatar_remove">
                                            </label>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                                                  data-bs-original-title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title=""
                                                  data-bs-original-title="Remove avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
@endsection

