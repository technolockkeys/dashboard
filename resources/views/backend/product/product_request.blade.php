@extends('backend.layout.app')
@section('title',trans('backend.menu.products').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card  flex-row-fluid mb-2">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.product_requests', ['name' => $title])}}</h3>

            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-row-bordered gy-5 gs-7">
                    {{--                    <div class="row">--}}
                    {{--                      --}}
                    {{--                        {!! apply_filter_button() !!}--}}
                    {{--                    </div>--}}


                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th class="">{{trans('backend.global.id')}}</th>
                        <th class=" min-w-150px">{{trans('backend.user.name')}}</th>
                        <th class=" min-w-150px">{{trans('backend.user.email')}}</th>
                        <th class=" min-w-150px">{{trans('backend.user.phone')}}</th>
                        <th class=" min-w-250px">{{trans('backend.product.quantity')}}</th>
                        <th class=" min-w-150px">{{trans('backend.product.last_request')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}

@endsection
