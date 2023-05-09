@extends('backend.layout.app')
@section('title', get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link href="{{asset('backend/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="row">

        @include('backend.dashboard.component.card')
        @include('backend.dashboard.component.order')
        @include('backend.dashboard.component.vist_and_week')
{{--        @include('backend.dashboard.component.user_countries')--}}
        @include('backend.dashboard.component.payment')

    </div>
@endsection

@section('script')
    <script src="{{asset('backend/plugins/global/plugins.bundle.js')}}"></script>

    {!! datatable_script() !!}
    {!! $datatable_script !!}
    <script>
        var date_array = [];
        var visits_array = [];
        var views_array = [];
        @foreach($analyticsData as $data)
        {{--console.log("{{$data['date']}}")--}}
        date_array.push("{{Carbon\Carbon::parse($data['date'])->format('d/m')}}");
        visits_array.push({{$data['visitors']}});
        views_array.push({{$data['pageViews']}});
            @endforeach
        var element = document.getElementById('chart');

        var height = parseInt(KTUtil.css(element, 'height'));
        var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
        var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
        var baseColor = KTUtil.getCssVariableValue('--bs-success');
        var baseLightColor = KTUtil.getCssVariableValue('--bs-light-success');
        var secondaryColor = KTUtil.getCssVariableValue('--bs-warning');
        var secondaryLightColor = KTUtil.getCssVariableValue('--bs-light-warning');

        var fun = function () {
            var options = {
                chart: {
                    fontFamily: "inherit",
                    type: "area",
                    height: height,
                    toolbar: false,
                    zoom: false,
                    sparkline: false,
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'solid',
                    opacity: 1
                },
                series: [{
                    name: 'views',
                    data: views_array
                },
                    {
                        name: 'visits',
                        data: visits_array
                    }
                ],
                xaxis: {
                    labels: {show: !1},
                    tooltip: {enabled: !0, formatter: void 0, offsetY: 0, style: {fontSize: "12px"}},
                    categories: date_array
                },
                colors: [baseColor, secondaryColor],
                yaxis: {min: 0, labels: {show: 0}},
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                grid: {
                    strokeDashArray: 4,
                    padding: {top: 0, right: -20, bottom: -20, left: -20},
                    yaxis: {lines: {show: false}}
                },
                markers: {
                    colors: [baseLightColor, secondaryLightColor],
                    strokeColor: [baseLightColor, secondaryLightColor],
                    strokeWidth: 3
                }
            }
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
        fun();


        //sellers
            @if(!empty($sellers))
        var seller_charts_widget_ = function () {
                // Private methods
                var initChart = function () {
                    var element = document.getElementById("seller_charts_widget_");

                    if (!element) {
                        return;
                    }

                    var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');

                    var options = {
                        series: [{
                            data: {!! json_encode($sellers['data']) !!},
                            show: false
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                horizontal: true,
                                distributed: true,
                                barHeight: 23
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false
                        },
                        colors: {!! json_encode($sellers['colors']) !!},
                        xaxis: {
                            categories:  {!! json_encode($sellers['labels']) !!},
                            labels: {
                                formatter: function (val) {
                                    return val
                                },
                                style: {
                                    colors: KTUtil.getCssVariableValue('--bs-gray-400'),
                                    fontSize: '14px',
                                    fontWeight: '600',
                                    align: 'left'
                                }
                            },
                            axisBorder: {
                                show: false
                            }
                        },

                        grid: {
                            borderColor: borderColor,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: false
                                }
                            },
                            strokeDashArray: 4
                        }
                    };

                    var chart = new ApexCharts(element, options);

                    setTimeout(function () {
                        chart.render();
                    }, 200);
                }

                // Public methods
                return {
                    init: function () {
                        initChart();
                    }
                }
            }();
        seller_charts_widget_.init();
        @endif


    </script>

{{--    <script>--}}

{{--        // var date_array = [];--}}
{{--        var countries = [];--}}
{{--        var users = [];--}}
{{--        var new_users = [];--}}
{{--        var sessions = [];--}}
{{--        var pageviews = [];--}}
{{--        var avgSessionDuration = [];--}}
{{--        @foreach($userCountries['countries'] as $index =>$country)--}}
{{--        countries.push("{{$country}}");--}}
{{--        users.push("{{$userCountries['users'][$index]}}");--}}
{{--        new_users.push("{{$userCountries['new_users'][$index]}}");--}}
{{--        sessions.push("{{$userCountries['sessions'][$index]}}");--}}
{{--        pageviews.push("{{$userCountries['pageviews'][$index]}}");--}}
{{--        avgSessionDuration.push("{{$userCountries['avgSessionDuration'][$index]}}");--}}
{{--        @endforeach--}}
{{--        var element = document.getElementById('kt_apexcharts_2');--}}

{{--        var height = parseInt(KTUtil.css(element, 'height'));--}}
{{--        var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');--}}
{{--        var borderColor = KTUtil.getCssVariableValue('--kt-gray-200');--}}
{{--        var baseColor = KTUtil.getCssVariableValue('--kt-warning');--}}
{{--        var secondaryColor = KTUtil.getCssVariableValue('--kt-gray-300');--}}

{{--        console.log(users)--}}

{{--        var options = {--}}
{{--            series: [{--}}
{{--                name: 'Users',--}}
{{--                data: users,--}}
{{--            }, {--}}
{{--                name: 'new users',--}}
{{--                data: new_users--}}
{{--            }, {--}}
{{--                name: 'sessions',--}}
{{--                data: sessions--}}
{{--            }, {--}}
{{--                name: 'pageviews',--}}
{{--                data: pageviews--}}
{{--            },],--}}
{{--            chart: {--}}
{{--                fontFamily: 'inherit',--}}
{{--                type: 'bar',--}}
{{--                height: height,--}}
{{--                toolbar: {--}}
{{--                    show: false--}}
{{--                }--}}
{{--            },--}}
{{--            plotOptions: {--}}
{{--                bar: {--}}
{{--                    endingShape: 'rounded',--}}
{{--                    barHeight: '40%',--}}
{{--                    horizontal: true,--}}
{{--                },--}}
{{--            },--}}
{{--            legend: {--}}
{{--                show: false--}}
{{--            },--}}
{{--            dataLabels: {--}}
{{--                enabled: false--}}
{{--            },--}}
{{--            stroke: {--}}
{{--                show: true,--}}
{{--                width: 2,--}}
{{--                colors: ['transparent']--}}
{{--            },--}}
{{--            xaxis: {--}}
{{--                categories: countries,--}}
{{--                axisBorder: {--}}
{{--                    show: false,--}}
{{--                },--}}
{{--                axisTicks: {--}}
{{--                    show: false--}}
{{--                },--}}
{{--                labels: {--}}
{{--                    style: {--}}
{{--                        colors: '#EB8C87',--}}
{{--                        fontSize: '12px'--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            yaxis: {--}}
{{--                labels: {--}}
{{--                    style: {--}}
{{--                        colors: '#EB8C87',--}}
{{--                        fontSize: '12px'--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            fill: {--}}
{{--                opacity: 1--}}
{{--            },--}}
{{--            states: {--}}
{{--                normal: {--}}
{{--                    filter: {--}}
{{--                        type: 'none',--}}
{{--                        value: 0--}}
{{--                    }--}}
{{--                },--}}
{{--                hover: {--}}
{{--                    filter: {--}}
{{--                        type: 'none',--}}
{{--                        value: 0--}}
{{--                    }--}}
{{--                },--}}
{{--                active: {--}}
{{--                    allowMultipleDataPointsSelection: false,--}}
{{--                    filter: {--}}
{{--                        type: 'none',--}}
{{--                        value: 0--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            tooltip: {--}}
{{--                style: {--}}
{{--                    fontSize: '12px'--}}
{{--                },--}}
{{--                y: {--}}
{{--                    formatter: function (val) {--}}
{{--                        return val--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            colors: ['#546E7A', '#E91E63', '#ADD8E6', '#FFA500'],--}}
{{--            grid: {--}}
{{--                borderColor: borderColor,--}}
{{--                strokeDashArray: 4,--}}
{{--                yaxis: {--}}
{{--                    lines: {--}}
{{--                        show: true--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        };--}}

{{--        var chart = new ApexCharts(element, options);--}}
{{--        chart.render();--}}
{{--    </script>--}}

    @include('backend.user_wallet.script')
@endsection
