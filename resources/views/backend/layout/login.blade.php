<!DOCTYPE html>
<html>

<head>

{{--    <title>{{ trans('panel.site_title') }}</title>--}}
    <base href="">
{{--    @dd(app()->getLocale())--}}
{{--    @dd(get_translatable_setting('system_name', app()->getLocale()))--}}
    <title>@yield('title', get_translatable_setting('system_name', app()->getLocale()).'') </title>
    <meta charset="utf-8"/>
    <meta name="description"
          content="ESG"/>
    <meta name="keywords"
          content="ESG"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="store"/>
    <meta property="og:title"
          content=" ESG |TLK"/>
    <meta property="og:url" content="{{asset('/')}}"/>
    <meta property="og:site_name" content="ESG |TLK"/>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="canonical" href="{{asset('/')}}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="shortcut icon" href="{{media_file(get_setting('system_logo_icon'))}}"/>
    <link href="{{asset("backend/css/style.bundle.css")}}"   rel="stylesheet" type="text/css" />
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="c-app flex-row align-items-center">
        <div class="container">
            @yield("content")
        </div>
    </div>
    <script>var hostUrl = "/";</script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"   ></script>


    @yield('scripts')

</body>

</html>
