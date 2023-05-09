<div class="col-12 col-md-12">
    <div class="form-group">
        <label  for="meta_title_{{$code}}"
               class="form-label  @if($key == 0 ) required @endif">SEO | {{trans('backend.product.title')}}</label>
        <input type="text" class="form-control has-error" name="meta_title_{{$code}}"
               value=" {{old('title_meta_'.$code ,$title)}}" @if($key == 0)required @endif
               maxlength="50" id="meta_title_{{$code}}" maxlength="70">
    </div>
</div>

<div class="col-12 col-md-12 form-group ">
    <div class="form-group">
        <label class="form-label  @if($key == 0 ) required @endif"
               for="meta_description_{{$code}}">SEO | {{trans('backend.category.description')}}</label>
        <textarea type="text" class="form-control" id="meta_description_{{$code}}"
                  maxlength="155" style="resize: none" rows="2" name="meta_description_{{$code}}"
        > {{old('description_meta_'.$code , $description)}}</textarea>
        @error('description_meta_'.$code)<b class="text-danger"> <i
                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
    </div>
</div>
