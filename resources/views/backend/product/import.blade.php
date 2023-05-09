@extends('backend.layout.app')
@section('title',trans('backend.menu.products').' | '.get_setting('title'))
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-flush">
                <div class="card-header">
                    <h2 class="card-title">
                        {{trans('backend.menu.import_product')}}
                    </h2>
                </div>
                <div class="card-body p-0">
                    <form action="{{route('backend.products.import.upload')}}" id="upload_excel_file" method="post">
                        @csrf
                        <div class="card-px     my-10">
                            <!--begin::Alert-->
                            <div
                                class="alert alert-dismissible bg-danger text-light d-flex flex-column flex-sm-row p-5 mb-10">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">  <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen007.svg-->
<span class="svg-icon svg-icon-light  svg-icon-2hx"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
<path opacity="0.3"
      d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z"
      fill="currentColor"/>
<path
    d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.6 8.7 5.6 11 5.1V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V5.1C15.3 5.6 17 7.6 17 10V13C17 14.1 17.9 15 19 15ZM11 10C11 9.4 11.4 9 12 9C12.6 9 13 8.6 13 8C13 7.4 12.6 7 12 7C10.3 7 9 8.3 9 10C9 10.6 9.4 11 10 11C10.6 11 11 10.6 11 10Z"
    fill="currentColor"/>
</svg></span>
                                    <!--end::Svg Icon--></span>
                                <!--end::Icon-->

                                <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                                    <h4 class="mb-2 text-light">Note</h4>

                                    <span>
                                        <ul>
                                            <li>
                                      If no new product is added,the required columns are:

                                                <ol>
                                                    <li>TL-Code</li>
                                                    <li>Quantity</li>
                                                </ol>

                                            </li>
                                            <li>
In the case of adding a new product,the required columns are:
                                                <ol>
                                                    <li>TL-Code</li>
                                                    <li>Quantity</li>
                                                    <li>Slug is unique(If the <b>Slug</b> is repeated, the product will not be added)</li>
                                                </ol>
                                            </li>
                                        </ul>
                                    </span>
                                </div>

                            </div>
                            <!--end::Alert-->
                            <p class="text-gray-400 fs-4 fw-bold mb-10 mb-3">

                                <label for="formFile"
                                       class="form-label">{{trans('backend.product.upload_excel_file')}}</label>
                                <input class="form-control" type="file" id="excelFile"
                                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

                            </p>

                            <a href="{{asset('excel/Import Product.xlsx')}}" target="_blank"
                               class="btn  btn-light-primary "><i
                                    class="las la-download"></i>{{trans('backend.product.download_template')}}</a>
                            <button id="button_submit" type="submit" class="btn btn-primary"><i
                                    class="las la-upload"></i> {{trans('backend.product.upload_file')}}
                            </button>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-flush mt-3 d-none" id="card_data">
                <div class="card-body">
                    <div class="mb-0">
                        <div class="d-flex flex-stack">
                            <div class="d-flex align-items-center me-5">
                                <div class="symbol symbol-30px me-5">
																<span class="symbol-label">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen004.svg-->
																	<span class="svg-icon svg-icon-3 svg-icon-gray-600">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path
                                                                                d="M21.7 18.9L18.6 15.8C17.9 16.9 16.9 17.9 15.8 18.6L18.9 21.7C19.3 22.1 19.9 22.1 20.3 21.7L21.7 20.3C22.1 19.9 22.1 19.3 21.7 18.9Z"
                                                                                fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M11 20C6 20 2 16 2 11C2 6 6 2 11 2C16 2 20 6 20 11C20 16 16 20 11 20ZM11 4C7.1 4 4 7.1 4 11C4 14.9 7.1 18 11 18C14.9 18 18 14.9 18 11C18 7.1 14.9 4 11 4ZM8 11C8 9.3 9.3 8 11 8C11.6 8 12 7.6 12 7C12 6.4 11.6 6 11 6C8.2 6 6 8.2 6 11C6 11.6 6.4 12 7 12C7.6 12 8 11.6 8 11Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                                                                    <!--end::Svg Icon-->
																</span>
                                </div>
                                <div class="me-5">
                                    <b class="text-gray-800 fw-bolder text-hover-primary fs-6">{{trans('backend.product.total_serial_number_is_duplicated')}}</b>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bolder fs-6 me-3"
                                      id="total_serial_number_is_duplicated">0</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="d-flex align-items-center me-5">
                                <div class="symbol symbol-30px me-5">
																<span class="symbol-label">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen004.svg-->
																	<span class="svg-icon svg-icon-3 svg-icon-gray-600">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path
                                                                                d="M21.7 18.9L18.6 15.8C17.9 16.9 16.9 17.9 15.8 18.6L18.9 21.7C19.3 22.1 19.9 22.1 20.3 21.7L21.7 20.3C22.1 19.9 22.1 19.3 21.7 18.9Z"
                                                                                fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M11 20C6 20 2 16 2 11C2 6 6 2 11 2C16 2 20 6 20 11C20 16 16 20 11 20ZM11 4C7.1 4 4 7.1 4 11C4 14.9 7.1 18 11 18C14.9 18 18 14.9 18 11C18 7.1 14.9 4 11 4ZM8 11C8 9.3 9.3 8 11 8C11.6 8 12 7.6 12 7C12 6.4 11.6 6 11 6C8.2 6 6 8.2 6 11C6 11.6 6.4 12 7 12C7.6 12 8 11.6 8 11Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                                                                    <!--end::Svg Icon-->
																</span>
                                </div>
                                <div class="me-5">
                                    <b class="text-gray-800 fw-bolder text-hover-primary fs-6">{{trans('backend.product.total_serial_number_is_added')}}</b>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bolder fs-6 me-3"
                                      id="total_serial_number_is_added">0</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="d-flex align-items-center me-5">
                                <div class="symbol symbol-30px me-5">
																<span class="symbol-label">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen004.svg-->
																	<span class="svg-icon svg-icon-3 svg-icon-gray-600">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path
                                                                                d="M21.7 18.9L18.6 15.8C17.9 16.9 16.9 17.9 15.8 18.6L18.9 21.7C19.3 22.1 19.9 22.1 20.3 21.7L21.7 20.3C22.1 19.9 22.1 19.3 21.7 18.9Z"
                                                                                fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M11 20C6 20 2 16 2 11C2 6 6 2 11 2C16 2 20 6 20 11C20 16 16 20 11 20ZM11 4C7.1 4 4 7.1 4 11C4 14.9 7.1 18 11 18C14.9 18 18 14.9 18 11C18 7.1 14.9 4 11 4ZM8 11C8 9.3 9.3 8 11 8C11.6 8 12 7.6 12 7C12 6.4 11.6 6 11 6C8.2 6 6 8.2 6 11C6 11.6 6.4 12 7 12C7.6 12 8 11.6 8 11Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                                                                    <!--end::Svg Icon-->
																</span>
                                </div>
                                <div class="me-5">
                                    <b class="text-gray-800 fw-bolder text-hover-primary fs-6">{{trans('backend.product.total_not_found_product')}}</b>
                                    <span class="text-gray-400 fw-bold fs-7 d-block text-start ps-0"
                                          id="sku_not_found"></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bolder fs-6 me-3" id="total_not_found_product">0</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex flex-stack">
                            <div class="d-flex align-items-center me-5">
                                <div class="symbol symbol-30px me-5">
																<span class="symbol-label">
																	<!--begin::Svg Icon | path: icons/duotune/general/gen004.svg-->
																	<span class="svg-icon svg-icon-3 svg-icon-gray-600">
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="24" height="24" viewBox="0 0 24 24"
                                                                             fill="none">
																			<path
                                                                                d="M21.7 18.9L18.6 15.8C17.9 16.9 16.9 17.9 15.8 18.6L18.9 21.7C19.3 22.1 19.9 22.1 20.3 21.7L21.7 20.3C22.1 19.9 22.1 19.3 21.7 18.9Z"
                                                                                fill="currentColor"></path>
																			<path opacity="0.3"
                                                                                  d="M11 20C6 20 2 16 2 11C2 6 6 2 11 2C16 2 20 6 20 11C20 16 16 20 11 20ZM11 4C7.1 4 4 7.1 4 11C4 14.9 7.1 18 11 18C14.9 18 18 14.9 18 11C18 7.1 14.9 4 11 4ZM8 11C8 9.3 9.3 8 11 8C11.6 8 12 7.6 12 7C12 6.4 11.6 6 11 6C8.2 6 6 8.2 6 11C6 11.6 6.4 12 7 12C7.6 12 8 11.6 8 11Z"
                                                                                  fill="currentColor"></path>
																		</svg>
																	</span>
                                                                    <!--end::Svg Icon-->
																</span>
                                </div>
                                <div class="me-5">
                                    <b class="text-gray-800 fw-bolder text-hover-primary fs-6">{{trans('backend.product.added_products')}}</b>
                                    <span class="text-danger fw-bold fs-7 d-block text-start ps-0"
                                          id="added_products"></span>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('submit', "#upload_excel_file", function (event) {
            event.preventDefault()
            var fromData = new FormData();
            var files = $('#excelFile')[0].files;

            if (files.length > 0) {
                fromData.append('file', files[0]);
                fromData.append('_token', "{{csrf_token()}}");
                $("#button_submit").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i> {{trans('backend.global.loading')}} ').attr('disabled', 'disabled');
                $.ajax({
                    url: "{{route('backend.products.import.upload')}}",
                    method: "post",
                    data: fromData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("#total_not_found_product").html(response.data.total_not_found_product);
                        $("#total_serial_number_is_added").html(response.data.total_serial_number_is_added);
                        $("#total_serial_number_is_duplicated").html(response.data.total_serial_number_is_duplicated);
                        $("#added_products").html(response.data.added_products);
                        $("#card_data").removeClass('d-none')
                        $("#button_submit").html('<i class="las la-upload"></i> {{trans("backend.product.upload_file")}}').removeAttr('disabled');
                        success_message("{{trans('backend.media.successful_upload')}}")
                    }, error: function (xhr, status, error) {
                        var response = JSON.parse(xhr.responseText)
                        error_message(response.message);

                    },
                })
            }

        });
    </script>
@endsection


