@extends('backend.layout.app')
@section('title',trans('backend.menu.sellers').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="row">

        <div class="col-12 ">
            <div class="card   flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.menu.sellers_earning')}}</h3>

                </div>
                <!--begin::Card Body-->
                <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                    <div class="row">

                        {!! multi_select_2('sellers',$sellers, 'backend.menu.sellers' ) !!}
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="years" class="col-form-label form-label">{{trans('backend.menu.years')}}</label>
                            <select class="form-control form-control-sm" name="years" data-control="select2" id="years">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                @foreach($years as $year)
                                    <option value="{{$year}}">{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="months" class="col-form-label form-label">{{trans('backend.seller.months')}}</label>
                            <select class="form-control form-control-sm" name="months" data-control="select2" id="months">
                                <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                                @foreach($months as $key => $year)
                                    <option value="{{$key + 1}}">{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="from_value"
                                   class="col-form-label form-label-sm">{{trans('backend.seller.from_value')}}</label>
                            <input type="number" id="from_value" name="from_value" class="form-control form-control-sm">
                        </div>
                        <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                            <label for="to_value"
                                   class="col-form-label form-label-sm">{{trans('backend.seller.to_value')}}</label>
                            <input type="number" id="to_value" name="to_value" class=" form-control form-control-sm">
                        </div>
                    </div>

                    {!! apply_filter_button() !!}

                    <div class="table-responsive">
                        <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                            <thead>
                            <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th>{{trans('backend.global.id')}}</th>
                                <th>{{trans('backend.user.name')}}</th>
                                <th>{{trans('backend.seller.date')}}</th>
                                <th>{{trans('backend.seller.total_orders')}}</th>
                                <th>{{trans('backend.seller.commission_rate')}}</th>
                                <th>{{trans('backend.seller.earning')}}</th>
                                <th>{{trans('backend.global.created_at')}}</th>
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
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}

@endsection
