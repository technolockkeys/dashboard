<div class="col">
    <div class=" card card-flush   ">

        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.order.details')}}</span>
            </h3>
            <div class="card-toolbar">
                <div class="">
                    @if($order->type == \App\Models\Order::$proforma && (permission_can('edit order', 'admin') || permission_can('edit order', 'seller')))
                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_1">
                            {{__('backend.order.make_order')}}
                        </button>

                    @endif
                    <button type="button" class="btn btn-light-info text-xl btn-icon btn-sm ">
                        <a href="{{auth('admin')->check() ? route('backend.orders.download', $order->uuid) : route('seller.orders.download' ,$order->uuid)}}">
                            <i class="fonticon-printer fs-2x"></i>
                        </a>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12">

                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    @if($order->type != 'pin_code')
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab"
                               href="#products">{{trans('backend.menu.products')}}</a>
                        </li>
                    @elseif($order->type == 'pin_code')
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab"
                               href="#pin_code">{{trans('backend.order.pin_code')}}</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab"
                           href="#payments">{{trans('backend.order.payment_history')}}</a>
                    </li>
                </ul>

                <div class="tab-content" id="information_tabs">
                    @if($order->type != 'pin_code')
                        <div class=" tab-pane fade show active" id="products">

                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <thead>
                                    <tr class="text-start text-gray-900 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{__('backend.menu.products')}}</th>
                                        <th class="min-w-70px text-center">{{__('backend.product.sku')}}</th>
                                        <th class="min-w-70px text-start"
                                            style="max-width: 100%">{{__('backend.product.serial_numbers')}}</th>
                                        <th class="min-w-70px text-center">{{__('backend.product.quantity')}}</th>
                                        <th class="min-w-100px text-center">{{__('backend.product.weight')}}</th>
                                        <th class="min-w-100px text-end">{{__('backend.product.price')}}</th>
                                        <th class="min-w-100px text-end">{{__('backend.order.total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    @foreach($products as $product)
                                        <tr @if( !empty($product->is_bundle == 1)) class="  border-dark" @endif>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{route('backend.products.edit', ['product'=>$product->id])}}"
                                                       class="symbol    symbol-50px  ">
                                                        <img class="symbol-label"
                                                             onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                                             src=" {{$product->image}}">
                                                    </a>
                                                    <div class="ms-5">
                                                        @if($product->deleted_at != null)
                                                            <span
                                                                class="badge badge-warning">{{$product->title}} </span>

                                                        @elseif(auth('admin')->check())
                                                            <a href="{{route('backend.products.edit', ['product'=>$product->id])}}"
                                                               class="fw-bolder text-gray-600 text-hover-primary">{{$product->title}}</a>
                                                        @elseif(auth('seller')->check())
                                                            <a href="{{rtrim(get_setting('app_url'),'/').'/product/'.$product->slug}}"
                                                               class="fw-bolder text-gray-600 text-hover-primary">{{$product->title}}</a>
                                                        @endif
                                                        @if(!empty($product->pivot->serial_number) && !empty(json_decode($product->pivot->serial_number)))
                                                            <br>  <span class="badge badge-info">{{trans('backend.product.serial_number')}} : {{implode(',',json_decode($product->pivot->serial_number))}} </span>
                                                        @endif
                                                        @if(!empty($product->pivot->coupon_discount))
                                                            <br>
                                                            <b class="text-danger"><i
                                                                    class="las la-gift text-danger"></i> {{exc_currency($product->pivot->coupon_discount, $order->exchange_rate , $currency->symbol)}}
                                                            </b>

                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{$product->sku}}</td>
                                            <td class="text-start" style="max-width: 100%"
                                                id="serial_numbers_{{$product->sku}}">
                                                @if($product->is_bundle == 0)
                                                    @if(!in_array($order->status , [ \App\Models\Order::$canceled , \App\Models\Order::$completed, \App\Models\Order::$refunded]))
                                                        <select multiple name="serial_numbers"
                                                                class="form-control save_order_products"
                                                                data-orderproductid="{{$product->pivot->id}}"
                                                                id="serial_numbers_{{$product->pivot->id}}"
                                                                data-control="select2">
                                                            @foreach($product->all_serile_numbers  as $key=>$serile_number )
                                                                <option
                                                                    @if(in_array($serile_number->id,$product->serile_number)) selected
                                                                    @endif
                                                                    value="{{$serile_number->id}}">{{$serile_number->serial_number}}</option>
                                                            @endforeach
                                                        </select>
                                                    @else

                                                        <b>@foreach($product->serile_number_show as $item)
                                                                <span class="badge badge-success">
                                                                    {{$item}}
                                                                    </span>
                                                            @endforeach</b>

                                                    @endif

                                                @else
                                                    <i class=" la la-minus"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">{{$product->pivot->quantity}}</td>
                                            <td class="text-center">{{$product->pivot->weight . "KG"}}</td>
                                            <td class="text-end ">{{exc_currency(($product->pivot->price/  $product->pivot->quantity ) , $order->exchange_rate , $currency->symbol ) }}</td>
                                            <td class="text-end">{{exc_currency(($product->pivot->price )   , $order->exchange_rate , $currency->symbol ) }}</td>
                                        </tr>
                                        @if( !empty($product->is_bundle == 1))

                                            @foreach($product->bundle_products as $bproduct)
                                                <tr class="  @if($loop->last) border-dark     @endif bg-gray-100">
                                                    <td class="ps-10">
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{route('backend.products.edit', ['product'=>$bproduct->id])}}"
                                                               class="symbol  @if( !empty($bproduct->is_bundle == 1)) symbol-25px @else symbol-50px @endif">
                                                                <img class="symbol-label"
                                                                     onerror="this.src='{{media_file(get_setting('default_images'))}}'"
                                                                     src=" {{$bproduct->image}}">
                                                            </a>
                                                            <div class="ms-5">
                                                                @if($bproduct->deleted_at != null)
                                                                    <span class="badge badge-warning">
                                                                         {{$bproduct->title}}
                                                                    </span>
                                                                @else
                                                                    <a href="{{route('backend.products.edit', ['product'=>$bproduct->id])}}"
                                                                       class="fw-bolder text-gray-600 text-hover-primary">{{$bproduct->title}}</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{$bproduct->sku}}</td>
                                                    <td class="text-center" style="max-width: 70px  !important;"
                                                        id="serial_numbers_{{$bproduct->sku}}">
                                                        @if(!in_array($order->status , [ \App\Models\Order::$canceled , \App\Models\Order::$completed, \App\Models\Order::$refunded]))
                                                            <select multiple name="serial_numbers"
                                                                    class="form-control save_order_products"
                                                                    data-orderproductid="{{$bproduct->pivot->id}}"
                                                                    id="serial_numbers_{{$bproduct->pivot->id}}"
                                                                    data-control="select2">
                                                                @foreach($bproduct->all_serile_numbers  as $key=>$serile_number )
                                                                    <option
                                                                        @if(!empty($bproduct->serile_number) && in_array($serile_number->id,$bproduct->serile_number)) selected
                                                                        @endif
                                                                        value="{{$serile_number->id}}">{{$serile_number->serial_number}}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <b>@foreach($bproduct->serile_number_show as $item)
                                                                    <span class="badge badge-light-success">
                                                                    {{$item}}
                                                                    </span>
                                                                @endforeach</b>

                                                        @endif
                                                    </td>
                                                    <td class="text-center"> {{$product->pivot->quantity}}</td>
                                                    <td class="text-center"><i class="la la-minus"></i></td>
                                                    <td class="text-center"><i class="la la-minus"></i></td>
                                                    <td class="text-center"><i class="la la-minus"></i></td>
                                                </tr>
                                            @endforeach

                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="6"
                                            class="text-end text-dark">{{__('backend.order.sub_total')}}</td>
                                        <td class="text-end text-dark">

                                            {{exc_currency($order->total  +$order->coupon_value - $order->shipping, $order->exchange_rate , $currency->symbol)}}

                                        </td>
                                    </tr>
                                    @if($order->coupon_value != 0)
                                        <tr>
                                            <td colspan="6"
                                                class="fs-3 text-success text-end">{{__('backend.order.discount')}}</td>
                                            <td class="text-success fs-3 fw-boldest text-end">{{exc_currency($order->coupon_value, $order->exchange_rate , $currency->symbol)}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td colspan="6"
                                            class="text-end @if($order->shipping == 0)  text-danger @else text-dark @endif">{{__('backend.order.shipping')}}</td>
                                        <td class="text-end @if($order->shipping == 0)  text-danger @else text-dark @endif">
                                            @if($order->shipping == 0)
                                                ({{trans('backend.product.free_shipping')}}
                                                )
                                            @else
                                                {{exc_currency($order->shipping , $order->exchange_rate , $currency->symbol)}}
                                            @endif

                                        </td>
                                    </tr>


                                    <tr>
                                        <td colspan="6"
                                            class="fs-3 text-dark text-end">{{__('backend.order.total')}}</td>
                                        <td class="text-dark fs-3 fw-boldest text-end"> {{exc_currency($order->total, $order->exchange_rate , $currency->symbol  )}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif($order->type == 'pin_code')
                        @php
                            $note = json_decode($order->note);
                            $brand = \App\Models\Brand::find($note->brand);
                            $brand = $brand->make;
                        @endphp
                        <div class=" tab-pane fade show active" id="pin_code">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <thead>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">{{__('backend.order.brand')}}</th>
                                        <th class="min-w-100px   ">{{__('backend.order.serial_number')}}</th>
                                        <th class="min-w-70px ">{{__('backend.order.contact_channel')}}</th>
                                        <th class="min-w-70px ">{{__('backend.order.contact_value')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <tr class="text-start fw-bolder fs-7 text-uppercase gs-0">
                                        <td class="min-w-50px">{{$brand}}</td>
                                        <td class="min-w-50px  ">{{$note->serial_number}}</td>
                                        <td class="min-w-50px">{{$note->contact_channel}}</td>
                                        <td class="min-w-50px">{{$note->contact_value}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="tab-pane fade " id="payments">
                        @include('orders.card.payment_history')
                    </div>
                </div>

                <div class="card-footer">

                    <form
                        action="{{auth('seller')->check() ? route('seller.orders.change.status', $order->uuid) : route('backend.orders.change.status', $order->id) }}"
                        method="post">
                        @csrf
                        <div class="row col-12 justify-content-end text-end mb-4">

                            <div
                                class="col-12 justify-content-start text-start @if($order->type != \App\Models\Order::$proforma) col-md-6 @else col-md-12 @endif">

                                <div class="col-12 mt-2">
                                    <label for="shipment_value"
                                           class="required form-label">{{trans('backend.order.shipment_value')}}</label>

                                    <input type="text"
                                           class="form-control form-control-sm" id="shipment_value"
                                           name="shipment_value"
                                           value="{{old('shipment_value', $order->shipment_value)}}"/>
                                    @error('shipment_value')<b class="text-danger"> <i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="shipment_description"
                                           class="required form-label">{{trans('backend.order.shipment_description')}}</label>

                                    <textarea type="text"
                                              class="form-control form-control-sm resize-none"
                                              id="shipment_description"
                                              name="shipment_description" rows="4"
                                    >{{old('shipment_description', $order->shipment_description)}} </textarea>
                                    @error('shipment_description')<b class="text-danger"> <i
                                            class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                </div>
                            </div>
                            @if($order->type != \App\Models\Order::$proforma)
                                <div class="col-12 col-md-6 justify-content-start text-start">

                                    <div class="col-12 mt-2">
                                        <label for="status"
                                               class="status form-label">{{trans('backend.order.status')}}</label>

                                        <select name="status" class=" form-control-sm form-control"
                                                data-control="select2" id="order_statuses"
                                                data-placeholder="Select an option">

                                            @foreach($order_statuses as $key => $status)
                                                <option
                                                    value="{{$key}}" {{old('status',$order->status) == $key? 'selected':''}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                        @error('status')<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                    <div class="col-12 mt-2" id="div_payment_status">
                                        <label for="payment_status"
                                               class="required form-label">{{trans('backend.order.payment_status')}}</label>

                                        <select name="payment_status" class=" form-control-sm form-control"
                                                data-control="select2" id="payment_status"
                                                data-placeholder="Select an option">
                                            <option value=""></option>
                                            @foreach($order_payment_status as $key => $status)
                                                <option
                                                    value="{{$key}}" {{old('payment_status',$order->payment_status) == $key? 'selected':''}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                        @error('payment_status')<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                    <div class="col-12 mt-2" id="tracking_number_dev">
                                        <label for="tracking_number"
                                               class="required form-label">{{trans('backend.order.tracking_number')}}</label>

                                        <input type="text" placeholder="{{trans('backend.order.tracking_number')}}"
                                               class="form-control form-control-sm" id="tracking_number"
                                               name="tracking_number"
                                               value="{{old('tracking_number', $order->tracking_number)}}">
                                        @error('tracking_number')<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>

                                </div>
                            @endif
                            <div class="col-2 mt-2">
                                <button type="submit"
                                        class="btn btn-sm btn-success col-12 "><i
                                        class="la la-save"></i>{{trans('backend.global.save')}} </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{__('backend.order.make_order_confirmation')}}</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
            </div>

            <form class="form fv-plugins-bootstrap5 fv-plugins-framework"
                  action="{{auth('admin')->check() ? route('backend.orders.make-order',$order->id) : route('seller.orders.make-order',$order->uuid) }}"
                  id="proforma_to_order">
                @csrf
                <div class="modal-body">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" name="payment_method" type="radio" value="transfer"
                               id="transfer_payment"/>
                        <label class="form-check-label"
                               for="transfer_payment">{{trans('backend.order.transfer')}}</label>
                    </div>
                    <br>
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" name="payment_method" type="radio" value="stripe_link"
                               id="stripe_link_payment_input"/>
                        <label class="form-check-label"
                               for="stripe_link_payment_input">{{trans('backend.order.stripe_link')}}</label>
                    </div>
                    <br>
                    <div class="w-100" style="display:none" id="stripe_link_dev">
                        <div class="d-flex">
                            <input id="stripe_link_value" type="text"
                                   class="form-control form-control-solid me-3 flex-grow-1"
                                   value="test"/>

                            <button type="button" id="stripe_link_payment_btn"
                                    class="btn btn-light fw-bold flex-shrink-0"
                                     >Copy Link
                            </button>
                        </div>
                        <!--end::Title-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                    <button id="submit_save_convert" type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
