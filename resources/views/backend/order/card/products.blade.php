<div class="col">
    <div class=" card card-flush   ">

        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder text-dark">{{__('backend.order.details')}}</span>
            </h3>
            <div class="card-toolbar">
                <div class="">
                    @if($order->type == \App\Models\Order::$proforma && permission_can('change to order', 'admin'))
                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_1">
                            {{__('backend.order.make_order')}}
                        </button>
                    @endif
                    <button type="button" class="btn btn-light-info text-xl btn-icon btn-sm ">
                        <a href="{{route('backend.orders.download', $order->id)}}">
                            {{--                            <i class="bi text-xl bi-printer"></i>--}}
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
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{__('backend.menu.products')}}</th>
                                        <th class="min-w-70px text-center">{{__('backend.product.sku')}}</th>
                                        <th class="min-w-70px text-center"
                                            style="max-width: 100%">{{__('backend.product.serial_numbers')}}</th>
                                        <th class="min-w-70px text-center">{{__('backend.product.quantity')}}</th>
                                        <th class="min-w-100px text-center">{{__('backend.order.shipping')}}</th>
                                        <th class="min-w-100px text-center">{{__('backend.product.price')}}</th>
                                        <th class="min-w-100px text-center">{{__('backend.order.total')}}</th>
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
                                                            <span class="badge badge-warning">
                                                        {{$product->title}} </span>
                                                        @else
                                                            <a href="{{route('backend.products.edit', ['product'=>$product->id])}}"
                                                               class="fw-bolder text-gray-600 text-hover-primary">{{$product->title}}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{$product->sku}}</td>
                                            <td class="text-center" style="max-width: 100%"
                                                id="serial_numbers_{{$product->sku}}">
                                                @if($product->is_bundle == 0)
                                                    @if($order->status == \App\Models\Order::$on_hold)
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

                                                        <b>{!!   implode(' , ' ,$product->serile_number_show) !!}</b>

                                                    @endif

                                                @else
                                                    <i class=" la la-minus"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">{{$product->pivot->quantity}}</td>
                                            <td class="text-center">{{currency($product->pivot->shipping_cost)}}</td>
                                            <td class="text-center">{{$product->pivot->quantity}}
                                                * {{currency($product->pivot->price  ) }}</td>
                                            <td class="text-center">{{currency(($product->pivot->price  * $product->pivot->quantity ) +$product->pivot->shipping_cost ) }}</td>
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
                                                        @if($order->status == \App\Models\Order::$on_hold)
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
                                                            <b>{{implode(',' ,$bproduct->serile_number_show)}}</b>

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
                                        <td colspan="5" class="text-end">{{__('backend.order.sub_total')}}</td>
                                        <td class="text-end"> {{currency($order->total - $order->shipping +$order->coupon_value )}}</td>
                                    </tr>
                                    @if($order->shipping != 0)
                                        <tr>
                                            <td colspan="5" class="text-end">{{__('backend.order.shipping')}}</td>
                                            <td class="text-end">{{currency($order->shipping)}}</td>
                                        </tr>
                                    @endif
                                    @if($order->coupon_value != 0)
                                        <tr>
                                            <td colspan="5"
                                                class="fs-3 text-dark text-end">{{__('backend.order.discount')}}</td>
                                            <td class="text-dark fs-3 fw-boldest text-end">{{currency($order->coupon_value)}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5"
                                            class="fs-3 text-dark text-end">{{__('backend.order.total')}}</td>
                                        <td class="text-dark fs-3 fw-boldest text-end"> {{currency($order->total  )}}</td>
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
                                        <th class="min-w-100px ">{{__('backend.order.serial_number')}}</th>
                                        <th class="min-w-70px ">{{__('backend.order.contact_channel')}}</th>
                                        <th class="min-w-70px ">{{__('backend.order.contact_value')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <tr class="text-start fw-bolder fs-7 text-uppercase gs-0">
                                        <td class="min-w-50px">{{$brand}}</td>
                                        <td class="min-w-50px">{{$note->serial_number}}</td>
                                        <td class="min-w-50px">{{$note->contact_channel}}</td>
                                        <td class="min-w-50px">{{$note->contact_value}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="tab-pane fade " id="payments">
                        @include('backend.order.card.payment_history')
                    </div>
                </div>
                @if($order->type != \App\Models\Order::$proforma)
                    <div class="card-footer">

                        <form action="{{route('backend.orders.change.status', $order->id)}}" method="post">
                            @csrf
                            <div class="row col-12 justify-content-end text-end mb-4">
                                <div class="col-12 justify-content-start text-start col-md-6">
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

                                <div class="col-12 col-md-6 justify-content-start text-start">

                                    <div class="col-12 mt-2">
                                        <label for="status"
                                               class="status form-label">{{trans('backend.order.status')}}</label>

                                        <select name="status" class=" form-control-sm form-control"
                                                data-control="select2"
                                                data-placeholder="Select an option">
                                            <option value=""></option>
                                            @foreach($order_statuses as $key => $status)
                                                <option
                                                    value="{{$key}}" {{old('status',$order->status) == $key? 'selected':''}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                        @error('status')<b class="text-danger"> <i
                                                class="las la-exclamation-triangle"></i> {{$message}}</b>@enderror
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="payment_status"
                                               class="required form-label">{{trans('backend.order.payment_status')}}</label>

                                        <select name="payment_status" class=" form-control-sm form-control"
                                                data-control="select2"
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
                                    <div class="col-12 mt-2">
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

                                <div class="col-2 mt-2">
                                    <button type="submit"
                                            class="btn btn-sm btn-success col-12 "><i
                                            class="la la-save"></i>{{trans('backend.global.save')}} </button>
                                </div>

                            </div>
                        </form>
                    </div>
                @endif
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
                  action="{{route('backend.orders.make-order',$order->id)}}" id="proforma_to_order">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                    <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

