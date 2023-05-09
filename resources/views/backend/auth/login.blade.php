@extends('backend.layout.login')
@section('title', 'login')
@section('content')

    <div
        class="d-flex flex-column flex-column-fluid   bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
        style="height: 100vh;background-image: url({{asset('backend/media/illustrations/sketchy-1/14.png')}})">

        <!--begin::Content-->
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
            <!--begin::Logo-->
            <a href="{{url('/')}}" class="mb-12">

                <img alt="Logo" src="{{media_file(get_setting('system_logo_black'))}}" class="h-100px w-100">
            </a>
            <!--end::Logo-->

            <!--begin::Wrapper-->
            <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                <!--begin::Signin Form-->
                <form method="POST" action="{{ route('backend.login') }}" class="form w-100" novalidate="novalidate"
                      id="sign_in_form">
                @csrf
                <!--begin::Heading-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark mb-3">
                            {{trans('backend.auth.sing_in_to')}}
                        </h1>
                        <!--end::Title-->


                    </div>
                    <!--begin::Heading-->

                    @if(session('message'))
                        <div class="mb-10 bg-light-info p-8 rounded">
                            <div class="text-info">
                                {{ session('message') }}
                            </div>
                        </div>
                    @endif

                <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="form-label fs-6 fw-bolder text-dark">{{trans('backend.auth.email')}}</label>
                        <!--end::Label-->

                        <!--begin::Input-->
                        <input
                            class="form-control form-control-lg form-control-solid {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            type="email" name="email" autocomplete="off" value="{{ old('email', null) }}" required=""
                            autofocus placeholder="{{ trans('backend.auth.email') }}">
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
                            <label
                                class="form-label fw-bolder text-dark fs-6 mb-0">{{trans('backend.auth.password')}}</label>
                            <!--end::Label-->

                            <!--begin::Link-->
                        {{--                            @if(Route::has('password.request'))--}}
                        {{--                                <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">--}}
                        {{--                                    {{ trans('global.forgot_password') }}--}}
                        {{--                                </a>--}}
                        {{--                        @endif--}}
                        <!--end::Link-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Input-->
                        <input
                            class="form-control form-control-lg form-control-solid {{ $errors->has('password') ? ' is-invalid' : '' }}"
                            type="password" name="password" autocomplete="off" required
                            placeholder="{{ trans('backend.auth.password') }}">
                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                    @endif
                    <!--end::Input-->
                    </div>
                    <!--end::Input group-->


                    <!--begin::Actions-->
                    <div class="text-center">
                        <!--begin::Submit button-->
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                            <!--begin::Indicator-->
                            <span class="indicator-label">
      {{ trans('backend.auth.login') }}
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


    </div>


@endsection
@section('scripts')
    <script>
        var login_route = "";
        $(document).on('submit' , '#sign_in_form' ,function () {
            $('#sign_in_form').preventDefault();
            alert(1);
        });

    </script>
{{--    <script type="text/javascript" src="{{asset('backend/js/login/index.js')}}"></script>--}}

@endsection

