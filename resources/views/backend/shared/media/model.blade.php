<div class="modal   fade" tabindex="-1" id="FileMangerModel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-none">
            <div class="modal-header">
                <input type="hidden" id="FileMangerCountImage" value="1">
                <input type="hidden" id="FileMangerIdInput" value="1">
                <input type="hidden" id="FileMangerAutoClose" value="1">
                <h5 class="modal-title">{{trans('backend.global.media')}}</h5>

                <!--begin::Close-->

                <div class="row">
                    <div id="base_control" class=" col-12 ">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                              height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                              fill="currentColor"></rect>
														<path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="currentColor"></path>
													</svg>
												</span>
                                    <input type="text" class="form-control form-control-solid w-250px ps-15"
                                           id="media_search"
                                           placeholder="Search Files...">
                                </div>

                            </div>
                            @if(permission_can('create new folder' ,'admin'))
                            <div class="col">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <button class="btn btn-primary btn-icon" id="btn_create_new_folder">


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

                            </div>
                                @endif
                        </div>
                    </div>
                    @if(permission_can('create new folder' ,'admin'))
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
                    @endif
                </div>

                <!--end::Close-->
            </div>

            <div class="modal-body">
                {{--                create folder--}}
                <form>
                    <div class="row d-none" id="create_new_folder">

                        <div class="col-12 col-md-7">
                            <input type="text" class="form-control"
                                   placeholder="{{trans('backend.media.folder_name')}}"></div>
                        <div class="col-6 col-md-2">
                            <button class="btn btn-primary w-100">{{trans('backend.media.save_folder')}} <i
                                    class=" fa fa-folder-plus"></i></button>
                        </div>
                        <div class="col-6 col-md-3">
                            <button class="btn btn-danger w-100">{{trans('backend.media.close_create_folder')}}</button>
                        </div>


                    </div>
                </form>
                {{--                tabs--}}
                <div class="row mb-2" id="dev_gallery">
                    <div class="d-flex flex-stack">
                        <div class="badge badge-lg badge-light-primary">
                            <div class="d-flex align-items-center flex-wrap" id="file_manager_items_path">

                            </div>
                        </div>
                        <div class="badge badge-lg badge-primary">
                            <span><span id="file_manager_items_counter"></span> {{trans('backend.global.items')}}</span>
                        </div>


                    </div>

                </div>
                <ul class="nav mt-5 nav-tabs file_manger_content nav-line-tabs mb-5 fs-6"
                    id="file_manger_content_languages">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"
                           href="#kt_media_tab_pane_1">{{trans('backend.global.media')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab"
                           href="#kt_media_tab_pane_2">{{trans('backend.media.upload_files')}}</a>
                    </li>

                </ul>
                <div class="tab-content file_manger_content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_media_tab_pane_1" role="tabpanel">
                        <div class="row" id="FileMangerModelBody">

                        </div>
                        <div class="row" id="FileMangerModelBodyReview">
                            <div class="col-12 text-center">
                                <img src="" class="border w-100" id="image_preview" alt="">
                            </div>
                            <div class="col-12 text-center">
                                <button
                                    class="btn mt-4   w-25 btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger"
                                    id="close_image_preview"><i
                                        class="la la-times"></i> {{trans('backend.global.close')}}</button>
                            </div>
                        </div>

                        <div class="row mt-4" id="FileMangerModelMediaSelected">

                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_media_tab_pane_2" role="tabpanel">


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
                <div class="row" id="div_cut" style="display:none;">
                    <div class="col-12" style="height: 400px;overflow-y: scroll">
                        <table
                            class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer overflow-scroll">
                            <tbody id="table_cut"></tbody>

                        </table>
                    </div>
                    <div class="col-12">
                        <div class="row w-100">

                            <div class="col" style="direction: rtl">
                                <button class="  ms-2 me-2 btn-icon  btn btn-primary paste" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="tooltip-dark" title="paste"><i
                                        class="las la-paste"></i></button>
                                <button data-bs-custom-class="tooltip-dark" type="button" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="cancel " onclick="close_cut()"
                                        class="ms-2 me-2 btn-icon btn btn-danger"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer" id="model_footer_file_manger">
                <div class="row w-100">
                    <div class="col-4   text-center" id="FileMangerModelPagination"></div>

                    <div class="col-8 " style="text-align: end">
                        <button type="button" class="btn btn-info"
                                id="MediaFileMangerSaveMultiMedia">{{trans('backend.global.save')}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

