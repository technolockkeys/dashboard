<div class="row">
    <div class="col-12">
        <div class="separator separator-content border-dark my-15"><span
                class="w-250px fw-bolder">{{trans('backend.product.accessories')}}</span>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="accessories">{{trans('backend.product.accessories')}}</label>
            <select multiple class="form-control" data-control="select2" name="accessories[]" id="accessories">

                @foreach(\App\Models\Product::query()->whereIn('id' ,old('accessories',[-1]) )->get() as $item)
                    <option selected value="{{$item->id}}"> {{$item->title}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="separator separator-content border-dark my-15"><span
                class="w-250px fw-bolder">{{trans('backend.product.bundles')}}</span>
        </div>
    </div>
    <div class="col-12">

        <div class="form-group">
            <label for="bundles">{{trans('backend.product.bundle')}}</label>
            <select multiple class="form-control" data-control="select2" name="bundles[]" id="bundles">
                @foreach(\App\Models\Product::query()->whereIn('id' ,old('bundles',[-1]) )->get() as $item)
                    <option selected value="{{$item->id}}"> {{$item->title}} </option>
                @endforeach

            </select>
        </div>
    </div>

</div>
