@extends('seller.layout.app')
@section('title',trans('seller.menu.after_sales').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"><b class="las la-comments"></b> {{trans('seller.menu.after_sales')}}</h3>
                <div class="card-toolbar">
                    <button class="btn btn-primary" id="user_black_list" type="button"><i
                            class="la la-user-slash"></i> {{trans('seller.after_sales.user_black_list')}}</button>
                </div>
            </div>
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th></th>
                            <th>{{trans('seller.after_sales.order_number')}}</th>
                            <th>{{trans('backend.global.created_at')}}</th>
                            <th>{{trans('seller.after_sales.user')}}</th>
                            <th>{{trans('seller.after_sales.feedback')}}</th>
                            <th>{{trans('seller.after_sales.feedback_date')}}</th>
                            <th class=>{{trans('backend.global.actions')}}</th>
                        </tr>
                        </thead>
                    </table>
                    <div class="modal fade" id="model_feedback" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form action="{{route('seller.after-sale.update')}}" id="form_feedback">
                                @csrf
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center mb-7">
                                                    <div class="symbol symbol-50px me-5">
                                                        <img src="assets/media/avatars/300-6.jpg" id="modal_avatar"
                                                             onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                                             class="" alt="">
                                                    </div>

                                                    <div class="flex-grow-1">
                                                        <b id="modal_username"
                                                           class="text-dark fw-bolder text-hover-primary fs-6">Emma
                                                            Smith</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label
                                                        for="feedback">{{trans('seller.after_sales.feedback')}}</label>
                                                    <textarea name="feedback" id="feedback" class="form-control "
                                                              style="resize: none" cols="30" rows="4"></textarea>
                                                    <input type="hidden" id="uuid" name="uuid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                onclick='$("#model_feedback").modal("hide")'
                                                data-dismiss="modal">{{trans('backend.global.close')}}</button>
                                        <button type="submit" class="btn btn-primary"
                                                id="save_feedback">{{trans('backend.global.save')}}</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal fade" id="model_black_list" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog  " role="document">
                            <form id="form_save_black_list">
                                @csrf
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <select name="users[]" multiple id="users" class="form-control"
                                                    data-control="select2">
                                                @php
                                                    $sellers_black_list = auth('seller')->user()->black_list_users;
                                                    $sellers_black_list =json_decode($sellers_black_list);
                                                @endphp
                                                @foreach($users as $user)
                                                    <option
                                                        @if(!empty($sellers_black_list) && in_array($user->id,$sellers_black_list)) selected
                                                        @endif value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                onclick='$("#model_black_list").modal("hide")'
                                                data-dismiss="modal">{{trans('backend.global.close')}}</button>
                                        <button type="submit" class="btn btn-primary"
                                                id="save_black_list">{{trans('backend.global.save')}}</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    <script>
        $(document).on('click', '.set_feedback', function () {
            var uuid = $(this).attr('id');
            var btn = $(this);
            $(this).html("<i class='fa fa-spinner fa-pulse  fa-fw'></i> {{trans('backend.global.loading')}}")

            $.ajax({
                url: "{{route('seller.after-sale.ger_order')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    uuid: uuid
                }, success: function (response) {
                    $("#uuid").val(uuid);
                    console.log(response)
                    $("#feedback").val(response.data.order.feedback);
                    $("#modal_username").text(response.data.order.name);
                    $("#modal_avatar").attr('src', response.data.order.avatar);
                    btn.html(' <i class="las la-comments"></i> {{trans('seller.after_sales.set_feedback')}}')
                    $("#model_feedback").modal('show')
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                }
            });
        });
        $(document).on('submit', "#form_feedback", function (event) {
            event.preventDefault();
            var url = $("#form_feedback").attr('action');
            $("#save_feedback").html("<i class='fa fa-spinner fa-pulse  fa-fw'></i> {{trans('backend.global.loading')}}");
            $.ajax({
                url: url,
                method: "post",
                data: $(this).serialize(),
                success: function (response) {
                    success_message(response.data.message);
                    $("#save_feedback").html("  {{trans('backend.global.save')}}");
                    $("#model_feedback").modal('hide');
                    dt.ajax.reload();
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                    dt.ajax.reload();
                }

            })
        })
        $(document).on('click', '.send_email', function () {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{route('seller.after-sale.send.email')}}",
                method: "post",
                data: {
                    _token: "{{csrf_token()}}",
                    uuid: id
                }, success: function (response) {
                    dt.ajax.reload(null, false);
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                    dt.ajax.reload(null, false);
                }
            })

        })
        $(document).on('click', "#user_black_list", function () {
            $("#model_black_list").modal('show')
        });
        $(document).on('submit', "#form_save_black_list", function (event) {
            event.preventDefault();

            $.ajax({
                url: "{{route('seller.after-sale.save.black.list')}}",
                method: "post",
                data: $("#form_save_black_list").serialize(),
                success: function (response) {
                    success_message(response.data.message);
                    $("#model_black_list").modal('hide');
                    dt.ajax.reload(null, false);
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    error_message(response.message);
                    dt.ajax.reload(null, false);
                    $("#model_black_list").modal('hide')
                }
            })
        });
    </script>
@endsection
