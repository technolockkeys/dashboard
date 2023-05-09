@extends('backend.layout.app')
@section('title',trans('backend.menu.user_wallets').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    {!! datatable_style() !!}
@endsection
@section('content')
    <div class="card   flex-row-fluid mb-2  ">
        <div class="card-header">
            <h3 class="card-title"> {{trans('backend.menu.user_wallets')}}</h3>

        </div>
        <!--begin::Card Body-->
        <div class="card-body  ">
            @include('backend.user_wallet.table')
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('backend/plugins/global/plugins.bundle.js')}}"></script>

    {!! datatable_script() !!}
    {!! $datatable_script !!}
    @include('backend.user_wallet.script')
@endsection
