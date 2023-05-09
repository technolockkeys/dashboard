<link href="{{asset("backend/plugins/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">


<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Title-->
        <div class="card-title">
            <h3>Billing Address</h3>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body">
        <!--begin::Addresses-->
        <div class="row gx-9 gy-6">
            <!--begin::Col-->
            @foreach($addresses as $key => $address)
                <div class="col-xl-6">
                    <!--begin::Address-->
                    <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                        <!--begin::Details-->
                        <div class="d-flex flex-column py-2">
                            <div
                                class="d-flex align-items-center fs-5 fw-bolder mb-5">{{trans('backend.address.address',['number'=> $key +1])}}
                                @if($address->is_default)<span
                                    class="badge badge-light-success fs-7 ms-2">{{trans('backend.address.primary')}}</span>@endif
                            </div>
                            <div class="fs-6 fw-bold text-gray-600">
                                {{$address->address}}
                                <br>{{$address->city}}
                                <br>{{$address->get_country()}}
                            </div>
                        </div>
                        <!--end::Details-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center py-2">
                            @if($address->is_default == 0)
                                @if(permission_can('edit user address','admin'))
                                <button href="{{route('backend.addresses.set-default', ['address' => $address->id])}}"
                                        class="btn btn-sm btn-light  btn-text-success set-default me-3">
                                    {{trans('backend.address.set_default')}}
                                </button>
                                @endif
                                @if(permission_can('delete user address','admin'))
                                    <button href="{{route('backend.addresses.destroy', ['address' => $address->id])}}"
                                            type="button" class="btn btn-sm btn-danger btn-icon destroy-address me-3">
                                        <i class="las la-trash"></i>
                                    </button>
                                @endif
                            @endif
                            @if(permission_can('edit user address','admin'))
                                <button id="edit-address-{{$address->id}}"
                                        data-href="{{route('backend.addresses.edit', ['address' => $address->id])}}"
                                        class="btn btn-sm btn-info btn-icon edit-address ">
                                    <i class="las la-highlighter"></i>
                                </button>
                            @endif

                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Address-->
                </div>
            @endforeach
            @if(permission_can('create user address' ,'admin'))
            <!--begin::Col-->
                <div class="col-xl-6">
                    <!--begin::Notice-->
                    <div
                        class="notice d-flex bg-light-primary rounded border-primary border border-dashed flex-stack h-xl-100 mb-10 p-6">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <!--begin::Content-->
                            <div class="mb-3 mb-md-0 fw-bold">
                                <h4 class="text-gray-900 fw-bolder">{{trans('backend.address.you_can_add_new_address')}}</h4>

                            </div>
                            <!--end::Content-->
                            <!--begin::Action-->
                            <button id="create-address" href="{{route('backend.addresses.create')}}"
                                    class="btn btn-primary px-6 align-self-center text-nowrap">{{trans('backend.address.new_address')}}
                            </button>
                            <!--end::Action-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Notice-->
                </div>
                <!--end::Col-->
            @endif
        </div>
        <!--end::Addresses-->

    </div>
    <!--end::Card body-->

    <div class="modal fade" id="modal_edit_address" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div id="modal_edit_content" class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_new_address" tabindex="-1" style="display: none;" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="model_new_address_content">
                <!--begin::Form-->
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--begin::Modal content-->
</div>


{{--<script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>--}}

{{--<script>--}}
{{--    var input = document.querySelector("#phone");--}}
{{--    window.intlTelInput(input, {--}}
{{--        initialCountry: "auto",--}}

{{--        utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",--}}
{{--    });--}}
{{--</script>--}}
