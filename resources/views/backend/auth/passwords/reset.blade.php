@extends('backend.layout.login')
@section('content')
    <div class="row justify-content-center">
        <div
                class="d-flex flex-column flex-column-fluid   bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
                style="height: 100vh;background-image: url({{asset('backend/media/illustrations/sketchy-1/14.png')}})">

            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <!--begin::Logo-->
                <a href="{{url('/')}}" class="mb-12">
                    <img alt="Logo" src="{{asset('backend/media/logos/logo.png')}}" class="h-100px w-100">
                </a>
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">

                    <form method="POST" action="{{ route('backend.resetPassword') }}">
                        @csrf

                        <input name="token" value="{{ $token }}" type="hidden">

                        <div class="form-group mb-10">
                            <input id="email" type="email" name="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                   autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}"
                                   value="{{ $email ?? old('email') }}">

                            @if($errors->has('email'))
                                <b class="text-danger"><i class="las la-exclamation-triangle"></i>
                                    {{ $errors->first('email') }}
                                </b>
                            @endif
                        </div>
                        <div class="form-group mb-10">
                            <input id="password" type="password" name="password" class="form-control" required
                                   placeholder="{{ trans('global.login_password') }}">

                            @if($errors->has('password'))
                                <b class="text-danger"><i class="las la-exclamation-triangle"></i>
                                    {{ $errors->first('password') }}
                                </b>
                            @endif
                        </div>
                        <div class="form-group mb-10">
                            <input id="password-confirm" type="password" name="password_confirmation"
                                   class="form-control" required
                                   placeholder="{{ trans('global.login_password_confirmation') }}">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">
                                    {{ trans('global.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection