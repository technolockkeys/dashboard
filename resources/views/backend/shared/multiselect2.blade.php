<div class="col-6 col-md-3 col-lg-2 col-xl-2 form-group">
    <label for="{{$name}}" class="col-form-label form-label">{{trans($translation)}}</label>
    <select class="form-control form-control-sm" name="{{$name}}" data-control="select2" id="{{$name}}">
        <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
        @foreach($options as $key=> $value)
            <option value="{{$value->id?? $key?? $value}}">{{$value->sku?? $value->name?? $value->title?? $value}}</option>
        @endforeach
    </select>
</div>
