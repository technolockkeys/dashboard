@if(auth('admin')->check())
    @php $layout= 'backend.layout.app' ;  @endphp
@else
    @php $layout= 'seller.layout.app' ;  @endphp
@endif
@extends($layout)
@section('title',trans('backend.menu.orders').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('style')
    <link rel="stylesheet" href="{{asset('backend/plugins/custom/intltell/css/intlTelInput.css')}}">
    <style>
        .iti {
            width: 100% !important;

        }
    </style>
@endsection
@section('content')
    <form action="{{auth('seller')->check() ? route('seller.orders.store') : route('backend.orders.store')}}"
          id="form_order">
        @csrf
        {{--        <input type="hidden" value="$" id="currecny">--}}
        <input type="hidden" value="$" id="currency_symbol" name="currency_symbol">
        <input type="hidden" value="1" id="currency_rate" name="currency_rate">

        <div class="col  ">

            <div class="d-flex flex-column flex-row flex-lg-row h-100">
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                    <div class="card   flex-row-fluid mb-2  ">
                        <div class="card-header">
                            <h3 class="card-title"> {{trans('seller.orders.create_new_order')}}</h3>
                            <div class="card-toolbar">
                                <a target="_blank"
                                   href="{{auth('admin')->check() ?  route('backend.orders.create') : route('seller.orders.create')}}"
                                   class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary"><i
                                        class="las la-redo text-primary"></i>{{trans('seller.orders.open_new_order')}}
                                </a>

                            </div>
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
                                                        @if($user->uuid == old('user')) selected @endif >{{$user->uuid}}</option>
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
                                            <option value="" selected
                                                    disabled>{{trans('seller.orders.please_select_option')}}</option>
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

                                            <option sevalue="order">{{trans('seller.orders.order')}}</option>
                                            <option value="proforma">{{trans('seller.orders.proforma')}}</option>
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
                                            <option @if(get_setting('shipping_default')== "dhl") selected
                                                    @endif value="dhl">{{trans('backend.order.dhl')}}</option>
                                            <option @if(get_setting('shipping_default')== "ups") selected
                                                    @endif value="ups">{{trans('backend.setting.ups')}}
                                            </option>
                                            <option @if(get_setting('shipping_default')== "aramex") selected
                                                    @endif value="aramex">{{trans('backend.order.aramex')}}
                                            </option>
                                            <option @if(get_setting('shipping_default')== "fedex") selected
                                                    @endif value="fedex">{{trans('backend.order.fedex')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="shipment_value"
                                               class="required">{{trans('backend.order.shipment_value')}}</label>
                                        <input type="text" class="form-control form-control-sm" value="" required
                                               name="shipment_value" id="shipment_value">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="required"
                                               for="shipment_description">{{trans('backend.order.shipment_description')}}</label>
                                        <input type="text" class="form-control form-control-sm" value="" required
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
                                            <option selected
                                                    value="{{\App\Models\Order::$on_hold}}">{{trans('backend.order.on_hold')}}</option>
                                            <option
                                                value="{{\App\Models\Order::$pending_payment}}">{{trans('backend.order.pending_payment')}}</option>
                                            <option
                                                value="{{\App\Models\Order::$processing}}">{{trans('backend.order.processing')}}</option>
                                            <option
                                                value="{{\App\Models\Order::$completed}}">{{trans('backend.order.completed')}}</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="note">{{trans('seller.orders.note')}}</label>
                                        <textarea rows="3" class="form-control form-control-sm  " name="note"
                                                  id="notert" style="resize: none"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12  col-md-3">
                                    <div class="form-group">
                                        <label for="currency">{{trans('backend.menu.currencies')}}</label>
                                        <select name="currency" id="currency" class="form-control-sm form-control"
                                                data-control="select2">
                                            @foreach($currencies as $currency)
                                                <option id="courrency_{{$currency->id}}"
                                                        @if($currency->symbol =='$') selected
                                                        @endif value="{{$currency->id}}"
                                                        data-rate="{{$currency->value}}"
                                                        data-symbol="{{$currency->symbol}}"
                                                        data-name="{{$currency->name}}"
                                                        data-code="{{$currency->code}}">{{$currency->symbol.' '.$currency->code.' '.$currency->name }}    @if($currency->symbol !='$')
                                                        ( 1 $
                                                        = {{number_format($currency->value ,2). ' '. $currency->symbol}}
                                                        )
                                                    @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 payment_div col-md-3">
                                    <div class="form-group">
                                        <label for="payment_method"
                                               class="required">{{trans('seller.orders.payment_method')}}</label>
                                        <select name="payment_method" id="payment_method" data-control="select2"
                                                class="form-control form-control-sm">
                                            <option value="" selected
                                                    disabled>{{trans('backend.product.please_select_option')}}</option>
                                            <option value="stripe_link">{{trans('seller.orders.stripe_link')}} </option>
                                            <option value="transfer">{{trans('seller.orders.transfer')}} </option>
                                        </select>
                                        <b class="text-danger" id="user_error">@error('user') <i
                                                class="fa fa-exclamation-triangle"></i> {{$message}} @enderror</b>
                                    </div>
                                </div>
                                <div class="col-12 payment_div col-md-6" id="payment_files_div">
                                    <div class="form-group">
                                        <label for="payment_files">{{trans('seller.orders.payment_files')}}</label>
                                        <input class="form-control form-control-sm" type="file" id="payment_files"
                                               name="payment_files[]" multiple>
                                    </div>
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
                                                    data-slug="{{$product['slug']}}"
                                                    data-image="{{$product['image']}}"
                                                    data-blockc="{{json_encode(empty($product['blocked_countries']) ? [1] : $product['blocked_countries'])}}"
                                                    data-blockcn="{{$product['blocked_countries_names']}}"
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

                        <div class="card mb-3" id="coupon_card">
                            <div class="card-header">
                                <b class="card-title"> {{trans('seller.orders.coupon')}}</b>
                            </div>
                            <div class="card-body ">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="coupon_code"
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
                                <div class="w-100 " style="display: none" id="coupon_note">
                                    <div class="row">
                                        <div class="col-12 alert alert-danger font-bold" id="coupon_note_detailes">

                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.sub_total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                        <span class="fw-bolder fs-6 text-gray-800">
                                            <span id="result_sub_total" class="number_price">0</span>
                                            <span class="currency">$</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.shipping_total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                <span class="fw-bolder fs-6 text-gray-800"
                                >
                                    <span id="result_shipping_total">0</span>
                                       <span class="currency"> $</span>

                                    </div>
                                </div>
                                <div class="row mb-7" style="display: none" id="coupon_row">
                                    <label class="col-7  text-success">
                                        <b class="text-danger" style="cursor: pointer" id="remove_coupon"><i
                                                class="las la-times-circle"></i></b>
                                        {{trans('seller.orders.coupon')}}
                                    </label>
                                    <div class="col-5 text-success" style="text-align: right">
                                        <span class="fw-bolder fs-6 "> <span id="result_discount_value"
                                                                             class="number_price "
                                                                             data-price="0"> 0 </span> <span
                                                class="currency"> $ </span></span>
                                    </div>
                                </div>
                                <div class="row mb-7">
                                    <label class="col-7  text-muted">{{trans('seller.orders.total')}}</label>
                                    <div class="col-5" style="text-align: right">
                                        <span class="fw-bolder fs-6 text-gray-800"
                                        ><span id="result_total">0</span> <span class="currency"> $</span></span>
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
                        <input type="text" readonly name="user_id" class="form-control bg-secondary form-control-sm"
                               value=""
                               id="address_user">
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
                    {{--                    country--}}
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
                    {{--                   state--}}
                    <div class="form-group">
                        <label for="address_state">{{trans('backend.user.state')}}</label>
                        <input type="text" name="address_state" id="address_state"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_state"></b>
                    </div>
                    {{--                    city--}}
                    <div class="form-group">
                        <label for="address_city" class="form-label required">{{trans('seller.orders.city')}}</label>
                        <input type="text" name="address_city" id="address_city"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_city"></b>
                    </div>
                    {{--                    street--}}
                    <div class="form-group">
                        <label for="address_street">{{trans('backend.user.street')}}</label>
                        <input type="text" name="address_street" id="address_street"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_street"></b>
                    </div>
                    {{--                    full  address--}}
                    <div class="form-group">
                        <label for="address_address">{{trans('seller.orders.address')}}</label>
                        <textarea style="resize: none" name="address_address" id="address_address"
                                  class="form-control form-control-sm"
                                  rows="3"></textarea>
                        <b class="text-danger" id="error_address_address"></b>
                    </div>
                    {{--                    post code--}}
                    <div class="form-group">
                        <label for="address_postal_code">{{trans('seller.orders.postal_code')}}</label>
                        <input type="text" name="address_postal_code" id="address_postal_code"
                               class="form-control form-control-sm">
                        <b class="text-danger" id="error_address_postal_code"></b>
                    </div>
                    {{--                    phone--}}
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
        @include('orders.admin_create')
    @else
        @include('orders.seller_create')
    @endif
@endsection
