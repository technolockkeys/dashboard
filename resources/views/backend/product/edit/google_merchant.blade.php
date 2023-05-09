<div class="card  card-flush">
    <div class="card-header">
        <div class="card-title">
            <h2>{{trans('backend.product.google_merchant')}}</h2>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col col-md-8 offset-md-2">
                {{--        SKU--}}
                <div class="mb-3 row">
                    <label for="g_sku" class="col-sm-2 col-form-label">{{trans('backend.product.sku')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_sku" name="g_sku"
                               value="{{old('g_sku',isset($google_merchant['sku'])?$google_merchant['sku'] :$product->sku)}}">
                    </div>
                </div>
                {{--        title--}}
                <div class="mb-3 row">
                    <label for="g_title" class="col-sm-2 col-form-label">{{trans('backend.product.title')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_title" name="g_title"
                               value="{{old('g_title',isset($google_merchant['title'])?$google_merchant['title'] :$product->meta_title)}} ">
                    </div>
                </div>
                {{--        image--}}
                <div class="mb-3 row">
                    <label for="g_image" class="col-2 col-form-label">{{trans('backend.product.image')}}</label>
                    <div class="col-8">
                        {!! single_image('g_image' , media_file(old('g_image',isset($google_merchant['image']) ?  $google_merchant['image'] : $product->image) ), old('g_image',isset($google_merchant['image']) ?  $google_merchant['image'] : $product->image )) !!}
                    </div>
                </div>
                {{--        gallery--}}
                <div class="mb-3 row">
                    <label for="g_gallery" class="col-2 col-form-label">{{trans('backend.product.gallery')}}</label>
                    <div class="col-8">
                        {!! multi_images('g_gallery' ,media_file( old('g_gallery' ,isset($google_merchant['gallery']) ?  $google_merchant['gallery'] : $product->gallery)) , old('gallery',isset($google_merchant['gallery']) ?  $google_merchant['gallery'] : $product->gallery ) , 'image' ,true) !!}
                    </div>
                </div>
                {{--        description--}}
                <div class="mb-3 row">
                    <label for="g_description"
                           class="col-sm-2 col-form-label">{{trans('backend.product.description')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_description" name="g_description"
                               value="{{old('g_description',isset($google_merchant['description']) ?  $google_merchant['description'] : $product->meta_description) }}">
                    </div>
                </div>
                {{--        category--}}
                <div class="mb-3 row">
                    <label for="g_category"
                           class="col-sm-2 col-form-label">{{trans('backend.product.category')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_category" name="g_category"
                               value="{{old('g_category'   ,isset($google_merchant['category']) ?  $google_merchant['category'] : $product->category ?->name)}}">
                    </div>
                </div>
                {{--        brand--}}
                <div class="mb-3 row">
                    <label for="g_manufacturer"
                           class="col-sm-2 col-form-label">{{trans('backend.product.manufacturer')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_manufacturer" name="g_manufacturer"
                               value="{{old('g_manufacturer' ,isset($google_merchant['manufacturer'])   ?  $google_merchant['manufacturer'] : ( !empty($product->manufacturer->title) ? $product->manufacturer->title : "")  ) }}">
                    </div>
                </div>
                {{--        color--}}
                <div class="mb-3 row">
                    <label for="g_color" class="col-sm-2 col-form-label">{{trans('backend.product.colors')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_color" name="g_color"
                               value="{{@$product->color->name}}">
                    </div>
                </div>
                {{--        link--}}
                <div class="mb-3 row">
                    <label for="g_link" class="col-sm-2 col-form-label">{{trans('backend.product.link')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_link"
                               value="{{old('g_link',route('backend.products.edit' , $product->id))}}" name="g_link">
                    </div>
                </div>
                {{--        price--}}
                <div class="mb-3 row">
                    <label for="g_price" class="col-sm-2 col-form-label">{{trans('backend.product.price')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_price" name="g_price"
                               value="{{old('g_price' ,isset($google_merchant['price']) ?  $google_merchant['price'] :$product->price) }}">
                    </div>
                </div>
                {{--        sale_price--}}
                <div class="mb-3 row">
                    <label for="g_sale_price"
                           class="col-sm-2 col-form-label">{{trans('backend.product.sale_price')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="g_sale_price" name="g_sale_price"
                               value="{{old('g_sale_price'  ,isset($google_merchant['sale_price']) ?  $google_merchant['sale_price'] : $product->sale_price)}}">
                    </div>
                </div>
                {{--        in_stock--}}
                <div class="mb-3 row">
                    <label for="g_in_stock"
                           class="col-sm-2 col-form-label">{{trans('backend.product.in_stock')}}</label>
                    <div class="col-sm-10">
                        <select name="g_in_stock" class="form-control  " data-control="select2" id="g_in_stock">
                            <option value="" disabled>{{trans('backend.global.select_an_option')}}</option>
                            @php  $select_in_stock = old('g_in_stock' , isset($google_merchant['back_in_stock']) ? $google_merchant['back_in_stock'] : $product->back_in_stock ); @endphp
                            <option @if($select_in_stock == 0  || $select_in_stock == 'new')  selected
                                    @endif  value="new">{{trans('backend.product.new')}}</option>
                            <option @if($select_in_stock  == 1 || $select_in_stock == 'back_in_stock' )  selected
                                    @endif value="back_in_stock">{{trans('backend.product.back_in_stock')}}</option>
                        </select>
                    </div>
                </div>
                {{--        weight--}}
                <div class="mb-3 row">
                    <label for="g_mpn" class="col-sm-2 col-form-label">{{trans('backend.product.weight')}}</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="g_weight" name="g_weight"
                               value="{{old('g_weight',isset($google_merchant['weight'])  ?$google_merchant['weight'] : $product->weight)}}">
                    </div>
                </div>
                {{--        GTIN--}}
                <div class="mb-3 row">
                    <label for="g_gtin" class="col-sm-2 col-form-label">{{trans('backend.product.GTIN')}}</label>
                    <div class="col-sm-10">
                        <input maxlength="14" type="text" class="form-control" id="g_gtin" name="g_gtin"
                               value="{{old('g_gtin',isset($google_merchant['gtin'])  ?$google_merchant['gtin'] :"")}}">
                    </div>
                </div>
                {{--        MPM--}}
                <div class="mb-3 row">
                    <label for="g_mpn" class="col-sm-2 col-form-label">{{trans('backend.product.mpn')}}</label>
                    <div class="col-sm-10">
                        <input maxlength="14" type="text" class="form-control" id="g_mpn" name="g_mpn"
                               value="{{old('g_mpn',isset($google_merchant['mpn'])  ?$google_merchant['mpn'] :"")}}">
                    </div>
                </div>
                {{--        Attributes--}}
                <div class="mb-3 row">
                    <!--begin::Accordion-->
                    <div class="accordion" id="kt_accordion_1">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="kt_accordion_1_header_1">
                                <button class="accordion-button fs-4 fw-bold" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#kt_accordion_1_body_1" aria-expanded="true"
                                        aria-controls="kt_accordion_1_body_1">
                                    {{trans('backend.product.attributes')}}                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show"
                                 aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                                <div class="accordion-body">
                                    <table class="table table-bordered table-striped ">

                                        <tbody>
                                        @if(isset($google_merchant['attributes']) && !empty($google_merchant['attributes']))
                                            @php  $g = 0 ;@endphp
                                            @foreach($google_merchant['attributes'] as $item)
                                                <tr id="g_attribute_{{$g}}">
                                                    <td><input name="g_attribute_name[]" type="text"
                                                               value="{{$item['name']}}"
                                                               class="form-control form-control-sm">
                                                    </td>
                                                    <td><input name="g_attribute_value[]" type="text"
                                                               value="{{$item['value']}}"
                                                               class="form-control form-control-sm">
                                                    </td>
                                                    <td>
                                                        <button
                                                            onclick="$('#g_attribute_{{$g}}').remove()"
                                                            type="button"
                                                            class="btn btn-danger btn-icon btn-sm"><i
                                                                class="fa fa-times"></i></button>
                                                    </td>
                                                </tr>
                                                @php  $g ++ ;@endphp
                                            @endforeach

                                        @else
                                            @php  $g = 0 ;@endphp
                                            @foreach($attributes as $attribute)
                                                @php $is_hide =true; @endphp
                                                @foreach($attribute->sub_attributes  as $sub_attribute)
                                                    @if(!empty(old('attribute' ,$products_attributes) )  && $sub_attribute->status == 1 && in_array($sub_attribute->id , old('attribute',$products_attributes)))

                                                        @foreach($attribute->sub_attributes  as $sub_attribute)
                                                            @if( $sub_attribute->status == 1 )
                                                                <tr id="g_attribute_{{$g}}">
                                                                    <td><input name="g_attribute_name[]" type="text"
                                                                               value="{{$attribute->name}}"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input name="g_attribute_value[]" type="text"
                                                                               value="{{$sub_attribute->value}}"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <button
                                                                            onclick="$('#g_attribute_{{$g}}').remove()"
                                                                            type="button"
                                                                            class="btn btn-danger btn-icon btn-sm"><i
                                                                                class="fa fa-times"></i></button>
                                                                    </td>
                                                                </tr>
                                                                @php  $g ++ ;@endphp
                                                            @endif
                                                        @endforeach

                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php  /*?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="kt_accordion_1_header_2">
                                <button class="accordion-button fs-4 fw-bold collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2"
                                        aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                                    {{trans('seller.orders.shipping')}}
                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_2" class="accordion-collapse collapse"
                                 aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                                <div class="accordion-body" style="  height: 400px;overflow-y: scroll;">
                                    <table style="  height: 100px;overflow-y: scroll;"
                                           class="table table-striped table-responsive table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{trans('backend.city.country_name')}}</th>
                                            <th> a{{trans('backend.setting.dhl')}}</th>
                                            <th>{{trans('backend.setting.fedex')}}   </th>
                                            <th>{{trans('backend.setting.aramex')}}  </th>
                                            <th>{{trans('backend.setting.ups')}}     </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $k= 1 ; @endphp
{{--                                        @if(!empty($google_merchant))--}}

                                        @foreach($shipping_cost as $key=>$item)
                                            @foreach(json_decode($item['countries']) as $country)
                                                <tr id="shipping_cost_{{$k  }}">


                                                    <td><input type="text" name="g_countries[]"
                                                               class="form-control form-control-sm"
                                                               value="{{$country  }}             "></td>
                                                    <td><input type="text" name="g_dhl[]"
                                                               class="form-control form-control-sm"
                                                               value="{{$item['cost']['DHL']}}   "></td>
                                                    <td><input type="text" name="g_fedex[]"
                                                               class="form-control form-control-sm"
                                                               value="{{$item['cost']['fedex']}} "></td>
                                                    <td><input type="text" name="g_aramex[]"
                                                               class="form-control form-control-sm"
                                                               value="{{$item['cost']['aramex']}}"></td>
                                                    <td><input type="text" name="g_ups[]"
                                                               class="form-control form-control-sm"
                                                               value="{{$item['cost']['ups']}}   "></td>

                                                    <td>
                                                        <button type="button"
                                                                onclick="$('#shipping_cost_{{$k  }}').remove()"
                                                                class="btn btn-danger btn-icon btn-sm"><i
                                                                class="fa fa-times"> </i></button>
                                                    </td>
                                                </tr>
                                                @php $k++ ; @endphp
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
          <?php  */?>


                    </div>


                </div>


            </div>
        </div>


    </div>
</div>
