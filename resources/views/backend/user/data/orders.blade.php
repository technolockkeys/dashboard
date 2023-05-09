
<div class="col">
    <div class="card   flex-row-fluid mb-2  ">
        <div class="card-header">
            <h3 class="card-title"> {{trans('backend.menu.orders')}}</h3>
            <div class="card-toolbar">
            </div>
        </div>
        <!--begin::Card Body-->
        <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

            <div class="table-responsive">
                <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th>{{trans('backend.global.id')}}</th>
                        <th>{{trans('backend.global.uuid')}}</th>
                        <th>{{trans('backend.user.seller')}}</th>
                        <th>{{trans('backend.order.payment_method')}}</th>
                        <th>{{trans('backend.order.payment_status')}}</th>
                        <th>{{trans('backend.order.total')}}</th>
                        <th>{{trans('backend.order.shipping')}}</th>
                        <th>{{trans('backend.global.status')}}</th>
                        <th>{{trans('backend.order.coupon_value')}}</th>
                        <th>{{trans('backend.order.type')}}</th>
                        <th>{{trans('backend.order.tracking_number')}}</th>
                        <th>{{trans('backend.global.created_at')}}</th>
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
{!! $datatable_script !!}
