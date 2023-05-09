@if(permission_can('show user wallet' , 'admin'))
<div class="col-xxl-12 col-12  col-lg-12 col-xl-12  col-md-12">
    <div class="card card-flush h-xl-100">
        <div class="card-header pt-7">
            <h4 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-gray-800">{{trans('backend.dashboard.pending_payment')}}</span>
            </h4>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                @include('backend.user_wallet.table')
            </div>
        </div>
    </div>
</div>

@endif
