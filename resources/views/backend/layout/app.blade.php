<html lang="{{session('lang')}}" direction="{{session('lang') == 'ar'? 'rtl': 'ltr' }}" style="direction: {{session('lang') == 'ar'? 'rtl': 'ltr'}}">

<head>
    <base href="">
    <title>@yield('title', get_translatable_setting('system_name', app()->getLocale())) </title>
    <meta charset="utf-8"/>
    <meta name="description"
          content="ESG"/>
    <meta name="keywords"
          content="ESG"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content=" ESG |TLK"/>
    <meta property="og:url" content="{{asset('/')}}"/>
    <meta property="og:site_name" content="ESG |TLK"/>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="storage_path" content="/">
    <meta name="watermark" content="true">
    <link rel="canonical" href="{{asset('/')}}"/>
    <link rel="shortcut icon" href="{{media_file(get_setting('system_logo_icon'))}}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>

    <script>
        var configxx = {
            apiKey: "{{get_setting('firebase_apiKey')}}",
            authDomain: "{{get_setting('firebase_authDomain')}}",
            projectId: "{{get_setting('firebase_projectId')}}",
            storageBucket: "{{get_setting('firebase_storageBucket')}}",
            messagingSenderId: "{{get_setting('firebase_messagingSenderId')}}",
            appId: "{{get_setting('firebase_appId')}}",
            measurementId: "{{get_setting('firebase_measurementId')}}"
        }
    </script>
    <style>
        .buttons-columnVisibility:hover {
            background: #0a6aa1;
        }

        table.dataTable tr th.select-checkbox.selected::after {
            content: "✔";
            margin-top: -11px;
            margin-left: -4px;
            text-align: center;
            text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
        }
    </style>
    @yield('style')
@if(app()->getLocale() == 'ar')
        <link href="{{asset("backend/plugins/custom/prismjs/prismjs.bundle.rtl.css")}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{asset("backend/plugins/global/plugins.bundle.rtl.css")}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset("backend/css/style.bundle.rtl.css")}}" rel="stylesheet" type="text/css"/>
    @else

        <link href="{{asset("backend/plugins/custom/prismjs/prismjs.bundle.css")}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset("backend/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset("backend/css/style.bundle.css")}}" rel="stylesheet" type="text/css"/>
    @endif


</head>

<body id="kt_body"
      class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
      style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
<div class="d-flex flex-column flex-root">


    <div class="page d-flex flex-row flex-column-fluid">

        <div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
             data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
             data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
             data-kt-drawer-toggle="#kt_aside_mobile_toggle">
            <div class="aside-logo flex-column-auto" id="kt_aside_logo">
                <a href="{{route('backend.home')}}">
                    <img alt="Logo" src="{{media_file(get_setting('system_logo_white'))}}" class="h-25px logo"/>
                </a>

                <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
                     data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                     data-kt-toggle-name="aside-minimize">
                    <span class="svg-icon svg-icon-1 rotate-180">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none">
									<path opacity="0.5"
                                          d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                                          fill="currentColor"/>
									<path
                                            d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                                            fill="currentColor"/>
								</svg>
							</span>
                </div>
            </div>

            @include('backend.partials.menu')

        </div>

        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <div id="kt_header" style="" class="header align-items-stretch">
                <div class="container-fluid d-flex align-items-stretch justify-content-between">
                    <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
                        <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                             id="kt_aside_mobile_toggle">
                            <span class="svg-icon svg-icon-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
											<path
                                                    d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                                    fill="currentColor"/>
											<path opacity="0.3"
                                                  d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                                  fill="currentColor"/>
										</svg>
									</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="{{route('home')}}" class="d-lg-none">
                            <img alt="Logo" src="{{media_file(get_setting('system_logo_black'))}}" class="h-30px"/>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">

                        <div class="d-flex align-items-center">

                            @include('backend.layout.calculate_shipping')
                            @include('backend.layout.notifications')
                        </div>
                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">

                            <div class="d-flex align-items-stretch" id="kt_header_nav">
                                <div class="header-menu align-items-stretch" data-kt-drawer="true"
                                     data-kt-drawer-name="header-menu"
                                     data-kt-drawer-activate="{default: true, lg: false}"
                                     data-kt-drawer-overlay="true"
                                     data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                                     data-kt-drawer-direction="end"
                                     data-kt-drawer-toggle="#kt_header_menu_mobile_toggle"
                                     data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                     data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                                </div>
                            </div>
                            <div class="d-flex align-items-stretch flex-shrink-0">
                                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
                                         data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                         data-kt-menu-placement="bottom-end">
                                        <img
                                                src="{{auth('admin')->user()->avatar??asset('backend/media/avatars/blank.png')}}"
                                                alt="user"/>
                                    </div>
                                    <div
                                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                                            data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-50px me-5">
                                                    <img alt="Logo"
                                                         src="{{auth('admin')->user()->avatar??asset('backend/media/avatars/blank.png')}}"/>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div
                                                            class="fw-bolder d-flex align-items-center fs-5">{{auth('admin')->user()->name}}
                                                    </div>
                                                    <a href="#"
                                                       class="fw-bold text-muted text-hover-primary fs-7">{{auth('admin')->user()->email}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">

                                            <a href="{{route('backend.profile')}}"
                                               class="menu-link px-5">My Profile</a>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5" data-kt-menu-trigger="hover"
                                             data-kt-menu-placement="left-start">
                                            <a href="#" class="menu-link px-5">
													<span class="menu-title position-relative">{{trans('backend.global.language')}}
                                                        @foreach(get_languages() as $language)
                                                            @if($language->code == app()->getLocale())
                                                                <span
                                                                        class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">{{$language->language}}
													<img class="w-15px h-15px rounded-1 ms-2"
                                                         src="{{media_file($language->flag)}}"
                                                         alt=""/></span>
                                                            @endif
                                                        @endforeach
                                                    </span>

                                            </a>
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                @foreach(get_languages() as $language)
                                                    <div class="menu-item px-3">
                                                        <a href="{{route('backend.set_locale',[ $language->code])}}"
                                                           class="menu-link d-flex px-5 @if(app()->getLocale() == $language->code)active @endif">
														<span class="symbol symbol-20px me-4">
															<img class="rounded-1"
                                                                 src="{{media_file($language->flag)}}"
                                                                 alt=""/>
														</span>{{$language->language}}</a>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">
                                            <a href="{{route('backend.logout')}}"
                                               class="menu-link px-5">{{trans('backend.global.logout')}}</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="post d-flex flex-column-fluid">
                    <div id="kt_content_container" class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
            @include('backend.partials.footer')
            <!--end::Footer-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<div
        id="drawer_media"

        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_example_basic_button"
        data-kt-drawer-close="#kt_drawer_example_basic_close"
        data-kt-drawer-width="500px"
>

</div>
{!! model_media() !!}
<script>
    var hostUrl = "/";
</script>
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>


<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>

<script src="{{asset("backend/plugins/global/plugins.bundle.js")}}"></script>
<script src="{{asset("backend/js/scripts.bundle.js" )}}"></script>
<script src="{{asset('backend/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('backend/plugins/custom/draggable/draggable.bundle.js')}}"></script>

<script>
    var device_token = "{{auth('admin')->user()->device_token}}";
    var fcm_route = "{{route('backend.save.token')}}";
    var storage_path = '/';
    var app_frontend = "{{rtrim(get_setting('app_url'),'/')}}";

    var asset_path = "{{asset('storage/')}}";
    var asset = "{{asset('/')}}";
    var count_files = 0;
    var files_token = "{{csrf_token()}}";
    var error_image = "{{asset('backend/media/svg/files/blank-image.svg')}}";
    var MediaFileManger = {
        'get': "{{route('backend.media.model.get')}}",
    };
    var messageFileManger = {
        'select_media': " ",
        'click_to_select_files_here': "{{trans('backend.global.click_to_select_files_here')}}"
    }
    var message_route = {
        'please_select_media': "{{trans('backend.media.please_select_media')}}",
        'successful_upload': "{{trans('backend.media.successful_upload')}}",
        'copy_like': "{{trans('backend.media.copy_like')}}",
        'details': "{{trans('backend.media.details')}}",
        'delete': "{{trans('backend.global.delete')}}",
        'cut': "{{trans('backend.global.cut')}}",
        'you_cant_use_this_name': "{{trans('backend.media.you_cant_use_this_name')}}",
        'loading': "{{trans('backend.global.loading')}}"
    };
    var media_route = {
        upload: '{{route('backend.media.upload.files')}}',
        files_route: '{{route('backend.media.get.files')}}',
        delete: '{{route('backend.media.delete.files')}}',
        details: '{{route('backend.media.details.files')}}',
        check_file: '{{route('backend.media.check.files')}}',
        update: '{{route('backend.media.update.files')}}',
        create_new_folder: "{{route('backend.media.create.folder')}}",
        delete_folder: "{{route('backend.media.delete.folder')}}",
        media_route: "{{route('backend.media.index')}}",
        cut_set_route: "{{route('backend.media.cut.set.folder')}}",
        cut_get_folder_route: "{{route('backend.media.cut.get.folder')}}"
    };
    var media_permission = {
        'edit': "{{permission_can('edit media','admin') ? 1 : 0 }}",
        'delete': "{{permission_can('delete media','admin') ? 1 : 0 }}",
    };
    var read_notifications_route = "{{route('backend.notifications.read')}}";
    var csrf_token = "{{csrf_token()}}";
    var calculate_shipping_cost_route = '{{route('backend.products.calculate-shipping-cost')}}';
    var messages_translate = {
        'get_shipping_price': "{{trans('seller.get_shipping_price')}}",

    }
</script>
<script src="{{asset("backend/js/app.js")}}"></script>
@yield('script')

<script>


    $(document).ready(function () {

        @if (Session::has('success') )
        success_message("{{ Session::get('success') }}");
        @endif
        @if (Session::has('error')  )
        error_message("{{Session::get('error')  }}");
        @endif
        @if($errors->any())
        error_message("{{ implode('', $errors->all(':message')) }}");
        @endif
    });

</script>

</body>
</html>

