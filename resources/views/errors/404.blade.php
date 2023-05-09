@extends('backend.layout.login')

@section('title', __('Not Found'))
@section('content')
<div class="d-flex flex-column flex-center flex-column-fluid p-10">
    <img src="{{asset('backend/media/illustrations/sketchy-1/18.png')}}" alt="" class="mw-100 mb-10 h-lg-450px">
    <h1 class="fw-bold mb-10" style="color: #A3A3C7">{{trans("backend.global.nothing_here")}}</h1>
    <a href="javascript:history.back()" class="btn btn-primary">{{trans('backend.global.get_back')}}</a>
</div>
@endsection

