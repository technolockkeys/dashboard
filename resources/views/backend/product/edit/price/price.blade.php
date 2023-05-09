@if(false)
<div class="col-md-12 col-12  mt-3">
    <div class="card card-flush py-4 ">

        <div class="card-body">
            <div class="row">

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="required" for="quantity">{{trans('backend.product.quantity')}}</label>
                        <input type="number" step="0.01" class="form-control" name="quantity" id="quantity"
                               value="{{old("quantity",$product->quantity)}}">
                        <b class="text-danger" id="error_quantity">  @error('quantity')<i
                                class="las la-exclamation-triangle"></i> {{$message}}
                            @enderror     </b>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endif
