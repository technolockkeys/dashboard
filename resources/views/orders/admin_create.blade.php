<script src="{{asset("backend/plugins/custom/intltell/js/intlTelInput.js")}}"></script>

<script>
    var is_edit =false;

    var route_order = {
        "get_users_by_seller": "{{route('backend.orders.get.user.by.seller')}}",
        "get_address": "{{route('backend.orders.get.address.by.user')}}",
        "insert_address": "{{route('backend.addresses.store')}}",
        "get_price" : "{{route('backend.orders.get.price')}}",
        "get_shipping_cost" : "{{route('backend.orders.get.shipping.cost')}}",
        'apply_coupon':"{{route('backend.orders.apply.coupon')}}"
    }

    var create_new_order_route = route_order;
    var tanslate_order = {
        'please_select_option': "{{trans('seller.orders.please_select_option')}}",
        'create_new_address': "{{trans('seller.orders.create_new_address')}}",
        'save': "{{trans('backend.global.save')}}",
    }
    var token = "{{csrf_token()}}"
    var order_token = "{{csrf_token()}}"
    var route_iti = "{{asset('backend/plugins/custom/intltell/js/utils.js')}}";
    var order_products = [];
    var tarnslate_order = {
        'create_new_address': "{{trans('seller.orders.create_new_address')}}",
        'please_select_option': "{{trans('seller.orders.please_select_option')}}",
        'this_field_is_required': "{{trans('seller.orders.this_field_is_required')}}",
        'save': "{{trans('backend.global.save')}}",
        'order': "{{trans('seller.orders.order')}}",
        'waiting_order': "{{trans('seller.orders.waiting_order')}}",
        'successfully_order': "{{trans('seller.orders.successfully_order')}}",
        'product': "{{trans('seller.orders.product')}}",
        'quantity': "{{trans('seller.orders.quantity')}}",
        'attributes': "{{trans('seller.orders.attributes')}}",
        'cost': "{{trans('seller.orders.cost')}}",
        'total': "{{trans('seller.orders.total')}}",
        'shipping_price': "{{trans('seller.orders.shipping_price')}}",
        'total_price': "{{trans('seller.orders.total_price')}}",
        'min_total_price': "{{trans('seller.orders.min_total_price')}}",
        'unit_price': "{{trans('seller.orders.unit_price')}}",
        'min_unit_price': "{{trans('seller.orders.min_unit_price')}}",
        'the_quantity_available_is': "{{trans('seller.orders.the_quantity_available_is')}}",
    };
    var product_image_default = "{{media_file(get_setting('default_images'))}}";

    var coupon_code = null ;
    var seller_free_shipping_cost = "{{get_setting('free_shipping_cost')}}";
    var is_edit = false ;
    var is_edit_page = false ;
    var ORDER_UUID ='';
</script>
<script src="{{asset('backend/js/order.js')}}"></script>
