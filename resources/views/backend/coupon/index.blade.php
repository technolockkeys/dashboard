@extends('backend.layout.app')
@section('title',trans('backend.menu.coupons').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.coupons')}}</h3>
                <div class="card-toolbar">
                    {!! @$create_button !!}
                </div>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
                    {!! multi_select_2('type', $types,'backend.coupon.type') !!}
                    {!! multi_select_2('discount_type', $discount_types, 'backend.coupon.discount_type') !!}
                    {!! multi_select_2('product', $products, 'backend.product.sku') !!}
                    {!! select_status() !!}
                    <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                        <label for="discount"
                               class="col-form-label form-label-sm">{{trans('backend.coupon.discount')}}</label>
                        <input type="number" id="discount" name="discount" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                        <label for="max_use"
                               class="col-form-label form-label-sm">{{trans('backend.coupon.max_use')}}</label>
                        <input type="number" id="max_use" name="max_use" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
                        <label for="per_user"
                               class="col-form-label form-label-sm">{{trans('backend.coupon.per_user')}}</label>
                        <input type="number" id="per_user" name="per_user" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">

                        <label for="date-picker "
                               class="col-form-label form-label">{{trans('backend.coupon.dates')}}</label>
                        <div class=" fv-row fv-plugins-icon-container ">
                            <div class="mb-0">
                                <input class="form-control form-control-sm date-picker form-control"
                                       id="date-picker" name="dates-picker" value="{{old('dates-picker')}}"/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="starts_at" name="starts_at" value=""/>
                    <input type="hidden" id="ends_at" name="ends_at" value=""/>
                </div>
                    {!! apply_filter_button() !!}
            <div class="table-responsive">
                <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th style="text-align: center"><input type="checkbox" id="select_all" /></th>
                        <th>{{trans('backend.global.id')}}</th>
                        <th>{{trans('backend.coupon.code')}}</th>
                        <th>{{trans('backend.coupon.type')}}</th>
                        <th>{{trans('backend.coupon.discount')}}</th>
                        <th>{{trans('backend.coupon.discount_type')}}</th>
                        <th>{{trans('backend.coupon.max_use')}}</th>
                        <th>{{trans('backend.coupon.starts_at')}}</th>
                        <th>{{trans('backend.coupon.ends_at')}}</th>
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
        </div>

        <!--end::Card Body-->
    </div>

@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $switch_script !!}

    <script>
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();

        $('.date-picker').daterangepicker({
            "maxYear": null,
            // "minDate": today,

            "locale": {
                "format": "YYYY-MM-DD hh:mm A",
                "firstDay": 1,
                cancelLabel: 'Clear'
            },
            "alwaysShowCalendars": true,
            "startDate": moment().subtract(29, "days"),
            "endDate": moment().add(29,"days"),
            // autoUpdateInput: false,
        }, function (start, end, label) {
            $('#starts_at').val(start.format('YYYY-MM-DD hh:mm A'));
            $('#ends_at').val(end.format('YYYY-MM-DD hh:mm A'));
            // dt.ajax.reload();
        });
        $('.date-picker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#starts_at').val('');
            $('#ends_at').val('');
        });

    </script>
@endsection
