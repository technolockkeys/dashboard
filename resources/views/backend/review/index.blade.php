@extends('backend.layout.app')
@section('title',trans('backend.menu.reviews').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.reviews')}}</h3>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
                    {!! multi_select_2('product', $products, 'backend.menu.products') !!}
                    {!! multi_select_2('user', $users, 'backend.menu.users') !!}
                    {!! multi_select_2('rating', $rating, 'backend.review.rating') !!}
                    {!! select_status() !!}
                </div>
                {!! apply_filter_button() !!}

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.user.name')}}</th>
                            <th>{{trans('backend.user.product')}}</th>
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
            <!--end::Card Body-->
        </div>
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $switch_script !!}

@endsection
