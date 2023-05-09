<div class="card card-flush col-md-4 col-12 py-4 flex-row-fluid">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{__('backend.order.customer_details')}}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">
                <!--begin::Customer name-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path opacity="0.3"
                                                                                  d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                                                  fill="currentColor"></path>
																			<path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            <!--end::Svg Icon-->{{__('backend.order.user')}}</div>
                    </td>
                    <td class="fw-bolder text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            <!--begin:: Avatar -->
                            <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">

                                <div class="symbol-label">
                                    <img src="{{$user->avatar}}" alt="Dan Wilson" class="w-100">
                                </div>

                            </div>
                            <!--end::Avatar-->
                            <!--begin::Name-->
                            <span   class="text-gray-600 text-hover-primary">{{$user->name}}</span>
                            <!--end::Name-->
                        </div>
                    </td>
                </tr>
                <!--end::Customer name-->
                <!--begin::Customer email-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path opacity="0.3"
                                                                                  d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                                  fill="currentColor"></path>
																			<path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            {{__('backend.user.email')}}
                        </div>
                    </td>
                    <td class="fw-bolder text-end">
                        <a href="mailto:{{$user->email}}"
                           class="text-gray-600 text-hover-primary">{{$user->email}}</a>
                    </td>
                </tr>
                <!--end::Payment method-->
                <!--begin::Date-->
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                            <span class="svg-icon svg-icon-2 me-2">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z"
                                                                                  fill="currentColor"></path>
																			<path opacity="0.3" d="M19 4H5V20H19V4Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                            <!--end::Svg Icon-->{{__('backend.user.phone')}}</div>
                    </td>
                    <td class="fw-bolder text-end"><a href="tel:{{$user->phone}}">{{$user->phone}}</a></td>
                </tr>
                <!--end::Date-->
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
    </div>
    <!--end::Card body-->
</div>
