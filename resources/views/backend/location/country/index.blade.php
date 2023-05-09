@extends('backend.layout.app')
@section('title',trans('backend.menu.countries').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.countries')}}</h3>
            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                {!! select_status() !!}
                {!! apply_filter_button() !!}

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>{{trans('backend.global.id')}}</th>
                            <th>{{trans('backend.country.name')}}</th>
                            <th>{{trans('backend.country.code')}}</th>
                            <th>{{trans('backend.country.capital')}}</th>
                            <th>{{trans('backend.country.zone_id')}}</th>
                            <th>{{trans('backend.country.phonecode')}}</th>
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
            <!--end::Card Body-->
        </div>
    </div>
    <div class="modal fade" id="modal_edit_zone" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div id="" class="modal-content">

                <div class="modal-header text-danger">
                    <h5 class="modal-title">{{trans('backend.country.zone_id')}}</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-show btn-active-light-primary " data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="las las la-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body" id="modal_edit_zone_content"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit_name" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div id="" class="modal-content">

                <div class="modal-header text-danger">
                    <h5 class="modal-title">{{trans('backend.country.edit_name')}}</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-show btn-active-light-primary " data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="las las la-times"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body" id="modal_edit_name_content"></div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    {!! $switch_script !!}


    <script>

        $(document).on('submit', '#zone_update', function (event) {
            event.preventDefault();
            var button = $(this).find(":submit");
            button.attr('disabled', true);
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "post",
                data: $(this).serialize(),
                success: function (response) {
                    button.removeAttr('disabled', false)
                    $("#modal_edit_zone").modal('hide');
                    document.getElementById('modal_edit_zone_content').innerHTML = '';
                    success_message(response.data.message);

                    dt.ajax.reload(null, false);

                },
            });
        });

        $(document).on('click', '.edit-zone', function () {

            var button = $(this);
            button.html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>')
            button.attr('disabled', true)
            document.getElementById('modal_edit_zone_content').innerHTML = '';

            var url = $(this).data('href');

            $.ajax({
                url: url,
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}",
                },
                success: function (response) {
                    button.html('{{trans('backend.country.edit_zone')}}')
                    button.attr('disabled', false)

                    document.getElementById('modal_edit_zone_content').innerHTML = response.data.view;
                    $("#modal_edit_zone").modal('show');
                    $("#zone_id").select2({
                        dropdownParent: $('#modal_edit_zone')

                    });

                }
            })
        });

        $(document).on('click', '.edit-name',function (){
            var button = $(this);
            button.html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>');
            button.attr('disabled', true);
            document.getElementById('modal_edit_zone_content').innerHTML = '';

            var url = $(this).data('href');

            $.ajax({
                url: url,
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}",
                },
                success: function (response) {
                    button.html('{{trans('backend.global.edit')}}')
                    button.attr('disabled', false)

                    document.getElementById('modal_edit_name_content').innerHTML = response.data.view;
                    $("#modal_edit_name").modal('show');

                }
            })

        })

        $(document).on('submit', '#update_name', function (event) {
            event.preventDefault();
            var button = $(this).find(":submit");
            button.attr('disabled', true);
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "post",
                data: $(this).serialize(),
                success: function (response) {
                    $("#modal_edit_name").modal('hide');
                    button.removeAttr('disabled', false)
                    document.getElementById('modal_edit_name_content').innerHTML = '';
                    success_message(response.data.message);

                    dt.ajax.reload(null, false);

                },
            });
        });

    </script>
@endsection
