<div class="card  card-flush">
    <div class="card-header">
        <div class="card-title">
            <h2>{{trans('backend.product.reviews')}}</h2>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="review_table" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200 w-100">
                    <th>{{trans('backend.global.id')}}</th>
                    <th>{{trans('backend.user.name')}}</th>
{{--                    <th>{{trans('backend.user.product')}}</th>--}}
                    <th>{{trans('backend.review.rating')}}</th>
                    <th>{{trans('backend.ticket.status')}}</th>
                    <th>{{trans('backend.global.created_at')}}</th>
                    <th>{{trans('backend.global.actions')}}</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
