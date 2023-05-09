<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="required" for="price">{{trans('backend.product.price')}}</label>
            <input type="number" step="2" class="form-control" name="price" id="price"
                   value="{{old('price', $product->price)}}">
            <b class="text-danger" id="error_price"> @error('price') <i
                    class="las la-exclamation-triangle"></i> {{$message}} @enderror
            </b>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label  for="sale_price">{{trans('backend.product.sale_price')}}</label>
            <input type="number" step="2" class="form-control" value="{{old('sale_price' , $product->sale_price)}}"
                   name="sale_price"
                   id="sale_price">
            <b class="text-danger" id="error_sale_price">     @error('sale_price') <i
                    class="las la-exclamation-triangle"></i> {{$message}} @enderror
            </b>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="required" for="quantity">{{trans('backend.product.quantity')}}</label>
            <input type="number" step="2" class="form-control" name="quantity" id="quantity"
                   value="{{old("quantity" , $product->quantity)}}">
            <b class="text-danger" id="error_quantity">  @error('quantity')<i
                    class="las la-exclamation-triangle"></i> {{$message}}
                @enderror     </b>
        </div>
    </div>

</div>
<div class="row">
    <div class="col">
        <div class="separator separator-content border-dark my-15"><span
                class="w-250px fw-bolder">{{trans('backend.product.discounts_and_offers')}}</span></div>

    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="discount_type">{{trans('backend.product.discount_type')}}</label>
            <select name="discount_type" class="form-control" data-control="select2" id="discount_type">
                <option @if( $product->discount_type == 'none') selected
                        @endif value="none">{{trans('backend.product.none')}}</option>
                <option @if( $product->discount_type == 'fixed') selected
                        @endif value="fixed">{{trans('backend.product.fixed')}}</option>
                <option @if( $product->discount_type == 'percent') selected
                        @endif value="percent">{{trans('backend.product.percent')}}</option>
            </select>
            @error('discount_type') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
            </b> @enderror
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="discount_value">{{trans('backend.product.discount_value')}}</label>
            <input type="number" step="2" value="{{old('discount_value' ,$product->discount_value )}}"
                   class="form-control"
                   name="discount_value" id="discount_value">
            @error('discount_value') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
            </b> @enderror
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table class="table table-hover table-bordered table-striped text-center" style="overflow-x: scroll">
            <thead class="bg-dark text-light">
            <tr>
                <th >{{trans('backend.product.from')}}</th>
                <th >{{trans('backend.product.to')}}</th>
                <th >{{trans('backend.product.price')}}</th>
                <th class="">{{trans('backend.global.actions')}}</th>


            </tr>
            </thead>
            <tbody id="offer_table">

            @if(!empty(old('from',$from)))
                @foreach(old('from'  ,$from) as  $key=>$from)
                    <tr data-row='{{$key}}'>
                        <td><input type='number' value="{{$from}}" required class='form-control' name='from[]'></td>
                        <td><input type='number' @if(!empty(old('to' , $to)[$key]))  value="{{old('to', $to)[$key]}}"
                                   @endif
                                   required class='form-control' name='to[]'></td>
                        <td><input required type='number'
                                   @if(!empty(old('packages_price',$packages_price)[$key])) value="{{old('packages_price',$packages_price)[$key]}}"
                                   @endif  step='2' class='form-control ' name='packages_price[]'></td>
                        <td>
                            <button type='button' data-uuid='{{$key}}'
                                    class='btn btn-danger btn-icon btn-sm  btn-hover-scale   me-5 remove-row'><i class='fa fa-times'></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif


            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">
                    <button type="button" class="btn btn-icom btn-primary" id="add_new_offer"><i
                            class=" fa fa-plus"></i> {{trans('backend.product.add_new_offer')}}
                    </button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="separator separator-content border-dark my-15"><span
                class="w-250px fw-bolder">{{trans('backend.product.serial_numbers')}}</span>
        </div>

    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table text-center table-striped table-bordered table-hover">
            <thead class="bg-dark text-light">
            <tr>
                <th >{{trans('backend.product.serial_number')}}</th>
                <th>{{trans('backend.global.actions')}}</th>
            </tr>
            </thead>
            <tbody id="serial_numbers_table">
            @if(!empty(old('serial_number' ,$serial_number_value)))
                @foreach(old('serial_number' ,$serial_number_value ) as  $key=>$from)
                    <tr data-row='uuid_{{$key}}'>

                        <td>
                            <input type="hidden" name="serial_number_id[]" value="{{$serial_number_ids[$key]}}">
                            <input type='text' value="{{old('serial_number' ,$serial_number_value)[$key]}}" required
                                   class='form-control' name='serial_number[]'></td>
                        <td>
                            <button type='button' data-uuid='uuid_{{$key}}'
                                    class='btn btn-danger btn-icon btn-sm remove-row'><i
                                    class='fa fa-times'></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <button type="button" class="btn-primary btn " id="add_serial_number"><i class=" fa fa-plus"></i>
                        {{trans('backend.product.add_new_serial_number')}}
                    </button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
