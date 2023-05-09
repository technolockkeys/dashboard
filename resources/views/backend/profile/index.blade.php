@extends('backend.layout.app')
@section('content')
    <div class="col">
        {{ Form::model($admin, array('method' => 'POST', 'route' => array('backend.profile.update'), 'enctype' => "multipart/form-data" )) }}

        @csrf

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 data-bs-target="#kt_account_profile_details" aria-expanded="true"
                 aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Profile Details</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                      novalidate="novalidate">
                    <div class="card-body border-top p-9">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.profile.avatar')}}</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline @if($admin->avatar==null) image-input-empty @endif "
                                     data-kt-image-input="true"
                                     style="background-image: url({{ asset('backend/media/avatars/blank.png')}})">
                                    <div class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                         style="background-image: url({{$admin->avatar}})">
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
                        <div class="row mb-6">
                            <label for="name"
                                   class="col-lg-4 col-form-label required fw-bold fs-6">{{trans('backend.profile.name')}}</label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input type="text" id="name" name="name"
                                               class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                               placeholder="{{trans('backend.profile.name')}}"
                                               value="{{old('name',$admin)}}">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label for="email" class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="required">{{trans('backend.profile.email')}}</span>
                            </label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="email" name="email" id="email"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.profile.email')}}"
                                       value="{{old('email', $admin)}}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label for="password" class="col-lg-4 col-form-label fw-bold fs-6">
                                <span>{{trans('backend.profile.password')}}</span>
                            </label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="password" id="password" name="password"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.profile.password')}}" value="">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label for="password_confirmation" class="col-lg-4 col-form-label fw-bold fs-6">
                                <span>{{trans('backend.profile.password_confirmation')}}</span>
                            </label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.profile.password_confirmation')}}" value="">
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                                Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{ Form::close() }}
    </div>

@endsection
