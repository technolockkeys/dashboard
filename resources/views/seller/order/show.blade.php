@extends('seller.layout.app')
@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')

    <div class="d-flex flex-column  mb-6 flex-xl-row gap-7 gap-lg-10">
        <div class="col-9">

            @include('seller.order.data.product')
        </div>
        <div class="col-3">

            @include('seller.order.data.order_details')

            @include('seller.order.data.seller')
        </div>
    </div>

@endsection
