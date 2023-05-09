<div class="modal-header">
    <h3>{{__('backend.order.coupon')}}</h3>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body ">

    <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800  ">
            <th>{{trans('backend.order.uuid')}}</th>
            <th>{{trans('backend.order.total')}}</th>
            <th>{{trans('backend.order.discount')}}</th>
            <th>{{trans('backend.order.created_at')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{$order->uuid}}</td>
                <td>{{currency($order->total)}}</td>
                <td>{{currency($order->coupon_value)}}</td>
                <td>{{$order->created_at}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>
