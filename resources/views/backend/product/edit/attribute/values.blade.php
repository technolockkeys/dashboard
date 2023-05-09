<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2><label for="image">{{trans('backend.product.details')}}</label></h2>
            </div>
        </div>

        <div class="card-body">
            <div class="col-12 col-md-12 mb-2">
                <div class="form-group">
                    <label for="slug" class="required form-label">{{trans('backend.product.slug')}}</label>
                    <input type="text" class="form-control" required id="slug" value="{{old('slug' ,$product->slug)}}" name="slug">
                    <span id="message_slug"></span>
                    <b class="text-danger" id="error_slug"> @error('slug')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
                </div>
            </div>

            <div class="row">
                <div class="col-11">
                    <div class="form-group">
                        <label for="main_attribute" class="form-label">{{trans('backend.product.attributes')}}</label>
                        <select data-control="select2" id="main_attribute" multiple class="form-control">.

                            @foreach($attributes as $attribute)
                                @php $is_hide =true; @endphp
                                @foreach($attribute->sub_attributes  as $sub_attribute)
                                    @if(!empty(old('attribute' ,$products_attributes) ) && $sub_attribute->status == 1 && in_array($sub_attribute->id , old('attribute',$products_attributes)))
                                        @php $is_hide =false; @endphp
                                    @endif
                                @endforeach
                                <option @if(!$is_hide) selected @endif value="{{$attribute->id}}">{{$attribute->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <div class="form-group">
                        <button class="btn btn-icon    btn-sm mt-9 btn-dark" id="add_new_attributes" type="button"><i
                                class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
            @include('backend.product.create.attribute.model_new_attribute')

            <div class="row" id="attribute_fileds">

                @foreach($attributes as $attribute)
                    @php $is_hide =true; @endphp
                    @foreach($attribute->sub_attributes  as $sub_attribute)
                        @if(!empty(old('attribute' ,$products_attributes) )  && $sub_attribute->status == 1 && in_array($sub_attribute->id , old('attribute',$products_attributes)))
                            @php $is_hide =false; @endphp
                        @endif
                    @endforeach

                    <div class="col-12  @if($is_hide )d-none @endif  attr" data-id="{{$attribute->id}}" id="div_attr_{{$attribute->id}}">
                       <div class="row">
                           <div class="col-11">
                               <div class="form-group">
                                   <label class="form-label" for="attr_{{$attribute->id}}">{{$attribute->name}}</label>
                                   <select multiple data-control="select2" name="attribute[]" class="form-control"
                                           id="attr_{{$attribute->id}}">
                                       @foreach($attribute->sub_attributes  as $sub_attribute)
                                           @if( $sub_attribute->status == 1 )
                                               <option
                                                   @if(!empty(old('attribute' ,$products_attributes) ) && in_array($sub_attribute->id , old('attribute',$products_attributes))) selected
                                                   @endif value="{{$sub_attribute->id}}">{{$sub_attribute->value}}</option>
                                           @endif
                                       @endforeach
                                   </select>
                               </div>
                           </div>
                           <div class="col-1">
                               <button class="btn btn-sm btn-secondary add_sub_attributes btn-icon mt-9"
                                       data-id="{{$attribute->id}}" type="button"><i class='fa fa-plus'></i></button>
                           </div>
                           <div class="row d-none" id="div_form_add_sub_attribute_{{$attribute->id}}"></div>
                       </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
