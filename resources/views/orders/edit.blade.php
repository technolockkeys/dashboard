@if(auth('admin')->check())
    @php $layout= 'backend.layout.app' ;  @endphp
@else
    @php $layout= 'seller.layout.app' ;  @endphp
@endif
@extends($layout)

@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name' , app()->getLocale()))

@section('style')
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    <style>
        .iti {
            width: 100% !important;

        }
    </style>
@endsection
@section('content')
    <form
        action="{{auth('seller')->check() ?route('seller.orders.update' , $order->uuid) :route('backend.orders.update' , $order->id)}}"
        id="form_order">
        @csrf
        <input type="hidden" value="$" id="currecny">
        <input type="hidden" value="{{$order->uuid}}" id="order_uuid">

        <input type="hidden" value="{{$currency->symbol }}" id="currency_symbol" name="currency_symbol">
        <input type="hidden" value="{{empty($order->exchange_rate) ? 1 : $order->exchange_rate }}" id="currency_rate"
               name="currency_rate">
        <input type="hidden" id="currency" name="currency" value="{{$currency->id }}">

        <input type="hidden" value="{{$currency->id }}" data-symbol="{{$currency->symbol }}"
               data-rate="{{empty($order->exchange_rate) ? 1 : $order->exchange_rate }}"
               id="courrency_{{$currency->id }}" name="currency">
        <div class="col  ">

            <div class="d-flex flex-column flex-row flex-lg-row h-100">
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                    <div class="card   flex-row-fluid mb-2  ">
                        <div class="card-header">
                            <h3 class="card-title"> {{trans('seller.orders.edit',['uuid'=>$order->uuid])}}</h3>

                        </div>
                        <div class="card-body">
                            <div class="row">

                                @if(auth('seller')->check())
                                    <input type="hidden" name="seller" value="{{auth('seller')->id()}}">
                                @else
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="seller">{{trans('backend.order.seller')}}</label>
                                            <select name="seller" class="form-control form-control-sm"
                                                    data-control="select2"
                                                    id="seller">
                                                <option value="-1">{{trans('backend.global.all')}}</option>
                                                @foreach($sellers as $seller )
                                                    <option id="seller_option_{{$seller->id}}"
                                                            @if($order->seller_id == $seller->id) selected @endif
                                                            data-ratepercent="{{$seller->seller_product_rate}}"
                                                            value="{{$seller->id}}">{{$seller->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="user" class="required">{{trans('seller.orders.user')}}</label>
                                        <select name="user" id="user" data-control="select2"
                                                class="form-control form-control-sm">
                                            <option value="" selected
                                                    disabled>{{trans('backend.product.please_select_option')}}</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->uuid}}"
                                                        @if($user->uuid == old('user') || $order->user_id == $user->id ) selected @endif >{{$user->uuid}}</option>
                                            @endforeach
                                        </select>
                                        <b class="text-danger" id="user_error">@error('user') <i
                                                class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="address" class="required">{{trans('seller.orders.address')}}</label>
                                        <select name="address" id="address" data-control="select2"
                                                class="form-control form-control-sm">
                                            <option value=""
                                                    disabled>{{trans('seller.orders.please_select_option')}}</option>
                                            @foreach($address as $item)
                                                <option data-country="{{$item->country_id}}"
                                                        {{ ($item->id == $order->address_id) ? 'selected' :'' }}  value="{{$item->id}}">   {{$item->get_country().'/'.$item->city . '/'.$item->street}}</option>
                                            @endforeach
                                            <option value="-1">{{trans('seller.orders.create_new_address')}}</option>
                                        </select>
                                        <b class="text-danger" id="address_error">@error('address') @enderror</b>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="type" class="required">{{trans('seller.orders.type')}}</label>
                                        <select name="type" id="type" data-control="select2"
                                                class="form-control form-control-sm">

                                            <option @if($order->type =='order') selected
                                                    @endif value="order">{{trans('seller.orders.order')}}</option>
                                            <option @if($order->type =='proforma') selected
                                                    @endif value="proforma">{{trans('seller.orders.proforma')}}</option>
                                        </select>
                                        <b class="text-danger" id="type_error">@error('type') @enderror</b>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="shipping_method"
                                               class="required">{{trans('seller.orders.shipping_method')}}</label>
                                        <select name="shipping_method" id="shipping_method"
                                                class="form-control form-control-sm"
                                                data-control="select2">
                                            <option @if($order->shipping_method == "dhl") selected @endif value="dhl">
                                                DHL
                                            </option>
                                            <option @if($order->shipping_method == "ups") selected @endif value="ups">
                                                UPS
                                            </option>
                                            <option @if($order->shipping_method == "aramex") selected
                                                    @endif value="aramex">ARAMEX
                                            </option>
                                            <option @if($order->shipping_method == "fedex") selected
                                                    @endif value="fedex">FEDEX
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="shipment_value"
                                               class="required">{{trans('backend.order.shipment_value')}}</label>
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{$order->shipment_value}}" required
                                               name="shipment_value" id="shipment_value">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="required"
                                               for="shipment_description">{{trans('backend.order.shipment_description')}}</label>
                                        <input type="text" class="form-control form-control-sm"
                                               value="{{$order->shipment_description}}" required
                                               name="shipment_description" id="shipment_description">
                                    </div>

                                </div>
                                <div class="col-md-3 col-12" id="div_order_status">
                                    <div class="form-group">
                                        <label for="order_status"
                                               class="required">{{trans('backend.order.status')}}</label>
                                        <select class="form-control form-control-sm" data-control="select2"
                                                name="status" required
                                                id="order_status">
                                            @foreach($order_statuses as $key=>$value)
                                                <option @if($order->status ==$key) selected
                                                        @endif value="{{$key}}">{{$value}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="note">{{trans('seller.orders.note')}}</label>
                                        <textarea rows="3" class="form-control form-control-sm  " name="note"
                                                  id="notert" style="resize: none">{{$order->note}}</textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 payment_div ">
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="payment_method"
                                               class="required">{{trans('seller.orders.payment_method')}}</label>
                                        <select name="payment_method" id="payment_method" data-control="select2"
                                                class="form-control form-control-sm">
                                            <option value=""
                                                    disabled>{{trans('backend.product.please_select_option')}}</option>
                                            <option @if($order->payment_method == 'stripe_link' ) selected
                                                    @endif value="stripe_link">{{trans('seller.orders.stripe_link')}} </option>
                                            <option @if($order->payment_method == 'transfer' ) selected
                                                    @endif    value="transfer">{{trans('seller.orders.transfer')}} </option>
                                        </select>
                                        <b class="text-danger" id="user_error">@error('user') <i
                                                class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
                                    </div>
                                </div>
                                <div class="col-12 col-md-9" id="payment_files_div"
                                     @if($order->payment_method== \App\Models\Order::$transfer) style="display: block" @endif>
                                    <div class="form-group">
                                        <label for="payment_files">{{trans('seller.orders.payment_files')}}</label>
                                        <input class="form-control form-control-sm" type="file" id="payment_files"
                                               name="payment_files[]" multiple>
                                    </div>
                                    @if($order->payment_method== \App\Models\Order::$transfer)
                                        <div class="row mt-1">

                                            <div class="col">
                                                @if(!empty($files))
                                                @foreach(json_decode($files) as  $key=>$item)
                                                    <a href="{{asset('storage/'.$item)}}" target="_blank"
                                                       class="btn btn-outline btn-outline-dashed btn-outline-default me-2 mb-2   btn-sm ">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                             viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                  d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                                  fill="currentColor"></path>
                                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                  fill="currentColor"></path>
                                                        </svg>
                                                        {{$key+1}}.File
                                                    </a>


                                                @endforeach
                                                    @endif
                                            </div>

                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card flex-row-fluid mb-2 ">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="products">{{trans('seller.orders.product')}}</label>
                                        <select name="products" id="products" data-control="select2"
                                                class="form-control form-control-sm">
                                            @foreach($products as $product)
                                                <option
                                                    data-title="{{$product['title']}}"
                                                    data-quantity="{{$product['quantity']}}"
                                                    data-price="{{$product['price']}}"
                                                    data-sku="{{$product['sku']}}"
                                                    data-blockc="{{json_encode(empty($product['blocked_countries']) ? [1] : $product['blocked_countries'])}}"
                                                    data-blockcn="{{$product['blocked_countries_names']}}"
                                                    data-image="{{$product['image']}}"
                                                    value="{{$product['sku']}}">
                                                    <b
                                                        class="text-body"> {{$product['sku']}}</b>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group mt-6">
                                        <button type="button" id="add_product" style="padding-right: 0px;
    padding-left: 0;"
                                                class="btn w-100 btn-outline btn-sm btn-outline-dashed btn-outline-primary btn-active-light-primary pr-0 pl-0">
                                            <i class="las la-plus"></i>
                                            {{trans('seller.orders.add_product')}}
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card flex-row-fluid mb-2 ">

                        <div class="card-body">
                            <div class="table-responsive mb-10">
                                <!--begin::Table-->
                                <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700" data-kt-element="items">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                        <th class="min-w-300px w-475px">{{trans('seller.orders.item')}}</th>
                                        <th class="min-w-150px w-150px">{{trans('seller.orders.quantity')}}</th>
                                        <th class="min-w-150px w-150px">{{trans('seller.orders.price')}}</th>
                                        <th class="min-w-125px w-150px text-end">{{trans('seller.orders.total')}}</th>
                                        <th class="min-w-75px w-75px text-end">{{trans('backend.global.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody id="product_table">

                                    </tbody>
                                    <!--end::Table body-->

                                </table>
                            </div>
                            <div id="card_products">

                            </div>

                        </div>
                    </div>
                </div>

                <div class="  flex-lg-auto min-w-lg-300px ">
                    <div data-kt-sticky="true" data-kt-sticky-offset="{default: true, lg: '200px'}"
                         data-kt-sticky-width="{lg: '250px', lg: '300px'}" data-kt-sticky-left="auto"
                         data-kt-sticky-top="80px" data-kt-sticky-animation="true" data-kt-sticky-zindex="94">

                        <div class="card mb-3" id="coupon_card" @if(!empty($coupon))  style="display: none" @endif>
                            <div class="card-header">
                                <b class="card-title"> {{trans('seller.orders.coupon')}}</b>
                            </div>
                            <div class="card-body ">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="coupon_code"
                                           value="{{!empty($coupon->code) ?$coupon->code :""}}"
                                           name="coupon_code">
                                </div>
                                <div class="form-group mt-2">
                                    <button type="button" id="apply_coupon" class="btn btn-light-success  w-100"><i
                                            class="las la-percent"></i> {{trans('seller.orders.apply_coupon')}}</button>
                                </div>

                            </div>

                        </div>
                        <div class="card   ">

                            <div class="card-header">
                                <h6 class="card-title">{{trans('seller.orders.order_details')}}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-7 ">
                                    <label
                                        class="col-7 text-start  text-danger"><b>{{trans('backend.menu.currencies')}}</b></label>
                                    <div class="col-5" style="text-align: right">
                                        <span
                                            class="fw-bolder fs-6   text-danger"> 1 $ = {{number_format($order->exchange_rate ,2).' '.$currency->symbol }} </span>
                                    </div>
                                </div>

                                <div class="separator border-danger mb-7"></div>

                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.sub_total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                        <span class="fw-bolder fs-6 text-gray-800"><span class="number_price"
                                                                                         id="result_sub_total"></span> <span
                                                class="currency"></span></span>
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.shipping_total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                <span class="fw-bolder fs-6 text-gray-800"
                                      id=""><span class="number_price" id="result_shipping_total"></span> <span
                                        class="currency"> </span> </span>
                                    </div>
                                </div>
                                <div class="row mb-7" @if( empty($coupon)) style="display: none" @endif id="coupon_row">
                                    <label class="col-7  text-success">
                                        <b class="text-danger" style="cursor: pointer" id="remove_coupon"><i
                                                class="las la-times-circle"></i></b>
                                        {{trans('seller.orders.coupon')}}
                                    </label>
                                    <div class="col-5 text-success" style="text-align: right">
                                        @if(!empty($coupon))
                                            <span class="fw-bolder fs-6 "><span data-price="{{$order->coupon_value}}"
                                                                                class="number_price"
                                                                                id="result_discount_value">{{$order->coupon_value}}</span> <span
                                                    class="currency">$</span></span>
                                        @else
                                            <span class="fw-bolder fs-6 "
                                            ><span data-price="0" class="number_price"
                                                   id="result_discount_value">0</span> <span class="currency">$</span>   </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                        <span class="fw-bolder fs-6 text-gray-800"
                                        >
                                            <span class="number_price" id="result_total"></span>
                                            <span class="currency"> $</span>
                                           </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button id="create_order_button" class="btn btn-success  w-100" type="submit">
                                            {{trans('backend.global.save')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <div class="modal fade" id="create_new_address" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">{{trans('seller.orders.create_new_address')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user" class="form-label required">{{trans('seller.orders.user')}}</label>
                        <input type="text" readonly class="form-control form-control-sm" value="" id="address_user">
                        <b class="text-danger" id="error_address_user"></b>
                    </div>
                    @if(false)
                        <div class="form-group">
                            <label for="address_title"
                                   class="form-label required">{{trans('seller.orders.title')}}</label>
                            <input type="text" name="address_title" id="address_title"
                                   class="form-control form-control-sm">
                            <b class="text-danger" id="error_address_title"></b>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="address_country"
                               class="form-label required">{{trans('seller.orders.country')}}</label>
                        <select name="address_country" id="address_country" class="form-control form-control-sm"
                                data-control="select2">
                            <option disabled selected>{{trans('seller.orders.please_select_option')}}</option>

                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </select>
                        <b class="text-danger" id="error_address_country"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_state">{{trans('seller.orders.state')}}</label>
                        <input type="text" name="address_state" id="address_state"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_state"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_city" class="form-label required">{{trans('seller.orders.city')}}</label>
                        <input type="text" name="address_city" id="address_city"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_city"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_street">{{trans('seller.orders.street')}}</label>
                        <input type="text" name="address_street" id="address_street"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_street"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_address">{{trans('seller.orders.address')}}</label>
                        <textarea style="resize: none" name="address_address" id="address_address"
                                  class="form-control form-control-sm"
                                  rows="3"></textarea>
                        <b class="text-danger" id="error_address_address"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_postal_code">{{trans('seller.orders.postal_code')}}</label>
                        <input type="text" name="address_postal_code" id="address_postal_code"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_postal_code"></b>
                    </div>
                    <div class="form-group">
                        <label for="address_phone" class="form-label required">{{trans('seller.orders.phone')}}</label>
                        <input name="address_phone" id="address_phone" class="form-control form-control-sm"/>
                        <b class="text-danger" id="error_address_address"></b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                    <button type="button" class="btn btn-primary"
                            id="save_address">{{trans('backend.global.save')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="successfully_order">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body" id="successfully_order_content">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col text-center">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                            <a class="btn btn-primary" href=""
                               id="backend_global_download">{{trans('backend.global.download')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if(auth('admin')->check())
        @include('orders.admin_edit')
    @else
        @include('orders.seller_edit')
    @endif
@endsection

