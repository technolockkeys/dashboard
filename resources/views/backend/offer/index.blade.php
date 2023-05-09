@extends('backend.layout.app')
@section('title',trans('backend.menu.offers').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.offers')}}</h3>
                <div class="card-toolbar">
                    {!! @$create_button !!}
                </div>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
                    {!! multi_select_2('discount_type', $discount_types, 'backend.offer.discount_type') !!}
                    {!! select_status() !!}

                    <div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">

                        <label for="date-picker "
                               class="col-form-label form-label">{{trans('backend.offer.dates')}}</label>
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
                            <th>{{trans('backend.offer.from')}}</th>
                            <th>{{trans('backend.offer.to')}}</th>
                            <th>{{trans('backend.offer.days')}}</th>
                            <th>{{trans('backend.offer.discount')}}</th>
                            <th>{{trans('backend.offer.discount_type')}}</th>
                            <th>{{trans('backend.offer.type')}}</th>
                            <th>{{trans('backend.offer.free_shipping')}} </th>
                            <th>{{trans('backend.global.created_at')}}</th>
                            <th>{{trans('backend.global.updated_at')}}</th>
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
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
            "startDate": moment().subtract(29, "days"),
            "endDate": moment().add(29,"days")
        }, function (start, end, label) {
            $('#starts_at').val(start.format('YYYY-MM-DD hh:mm A'));
            $('#ends_at').val(end.format('YYYY-MM-DD hh:mm A'));
            // dt.ajax.reload();
        });
    </script>
@endsection
