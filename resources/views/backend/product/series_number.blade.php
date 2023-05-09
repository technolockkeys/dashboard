@extends('backend.layout.app')
@section('title',trans('backend.product.serial_numbers').' '. $product->title.' | '.get_setting('title'))
@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card card-flush">
            <div class="card-header">
                <h2 class="card-title">
                    {{trans('backend.product.series_number',['product'=>$product->title])}}
                </h2>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-row-bordered gy-5 gs-7">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>#</th>
                            <th>{{trans('backend.product.serial_number')}}</th>
                            <th>{{trans('backend.menu.orders')}}</th>
                            <th>{{trans('backend.menu.users')}}</th>
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
