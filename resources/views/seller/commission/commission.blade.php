@extends('seller.layout.app')
@section('title',trans('backend.order.seller_commission').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"><b class="las la-percent"></b> {{trans('backend.order.seller_commission')}}</h3>

            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">


                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th></th>
                            <th>{{trans('backend.seller.from')}}</th>
                            <th>{{trans('backend.seller.to')}}</th>
                            <th>{{trans('backend.order.seller_commission')}}</th>
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
    <div class="modal fade" tabindex="-1" id="commission_seller">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="las la-times"></i> Add Commission</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form id="add_commission">
                        @csrf
                        {!! Form::input('hidden','seller_id' ,$seller->id) !!}
                        <div class="mb-10">
                            <label for="from" class="required form-label">{{trans('backend.seller.from')}}</label>
                            <input type="number" class="form-control form-control-solid" id="from" name="from"/>
                            <b class="text-danger" id="error_from"></b>
                        </div>
                        <div class="mb-10">
                            <label for="to" class="required form-label">{{trans('backend.seller.to')}}</label>
                            <input type="number" class="form-control form-control-solid" id="to" name="to"/>
                            <b class="text-danger" id="error_to"></b>

                        </div>
                        <div class="mb-10">
                            <label for="commission"
                                   class="required form-label">{{trans('backend.order.seller_commission')}}</label>
                            <input type="number" class="form-control form-control-solid"
                                   id="commission" name="commission"/>
                            <b class="text-danger" id="error_commission"></b>
                        </div>

                        @if(permission_can('add commission seller' ,'admin'))
                            <div class="form-group">
                                <button id="btn_save" type="submit"
                                        class="btn btn-icon btn-primary">{{trans('backend.global.save')}}</button>
                            </div>
                        @endif


                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}
    <script>
        $(document).on('click', '#add-seller', function () {
            var seller_id = $(this).data('seller');
            $("#commission_seller").modal('show');
        });
        $(document).on('submit', '#add_commission', function (event) {
            event.preventDefault();
            $("#error_from").html("");
            $("#error_to").html("");
            $("#error_commission").html("");
            $("#btn_save").html('<i class="fa fa-spinner fa-pulse   fa-fw"></i>\n').attr('disabled', 'disabled');
            $.ajax({
                url: "{{route('backend.sellers.commission.store')}}",
                method: "post",
                data: $("#add_commission").serialize(),
                success: function () {
                    dt.ajax.reload(null, false);
                    $("#btn_save").html("{{trans('backend.global.save')}}").removeAttr('disabled');
                    $("#from").val("");
                    $("#to").val("");
                    $("#commission").val("");

                },
                error: function (response) {
                    $("#error_from").html(response.responseJSON.errors.from);
                    $("#error_to").html(response.responseJSON.errors.to);
                    $("#error_commission").html(response.responseJSON.errors.commission);
                    $("#btn_save").html("{{trans('backend.global.save')}}").removeAttr('disabled');

                }
            })
        })
    </script>
@endsection

