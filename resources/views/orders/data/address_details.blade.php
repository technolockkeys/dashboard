<div class="card card-flush col-md-4 col-12 py-4 flex-row-fluid overflow-hidden">
    <!--begin::Background-->
    <div class="position-absolute top-0 end-0 opacity-50 pe-none text-end">
        <img src="{{asset('backend/media/icons/duotune/ecommerce/ecm006.svg')}}" alt="shipping" class="w-100px">
    </div>
    <!--end::Background-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{__('backend.user.address')}}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
            <tbody class="fw-bold text-gray-600">
            <tr>
                <td class="text-muted">{{__('backend.order.city')}}/{{__('backend.order.country')}}:</td>
                <td class="fw-bolder text-end">
                    {{$address->get_city()}}/{{$address->get_country()}}
                </td>
            </tr>
            <tr>
                <td class="text-muted">{{__('backend.order.address')}}:</td>
                <td class="fw-bolder text-end">
                    {{$address->address}}
                </td>
            </tr>
            <tr>
                <td class="text-muted">{{__('backend.address.postal_code')}}:</td>
                <td class="fw-bolder text-end">
                    {{$address->postal_code}}
                </td>
            </tr>
            <tr>
                <td class="text-muted">{{__('backend.user.phone')}}:</td>
                <td class="fw-bolder text-end">
                    <a href="tel:{{$address->phone}}"> {{$address->phone}}</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!--end::Card body-->
    <!--end::Payment address-->
</div>
