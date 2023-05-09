<div class="card card-flush">
    <div class="card-header">
        <div class="card-title">
            <h2>General</h2>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 mt-2 ">
                <div class="form-group">
                    <label for="sku" class="required form-label">{{trans('backend.product.sku')}}</label>
                    <input type="text" class="form-control" required id="sku" name="sku" value="{{old('sku' , $sku)}}">
                    <b class="text-danger" id="error_sku"> @error('sku')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label class="required" for="price">{{trans('backend.product.price')}}</label>
                    <input type="number" step="0.01"  class="form-control" name="price" id="price" value="{{old('price',$product->price)}}">
                    <b class="text-danger" id="error_price"> @error('price') <i
                            class="las la-exclamation-triangle"></i> {{$message}} @enderror
                    </b>
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label  for="sale_price">{{trans('backend.product.sale_price')}}</label>
                    <input type="number" step="0.01"  class="form-control" value="{{old('sale_price',$product->sale_price)}}" name="sale_price"
                           id="sale_price">
                    <b class="text-danger" id="error_sale_price">     @error('sale_price') <i
                            class="las la-exclamation-triangle"></i> {{$message}} @enderror
                    </b>
                </div>
            </div>

            <div class="col-12 col-md-12 mt-2">
                <label for="category" class="required form-label">{{trans('backend.product.category')}}</label>
                <select data-control="select2" class="form-control" required id="category" name="category">
                    <option value="null"></option>
                    {!! \App\Models\Category::select2([old('category',$product->category_id)],0,0) !!}
                </select>
                <b class="text-danger" id="error_category"> @error('category') <i
                        class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
            </div>
            <div class="col-12 col-md-12  mt-2 ">
                <div class="form-group">
                    <label for="weight" class="required form-label">{{trans('backend.product.weight')}}</label>
                    <input type="number" step="0.01" class="form-control" required id="weight" name="weight"
                           value="{{old('weight',$product->weight)}}">
                    <b class="text-danger" id="error_weight"> @error('weight')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror
                    </b>
                </div>
            </div>
            <div class="col-12 col-md-12  mt-2">
                <label for="priority" class="required form-label">{{trans('backend.product.priority')}}</label>
                <input type="number"  min="1" value="{{old('priority',$product->priority)}}" class="form-control" required id="priority" name="priority">
                <b class="text-danger" id="error_priority">     @error('priority')
                    <i class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label >{{trans('backend.product.quantity')}}</label>
                    <input readonly type="number"  class="form-control bg-secondary"
                           value="{{$product->quantity}}">
                </div>
            </div>
         <div class="col-12 col-md-12  mt-2">
                <label for="colors" class="form-label">{{trans('backend.product.colors')}}</label>
                <select class="form-control" id="color" name="color">
                    <option @if(old('colors' ,$product->color_id) == "") selected @endif value="">{{trans('backend.global.not_found')}}</option>
                    @foreach($colors as $item)
                        <option data-color="{{$item->code}}" @if($item->id ==  old('color',$product->color_id ) ) selected
                                @endif  value="{{$item->id}}">   {{$item->name}}</option>
                    @endforeach
                </select>
                <b class="text-danger" id="error_color">  @error('color')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
            </div>
        </div>
        <div class="col-12 col-md-12  mt-2">
            <label for="blocked_countries" class="form-label">{{trans('backend.product.blocked_countries')}}</label>
            <select class="form-control select2" multiple id="blocked_countries" name="blocked_countries[]" data-controls="select">
                <option @if(old('blocked_countries' ,$product->blocked_countries??[] ) == "")  @endif value="">{{trans('backend.global.select_an_option')}}</option>
                @foreach($countries as $country)
                    <option data-color="{{$country->code}}" @if(in_array($country->id ,  old('blocked_countries',$product->blocked_countries??[] )) ) selected
                            @endif  value="{{$country->id}}">   {{$country->name}}</option>
                @endforeach
            </select>
            <b class="text-danger" id="error_blocked_countries">  @error('blocked_countries')<i class="fa fa-exclamation-triangle"></i> {{$message}}@enderror</b>
        </div>
    </div>
</div>
