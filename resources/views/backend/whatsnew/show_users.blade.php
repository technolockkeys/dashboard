@extends('backend.layout.app')
@section('title',trans('backend.menu.whatsnew').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="col">
        @if(!empty($whatnew))
        <div class="card mb-2">
            <div class="card-header">
                <h3 class="card-title"> {{$whatnew->title}}</h3>
            </div>
            <div class="card-body">{!! $whatnew->content !!}</div>
        </div>
        @endif
        <div class="card   flex-row-fluid mb-2   ">
            <div class="card-header">
                <h3 class="card-title"> {{trans('backend.whatnew.show_users')}}</h3>
                <div class="card-toolbar">
                    <a href="{{route('backend.whatsnew.index')}}" class="btn btn-info"><i
                                class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                </div>
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
                            <th>{{trans('backend.whatnew.message_id')}}</th>
                            <th>{{trans('backend.whatnew.title')}}</th>
                            <th>{{trans('backend.whatnew.user')}}</th>
                            <th>{{trans('backend.whatnew.read')}}</th>
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
@endsection
@section('script')
    {!! datatable_script() !!}
    {!! $datatable_script !!}

@endsection
