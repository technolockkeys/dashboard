<div class="card card-flush">
    <div class="card-header">
        <div class="card-title">
            <h2>{{trans('backend.product.competitors')}}</h2>
        </div>
    </div>

    @php
        $competitors  =json_decode($product->competitors_price);
        $competitors_url=[];
        $competitors_selector=[];
        $competitors_name=[];
        $competitors_html_type=[];
        $competitors_price=[];
        if($competitors != null)
        foreach ($competitors as $competitor ){
            $competitors_url[] =  $competitor->url;
            $competitors_selector[] =  $competitor->selector;
            $competitors_name[] =  $competitor->name;
            $competitors_html_type[] =  $competitor->html_tag;
            $competitors_price[] =  $competitor->price ;
        }

    @endphp
    <div class="card-body">

        <div class="row mt-3">
            <div class="col">
                <table class="table table-hover table-bordered table-striped text-center table-rounded" style="overflow-x: scroll">
                    <thead class="bg-dark text-light rounded-lg">
                    <tr>
                        <th class="w-4" style="width: 35%;">{{trans('backend.product.competitors_url')}}</th>
                        <th class="w-2" style="width: 15%;">{{trans('backend.product.competitors_tag')}}</th>
                        <th class="w-2" style="width: 15%;">{{trans('backend.product.competitors_type')}}</th>
                        <th class="w-2" style="width: 15%;">{{trans('backend.product.competitors_html_type')}}</th>
                        <th class="w-2" style="width: 15%;">{{trans('backend.product.competitors_price')}}</th>
                        <th class="" style="width: 5%;">{{trans('backend.global.actions')}}</th>

                    </tr>
                    </thead>
                    <tbody id="add_competitors">

                    @if(!empty(old('competitors_url', $competitors_url)))
                        @foreach(old('competitors_url',$competitors_url ) as  $key=>$competitor_url)

                            <tr data-row='{{$key}}'>
                                <td><input type='text' value="{{$competitor_url}}" required class='form-control' name='competitors_url[]'></td>
                                <td>
                                    <select class="form-control" id="competitors_selector_{{$key}}"
                                            name="competitors_selector[]" required
                                            data-control="select2">
                                        <option value="id" @if(old('competitors_selector',$competitors_selector)[$key] == 'id') selected @endif>{{trans('backend.global.id')}}</option>
                                        <option value="class" @if(old('competitors_selector',$competitors_selector)[$key] == 'class') selected @endif>{{trans('backend.global.class')}}</option>
                                    </select>
                                </td>

                                <td><input required type='text'
                                           @if(!empty(old('competitors_name',$competitors_name)[$key])) value="{{old('competitors_name',$competitors_name)[$key]}}"
                                           @endif  step="0.01"  class='form-control ' name='competitors_name[]'></td>
                                <td><input required type='text'
                                           @if(!empty(old('competitors_html_type',$competitors_html_type)[$key])) value="{{old('competitors_html_type',$competitors_html_type)[$key]}}"
                                           @endif  step="0.01"  class='form-control ' name='competitors_html_type[]'></td>
                                <td><input type='text'
                                           @if(!empty(old('competitors_price',$competitors_price)[$key])) value="{{old('competitors_price',$competitors_price)[$key]}}"
                                           @endif  step="0.01"  class='form-control ' name='competitors_price[]'></td>
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
                            <button type="button" onclick="add_competitors()" class="btn btn-icom btn-primary" ><i
                                        class=" fa fa-plus"></i> {{trans('backend.product.add_competitors')}}
                            </button>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

</div>
