<div class="tab-pane fade" id="esg_customers_orders" role="tabpanel">
    <div class="card mb-6 mb-xl-9">
        <div class="card-header">
            <div class="card-title">
                <h2>{{trans('seller.user.orders')}}  </h2>
            </div>


        </div>
        <div class="card-body pb-5">
            <div class="row">
                <div class="col">

                    <table id="datatable_orders"
                           class="table w-100 align-middle table-row-dashed fs-6 text-gray-600 fw-bold gy-4 dataTable no-footer">
                        <thead class="border-bottom border-gray-200">
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th></th>
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('seller.orders.type')}}</th>
                            <th>{{trans('seller.orders.total')}}</th>
                            <th>{{trans('backend.order.seller_commission')}}</th>
                            <th>{{trans('backend.global.status')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
