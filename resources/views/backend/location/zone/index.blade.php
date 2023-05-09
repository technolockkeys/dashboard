@extends('backend.layout.app')
@section('title',trans('backend.menu.zones').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        <div class="card   flex-row-fluid mb-2  ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.menu.zones')}}</h3>

            </div>
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">

                <div class="table-responsive">
                    <table id="datatable" class="table table-rounded table-striped border gy-7 w-100 gs-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                             <th>{{trans('backend.country.zone_id')}}</th>

                            <th>{{trans('backend.global.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i= 1 ; $i<= 10 ; $i++)
                        <tr>
                            <td>Zone - {{$i}}</td>
                            <td>
                                 <a id="edit-zone-{{$i}}" href="{{route("backend.zones.edit", [$i])}}"
                                class="btn  btn-info btn-sm  edit-zone ">
                                     {{trans('backend.country.edit_zone')}}
                                </a>
                            </td>
                        </tr>
                        @endfor

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

                <div class="modal-header">
                    <h5 class="modal-title">{{trans('backend.country.zone_id')}}</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body" id="modal_edit_zone_content"></div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    {!! datatable_script() !!}
    <script>
        $("#datatable").dataTable();

    </script>
@endsection
