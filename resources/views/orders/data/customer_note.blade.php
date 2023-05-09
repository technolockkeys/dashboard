<div class="card card-flush col-md-4 col-12 py-4 flex-row-fluid overflow-hidden">
    <!--begin::Background-->
    <div class="position-absolute top-0 end-0 opacity-50 pe-none text-end">
        <img src="{{asset('backend/media/icons/duotune/general/gen005.svg')}}" alt="shipping" class="w-100px">
    </div>
    <!--end::Background-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{__('backend.order.note')}}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
            <tbody class="fw-bold text-gray-600">
            <tr>
                <td class="text-muted">{{__('backend.order.note')}}:</td>
            </tr>
            <tr>
                <td class="fw-bolder ">
                    {{$order->note}}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    <!--end::Card body-->
    <!--end::Payment address-->
</div>
