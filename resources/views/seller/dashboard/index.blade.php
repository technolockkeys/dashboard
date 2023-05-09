@extends('seller.layout.app')
@section('title',trans('backend.menu.dashboard').' | '.get_translatable_setting('system_name', app()->getLocale()))
@section('content')

    <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
            <!--begin::Mixed Widget 1-->
            <div class="card card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body p-0">
                    <!--begin::Header-->
                    <div class="px-9 pt-7 card-rounded h-275px w-100 bg-primary">
                        <!--begin::Heading-->
                        <div class="d-flex flex-stack">
                            <h3 class="m-0 text-white fw-bolder fs-3">{{trans('backend.dashboard.order')}}</h3>

                        </div>
                        <!--end::Heading-->
                        <!--begin::Balance-->
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <span class="fw-bold fs-5">{{trans('backend.dashboard.total')}}  </span>
                            <span class="fw-bolder fs-2x pt-1">{{currency($total_orders_value)}}</span>
                        </div>
                        <!--end::Balance-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Items-->
                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1"
                         style="margin-top: -100px">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
															<span class="symbol-label bg-lighten">
																<!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
																<span class="svg-icon svg-icon-1">
																	<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                         height="24" viewBox="0 0 24 24" fill="none">
																		<path opacity="0.3"
                                                                              d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z"
                                                                              fill="currentColor"></path>
																		<path
                                                                            d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z"
                                                                            fill="currentColor"></path>
																	</svg>
																</span>
                                                                <!--end::Svg Icon-->
															</span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="{{route('seller.orders.index', ['status_filter' => 'completed'])}}"
                                       class="fs-5 text-gray-800 text-hover-primary fw-bolder">{{trans('backend.dashboard.order_completed')}} </a>
                                    <div class="text-gray-400 fw-bold fs-5">{{$completed_orders_count}}</div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                    <div
                                        class="fw-bolder fs-5 text-gray-800 pe-1">{{currency($completed_orders_value)}}</div>

                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                        <span class="symbol-label bg-lighten">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                     height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3"
                                          d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z"
                                          fill="currentColor"></path>
                                    <rect x="7" y="17" width="6" height="2" rx="1"
                                          fill="currentColor"></rect>
                                    <rect x="7" y="12" width="10" height="2" rx="1"
                                          fill="currentColor"></rect>
                                    <rect x="7" y="7" width="6" height="2" rx="1"
                                          fill="currentColor"></rect>
                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="{{route('seller.orders.index', ['status_filter' => 'processing'])}}"
                                       class="fs-5 text-gray-800 text-hover-primary fw-bolder">{{trans('backend.dashboard.order_processing')}}</a>
                                    <div class="text-gray-400 fw-bold fs-5">{{$processing_orders_count}}</div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                    <div
                                        class="fw-bolder fs-5 text-gray-800 pe-1">{{currency($processing_orders_value)}}</div>

                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                        <span class="symbol-label bg-lighten">
                            <!--begin::Svg Icon | path: icons/duotune/electronics/elc005.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                     height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3"
                                          d="M15 19H7C5.9 19 5 18.1 5 17V7C5 5.9 5.9 5 7 5H15C16.1 5 17 5.9 17 7V17C17 18.1 16.1 19 15 19Z"
                                          fill="currentColor"></path>
                                    <path
                                        d="M8.5 2H13.4C14 2 14.5 2.4 14.6 3L14.9 5H6.89999L7.2 3C7.4 2.4 7.9 2 8.5 2ZM7.3 21C7.4 21.6 7.9 22 8.5 22H13.4C14 22 14.5 21.6 14.6 21L14.9 19H6.89999L7.3 21ZM18.3 10.2C18.5 9.39995 18.5 8.49995 18.3 7.69995C18.2 7.29995 17.8 6.90002 17.3 6.90002H17V10.9H17.3C17.8 11 18.2 10.7 18.3 10.2Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="{{route('seller.orders.index', ['status_filter' => 'pending_payment'])}}"
                                       class="fs-5 text-gray-800 text-hover-primary fw-bolder">{{trans('backend.dashboard.pending_payment')}}</a>
                                    <div class="text-gray-400 fw-bold fs-5">{{$pending_payment_orders_count}}</div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                    <div
                                        class="fw-bolder fs-5 text-gray-800 pe-1">{{currency($pending_payment_orders_value)}}</div>


                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                        <span class="symbol-label bg-lighten">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                     height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2"
                                          fill="currentColor"></rect>
                                    <rect opacity="0.3" x="13" y="2" width="9"
                                          height="9" rx="2"
                                          fill="currentColor"></rect>
                                    <rect opacity="0.3" x="13" y="13" width="9"
                                          height="9" rx="2"
                                          fill="currentColor"></rect>
                                    <rect opacity="0.3" x="2" y="13" width="9"
                                          height="9" rx="2"
                                          fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="{{route('seller.orders.index', ['status_filter' => \App\Models\Order::$on_hold])}}"
                                       class="fs-5 text-gray-800 text-hover-primary fw-bolder">{{trans('backend.order.on_hold')}}</a>
                                    <div class="text-gray-400 fw-bold fs-5">{{$on_hold_orders_count}}</div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                    <div
                                        class="fw-bolder fs-5 text-gray-800 pe-1">{{currency($on_hold_orders_value)}}</div>

                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Items-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 1-->
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-flush ">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-gray-800">{{trans('seller.orders.user')}}</span>

                    </h3>

                </div>

                <div class="card-body pt-2">
                    @foreach($users as $user)
                        <div class="">
                            <div class="d-flex flex-stack">
                                <div class="d-flex align-items-center me-5">
                                    <img src="{{asset($user->avatar??'/backend/media/avatars/blank.png')}}"
                                         onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'"
                                         class="me-4 w-30px" style="border-radius: 4px" alt="">

                                    <div class="me-5">
                                        <a href="{{route('seller.users.show', ['user' => $user->uuid])}}"
                                           class="text-gray-800 fw-bolder text-hover-primary fs-6">{{$user->name}}</a>
                                        <span class="text-gray-400 fw-bold fs-5 d-block text-start ps-0">
                                        @if(!empty($user->phone))
                                            <a class="text-gray-400" href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                            @endif
                                            @if(!empty($user->email))
                                                @if(!empty($user->phone))
                                                    <br>
                                                @endif
                                                    <a class="text-gray-400" href="mail:{{$user->email}}">{{$user->email}}</a>
                                            @endif
                                    </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-gray-800 fw-bolder fs-4 me-3">{{currency($user->balance)}}</span>

                                </div>
                            </div>
                            @if(!$loop->last)
                            <div class="separator separator-dashed my-3"></div>
                            @endif
                        </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <div class="d-flex flex-stack text-center w-100">
                            <a class="btn btn-primary btn-sm"
                               href="{{route('seller.users.index')}}">{{trans('seller.orders.show')}} <i
                                    class="la la-arrow-down"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card card-flush ">
                <div class="card-header">
                    <h2 class="card-title">{{trans('seller.after_sales.last_orders')}} </h2>
                </div>
                <div class="card-body pt-2">
                    <!--begin::Table-->
                    <div id="kt_table_widget_4_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer"
                                   id="kt_table_widget_4_table">

                                <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-8 text-uppercase gs-0">

                                    <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 100px;">{{trans('backend.wallet.order')}}
                                    </th>
                                    <th class="text-start min-w-125px sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 125px;">{{trans('backend.order.customer_details')}}
                                    </th>
                                    <th class="text-start min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 100px;">{{trans('backend.order.total')}}
                                    </th>

                                </tr>
                                <!--end::Table row-->
                                </thead>

                                <tbody class="fw-bolder text-gray-600">
                                @foreach($orders as $item)
                                    <tr>
                                        <td class="fs-8">
                                            <a href="{{route('seller.orders.show',['order' => $item->uuid])}}"
                                               class="text-gray-800 fw-bolder text-hover-primary fs-8">
                                                #{{$item->uuid}}</a>

                                            <br>
                                            {{$item->created_at->format('Y-m-d')}}
                                        </td>
                                        <td class="fs-8">
                                            <div class="d-flex align-items-center me-5">
                                                <img
                                                    src="{{$item->provider_type=='email' ? asset($item->avatar??'/backend/media/avatars/blank.png') : $item->avatar }}"
                                                    onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'"
                                                    class="me-4 w-30px" style="border-radius: 4px" alt="">

                                                <div class="me-5">
                                                    <a href="{{route('seller.users.show', ['user' => $item->user->uuid])}}"
                                                       class="text-gray-800 fw-bolder text-hover-primary fs-8"> {{$item->name}}</a>

                                                </div>
                                            </div>

                                        </td>
                                        <td class="fs-8">
                                            <span class="badge badge-light-primary">{{currency($item->total)}}</span>
                                            <span class="badge badge-primary">{{currency($item->seller_commission)}}</span>

                                        </td>

                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card mt-3 card-xl-stretch-50 mb-5 mb-xl-8">
                <div class="card-body d-flex flex-column p-0">
                    <div class="flex-grow-1 card-p pb-0">
                        <div class="d-flex flex-stack flex-wrap">
                            <div class="me-2">
                                <a href="#"
                                   class="text-dark text-hover-primary fw-bolder fs-3">{{trans('backend.seller.earning')}}</a>
                            </div>
                            <div class="fw-bolder fs-3 text-primary">{{currency($total_earnings)}}</div>
                        </div>
                    </div>
                    <div class="mixed-widget-7-chart card-rounded-bottom" data-kt-chart-color="primary"
                         style="height: 150px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var initMixedWidget7 = function () {
            var charts = document.querySelectorAll('.mixed-widget-7-chart');

            [].slice.call(charts).map(function (element) {
                var height = parseInt(KTUtil.css(element, 'height'));

                if (!element) {
                    return;
                }

                var color = element.getAttribute('data-kt-chart-color');

                var labelColor = KTUtil.getCssVariableValue('--bs-' + 'gray-800');
                var strokeColor = KTUtil.getCssVariableValue('--bs-' + 'gray-300');
                var baseColor = KTUtil.getCssVariableValue('--bs-' + color);
                var lightColor = KTUtil.getCssVariableValue('--bs-light-' + color);

                var options = {
                    series: [{
                        name: ' ',
                        data: {!! json_encode((!empty($sellers['data']) ? $sellers['data'] : [])) !!}

                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'area',
                        height: height,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                        sparkline: {
                            enabled: true
                        }
                    },
                    plotOptions: {},
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
                    stroke: {
                        curve: 'smooth',
                        show: true,
                        width: 3,
                        colors: [baseColor]
                    },
                    xaxis: {
                        categories:  {!! json_encode( empty( $sellers['labels'] ) ? [] : $sellers['labels'] ) !!},
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            show: false,
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        },
                        crosshairs: {
                            show: false,
                            position: 'front',
                            stroke: {
                                color: strokeColor,
                                width: 1,
                                dashArray: 3
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: undefined,
                            offsetY: 0,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        max: 1000,
                        labels: {
                            show: false,
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
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
                                return "$" + val
                            }
                        }
                    },
                    colors: [lightColor],
                    markers: {
                        colors: [lightColor],
                        strokeColor: [baseColor],
                        strokeWidth: 3
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            });
        }
        initMixedWidget7();


    </script>
@endsection
