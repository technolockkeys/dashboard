@extends('backend.layout.app')
@section('title',trans('backend.menu.users').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.users')}}</h3>
                <div class="card-toolbar">
                    {!! @$create_button !!}
                </div>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">

                {!! select_status() !!}
                {!!  multi_select_2('seller', $sellers, 'backend.user.seller')!!}
                {!!  multi_select_2('country', $countries, 'backend.user.country')!!}
                {!!  multi_select_2('balance', $balance, 'backend.user.balance')!!}
                </div>
                {!! apply_filter_button() !!}

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th style="text-align: center"><input type="checkbox" id="select_all" /></th>
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.user.name')}}</th>
                            <th>{{trans('backend.global.uuid')}}</th>
                            <th>{{trans('backend.user.phone')}}</th>
                            <th>{{trans('backend.user.email')}}</th>
{{--                            <th>{{trans('backend.user.orders_count')}}</th>--}}
{{--                            <th>{{trans('backend.user.purchase_value')}}</th>--}}
{{--                            <th>{{trans('backend.user.avg_purchase_value')}}</th>--}}
                            <th>{{trans('backend.user.balance')}}</th>
                            <th>{{trans('backend.user.seller')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
                            <th>{{trans('backend.global.updated_at')}} </th>
                            <th>{{trans('backend.global.status')}}</th>
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
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $switch_script !!}

@endsection
