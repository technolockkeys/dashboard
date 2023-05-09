@extends('backend.layout.app')
@section('title',trans('backend.setting.translate').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
<div class="card">
    <div class="card-body">
        <iframe src="{{url('translations')}}" allowfullscreen style="width: 100%;height: 800px;border:none;" class="embed-responsive-item"></iframe>

    </div>
</div>
@endsection
