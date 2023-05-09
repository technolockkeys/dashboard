@extends('seller.layout.app')
@section('style')
    {!! datatable_style() !!}
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    <style>
        .iti {
            width: 100% !important;
        }

        .dataTables_scrollHeadInner {
            width: 100%;
        }
    </style>
@endsection
@section('content')

    <input type="hidden" id="user_id" value="{{$user->id}}">
    <div id="kt_content_container" class="container-xxl">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body pt-15">
                        <div class="d-flex flex-center flex-column mb-5">
                            <div class="symbol symbol-100px symbol-circle mb-7">
                                <img src="{{asset($user->avatar)}}" id="info_avatar"
                                     onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'" alt="image">
                            </div>
                            <a id="information_name"
                               class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{$user->name}}</a>

                            <div id="information_email" class="fs-5 fw-bold text-muted mb-6">{{$user->email}}</div>

                        </div>
                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                                 href="#kt_customer_view_details" role="button" aria-expanded="false"
                                 aria-controls="kt_customer_view_details">{{trans('seller.user.details')}}
                                <span class="ms-2 rotate-180">
														<span class="svg-icon svg-icon-3">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="currentColor"></path>
															</svg>
														</span>
													</span></div>

                            @if(permission_can('edit seller user','seller'))
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title=""
                                      data-bs-original-title="Edit customer details">
														<button type="button" class="btn btn-sm btn-light-primary"
                                                                id="edit_user">{{trans('backend.global.edit')}}</button>
													</span>
                            @endif

                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div id="kt_customer_view_details" class="collapse show">
                            <div class="py-5 fs-6">
                                <div class="fw-bolder mt-5">{{trans('seller.user.account_id')}}</div>
                                <div class="text-gray-600">{{$user->uuid}}</div>
                                <div class="fw-bolder mt-5"> {{trans('seller.user.email_address')}}</div>
                                <div class="text-gray-600" id="info_email">
                                    {{$user->email}}
                                </div>
                                @php
                                    $addresses = $user->addresses()->where('is_default' , 1)->get();

                                @endphp
                                @if(!empty($addresses) && !empty($addresses[0]))

                                    <div class="fw-bolder mt-5">{{trans('backend.user.address')}}</div>
                                    <div class="text-gray-600">
                                        <span id="info_country">{{$addresses[0]->get_country()}}</span>
                                        <br><span id="info_city">{{$addresses[0]->city}}</span>
                                        <br><span id="info_address">{{$addresses[0]->address}}</span>
                                    </div>
                                @endif

                                @if(!empty($user->company_name))
                                    <div class="fw-bolder mt-5">{{trans('seller.user.company_name')}}</div>
                                    <div class="text-gray-600" id="info_company_name">{{$user->company_name}}</div>
                                @endif

                                @if(!empty($user->website_url))
                                    <div class="fw-bolder mt-5">{{trans('seller.user.website_url')}}</div>
                                    <div class="text-gray-600" id="info_website_url">{{$user->website_url}}</div>
                                @endif
                                @if(!empty($user->type_of_business))
                                    <div class="fw-bolder mt-5"> {{trans('seller.user.type_of_business')}}</div>
                                    <div class="text-gray-600"
                                         id="info_type_of_business">{{$user->type_of_business}}</div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-15">
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                    @if(permission_can('show payment recodes seller user' ,'seller'))
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4  active" data-bs-toggle="tab" id="payment"
                               href="#kt_customer_view_payment_recodes">{{trans('seller.user.payment_recodes')}} </a>
                        </li>
                    @endif
                    @if(permission_can('show address seller user' ,'seller'))
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 " data-kt-countup-tabs="true"
                               data-bs-toggle="tab" id="addresses"
                               href="#esg_customers_addresses">{{trans('seller.user.addresses')}}</a>
                        </li>
                    @endif
                    @if(permission_can('show orders seller user' ,'seller'))
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                               id="order"
                               href="#esg_customers_orders">{{trans('seller.user.orders')}}</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if(permission_can('show payment recodes seller user' ,'seller'))
                        @include('seller.user.tabs.payment_recodes')
                    @endif
                    @if(permission_can('show address seller user' ,'seller'))
                        @include('seller.user.tabs.addesses')
                    @endif
                    @if(permission_can('show orders seller user' ,'seller'))
                        @include('seller.user.tabs.orders')
                    @endif


                </div>
            </div>
        </div>
    </div>
    @if(permission_can('edit seller user','seller'))
        <div class="modal fade" id="edit_model_user" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="FormClient" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('seller.user.edit')}} <b id="user_id">{{$user->uuid}}</b>
                            </h5>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                 data-bs-dismiss="modal" aria-label="Close">
                                <span class="svg-icon svg-icon-2x"></span>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="full_name">{{trans('seller.user.full_name')}}</label>
                                        <input class="form-control" name="full_name" id="full_name"
                                               value="{{$user->name}}"
                                               type="text">
                                        <b class="text-danger" id="full_name_error"></b>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="email_address">{{trans('seller.user.email_address')}}</label>
                                        <input class="form-control" name="email_address" value="{{$user->email}}"
                                               id="email_address"
                                               type="email"></div>
                                    <b class="text-danger" id="email_error"></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="phone">{{trans('seller.user.phone_number')}}</label>
                                        <input class="form-control" name="phone" id="phone" value="{{$user->phone}}"
                                               type="text"></div>
                                    <b class="text-danger" id="phone_number_error"></b>

                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label"
                                                                   for="type_of_business">{{trans('seller.user.type_of_business')}}</label>
                                        <input class="form-control" name="type_of_business"
                                               value="{{$user->type_of_business}}"
                                               id="type_of_business" type="text"></div>
                                    <b class="text-danger" id="type_of_business_error"></b>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label"
                                                                   for="company_name">{{trans('seller.user.company_name')}}</label>
                                        <input class="form-control" name="company_name" id="company_name"
                                               value="{{$user->company_name}}"
                                               type="text"></div>
                                    <b class="text-danger" id="company_name_error"></b>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label"
                                                                   for="website_url">{{trans('seller.user.website_url')}}</label>
                                        <input class="form-control" name="website_url" id="website_url"
                                               value="{{$user->website_url}}"
                                               type="text"></div>
                                    <b class="text-danger" id="website_url_error"></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="country">{{trans('seller.user.country')}}</label>
                                        <select class="form-control" data-control="select2" name="country"
                                                id="country">
                                            @foreach($countries as $item)
                                                <option @if($item->id == $user->country_id) selected
                                                        @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>

                                        <b class="text-danger" id="country_error"></b>
                                    </div>

                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group"><label class="form-label"
                                                                   for="state">{{trans('seller.user.state')}}</label>
                                        <input class="form-control" name="state" id="state" value="{{$user->state}}"
                                               type="text"></div>
                                    <b class="text-danger" id="state_error"></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="city">{{trans('seller.user.city')}}</label>
                                        <input class="form-control" name="city" id="city" value="{{$user->city}}"
                                               type="text"></div>
                                    <b class="text-danger" id="city_error"></b>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="street">{{trans('seller.user.street')}}</label>
                                        <input class="form-control" name="street" id="street" value="{{$user->street}}"
                                               type="text"></div>
                                    <b class="text-danger" id="street_error"></b>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group"><label class="form-label"
                                                                   for="postal_code">{{trans('seller.user.postal_code')}}</label>
                                        <input class="form-control" name="postal_code" id="postal_code" value="{{$user->postal_code}}"
                                               type="text"></div>
                                    <b class="text-danger" id="postal_code_error"></b>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group"><label class="form-label required"
                                                                   for="full_address">{{trans('seller.user.full_address')}}</label>
                                        <textarea class="form-control" name="full_address" id="full_address"
                                                  style="resize: none" rows="3">{{$user->address}}</textarea>
                                        <b class="text-danger" id="full_address_error"></b>
                                    </div>
                                </div>


                            </div>


                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group ">

                                        <label
                                            class=" form-label fw-bold m-4   mx-auto">{{trans('backend.profile.avatar')}}</label>

                                        <div class=" mx-auto">
                                            <div class="image-input image-input-outline image-input-empty"
                                                 data-kt-image-input="true"
                                                 style="background-image: url({{ old('avatar',(!empty($user->avatar) ? asset($user->avatar)  : asset('backend/media/avatars/blank.png')) )}})">
                                                <div
                                                    class="image-input-wrapper text-center z-index-3 w-125px h-125px p-5"
                                                    style="background-image: none">
                                                </div>
                                                <label
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow "
                                                    data-kt-image-input-action="change"
                                                    data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Change avatar">
                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                    <input type="file" name="avatar"
                                                           value="{{old('avatar')}}"
                                                           accept=".png, .jpg, .jpeg">
                                                    <input type="hidden" name="avatar_remove">
                                                </label>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Cancel avatar"><i
                                                        class="bi bi-x fs-2"></i></span>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Remove avatar"><i
                                                        class="bi bi-x fs-2"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                            <button type="submit" id="btn_client_save" class="btn btn-primary"><i
                                    class="la la-save"></i>{{trans('backend.global.save')}}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection
@section('script')
    <script>
        var user = {
            user_id: '{{$user->id}}',
        };
        var token = '{{csrf_token()}}';
        var overview_error = {
            'name': "{{old('name' ,"")}}",
        };
        var user_routes = {
            payment_info: "{{route('seller.users.wallet.payment.info')}}",
            payment_change: "{{route('seller.users.wallet.payment.change.status')}}",
            payment_get: "{{route('seller.users.wallet.payment.get')}}",
            payment_set: "{{route('seller.users.wallet.payment.set')}}"
        };
        //load cities function

    </script>
    <script src="{{asset('backend/js/user.js')}}"></script>
    <script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $datatable_script_order !!}
    {!! $datatable_script_payment !!}
    <script>
        //region document ready
        $(document).ready(function () {
            $("#form_payment_recode_orders_id").select2({
                dropdownParent: $('#add_payment_recodes')
            });
            $("#new_address_country").select2({
                dropdownParent: $('#add_form_address')
            });
            $("#edit_address_country").select2({
                dropdownParent: $('#edit_form_address_model')
            })
            $("#country").select2({
                dropdownParent: $('#FormClient')
            })
            // dt_orders.ajax.reload();

        });
        //endregion
    </script>
    <script>
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            initialCountry: "tr",
            hiddenInput: "full_phone",
            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });
        //region edit user
        $(document).on('click', "#edit_user", function () {
            $("#edit_model_user").modal('show');
        });
        $(document).on('submit', '#FormClient', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{route('seller.users.update',['uuid'=>$user->uuid])}}",
                method: "post",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $("#information_name").text(response.data.user.name);
                    $("#information_email").text(response.data.user.email);
                    $("#info_country").html(response.data.user.country);
                    $("#info_city").html(response.data.user.city);
                    $("#info_address").html(response.data.user.address);
                    $("#info_company_name").html(response.data.user.company_name);
                    $("#info_website_url").html(response.data.user.website_url);
                    $("#info_type_of_business").html(response.data.user.type_of_business);
                    $("#info_type_of_business").html(response.data.user.type_of_business);
                    $("#info_avatar").removeAttr("src");
                    $("#info_avatar").attr("src", response.data.user.image_url);
                    document.getElementById('info_avatar').src = response.data.user.image_url;
                    $("#edit_model_user").modal('hide');
                    success_message(response.data.message)
                },
                error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                },
            });
        });
        //endregion

        //region addresses

        //region create address

        var input_phone = document.querySelector("#new_address_phone");

        window.intlTelInput(input_phone, {
            initialCountry: "tr",
            hiddenInput: "address_full_phone",
            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });


        $(document).on('click', '#create_new_address_for_customer', function () {
            $("#add_form_address").modal('show');
        });

        $(document).on('submit', "#form_new_address", function (event) {
            event.preventDefault();
            $("#btn_new_address").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> {{trans('backend.global.loading')}}');
            $("#btn_new_address").attr('disabled', 'disabled');
            $.ajax({
                url: "{{route('seller.users.address.create')}}",
                method: "post",
                data: $("#form_new_address").serialize(),
                success: function (response) {
                    success_message(response.data.message);
                    dt_addresses.ajax.reload();
                    $("#new_address_country").val("");
                    $("#new_address_state").val("");
                    $("#new_address_city").val("");
                    $("#new_address_street").val("");
                    $("#new_address_full_address").val("");
                    $("#new_address_postal_code").val("");
                    $("#new_address_phone").val("");
                    $("#btn_new_address").html("<i class='fa  fa-save'></i> {{trans('backend.global.save')}}");
                    $("#btn_new_address").removeAttr('disabled');
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                    $("#btn_new_address").html("<i class='fa  fa-save'></i> {{trans('backend.global.save')}}");
                    $("#btn_new_address").removeAttr('disabled');
                },
            })
        });
        //endregion

        //region delete address ..
        $(document).on('click', ".delete-address", function () {
            var id = $(this).data('id');
            var uuid = $(this).data('user');
            $(this).html("<i class='fa fa-spinner fa-pulse fa-fw'></i>");
            $.ajax({
                url: "{{route('seller.users.address.delete')}}",
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}",
                    'id': id,
                    'uuid': uuid
                },
                success: function (response) {
                    success_message(response.data.message);
                    dt_addresses.ajax.reload();
                }
            })
        })
        //endregion

        //region edit address
        var edit_address_phone = document.querySelector("#edit_address_phone");
        window.intlTelInput(edit_address_phone, {
            initialCountry: "tr",
            hiddenInput: "address_full_phone",
            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });
        $(document).on('click', '.edit_address', function () {
            var data = $(this).data('address');
            console.log(data)
            $("#edit_form_address_model").modal('show');
            $('#edit_address_country').val(data.country_id).trigger('change');
            $('#edit_address_state').val(data.state);
            $('#edit_address_city').val(data.city);
            $('#edit_address_full_address').val(data.address);
            $('#edit_address_street').val(data.street);
            $('#edit_address_postal_code').val(data.postal_code);
            $('#edit_address_phone').val(data.phone);
            $('#edit_address_id').val(data.id);

            $("#edit_as_default_address").removeAttr( 'checked')
            $("#edit_as_default_address").removeProp( 'checked')
            if (data.is_default != 0) {
                $("#edit_as_default_address").attr('checked', 'checked')
            }
            var edit_address_phone = document.querySelector("#edit_address_phone");

            window.intlTelInput(edit_address_phone, {
                initialCountry: "tr",
                hiddenInput: "address_full_phone",
                utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
            });
        });
        $(document).on('submit', '#edit_form_address', function (event) {
            event.preventDefault();
            $("#btn_edit_address").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> {{trans('backend.global.loading')}}');
            $("#btn_edit_address").attr('disabled', 'disabled');
            $.ajax({
                url: "{{route('seller.users.address.edit')}}",
                method: "post",
                data: $("#edit_form_address").serialize(),
                success: function (response) {
                    success_message(response.data.message);
                    dt_addresses.ajax.reload();
                    $("#edit_address_country").val("");
                    $("#edit_address_state").val("");
                    $("#edit_address_city").val("");
                    $("#edit_address_street").val("");
                    $("#edit_address_full_address").val("");
                    $("#edit_address_postal_code").val("");
                    $("#edit_address_phone").val("");
                    $("#edit_as_default_address").removeAttr( 'checked')
                    $("#edit_as_default_address").removeProp( 'checked')
                    $("#btn_edit_address").html("<i class='fa  fa-save'></i> {{trans('backend.global.save')}}");
                    $("#btn_edit_address").removeAttr('disabled');
                    $("#edit_form_address_model").modal('hide');
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                    $("#btn_edit_address").html("<i class='fa  fa-save'></i> {{trans('backend.global.save')}}");
                    $("#btn_edit_address").removeAttr('disabled');
                },
            })
        });
        //endregion


        //endregion

        $(document).on('click', '#send-reminder', function () {
            var url = '{{ route("seller.users.payment_recodes.send.reminder")}}';
            $.ajax({
                url: url,
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    id: '{{$user->id}}'
                },
                success: function (response) {
                    success_message(response.data.message);
                },
                error: function (response) {
                    error_message(response.responseJSON.message)
                }
            })
        });
        $(document).on('click', '#send-account-statement', function () {
            var url = '{{ route("seller.users.payment_recodes.send.account-statement")}}';
            $.ajax({
                url: url,
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    id: '{{$user->id}}'
                },
                success: function (response) {
                    success_message(response.data.message);
                },
                error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                }
            })
        });
        //region payment recodes
        $(document).on('click', "#create_new_payment_recode", function () {
            $("#form_payment_recode_orders_id").empty();
            $.ajax({
                url: "{{route('seller.users.payment_recodes.get.balanec')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    uuid: "{{$user->uuid}}"
                }, success: function (response) {
                    $("#add_payment_balance").val(response.data.balance);

                    $("#form_payment_recode_orders_id").append("<option value=''>{{trans('seller.orders.please_select_option')}}</option>");
                    for (var i = 0; i < response.data.orders.length; i++) {
                        $("#form_payment_recode_orders_id").append("<option value='" + response.data.orders[i].uuid + "'>( " + response.data.orders[i].uuid + " / " + response.data.orders[i].created_at + " ) " + response.data.orders[i].balance + " </option>");
                    }
                    $("#add_payment_recodes").modal('show');
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                }
            })
        });
        $(document).on('submit', "#form_payment_recode", function (event) {

            event.preventDefault();

            var html_btn = $("#button_create_new_payment").html();
            $("#btn_save_payment").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>');
            $("#btn_save_payment").attr('disabled', 'disabled');

            var formData = new FormData();
            formData.append('user_id', $("#form_payment_recode_user_id").val());
            formData.append('order', $("#form_payment_recode_orders_id").val());
            formData.append('amount', $("#add_payment_amount").val());
            formData.append('note', $("#add_payment_note").val());
            var ins = document.getElementById('add_payment_files').files.length;
            for (var x = 0; x < ins; x++) {
                formData.append("files[]", document.getElementById('add_payment_files').files[x]);
            }
            formData.append('_token', '{{csrf_token()}}')

            $.ajax({
                url: "{{route('seller.users.payment_recodes.set.balanec')}}",
                method: "post",
                cache: false,
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    dt_payment.ajax.reload();
                    $("#add_payment_recodes").modal('hide');
                    // $("#form_payment_recode_user_id").val('');
                    $("#form_payment_recode_orders_id").val('');
                    $("#add_payment_amount").val('');
                    $("#add_payment_note").val('');
                    $("#add_payment_files").val('');
                    $("#btn_save_payment").html('<i class="la la-save"></i> {{trans('backend.global.save')}}')
                    $("#btn_save_payment").removeAttr('disabled');
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);

                    $("#btn_save_payment").html('<i class="la la-save"></i> {{trans('backend.global.save')}}')
                    $("#btn_save_payment").removeAttr('disabled');
                }
            })
        });
        $(document).on('click', '.show_transfer_order', function () {
            var id = $(this).data('id');

            $("#row_balance_modal").hide();
            var btn = $(this);
            $(this).html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>').addClass('btn-icon').attr('disabled', 'disabled');
            $.ajax({
                url: "{{route('seller.users.payment.show')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    id: id
                },
                success: function (response) {
                    $("#modal_order_price").val(response.data.order.total)
                    $("#modal_order_balance").val(response.data.order.wallet)
                    $("#modal_order_payment_method").val(response.data.order.payment_method)
                    $("#modal_order_amount").val(response.data.wallet.amount)
                    $("#transfer_type").val('credit').trigger('change');
                    $("#modal_value").val(0);
                    $("#row_balance_modal").hide();
                    $("#transfer_id_form").val(id)

                    // $("#modal_value").attr('max', parseFloat(parseFloat(response.data.order.wallet) * -1))
                    $("#files_modal").html('');
                    if (response.data.wallet.files.length == 0) {
                        $("#row_filess").hide()
                    } else {
                        $("#row_filess").show()
                    }
                    for (var i = 0; i < response.data.wallet.files.length; i++) {
                        $("#files_modal").append('<a class="form-control form-control-solid w-100 mt-3" href="storage/' + response.data.wallet.files[i] + '" target="_blank"><i class="fa fa-file"></i> ' + (i + 1) + ' file</a>')
                    }

                    btn.html('<i class="la la-wallet"></i> {{trans('backend.global.show')}}').removeClass('btn-icon').removeAttr('disabled');
                    if (response.data.wallet.status == 'approve') {
                        $("#transfer_data_save").attr('disabled', 'disabled')
                    } else {
                        $("#transfer_data_save").removeAttr('disabled')
                    }
                    $("#modal_transfer_order").modal('show')
                }
            });

        });

        //endregion

        const tabEl = document.querySelector('a[data-bs-toggle="tab"]')

        tabEl.addEventListener('shown.bs.tab', event => {
            event.target // newly activated tab
            event.relatedTarget // previous active tab

        })
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', (event) => {
            window['dt_' + event.target.id].ajax.reload();
        })

    </script>
    @include('backend.user_wallet.script')
@endsection
