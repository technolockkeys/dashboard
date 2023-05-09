@extends('backend.layout.app')
@section('title',trans('backend.menu.users').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    {{--    <style>--}}
    {{--        .iti {--}}
    {{--            width: 100% !important;--}}
    {{--        }--}}
    {{--    </style>--}}
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="container" class="container-xxl">
            <!--begin::Navbar-->
            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{$user->avatar??asset('backend/media/avatars/blank.png')}}" alt="image">
                            </div>
                        </div>

                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-4">
                                        <a href="#"
                                           class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{$user->name}}</a>

                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
                                    @if(!empty($user->email))

                                        <div class="d-flex flex-wrap fw-bold fs-6  pe-2">

                                            <a href="#"
                                               class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                                <span class="svg-icon svg-icon-4 me-1">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3"
                                                                          d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                          fill="currentColor"></path>
																	<path
                                                                        d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                        fill="currentColor"></path>
																</svg>
															</span>
                                                <!--end::Svg Icon-->{{$user->email}}</a>
                                        </div>
                                    @endif
                                    @if(!empty($user->uuid))

                                        <div class="d-flex flex-wrap fw-bold fs-6  pe-2">

                                            <a href="#"
                                               class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">


                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen049.svg-->
                                                <span class="svg-icon svg-icon-4 me-1"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
<path opacity="0.3"
      d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
      fill="currentColor"/>
<path
    d="M12.0006 11.1542C13.1434 11.1542 14.0777 10.22 14.0777 9.0771C14.0777 7.93424 13.1434 7 12.0006 7C10.8577 7 9.92348 7.93424 9.92348 9.0771C9.92348 10.22 10.8577 11.1542 12.0006 11.1542Z"
    fill="currentColor"/>
<path
    d="M15.5652 13.814C15.5108 13.6779 15.4382 13.551 15.3566 13.4331C14.9393 12.8163 14.2954 12.4081 13.5697 12.3083C13.479 12.2993 13.3793 12.3174 13.3067 12.3718C12.9257 12.653 12.4722 12.7981 12.0006 12.7981C11.5289 12.7981 11.0754 12.653 10.6944 12.3718C10.6219 12.3174 10.5221 12.2902 10.4314 12.3083C9.70578 12.4081 9.05272 12.8163 8.64456 13.4331C8.56293 13.551 8.49036 13.687 8.43595 13.814C8.40875 13.8684 8.41781 13.9319 8.44502 13.9864C8.51759 14.1133 8.60828 14.2403 8.68991 14.3492C8.81689 14.5215 8.95295 14.6757 9.10715 14.8208C9.23413 14.9478 9.37925 15.0657 9.52439 15.1836C10.2409 15.7188 11.1026 15.9999 11.9915 15.9999C12.8804 15.9999 13.7421 15.7188 14.4586 15.1836C14.6038 15.0748 14.7489 14.9478 14.8759 14.8208C15.021 14.6757 15.1661 14.5215 15.2931 14.3492C15.3838 14.2312 15.4655 14.1133 15.538 13.9864C15.5833 13.9319 15.5924 13.8684 15.5652 13.814Z"
    fill="currentColor"/>
</svg></span>
                                                <!--end::Svg Icon-->
                                                {{$user->uuid}}</a>
                                        </div>
                                    @endif
                                    @if(!empty($user->get_default_address()?->city))

                                        <div class="d-flex flex-wrap fw-bold fs-6 pe-2">

                                            <a href="#"
                                               class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen018.svg-->
                                                <span class="svg-icon svg-icon-4 me-1"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
<path opacity="0.3"
      d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
      fill="currentColor"/>
<path
    d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
    fill="currentColor"/>
</svg></span>
                                                <!--end::Svg Icon-->
                                                <!--end::Svg Icon-->{{$user->get_default_address()?->city}}</a>
                                        </div>
                                    @endif
                                <!--end::Info-->
                                    @if(!empty($user->company_name))
                                        <div class="d-flex flex-wrap fw-bold fs-6 pe-2">

                                            <a href="#"
                                               class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com014.svg-->
                                                <span class="svg-icon svg-icon-4 me-1"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
<path
    d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z"
    fill="currentColor"/>
<rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
<path
    d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z"
    fill="currentColor"/>
<rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
</svg></span>
                                                <!--end::Svg Icon-->
                                                <!--end::Svg Icon-->{{$user->company_name}}</a>
                                        </div>
                                    @endif
                                <!--end::Info-->
                                    @if(!empty($user->website_url))

                                        <div class="d-flex flex-wrap fw-bold fs-6 pe-2">

                                            <a href="#"
                                               class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com001.svg-->
                                                <span class="svg-icon svg-icon-4 me-1"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
<path opacity="0.3"
      d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
      fill="currentColor"/>
<path
    d="M19 10.4C19 10.3 19 10.2 19 10C19 8.9 18.1 8 17 8H16.9C15.6 6.2 14.6 4.29995 13.9 2.19995C13.3 2.09995 12.6 2 12 2C11.9 2 11.8 2 11.7 2C12.4 4.6 13.5 7.10005 15.1 9.30005C15 9.50005 15 9.7 15 10C15 11.1 15.9 12 17 12C17.1 12 17.3 12 17.4 11.9C18.6 13 19.9 14 21.4 14.8C21.4 14.8 21.5 14.8 21.5 14.9C21.7 14.2 21.8 13.5 21.9 12.7C20.9 12.1 19.9 11.3 19 10.4Z"
    fill="currentColor"/>
<path
    d="M12 15C11 13.1 10.2 11.2 9.60001 9.19995C9.90001 8.89995 10 8.4 10 8C10 7.1 9.40001 6.39998 8.70001 6.09998C8.40001 4.99998 8.20001 3.90005 8.00001 2.80005C7.30001 3.10005 6.70001 3.40002 6.20001 3.90002C6.40001 4.80002 6.50001 5.6 6.80001 6.5C6.40001 6.9 6.10001 7.4 6.10001 8C6.10001 9 6.80001 9.8 7.80001 10C8.30001 11.6 9.00001 13.2 9.70001 14.7C7.10001 13.2 4.70001 11.5 2.40001 9.5C2.20001 10.3 2.10001 11.1 2.10001 11.9C4.60001 13.9 7.30001 15.7 10.1 17.2C10.2 18.2 11 19 12 19C12.6 20 13.2 20.9 13.9 21.8C14.6 21.7 15.3 21.5 15.9 21.2C15.4 20.5 14.9 19.8 14.4 19.1C15.5 19.5 16.5 19.9 17.6 20.2C18.3 19.8 18.9 19.2 19.4 18.6C17.6 18.1 15.7 17.5 14 16.7C13.9 15.8 13.1 15 12 15Z"
    fill="currentColor"/>
</svg></span>
                                                <!--end::Svg Icon-->
                                                <!--end::Svg Icon-->{{$user->website_url}}</a>
                                        </div>
                                        <!--end::Info-->
                                    @endif
                                </div>
                                <!--end::User-->
                                <!--begin::Actions-->

                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->
                            <!--begin::Stats-->

                            <!--end::Stats-->
                        </div>
                        <!--end::Info-->
                        <div class="card-toolbar">
                            <a href="{{route('backend.users.index')}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                        </div>
                    </div>
                    <!--end::Details-->
                    <!--begin::Navs-->
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                        <!--begin::Nav item-->
                        <li class="nav-item mt-2">
                            <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn" id="overview"
                                    data-route="{{route('backend.users.overview', [$user->id])}}"
                            >{{trans('backend.user.overview')}}
                            </button>
                        </li>
                        <!--end::Nav item-->
                        <!--begin::Nav item-->
                        @if(permission_can('show user addresses', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="addresses" data-route="{{route('backend.users.addresses', [$user->id])}}"
                                >{{trans('backend.user.addresses')}}</button>
                            </li>
                        @endif
                    <!--end::Nav item-->
                        <!--begin::Nav item-->
                        @if(permission_can('show tickets', 'admin'))

                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="tickets" data-route="{{route('backend.users.tickets', [$user->id])}}"
                                >{{trans('backend.user.tickets')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show wishlists', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="wishlists"
                                        data-route="{{route('backend.users.wishlists', [$user->id])}}"
                                >{{trans('backend.user.wishlists')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show wallets', 'admin'))

                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="wallet"
                                        data-route="{{route('backend.users.wallet', [$user->id])}}"
                                >{{trans('backend.user.wallet')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show reviews', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="reviews"
                                        data-route="{{route('backend.users.reviews', [$user->id])}}"
                                >{{trans('backend.user.reviews')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show carts', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn" id="carts"
                                        data-route="{{route('backend.users.carts', [$user->id])}}"
                                >{{trans('backend.menu.carts')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show orders', 'admin'))

                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="orders"
                                        data-route="{{route('backend.users.orders', [$user->id])}}"
                                >{{trans('backend.menu.orders')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show coupons', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="orders"
                                        data-route="{{route('backend.users.coupon', [$user->id])}}"
                                >{{trans('backend.menu.coupons')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show cards', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="orders"
                                        data-route="{{route('backend.users.cards', [$user->id])}}"
                                >{{trans('backend.menu.cards')}}</button>
                            </li>
                        @endif
                        @if(permission_can('show compares', 'admin'))
                            <li class="nav-item mt-2">
                                <button class="nav-link text-active-primary ms-0 me-10 bg-white py-5 tab-btn"
                                        id="orders"
                                        data-route="{{route('backend.users.compares', [$user->id])}}"
                                >{{trans('backend.menu.compares')}}</button>
                            </li>
                        @endif

                    </ul>
                    <!--begin::Navs-->
                </div>
            </div>
            <!--end::Navbar-->
            <!--begin::details View-->
            <div class="  mb-5 mb-xl-10" id="content">

            </div>
            <!--end::details View-->

            <!--end::Container-->
        </div>
    </div>

    {{--    <div class="modal fade" id="info_payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
    {{--        <div class="modal-dialog" role="document">--}}
    {{--            <div class="modal-content">--}}
    {{--                <div class="modal-header">--}}
    {{--                    <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.wallet.payment_info')}}</h5>--}}

    {{--                </div>--}}
    {{--                <div class="modal-body" id="info_payment_body">--}}
    {{--                    ...--}}
    {{--                </div>--}}

    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    @include('backend.user_wallet.show_transfer')

@endsection

@section('script')

    <script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>
    <script src="{{asset("backend/plugins/global/plugins.bundle.js")}}"></script>
    <script src="{{asset('backend/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
    <script src="{{asset("backend/js/scripts.bundle.js" )}}"></script>
    {!! datatable_script() !!}
    <script>
        var user = {
            user_id: '{{$user_id}}',
        };
        var token = '{{csrf_token()}}';
        var overview_error = {
            'name': "{{old('name' ,"")}}",
        };
        var user_routes = {
            payment_info: "{{route('backend.users.wallet.payment.info')}}",
            payment_change: "{{route('backend.users.wallet.payment.change.status')}}",
            payment_get: "{{route('backend.users.wallet.payment.get')}}",
            payment_set: "{{route('backend.users.wallet.payment.set')}}",
            send_account_statement: "{{route("backend.users.wallet.send.account.statement",$user_id)}}",
            send_reminder: "{{ route("backend.users.wallet.send.reminder",$user_id)}}"
        };
        //load cities function

    </script>
    <script src="{{asset("backend/js/user.js")}}"></script>
    @include('backend.user_wallet.script')
@endsection
