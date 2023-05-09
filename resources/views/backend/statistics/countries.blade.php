<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.statistic.user_countries')}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <h3>{{trans('backend.statistic.avg_session_duration')}}</h3>
                <div class="row mt-6">

                    @foreach($data['countries']??[] as $index =>$country)

                        <div class=" col-2 col-md-2 col-xl-2 mb-6">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center ">
                                    <img style='width: 10%'
                                         src="{{asset('backend/media/icons/duotune/general/gen013.svg')}}"/>
                                    <!--end::Svg Icon-->
                                    <div class="fs-2 fw-bold counted px-3 text-success" data-kt-countup="true"
                                         data-kt-countup-value="4500"
                                         data-kt-countup-prefix=""
                                         data-kt-initialized="1">{{number_format($data['avgSessionDuration'][$index])}} sec</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 ">{{$country}}</div>
                                <!--end::Label-->
                            </div>
                        </div>
                    @endforeach
                </div>
                <h3>{{trans('backend.statistic.bounce_rate')}}</h3>
                <div class="row mt-6">

                    @foreach($data['countries']??[] as $index =>$country)

                        <div class=" col-2 col-md-2 col-xl-2 mb-6">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-3 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center ">
                                    <img style='width: 10%'
                                         src="{{asset('backend/media/icons/duotune/arrows/arr011.svg')}}"/>
                                    <!--end::Svg Icon-->
                                    <div class="fs-2 fw-bold counted px-6 text-warning" data-kt-countup="true"
                                         data-kt-countup-value="4500"
                                         data-kt-countup-prefix=""
                                         data-kt-initialized="1">{{number_format($data['bounceRate'][$index])}} %</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 ">{{$country}}</div>
                                <!--end::Label-->
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row gx-9 gy-6">
                    <div id="kt_apexcharts_2" style="height: 350px;"></div>
                </div>
            </div>


        </div>

        <script>

            // var date_array = [];
            var countries = [];
            var users = [];
            var new_users = [];
            var sessions = [];
            var pageviews = [];
            var avgSessionDuration = [];
            @foreach($data['countries']??[] as $index =>$country)
            countries.push("{{$country}}");
            users.push("{{$data['users'][$index]}}");
            new_users.push("{{$data['new_users'][$index]}}");
            sessions.push("{{$data['sessions'][$index]}}");
            pageviews.push("{{$data['pageviews'][$index]}}");
            avgSessionDuration.push("{{$data['avgSessionDuration'][$index]}}");
            @endforeach
            var element = document.getElementById('kt_apexcharts_2');

            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--kt-gray-200');
            var baseColor = KTUtil.getCssVariableValue('--kt-warning');
            var secondaryColor = KTUtil.getCssVariableValue('--kt-gray-300');

            console.log(users)

            var options = {
                series: [{
                    name: 'Users',
                    data: users,
                }, {
                    name: 'new users',
                    data: new_users
                }, {
                    name: 'sessions',
                    data: sessions
                }, {
                    name: 'pageviews',
                    data: pageviews
                },],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        endingShape: 'rounded',
                        barHeight: '40%',
                        horizontal: true,
                    },
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: countries,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#EB8C87',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#EB8C87',
                            fontSize: '12px'
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
                colors: ['#546E7A', '#E91E63', '#ADD8E6', '#FFA500'],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        </script>
