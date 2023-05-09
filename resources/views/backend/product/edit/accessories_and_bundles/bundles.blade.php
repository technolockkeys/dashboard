
<div class="col-12">
    <div class="card mt-3">
        <div class="card-body">
            <div class="form-group">
                <label for="bundles">{{trans('backend.product.bundle')}}</label>
                <select multiple class="form-control" data-control="select2" name="bundles[]" id="bundles">

                    @foreach(\App\Models\Product::query()->whereIn('id' ,old('bundles',json_decode($product->bundled)) )->get() as $item)
                        <option selected value="{{$item->id}}"> {{$item->title}} </option>
                    @endforeach

                </select>
            </div>

        </div>
    </div>
</div>
