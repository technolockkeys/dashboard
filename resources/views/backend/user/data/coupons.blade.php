
<div class="col">
    <div class="card   flex-row-fluid mb-2  ">
        <div class="card-header">
            <h3 class="card-title"> {{trans('backend.menu.coupons')}}</h3>
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
                        <th>{{trans('backend.coupon.code')}}</th>
                        <th>{{trans('backend.coupon.discount')}}</th>
                        <th>{{trans('backend.coupon.discount_type')}}</th>
                        <th>{{trans('backend.coupon.type')}}</th>
                        <th>{{trans('backend.coupon.times_used')}}</th>
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


<div class="modal fade" id="modal_coupon_usage" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">

        <!--begin::Modal content-->
        <div id="modal_show_coupon_usage" class="modal-content">

        </div>
    </div>
</div>

{!! $datatable_script !!}
