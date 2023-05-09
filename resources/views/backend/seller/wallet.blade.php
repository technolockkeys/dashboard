@extends('backend.layout.app')
@section('title',trans('backend.menu.sellers').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')

    <div class="row">

        @foreach($statistics as $element)
            @php
                $svg = $element['svg'] ;
                $number= $element['number'];
                $name=$element['name'];
                $sum=$element['sum'];
            @endphp
            <div class=" col-2 col-md-2 col-xl-2 mb-6">

                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="w-50 h-50">
                            <img style='width: 50%' src="{{$svg}}"/>
                        </div>
                        <div class="d-flex flex-column my-7">
                            <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{$sum}}</span>
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{$name}}</span>
                            </div>
                        </div>
                        <span class="badge badge-light-success fs-base">
                            {{$number}}</span>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <div class="card   flex-row-fluid mb-2  ">
        <div class="card-header">
            <h3 class="card-title"> {{trans('backend.seller.show_wallet', ['name'=> $seller->name])}}</h3>
            <div class="card-toolbar">
                {!! @$create_button !!}
            </div>
        </div>
        <!--begin::Card Body-->
        <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

            <div class="table-responsive">
                <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th>{{trans('backend.global.id')}}</th>
                        <th>{{trans('backend.order.amount')}}</th>
                        <th>{{trans('backend.order.before_balance')}}</th>
                        <th>{{trans('backend.order.balance')}}</th>
                        <th>{{trans('backend.order.type')}}</th>
                        <th>{{trans('backend.global.status')}}</th>
                        <th>{{trans('backend.global.created_at')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!--end::Card Body-->
        </div>
    </div>

@endsection

@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}

@endsection