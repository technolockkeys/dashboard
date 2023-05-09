<!DOCTYPE html>
<html>

<head>

{{--    <title>{{ trans('panel.site_title') }}</title>--}}

     <meta charset="utf-8" />
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{asset('backend/media/logos/favicon.ico')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset("backend/plugins/custom/fullcalendar/fullcalendar.bundle.css")}}"   rel="stylesheet" type="text/css" />
    <link href="{{asset("backend/plugins/custom/datatables/datatables.bundle.css"    )}}"   rel="stylesheet" type="text/css" />
    <link href="{{asset("backend/plugins/global/plugins.bundle.css"                  )}}"   rel="stylesheet" type="text/css" />
    <link href="{{asset("backend/css/style.bundle.css"                               )}}"   rel="stylesheet" type="text/css" />
    @yield('styles')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-233146199-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-233146199-1');
    </script>
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="c-app flex-row align-items-center">
        <div class="container">
            @yield("content")
        </div>
    </div>
    <script>var hostUrl = "/";</script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{asset("backend/plugins/global/plugins.bundle.js")}}"   ></script>
    <script src="{{asset("backend/js/scripts.bundle.js"  )          }}"   ></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{asset("backend/plugins/custom/fullcalendar/fullcalendar.bundle.js")}}" ></script>
    <script src="{{asset("backend/plugins/custom/datatables/datatables.bundle.js"    )}}" ></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{asset("backend/js/widgets.bundle.js"                      )}}"></script>
    <script src="{{asset("backend/js/custom/widgets.js"                      )}}"></script>
    <script src="{{asset("backend/js/custom/apps/chat/chat.js"               )}}"></script>
    <script src="{{asset("backend/js/custom/utilities/modals/upgrade-plan.js")}}"></script>
    <script src="{{asset("backend/js/custom/utilities/modals/create-app.js"  )}}"></script>
    <script src="{{asset("backend/js/custom/utilities/modals/users-search.js") }}"></script>
    @yield('scripts')

</body>

</html>
