<div class="col-12">
    <div class="card card-flush mt-3">

        <div class="card-body">
            <div class="form-group">
                <label for="accessories">{{trans('backend.product.accessories')}}</label>
                <select multiple class="form-control" data-control="select2" name="accessories[]" id="accessories">

                    @foreach(\App\Models\Product::query()->whereIn('id' ,old('accessories',[-1]) )->get() as $item)
                        <option selected value="{{$item->id}}"> {{$item->title}} </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
