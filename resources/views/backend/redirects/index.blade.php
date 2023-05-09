@extends('backend.layout.app')
@section('title',trans('backend.menu.redirects').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.redirects')}}</h3>
                @if(permission_can('create redirect', 'admin'))
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#create_redirect">
                        {{trans('backend.redirect.create_new_redirect')}}
                    </button>
                </div>
                    @endif
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="row">
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
                <div class="row">
                    {!! apply_filter_button() !!}
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th style="text-align: center"><input type="checkbox" id="select_all"/></th>
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.redirect.old_url')}}</th>
                            <th>{{trans('backend.redirect.new_url')}}</th>
                            <th>{{trans('backend.redirect.clicks_count')}}</th>
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

    <div class="modal fade" tabindex="-1" id="create_redirect">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{trans('backend.redirect.create_new_redirect')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="svg-icon svg-icon-1"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{route('backend.redirects.store')}}" method="post" id="create_form">
                    @csrf
                    <div class="modal-body">
                        <div class="row my-6">
                            <label for="old_url"
                                   class="col-lg-12 col-form-label required fw-bold fs-6">{{trans('backend.redirect.old_url')}}</label>
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <input type="text" id="old_url" name="old_url"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.redirect.old_url')}}"
                                       value="{{old('old_url')}}">
                                <b class="text-danger" id="old_url_error"> </b>

                                @error('old_url')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                        <div class="row my-6">
                            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                <label for="new_url"
                                       class="col-lg-12 col-form-label required fw-bold fs-6">{{trans('backend.redirect.new_url')}}</label>
                                <input type="text" id="new_url" name="new_url"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="{{trans('backend.redirect.new_url')}}"
                                       value="{{old('app_url')}}">
                                <b class="text-danger" id="new_url_error"> </b>

                                @error('new_url')<b class="text-danger"><i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light"
                                data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modal_edit_redirect">
        <div class="modal-dialog">
            <div class="modal-content" id="modal_edit_content">


            </div>
        </div>
    </div>

@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}

    <script>
        $(document).on("submit", '#create_form', function (event) {
            event.preventDefault();
            var button = $(this).find(":submit");
            button.attr('disabled', true);
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "post",
                data: $(this).serialize(),
                success: function (response) {
                    button.removeAttr('disabled', false);
                    $("#create_redirect").modal('hide');
                    $('#new_url').val('');
                    $('#old_url').val('');
                    success_message(response.data.message)
                    dt.ajax.reload(true);

                },
                error: function (response) {
                    $('#old_url_error').text(response.responseJSON.errors.old_url);
                    $('#new_url_error').text(response.responseJSON.errors.new_url);
                    button.attr('disabled', false);


                }
            })
        });


        $(document).on('click', '.edit-redirect', function () {

            var button = $(this);
            button.html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>')
            button.attr('disabled', true)
            document.getElementById('modal_edit_content').innerHTML = '';

            var url = $(this).data('href');
            console.log(url);
            $.ajax({
                url: url,
                method: "post",
                data: {
                    "_token": '{{csrf_token()}}'
                },
                success: function (response) {
                    button.attr('disabled', false)
                    button.html('<i class="las la-highlighter"></i>')

                    document.getElementById('modal_edit_content').innerHTML = response.data.view;
                    $("#modal_edit_redirect").modal('show');

                }
            })
        });

        $(document).on("submit", '.edit_redirect_form', function (event) {
            event.preventDefault();
            var button = $(this).find(":submit");
            button.attr('disabled', true);
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "patch",
                data: $(this).serialize(),
                success: function (response) {
                    button.removeAttr('disabled', false);
                    $("#modal_edit_redirect").modal('hide');
                    $('#new_url').val('');
                    $('#old_url').val('');
                    success_message(response.data.message)

                    dt.ajax.reload(null, false);

                },
                error: function (response) {
                    $('#old_url_error').text(response.responseJSON.errors.old_url);
                    $('#new_url_error').text(response.responseJSON.errors.new_url);
                    button.attr('disabled', false);
                }
            })
        });
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
                format: 'DD-MM-YYYY'
            }

        }, function (start, end, label) {
            $('#start_date').val(start.format('DD-MM-YYYY'));
            $('#end_date').val(end.format('DD-MM-YYYY'));
        });
        cb(start, end);

    </script>
@endsection
