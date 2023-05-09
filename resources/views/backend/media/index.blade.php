@extends('backend.layout.app')
@section('title',trans('backend.menu.media').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <input type="hidden" id='is_midea_page' value="1">
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="files_manger" role="tabpanel">
            <div class="card card-flush">
                <div class="card-header pt-8">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                              height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                              fill="currentColor"/>
														<path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="currentColor"/>
													</svg>
												</span>
                            <input type="text" class="form-control form-control-solid w-250px ps-15" id="media_search"
                                   placeholder="Search Files..."/>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-filemanager-table-toolbar="base" id="">
                            <div id="base_control">
                                @if(permission_can('delete media' , 'admin'))
                                    <button type="button"
                                            class=" me-3 btn-icon btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger"
                                            id="delete_media"><i class="fa fa-trash"></i></button>
                                @endif
                                @if(permission_can('upload media', 'admin'))
                                    <button type="button" class="  btn btn-primary tab-pane" id="upload_button">
                                <span class="svg-icon svg-icon-2">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z"
                                                              fill="currentColor"/>
														<path
                                                            d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM16 11.6L12.7 8.29999C12.3 7.89999 11.7 7.89999 11.3 8.29999L8 11.6H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H16Z"
                                                            fill="currentColor"/>
														<path opacity="0.3"
                                                              d="M11 11.6V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V11.6H11Z"
                                                              fill="currentColor"/>
													</svg>
												</span>

                                        {{trans('backend.media.upload_files')}}
                                    </button>
                                @endif
                                @if(permission_can('create new folder' ,'admin') )
                                    <button type="button" class="btn btn-success"
                                            id="btn_create_new_folder">
                                    <span class="svg-icon svg-icon-muted "><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
<path
    d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.2C9.7 3 10.2 3.20001 10.4 3.60001ZM16 12H13V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V12H8C7.4 12 7 12.4 7 13C7 13.6 7.4 14 8 14H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z"
    fill="currentColor"/>
<path opacity="0.3"
      d="M11 14H8C7.4 14 7 13.6 7 13C7 12.4 7.4 12 8 12H11V14ZM16 12H13V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z"
      fill="currentColor"/>
</svg></span>
                                        {{trans('backend.media.create_new_folder')}}</button>
                                @endif
                            </div>
                            <div class="d-none" id="create_new_folder">
                                <form class="row" id="form_create_new_folder">
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control w-100" id="folder_name"
                                               placeholder="{{trans('backend.media.folder_name')}}"></div>
                                    <div class="col-6 col-md-2">
                                        <button class="btn btn-icon btn-primary ">


                                            <span class="svg-icon svg-icon-muted "><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
<path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
<path
    d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.2C9.7 3 10.2 3.20001 10.4 3.60001ZM16 12H13V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V12H8C7.4 12 7 12.4 7 13C7 13.6 7.4 14 8 14H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z"
    fill="currentColor"/>
<path opacity="0.3"
      d="M11 14H8C7.4 14 7 13.6 7 13C7 12.4 7.4 12 8 12H11V14ZM16 12H13V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z"
      fill="currentColor"/>
</svg></span>


                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button id="close_create_new_folder" type="button"
                                                class="btn btn-danger btn-icon "><i class="fa fa-times"></i></button>
                                    </div>


                                </form>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="card-body">
                    <div class="container-xxl">

                        <div class="d-flex flex-stack" id="hide_when_cut">
                            <div class="badge badge-lg badge-light-primary">
                                <div class="d-flex align-items-center flex-wrap" id="file_manager_items_path">

                                </div>
                            </div>
                            <div class="badge badge-lg badge-primary">
                                <span><span id="file_manager_items_counter">{{ $count_files  }}</span> {{trans('backend.global.items')}}</span>
                            </div>


                        </div>
                        <div class="row g-6 g-xl-9 mb-6 mb-xl-9 mt-2 file_manger_content" id="FileMangerModelBody">
                        </div>
                        <div class="row g-6 g-xl-9 mb-6 mb-xl-9 mt-2 file_manger_content" id="FileMangerModelBodyReview">
                            <div class="col-12 text-center">
                                <img src="" class="border w-100" id="image_preview" alt="">
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn mt-4   w-25 btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger" id="close_image_preview"><i class="la la-times"></i> {{trans('backend.global.close')}}</button>
                            </div>
                        </div>
                        <div class="row" id="div_cut" style="display:none;   ">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                <tbody id="table_cut"  ></tbody>
                                <tfoot id="table_cut_foot">
                                <tr>
                                    <td>
                                        <div class="row w-100">

                                            <div class="col" style="direction: rtl">
                                                <button class="  ms-2 me-2 btn-icon  btn btn-primary paste"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-custom-class="tooltip-dark" title="paste"><i
                                                        class="las la-paste"></i></button>
                                                <button data-bs-custom-class="tooltip-dark" type="button"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="cancel "
                                                        onclick="close_cut()" class="ms-2 me-2 btn-icon btn btn-danger">
                                                    <i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row  " id="model_footer_file_manger">
                            <div class="col text-center">
                                <div class="btn-group mr-2" role="group" aria-label="First group" id="media_pagination">
                                    @for(  $i = 1 ; $i<= ( ceil($count_files / $pagination) )  && $i<=3  ; $i++)
                                        <button onclick="MediaFileMangerGet({{$i}})" type="button"
                                                data-id="{{$i}}"
                                                class="btn pagination_media btn-info {{($i == 1 ? "active":  " ")}}">{{$i}}</button>
                                    @endfor

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!--end::Card body-->
            </div>
        </div>

        <div class="tab-pane fade" id="upload_tab" role="tabpanel">

            <div class="card card-flush">

                <div class="card-header">
                    <div class="card-title">
                        <h3>{{trans('backend.media.upload_files')}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-filemanager-table-toolbar="base">
                            <!--begin::Export-->
                            <button type="button" class="btn btn-light-primary me-3" id="all_files">
                                <span class="svg-icon svg-icon-2"><i class="las la-backward"></i></span>
                                <!--end::Svg Icon-->{{trans('backend.global.back')}}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin::Form-->
                    <form class="form" action="#" method="post">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Dropzone-->
                            <div class="dropzone    rounded-3 border-dashed border-primary " id="dropzonejs">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <!--begin::Icon-->
                                    <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                    <!--end::Icon-->

                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="fs-5 mt-5 fw-bolder text-gray-900 mb-1">{{trans('backend.media.drop_files_here_or_click_to')}}</h3>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <!--end::Dropzone-->
                        </div>
                        <!--end::Input group-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>

        </div>

    </div>
    @if(permission_can('edit media' , 'admin'))
        <div class="modal fade" tabindex="-1" id="details_model">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{trans('backend.media.details')}}</h5>


                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <span class="svg-icon svg-icon-2x"></span>
                        </div>

                    </div>

                    <div class="modal-body" id="media_modal_body">

                    </div>


                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>

    </script>
    <script src="{{asset("backend/js/media.js")}}"></script>

@endsection
