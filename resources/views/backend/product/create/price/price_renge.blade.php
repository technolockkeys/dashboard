<div class="col-12">
    <div class="card card-flush mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2>{{trans('backend.product.discounts_and_offers')}}</h2>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12 col-md-3" >

                        <label  class="form-label" for="date_type">{{trans('backend.product.date_type')}}</label>
                        <select name="date_type"  data-control="select2" class="form-control" id="date_type">
                            <option selected value="custom_date">{{trans('backend.product.custom_date')}}</option>
                            <option value="for_ever">{{trans('backend.product.for_ever')}}</option>
                        </select>

                </div>
                <div class="col-12 col-md-3" id="div_date_type">
                    <label class="form-label">{{trans('backend.product.discount_range')}}</label>
                    <input class="form-control" placeholder="" name="discount_range" id="discount_range"/>
                    <input type="hidden" name="discount_range_start" id="discount_range_start"/>
                    <input type="hidden" name="discount_range_end"   id="discount_range_end"/>

                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label class="form-label" for="discount_type">{{trans('backend.product.discount_type')}}</label>
                        <select name="discount_type" class="form-control" data-control="select2" id="discount_type">
                            <option value="none">{{trans('backend.product.none')}}</option>
                            <option value="fixed">{{trans('backend.product.fixed')}}</option>
                            <option value="percent">{{trans('backend.product.percent')}}</option>
                        </select>
                        @error('discount_type') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
                        </b> @enderror
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label class="form-label"  for="discount_value">{{trans('backend.product.discount_value')}}</label>
                        <input type="number" step="0.01" value="{{old('discount_value' , '')}}" class="form-control"
                               name="discount_value" id="discount_value">
                        @error('discount_value') <b class="text-danger"><i class="las la-exclamation-triangle"></i> {{$message}}
                        </b> @enderror
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <table class="table table-hover table-bordered table-striped text-center" style="overflow-x: scroll">
                        <thead class="bg-dark text-light ">
                        <tr>
                            <th>{{trans('backend.product.from')}}</th>
                            <th>{{trans('backend.product.to')}}</th>
                            <th>{{trans('backend.product.price')}}</th>
                            <th class="">{{trans('backend.global.actions')}}</th>


                        </tr>
                        </thead>
                        <tbody id="offer_table">

                        @if(!empty(old('from', [])))
                            @foreach(old('from' ) as  $key=>$from)
                                <tr data-row='{{$key}}'>
                                    <td><input type='number' value="{{$from}}" required class='form-control' name='from[]'></td>
                                    <td><input type='number'  @if(!empty(old('to')[$key]))  value="{{old('to')[$key]}}" @endif
                                        required class='form-control' name='to[]'></td>

                                    <td><input required type='number'
                                               @if(!empty(old('packages_price')[$key])) value="{{old('packages_price')[$key]}}"
                                               @endif  step="0.01"  class='form-control ' name='packages_price[]'></td>
                                    <td>
                                        <button type='button' data-uuid='{{$key}}'
                                                class='btn btn-danger btn-icon btn-sm remove-row'><i class='fa fa-times'></i>
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
            </div>
    </div>
</div>
