
@extends('layouts.app')

@section('title', trans('backend.auth.login'))
@section('content')

    <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
         style="background-image: url({{asset('backend/media/illustrations/sketchy-1/14.png')}})">

        <!--begin::Content-->
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
            <!--begin::Logo-->
            <a href="{{url('/')}}" class="mb-12">
                <img alt="Logo" src="{{asset('backend/media/logos/logo-1.svg')}}" class="h-45px">
            </a>
            <!--end::Logo-->

            <!--begin::Wrapper-->
            <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                <!--begin::Signin Form-->
                <form  method="POST" action="{{ route('login') }}" class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
                        @csrf
                    <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark mb-3">
                            Sign In to Metronic
                        </h1>
                        <!--end::Title-->


                    </div>
                    <!--begin::Heading-->

                            @if(session('message'))
                                <div class="mb-10 bg-light-info p-8 rounded"><div class="text-info">
                                    {{ session('message') }}
                                    </div></div>
                            @endif

                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                        <!--end::Label-->

                        <!--begin::Input-->
                        <input class="form-control form-control-lg form-control-solid {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" autocomplete="off"  value="{{ old('email', null) }}"  required=""  autofocus placeholder="{{ trans('global.login_email') }}">
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                    @endif
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack mb-2">
                            <!--begin::Label-->
                            <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                            <!--end::Label-->

                            <!--begin::Link-->
                            @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">
                                {{ trans('global.forgot_password') }}
                            </a>
                        @endif
                            <!--end::Link-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Input-->
                        <input class="form-control form-control-lg form-control-solid {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password" autocomplete="off"  required placeholder="{{ trans('global.login_password') }}">
                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                    @endif
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <label class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="remember">
                            <span class="form-check-label fw-bold text-gray-700 fs-6">   {{ trans('global.remember_me') }}
            </span>
                        </label>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="text-center">
                        <!--begin::Submit button-->
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                            <!--begin::Indicator-->
                            <span class="indicator-label">
      {{ trans('global.login') }}
</span>
                            <span class="indicator-progress">
    Please wait...
    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
</span>
                            <!--end::Indicator-->
                        </button>
                        <!--end::Submit button-->



                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Signin Form-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->

        <!--begin::Footer-->
        <div class="d-flex flex-center flex-column-auto p-10">
            <!--begin::Links-->
            <div class="d-flex align-items-center fw-bold fs-6">
                <a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>

                <a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact Us</a>

                <a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Purchase</a>
            </div>
            <!--end::Links-->
        </div>
        <!--end::Footer-->
    </div>


@endsection


