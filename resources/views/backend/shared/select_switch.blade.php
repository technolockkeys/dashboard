<div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
    <label for="{{$name}}" class="col-form-label form-label">
    {!!trans($translation)!!}

    </label>
    <select class="form-control form-control-sm" name="{{$name}}" data-control="select2" id="{{$name}}">
        <option value="-1">{{trans('backend.global.all')}}</option>
        <option value="1">{{trans('backend.global.on')}}</option>
        <option value="0">{{trans('backend.global.off')}}</option>
    </select>
</div>
