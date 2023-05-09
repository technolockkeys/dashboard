@extends('seller.layout.app')
@section('title',trans('backend.menu.notifications').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.notifications')}}</h3>
                <div class="card-toolbar">
                    {{--                    {!! @$create_button !!}--}}
                    <button type="button" class="btn btn-primary read-all">
                        {{trans('backend.notifications.read_all')}}
                    </button>
                </div>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
                    {!! select_bool('read',  trans('backend.notifications.read')) !!}
                </div>

                <div class="row">

                    {!! apply_filter_button() !!}

                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.notifications.title')}}</th>
                            <th>{{trans('backend.notifications.content')}}</th>
                            <th>{{trans('backend.notifications.sender_type')}}</th>
                            <th>{{trans('backend.notifications.sender')}}</th>
                            <th>{{trans('backend.notifications.notification_type')}}</th>
                            <th>{{trans('backend.notifications.read')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
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
    {{--    {!! $switch_script !!}--}}

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
            "endDate": moment().add(29, "days")
        }, function (start, end, label) {
            $('#starts_at').val(start.format('YYYY-MM-DD hh:mm A'));
            $('#ends_at').val(end.format('YYYY-MM-DD hh:mm A'));
            // dt.ajax.reload();
        });

        $(document).on('click', '.read', function () {
            var id = $(this).data('id');

            $.ajax({

                url: '{{route('seller.notifications.read')}}',
                method: "post",
                data: {
                    '_token': '{{csrf_token()}}',
                    'id': id,
                }, success: (response) => {
                    console.log(response)
                }
            });
        });
        $(document).on('click', '.read-all', function () {
            var id = $(this).data('id');

            $.ajax({

                url: '{{route('seller.notifications.read_all')}}',
                method: "post",
                data: {
                    '_token': '{{csrf_token()}}',
                }, success: (response) => {
                    dt.ajax.reload();
                    success_message(response.data.message);
                }
            });
        });
    </script>
@endsection
