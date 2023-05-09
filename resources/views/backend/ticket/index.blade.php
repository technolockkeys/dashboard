@extends('backend.layout.app')
@section('title',trans('backend.menu.tickets').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.tickets')}}</h3>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
                    {!! multi_select_2('type', $types, 'backend.ticket.type') !!}
                    {!! multi_select_2('status', $statuses, 'backend.ticket.status') !!}
                    <div class="col-6 col-md-3 col-lg-2  form-group">
                        <label class="col-form-label form-label">{{trans('backend.global.time')}}</label>
                        <input class="form-control form-control-sm" name="date"
                               placeholder="{{trans('backend.global.select_time_range')}}" id="date"/>
                        <input type="hidden" name="start_date"
                               placeholder="{{trans('backend.global.select_time_range')}}" id="start_date"/>
                        <input type="hidden" name="end_date" placeholder="{{trans('backend.global.select_time_range')}}"
                               id="end_date"/>
                    </div>
                </div>
                {!! apply_filter_button() !!}

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th style="text-align: center"><input type="checkbox" id="select_all"/></th>
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.ticket.ticket_id')}}</th>
                            <th>{{trans('backend.ticket.type')}}</th>
                            <th>{{trans('backend.ticket.model')}}</th>
                            <th>{{trans('backend.ticket.subject')}}</th>
                            <th>{{trans('backend.ticket.user')}}</th>
                            <th>{{trans('backend.ticket.status')}}</th>
                            <th>{{trans('backend.ticket.last_reply')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
                            <th>{{trans('backend.global.updated_at')}} </th>
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

    <script>
        var start = moment().subtract(29, "days");
        var end = moment();

        function cb(start, end) {
            $("#date").html(start.format("dd/mm/yyyy") + " - " + end.format("dd/mm/yyyy"));
        }

        $("#date").daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "{{trans('backend.global.today')}}": [moment(), moment()],
                "{{trans('backend.global.yesterday')}}": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "{{trans('backend.global.last_7_days')}}": [moment().subtract(6, "days"), moment()],
                "{{trans('backend.global.last_30_days')}}": [moment().subtract(29, "days"), moment()],
                "{{trans('backend.global.this_month')}}": [moment().startOf("month"), moment().endOf("month")],
                "{{trans('backend.global.last_month')}}": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            },
            locale: {
                format: 'DD-MM-YYYY',
                cancelLabel: 'Clear'

            }

        }, function (start, end, label) {
            $('#start_date').val(start.format('DD-MM-YYYY'));
            $('#end_date').val(end.format('DD-MM-YYYY'));
        });
        cb(start, end);
        $('#date').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $('#start_date').val('');
            $('#start_date').val('');
        });

    </script>
@endsection
