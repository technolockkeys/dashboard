<div class="col-12 col-md-12 col-lg-12 col-xl-12">
    <div class=" card card-flush   ">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.menu.products')}}</span>
            </h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-secondary text-xl btn-icon ">
                    <a href="{{route('seller.orders.download', $order->uuid)}}">
                        <i class="fonticon-printer fs-2x"></i>

                    </a>
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab"
                           href="#products">{{trans('backend.menu.products')}}</a>
                    </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab"
                       href="#payments">{{trans('backend.order.payment_history')}}</a>
                </li>
            </ul>
            <div class="tab-content" id="information_tabs">
            <div class=" tab-pane fade show active" id="products">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <!--begin::Table head-->
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-175px">{{__('backend.menu.products')}}</th>
                            {{--                        <th class="min-w-100px text-end">{{__('backend.product.attributes')}}</th>--}}
                            <th class="min-w-100px text-end">{{__('backend.product.sku')}}</th>
                            <th class="min-w-70px text-end">{{__('backend.product.quantity')}}</th>
                            <th class="min-w-100px text-end">{{__('backend.product.weight')}}</th>
                            <th class="min-w-100px text-end">{{__('backend.product.price')}}</th>
                            <th class="min-w-100px text-end">{{__('backend.order.total')}}</th>
                        </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                        <!--begin::Products-->
                        @foreach($products as $product)
                            <tr>
                                <!--begin::Product-->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!--begin::Thumbnail-->
                                        <a href="{{route('backend.products.edit', ['product'=>$product->id])}}"
                                           class="symbol symbol-50px">
                                            <img class="symbol-label"
                                                 onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                                 src=" {{media_file($product->image)}}">
                                        </a>
                                        <!--end::Thumbnail-->
                                        <!--begin::Title-->
                                        <div class="ms-5">
                                            @if($product->deleted_at != null) <span class="badge badge-warning">
                                            {{$product->getTranslation('title', app()->getLocale())}} </span> @else
                                                <span href="{{route('backend.products.edit', ['product'=>$product->id])}}"
                                                      class="fw-bolder text-gray-600 text-hover-primary">{{$product->getTranslation('title', app()->getLocale())}}</span> @endif
                                            {{--																					<div class="fs-7 text-muted">Delivery Date: 02/07/2022</div>--}}
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                </td>
                                <!--end::Product-->
                                <!--begin::SKU-->
                                {{--                            <td class="text-end">--}}
                                {{--                                @if(isset($product->pivot->attributes) && !empty($product->pivot->attributes) && json_decode($product->pivot->attributes, true) != null)--}}
                                {{--                                    @foreach(json_decode($product->pivot->attributes, true) as $attribute)--}}
                                {{--                                        @php--}}
                                {{--                                            $sub_attribute = \App\Models\SubAttribute::find($attribute);--}}
                                {{--                                            $attribute = $sub_attribute->attribute;--}}
                                {{--                                        @endphp--}}
                                {{--                                        <span>{{$attribute->name}}: {{$sub_attribute->value}}</span>,--}}
                                {{--                                    @endforeach--}}
                                {{--                                @else--}}
                                {{--                                    <span class="badge badge-warning">{{trans('backend.global.not_found')}}   </span>--}}
                                {{--                                @endif--}}

                                {{--                            </td>--}}
                                <td class="text-end">{{$product->sku}}</td>
                                <!--end::SKU-->
                                <!--begin::Quantity-->
                                <td class="text-end">{{$product->pivot->quantity}}</td>

                                <td class="text-end">{{$product->pivot->weight}} KG</td>
                                <!--end::Quantity-->
                                <!--begin::Price-->
                                <td class="text-end">
                                    @if($product->pivot->coupon_discount != null)
                                        <s class="text-info disabled">
                                            <b> {{currency($product->pivot->price / $product->pivot->quantity)}} </b></s>  {{currency(($product->pivot->price - $product->pivot->coupon_discount)/ $product->pivot->quantity )}}
                                    @else
                                        {{currency($product->pivot->price/ $product->pivot->quantity)}}
                                    @endif
                                </td>
                                <!--end::Price-->
                                <!--begin::Total-->
                                <td class="text-end">{{currency($product->pivot->price  ) }}</td>
                                <!--end::Total-->
                            </tr>

                            @if($product->is_bundle == 1)

                                @foreach($product->product_bundle as $bproduct)
                                    <tr  class="bg-gray-100">
                                        <!--begin::Product-->
                                        <td class="ps-10">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Thumbnail-->
                                                <a href="{{route('backend.products.edit', ['product'=>$bproduct->id])}}"
                                                   class="symbol symbol-50px">
                                                    <img class="symbol-label"
                                                         onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                                         src="{{media_file($bproduct->image)}}">
                                                </a>
                                                <!--end::Thumbnail-->
                                                <!--begin::Title-->
                                                <div class="ms-5">
                                                    @if($product->deleted_at != null) <span class="badge badge-warning">
                                            {{$product->getTranslation('title', app()->getLocale())}} </span> @else
                                                        <span href="{{route('backend.products.edit', ['product'=>$bproduct->id])}}"
                                                              class="fw-bolder text-gray-600 text-hover-primary">{{$bproduct->getTranslation('title', app()->getLocale())}}</span> @endif
                                                    {{--																					<div class="fs-7 text-muted">Delivery Date: 02/07/2022</div>--}}
                                                </div>
                                                <!--end::Title-->
                                            </div>
                                        </td>
                                        <!--end::Product-->
                                        <!--begin::SKU-->
                                        {{--                                    <td class="text-end">--}}
                                        {{--                                        @if(isset($bproduct->pivot->attributes) && !empty($bproduct->pivot->attributes) && json_decode($product->pivot->attributes, true) != null)--}}
                                        {{--                                            @foreach(json_decode($bproduct->pivot->attributes, true) as $attribute)--}}
                                        {{--                                                @php--}}
                                        {{--                                                    $sub_attribute = \App\Models\SubAttribute::find($attribute);--}}
                                        {{--                                                    $attribute = $sub_attribute->attribute;--}}
                                        {{--                                                @endphp--}}
                                        {{--                                                <span>{{$attribute->name}}: {{$sub_attribute->value}}</span>,--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        @else--}}
                                        {{--                                            <span class="badge badge-warning">{{trans('backend.global.not_found')}}   </span>--}}
                                        {{--                                        @endif--}}

                                        {{--                                    </td>--}}
                                        <td class="text-end">{{$bproduct->sku}}</td>
                                        <!--end::SKU-->
                                        <!--begin::Quantity-->
                                        <td class="text-end">{{$bproduct->pivot->quantity}}</td>

                                        <td class="text-end"><i class="la la-minus"></i></td>
                                        <!--end::Quantity-->
                                        <!--begin::Price-->
                                        <td class="text-end"><i class="la la-minus"></i></td>
                                        <!--end::Price-->
                                        <!--begin::Total-->
                                        <td class="text-end"><i class="la la-minus"></i></td>
                                        <!--end::Total-->
                                    </tr>

                                @endforeach
                            @endif
                        @endforeach

                        <tr>
                            <td colspan="5" class="text-end">{{__('backend.order.sub_total')}}</td>

                            <td class="text-end"> {{currency($order->total-$order->shipping+(!empty($order->coupon_value ) ? $order->coupon_value  : 0)  )}}</td>
                        </tr>
                        @if($order->shipping != 0)
                            <tr>
                                <td colspan="5" class="text-end">{{__('backend.order.shipping')}}</td>
                                <td class="text-end">{{currency($order->shipping)}}</td>
                            </tr>
                        @endif


                        @if($order->coupon_value != 0)
                            <tr>
                                <td colspan="5" class="fs-3 text-dark text-end">{{__('backend.order.discount')}}</td>
                                <td class="text-dark fs-3 fw-boldest text-end"> {{currency($order->coupon_value)}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="5" class="fs-3 text-dark text-end">{{__('backend.order.total')}}</td>
                            <td class="text-dark fs-3 fw-boldest text-end"> {{currency($order->total)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class=" tab-pane fade    " id="payments">
                @include('seller.order.data.payment_history')
            </div>
            </div>


            </div>

    </div>
</div>
