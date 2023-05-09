@extends('seller.layout.app')
@section('title',trans('backend.seller.wallet').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')

    <div class="card   flex-row-fluid mb-2  ">
        <div class="card-header">
            <h3 class="card-title"> {{trans('backend.seller.wallet')}}</h3>

        </div>
        <!--begin::Card Body-->
        <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
            <div class="row">

                @foreach($statistics as $element)
                    @php
                        $svg = $element['svg'] ;
                        $number= $element['number'];
                        $name=$element['name'];
                        $sum=$element['sum'];
                    @endphp
                    <div class=" col-2 col-md-2 col-xl-2 mb-6">
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <!--begin::Number-->
                            <div class="d-flex align-items-center ">
                                <img style='width: 10%' src="{{$svg}}"/>
                                <!--end::Svg Icon-->
                                <div class="fs-2 fw-bold counted px-6 text-{{$element['color']}}" data-kt-countup="true" data-kt-countup-value="4500"
                                     data-kt-countup-prefix="$" data-kt-initialized="1">{{currency($sum)}}</div>
                            </div>
                            <!--end::Number-->
                            <!--begin::Label-->
                            <div class="fw-semibold fs-6 ">{{$name}}</div>
                            <!--end::Label-->
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="table-responsive">
                <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th></th>
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