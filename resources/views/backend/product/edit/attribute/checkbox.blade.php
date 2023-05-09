<div class="card mt-3">
    <div class="card-body">
        @foreach([
'is_best_seller',
'is_saudi_branch',
'is_featured',
'is_free_shipping',
'hide_price',
'status',
'back_in_stock',
] as $item)
            <div class="col-12 col-md-12">
                <div class="form-group  align-items-center">
                    <br>
                    <div class="form-check form-switch form-check-custom form-check-solid me-10">
                        <input class="form-check-input h-20px w-30px" @if(old($item, $product->$item) == 1 ) checked
                               @endif type="checkbox"
                               value="1"
                               name="{{$item}}" id="{{$item}}"/>
                        <label class="form-check-label" for="{{$item}}">
                            {{trans('backend.product.'.$item)}}
                        </label>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
</div>
