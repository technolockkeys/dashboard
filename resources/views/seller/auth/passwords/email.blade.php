@extends('seller.layout.login')
@section('content')
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

                <form method="POST" action="{{ route('seller.password.email') }}">
                    @csrf

                    <div class="form-group mb-10">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email') }}">

                        @if($errors->has('email'))
                            <b class="text-danger"><i class="las la-exclamation-triangle"></i>{{ $errors->first('email') }}
                            </b>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-flat btn-block">
                                {{ trans('global.send_password') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection