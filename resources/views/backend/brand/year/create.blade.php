@extends('backend.layout.app')
@section('title',trans('backend.menu.years').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="col">
        <form action="{{route('backend.brands.models.years.store',$model->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card flex-row-fluid mb-2  ">
                <div class="card-header">
                    <h3 class="card-title"> {{trans('backend.model.add_year_to', ['name' => $model->model])}}</h3>
                    <div class="card-toolbar">
                        <a href="{{route('backend.brands.models.years.index',['model_id'=> $model->id])}}" class="btn btn-info"><i
                                    class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row flex-center mb-6">
                        <div class="col-12 col-md-6">
                            <label for="year" class="form-label">{{trans('backend.brand.year')}}</label>
                            <input type="number" min="1908" class="form-control" id="year" name="year"
                                   value="{{old('year')}}"
                                   placeholder="Type a year"/>

                            @error('year') <b class="text-danger"><i
                                        class="las la-exclamation-triangle"></i> {{$message}} </b> @enderror
                        </div>
                        <div class="col  align-items-center">
                            <div class="form-group  align-items-center">
                                <br>
                                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                    <input class="form-check-input h-20px w-30px" @if(old('status') == 1) checked @endif type="checkbox" value="1"
                                           name="status" id="status"/>
                                    <label class="form-check-label" for="status">
                                        {{trans('backend.global.do_you_want_active')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
