<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3>{{trans('backend.menu.products')}}</h3>
        </div>
    </div>

    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <div class="row">

            <div class="col form-group">
                <div class="row">

                    <div class="col-3">

                    <label for="reports" class="col-form-label form-label">{{trans('backend.menu.products')}}</label>

                    <select class="form-control" name="route" data-control="select2"
                            id="route">
                        <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                        @foreach($routes as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-3">

                    <label for="reports" class="col-form-label form-label">{{trans('backend.menu.products')}}</label>
                    <select class="form-control" name="product" data-control="select2"
                            id="select_product">
                        <option value="{{null}}">{{__('backend.global.select_an_option')}}</option>
                        @foreach($products as $value)
                            <option value="{{$value->id}}">{{$value->sku}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="product_chart_wrapper">

        </div>
    </div>
</div>


<script>
$("#select_product").select2();
$("#route").select2();
</script>
