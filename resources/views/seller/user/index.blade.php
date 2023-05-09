@extends('seller.layout.app')
@section('style')
    {!! datatable_style() !!}
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.users')}}</h3>
                @if(permission_can('create seller user' ,'seller'))
                    <div class="card-toolbar">
                        <button data-bs-toggle="modal" data-bs-target="#kt_modal_2" class="btn btn-info"><i
                                    class="bi bi-person-plus-fill fs-4 me-2"></i> {{trans('seller.user.create_new_client')}}
                        </button>
                    </div>

                    <div class="modal  fade" tabindex="-1" id="kt_modal_2">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content shadow-none">
                                <form method="post" id="FormClient">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{trans('seller.user.create_new_client')}}</h5>

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
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                           for="full_name">{{trans('seller.user.full_name')}}</label>
                                                    <input class="form-control" name="full_name" id="full_name"
                                                           type="text">
                                                    <b class="text-danger error_validation"  id="full_name_error"></b>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                           for="email_address">{{trans('seller.user.email_address')}}</label>
                                                    <input class="form-control" name="email_address" id="email_address"
                                                           type="email"></div>
                                                <b class="text-danger error_validation" id="email_address_error"></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                           for="phone">{{trans('seller.user.phone_number')}}</label>
                                                    <input class="form-control" name="phone" id="phone"
                                                           type="tel"></div>
                                                <b class="text-danger error_validation" id="phone_number_error"></b>

                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                           for="type_of_business">{{trans('seller.user.type_of_business')}}</label>
                                                    <input class="form-control" name="type_of_business"
                                                           id="type_of_business" type="text"></div>
                                                <b class="text-danger error_validation" id="type_of_business_error"></b>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                           for="company_name">{{trans('seller.user.company_name')}}</label>
                                                    <input class="form-control" name="company_name" id="company_name"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="company_name_error"></b>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group"><label class="form-label"
                                                                               for="website_url">{{trans('seller.user.website_url')}}</label>
                                                    <input class="form-control" name="website_url" id="website_url"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="website_url_error"></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label required"
                                                           for="country">{{trans('seller.user.country')}}</label>
                                                    <select class="form-control" data-control="select2" name="country"
                                                            id="country">
                                                        @foreach($countries as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>

                                                    <b class="text-danger error_validation" id="country_error"></b>
                                                </div>

                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group"><label class="form-label"
                                                                               for="state">{{trans('seller.user.state')}}</label>
                                                    <input class="form-control" name="state" id="state"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="state_error"></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group"><label class="form-label required"
                                                                               for="city">{{trans('seller.user.city')}}</label>
                                                    <input class="form-control" name="city" id="city"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="city_error"></b>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group"><label class="form-label required"
                                                                               for="street">{{trans('seller.user.street')}}</label>
                                                    <input class="form-control" name="street" id="street"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="street_error"></b>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <div class="form-group"><label class="form-label"
                                                                               for="postal_code">{{trans('seller.user.postal_code')}}</label>
                                                    <input class="form-control" name="postal_code" id="postal_code"
                                                           type="text"></div>
                                                <b class="text-danger error_validation" id="postal_code_error"></b>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <div class="form-group"><label class="form-label required"
                                                                               for="full_address">{{trans('seller.user.full_address')}}</label>
                                                    <textarea class="form-control" name="full_address" id="full_address"
                                                              style="resize: none" rows="3"></textarea>
                                                    <b class="text-danger error_validation" id="full_address_error"></b>
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
                                                             style="background-image: url({{ old('avatar',asset('backend/media/avatars/blank.png'))}})">
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
                                                                    data-kt-image-input-action="cancel"
                                                                    data-bs-toggle="tooltip"
                                                                    title=""
                                                                    data-bs-original-title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                                            <span
                                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                    data-kt-image-input-action="remove"
                                                                    data-bs-toggle="tooltip"
                                                                    title=""
                                                                    data-bs-original-title="Remove avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
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
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
{{--                {!! select_status() !!}--}}

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th></th>
                            <th>{{trans('backend.global.uuid')}}</th>
                            <th>{{trans('backend.user.name')}}</th>
                            <th>{{trans('backend.user.email')}}</th>
                            <th>{{trans('backend.user.phone')}}</th>
                            <th>{{trans('backend.user.orders_count')}}</th>
                            <th>{{trans('backend.user.purchase_value')}}</th>
                            <th>{{trans('backend.user.avg_purchase_value')}}</th>
                            <th>{{trans('backend.global.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Card Body-->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

    {!! datatable_script() !!}
    {!! $datatable_script !!}
    <script>
        $(document).ready(function () {
            $("#country").select2({
                dropdownParent: $('#kt_modal_2')
            });
        })
        $(document).on('submit', "#FormClient", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $("#btn_client_save").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i> {{trans('backend.global.loading')}}');
            $("#btn_client_save").attr('disabled', 'disabled');
            $.ajax({
                url: "{{route('seller.users.store')}}",
                method: "post",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (response) {
                    var data_error = ['full_name_error', 'email_error', 'phone_number_error', 'type_of_business_error', 'company_name_error', 'website_url_error', 'country_error', 'state_error', 'city_error', 'street_error', 'full_address_error'];
                    for (var i = 0; i < data_error.length; i++) {
                        $("#" + data_error[i]).html('');
                    }
                    var data = ['full_name', 'phone', 'email_address', 'phone_number', 'type_of_business', 'company_name', 'website_url', 'country', 'state', 'city', 'street', 'postal_code', 'full_address'];
                    for (var i = 0; i < data.length; i++) {
                        $("#" + data[i]).val('');
                    }
                    $('span[data-kt-image-input-action="cancel"]').click();
                    $("#kt_modal_2").modal('hide');
                    dt.ajax.reload()
                    $("#btn_client_save").html('<i class="fa fa-save"></i> {{trans('backend.global.save')}}');
                    $("#btn_client_save").removeAttr('disabled');
                    $(".error_validation").html("")
                },  error: function (xhr, status, error) {

                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);


                    var obj = xhr.responseJSON.errors;
                    $(".error_validation").html("")
                    for (var key in obj) {
                        var value = obj[key];
                        $("#"+key+"_error").html("<i class='fa fa-info-circle text-danger'></i> " + value);

                    }

                    $("#btn_client_save").html('<i class="fa fa-save"></i> {{trans('backend.global.save')}}');
                    $("#btn_client_save").removeAttr('disabled');

                },
            })
        })
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            initialCountry: "tr",
            hiddenInput: "phone_number",
            utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
        });


    </script>
@endsection
