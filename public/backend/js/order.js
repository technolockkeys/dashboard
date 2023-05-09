var iti;
var order_products = [];
var coupon = null;
var coupon_code = null;
var coupon_products = null;
var coupon_free_shipping = 0;
$(document).ready(function () {
    $("#address_country").select2({
        dropdownParent: $("#create_new_address")
    });
    // $("#address_city").select2({
    //     dropdownParent: $("#create_new_address")
    // });
    var create_phone = document.querySelector("#address_phone");
    iti = window.intlTelInput(create_phone, {
        initialCountry: "tr",
        hiddenInput: 'phone',
        utilsScript: route_iti,
    });

    $("#payment_files_div").hide();
    select_type()

});

//region get users
$(document).on('change', '#seller', function () {
    var seller = $(this).val();
    var hide = false;
    var seller_option = $("#seller_option_" + seller);
    rate_percent = seller_option.data('ratepercent');
    if (seller != -1) {

        $(".has_seller").show()
    } else {
        $(".has_seller").hide()
    }
    $.ajax({
        url: create_new_order_route.get_users_by_seller,
        method: "post",
        data: {
            _token: token,
            seller: seller
        }, success: function (response) {
            users = response.data.users;
            $("#user").html("");
            $("#address").html("");
            $("#user").append("<option value='-1'>" + tanslate_order.please_select_option + "</option>");
            for (var i = 0; i < users.length; i++) {
                $("#user").append("<option value='" + users[i].uuid + "'>" + users[i].uuid + "</option>");
            }


        }

    })
})
//endregion


//region address

//change address
$(document).on('change', '#address', function () {
    var address_id = $("#address").val();
    if (address_id == -1) {
        if ($("#user").val() == null || $("#user").val() == "") {
            error_message('please select user')
        } else {
            $("#address_user").val($("#user").val())
            $("#create_new_address").modal('show');
        }
        $("#address").val('-2');
        $("#address").change();
    } else {
        $(".quantity_product").change();
    }
    let allow_address = true;
    var selected_country = $("#address").select2().find(":selected").data("country");

    order_products.forEach((item) => {
        let blocked_countries = $("#products").find("option[data-sku=" + item + "]").data('blockc');
        console.log(blocked_countries.includes(String(selected_country)))

        if (blocked_countries.includes(String(selected_country))){
            allow_address=false;
        }
    });

    if (!allow_address){
        $("#create_order_button").attr('disabled','disabled');
        error_message("This product is not available at this address")
    }else{
        $("#create_order_button").removeAttr('disabled');
    }

});

//for change cities is broken/old requirements
$(document).on('change', "#address_country", function () {
    var country = $("#address_country").val();
    /*
    $.ajax({
        url: create_new_order_route.get_city,
        method: "post",
        data: {
            "_token": order_token,
            "country": country
        }, success: function (response) {
            $("#address_city").html("");
            $("#address_city").append("<option  selected disabled>" + tarnslate_order.please_select_option + "</option>")

            for (var i = 0; i < response.data.cities.length; i++) {
                $("#address_city").append("<option value='" + response.data.cities[i].id + "'>" + response.data.cities[i].text + "</option>");
            }
        }
    })*/
})

//save address
$(document).on('click', "#save_address", function () {
    var user = $("#user").val();
    var country = $("#address_country").val();
    var city = $("#address_city").val();
    var address = $("#address_address").val();
    var postal_code = $("#address_postal_code").val();
    $("#address").val('-2');
    $("#address").change();
    var number = iti.getNumber();


    if (user == "" || country == "" || city == "" || address == "" || number == "" || postal_code == "") {
        if (country == "") {
            $("#error_address_country").html('<i class="la las-times"></i> ' + tarnslate_order.this_field_is_required);
        } else {
            $("#error_address_country").html("");
        }
        if (city == "") {
            $("#error_address_city").html('<i class="la las-times"></i> ' + tarnslate_order.this_field_is_required);
        } else {
            $("#error_address_city").html("");
        }
        if (address == "") {
            $("#error_address_address").html('<i class="la las-times"></i> ' + tarnslate_order.this_field_is_required);
        } else {
            $("#error_address_address").html("");
        }
        if (number == "") {
            $("#error_address_number").html('<i class="la las-times"></i> ' + tarnslate_order.this_field_is_required);
        } else {
            $("#error_address_number").html("");
        }
        if (postal_code == "") {
            $("#error_address_postal_code").html('<i class="la las-times"></i> ' + tarnslate_order.this_field_is_required);
        } else {
            $("#error_address_postal_code").html("");
        }
    } else {
        $("#save_address").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>').attr('disabled', 'disabled');
        $.ajax({
            url: create_new_order_route.insert_address,
            method: "post",
            data: {
                "_token": order_token,
                user_id: user,
                country: country,
                postal_code: postal_code,
                city: city,
                address: address,
                number: number,
            }, success: function (response) {
                success_message(response.data.message);
                var user_uuid = $("#user").val();
                $("#save_address").html(tarnslate_order.save).removeAttr('disabled');

                $("#error_address_country").html("");
                $("#error_address_city").html("");
                $("#error_address_address").html("");
                $("#error_address_number").html("");
                $("#error_address_postal_code").html("");


                $("#address_country").val("");
                $("#address_state").val("");
                $("#address_street").val("");
                $("#address_city").val("");
                $("#address_address").val("");
                $("#address_phone").val("");
                $("#address_postal_code").val("");


                $.ajax({
                    url: create_new_order_route.get_address,
                    method: "post",
                    data: {
                        "_token": order_token,
                        uuid: user_uuid,
                    }, success: function (response) {
                        $("#address").html("");
                        $("#address").append("<option  selected disabled>" + tarnslate_order.please_select_option + "</option>")
                        for (var i = 0; i < response.data.addresses.length; i++) {
                            var address = response.data.addresses[i];
                            $("#address").append("<option data-country='" + address.country + "'  data-city='" + address.country + "'  data-address='" + address.address + "'  data-postal_code='" + address.postal_code + "'  value='" + address.id + "'>" + address.text + "</option>")
                        }
                        $("#address").append("<option value='-1'>" + tarnslate_order.create_new_address + "</option>")
                        $("#create_new_address").modal('hide');

                    }
                })
            }, error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message(response.message);
                $("#save_address").html('<i class="fa fa-save"></i>' + tarnslate_order.save).removeAttr('disabled');

            },
        })
    }
});

//endregion

//region change user , get data from serve all address for this user
$(document).on('change', '#user', function () {
    var user_uuid = $("#user").val();
    $("#remove_coupon").click();
    $.ajax({
        url: create_new_order_route.get_address,
        method: "post",
        data: {
            "_token": order_token,
            uuid: user_uuid,
        }, success: function (response) {
            $("#address").html("");
            $("#address").append("<option  selected disabled>" + tarnslate_order.please_select_option + "</option>")
            for (var i = 0; i < response.data.addresses.length; i++) {
                var address = response.data.addresses[i];
                $("#address").append("<option data-country='" + address.country_id + "'  data-city='" + address.country + "'  data-address='" + address.address + "'  data-postal_code='" + address.postal_code + "'  value='" + address.id + "'>" + address.text + "</option>")
            }
            $("#address").append("<option value='-1'>" + tarnslate_order.create_new_address + "</option>")
        }
    })
});
//endregion

//region products

//change product
$(document).on('change', '.products', function () {
    var value = $(this).val();
    var uuid = $(this).data('uuid');
    var quantity = $("option[value='" + value + "']").data('quantity');
    $("#valid_quantity_" + uuid).html("<span class='text-primary'><i class='fa fa-cubes'></i> " + tarnslate_order.the_quantity_available_is + quantity + "</span>")
    calculator_cart()
});

//add product
$(document).on('click', '#add_product', function () {

    var id = $("#products").val();
    var currecny = $("#currecny").val();
    var product_title = $("option[value=" + id + "]").data('title');
    var unit_price = $("option[value=" + id + "]").data('price');
    var image = $("option[value=" + id + "]").data('image');
    var sku = $("option[value=" + id + "]").data('sku');
    var quantity = $("option[value=" + id + "]").data('quantity');

    var slug = $("option[value=" + id + "]").data('slug');
    var blocked_countries = $("option[value=" + id + "]").data('blockc');
    var address = $("#address").val();
    var selected_country = $("#address").select2().find(":selected").data("country");
    if (blocked_countries.includes(selected_country + "")) {
        error_message("This product is not available at this address")
    } else {
        add_product_to_cart(id, currecny, product_title, unit_price, image, sku, quantity, slug)
    }
    get_price(sku, 1, address)
});

//remove product from cart
$(document).on('click', '.remove_product_from_cart', function () {
    var id = $(this).data('id');
    $("#row_product_" + id).remove();
    const index = order_products.indexOf(id + '');
    if (index > -1) { // only splice array when item is found
        order_products.splice(index, 1); // 2nd parameter means remove one item only
        calculator_cart();
    }
});

//change quantity
$(document).on('change keyup', '.quantity_product', function () {
    var sku = $(this).data('sku');
    var quantity = $(this).val();
    var address = $("#address").val();
    var max_quantity = $(this).data('max');
    if (max_quantity <= quantity) {
        $(this).val(max_quantity);
        quantity = max_quantity;
    }
    get_price(sku, quantity, address)

});

//get price from serve
function get_price(sku, quantity, address, recalcultor = true) {

    var currecny = $("#currecny").val();

    var symbol = $("#currency").val();
    var currency_rate = $("#currency").val();
    var currency = $("#currency").val();
    var currency_option = $("#courrency_" + currency);
    symbol = currency_option.data('symbol');
    currency_rate = parseFloat(currency_option.data('rate')).toFixed(2);

    $.ajax({
        url: create_new_order_route.get_price,
        method: "post",
        data: {
            "_token": order_token,
            'sku': sku,
            'order_uuid': ORDER_UUID,
            'quantity': quantity,
            'address': address,
            'seller': $("#seller").val()
        }
        , success: function (response) {
            $("option[value=" + sku + "]").data('quantity', response.data.quantity)
            //region product price
            if (is_edit == false) {
                {
                    $("#product_price_" + sku).val(response.data.unit_price);
                }
                $("#product_price_" + sku).attr('data-min', response.data.min_price_for_unit);
                // $("#product_price_" + sku).attr('data-shipping', response.data.shipping_price);
                //endregion

                //region shipping value
                $("#product_shipping_value_price_" + sku).data('sku', sku);
                $("#product_shipping_value_price_" + sku).data('product_price_without_shipping', response.data.total.toFixed(2));

                if (response.data.total_min_price != null) {
                    $("#product_shipping_value_price_" + sku).data('product_min_total_price', response.data.total_min_price.toFixed(2));
                    $("#product_shipping_value_price_" + sku).data('product_total_price', response.data.total_min_price.toFixed(2));
                }
                //endregion
                $("#product_price_label_" + sku).text(parseFloat(response.data.total).toFixed(2));
                $("#product_price_label_" + sku).data('price', parseFloat(response.data.total).toFixed(2));
                $("#product_price_label_" + sku).addClass('number_price');

            }

            $("#product_unit_price_" + sku).text(parseFloat(response.data.unit_price).toFixed(2) + " $");
            //total without shipping
            $("#product_price_without_shipping_" + sku).text(parseFloat(parseFloat(response.data.total.toFixed(2)) * currency_rate).toFixed(2));
            $("#product_price_without_shipping_" + sku).data('price', response.data.total.toFixed(2));
            $("#product_price_without_shipping_" + sku).addClass('number_price');

            //total price
            $("#product_total_price_" + sku).text(parseFloat(parseFloat(parseFloat(parseFloat(response.data.total) + parseFloat(response.data.shipping_price)) * currency_rate).toFixed(2)).toFixed(2));
            $("#product_total_price_" + sku).data('price', parseFloat(parseFloat(response.data.total) + parseFloat(response.data.shipping_price)).toFixed(2));
            $("#product_total_price_" + sku).addClass('number_price')
            if (response.data.total_min_price != null) {
                //total min price
                $("#product_min_total_price_" + sku).text(parseFloat(parseFloat(response.data.total_min_price.toFixed(2)) * currency_rate).toFixed(2));
                $("#product_min_total_price_" + sku).data('price', response.data.total_min_price.toFixed(2));
                $("#product_min_total_price_" + sku).addClass('number_price');
                if (is_edit_page == false) {
                    $("#product_price_" + sku).data('min', parseFloat(response.data.total_min_price.toFixed(2)))

                }
                //don't use currency at unit
                $("#product_min_unit_price_" + sku).text(parseFloat(response.data.min_price_for_unit).toFixed(2) + " $");
                $(".has_seller").show()
            } else {
                $("#product_price_" + sku).data('min', 0)
                $(".has_seller").hide()
            }
            if (is_edit == false && false) {
                $("#product_shipping_value_price_" + sku).val(response.data.shipping_price);

            }
            if (recalcultor && is_edit == false) {
                calculator_cart();
            }
            $("#product_price_" + sku).data('quantity', response.data.quantity);
            $("#text_quantity_" + sku).text(response.data.quantity);

        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
        },
    })

}

//you change price from panel
$(document).on('change', '.product_price', function () {
    changeQuantity(this)
    calculator_cart()
});
//endregion

//region calculate cart
$(document).on('change', '#shipping_method', function () {
    // success_message($("#shipping_method").val())
    calculator_cart();
});

function changeQuantity(item) {
    var currecny = $("#currecny").val();
    var id = $(item).data('id');
    var min = $(item).data('min');
    var price = $(item).val();
    var quantity = $("#quantity_" + id).val();

    if (parseFloat(price) < parseFloat(min)) {
        $(item).val(min * 1);
    } else {
        calculator_cart();
    }
}

function calculator_cart() {
    var currecny = $("#currecny").val();
    var symbol = $("#currency").val();
    var currency_rate = $("#currency").val();
    var currency = $("#currency").val();
    var currency_option = $("#courrency_" + currency);

    symbol = currency_option.data('symbol');
    currency_rate = parseFloat(currency_option.data('rate')).toFixed(2);

    $("#currency_symbol").val(symbol);
    $("#currency_rate").val(currency_rate);

    var total_shipping = 0;
    var total_price_with_out_shipping = parseFloat(0);
    var total_price = 0;
    var over_products = false;
    var discount_value = 0;
    $(".product_price").each(function () {
        var price = $(this).val();
        var id = $(this).data('id');
        var sku = $(this).data('sku');
        var quantity_stock = $(this).data('quantity');
        // var shipping_price = $(this).data('shipping');
        var quantity = $("#quantity_" + id).val();
        if (quantity > quantity_stock) {
            over_products = true;
        }

        $("#product_price_label_" + id).text(parseFloat((parseFloat(price) * parseFloat(quantity) * currency_rate)).toFixed(2)).data('price', parseFloat(parseFloat(price) * parseFloat(quantity)));
        total_price = parseFloat(parseFloat(total_price) + parseFloat((parseFloat(price) * parseFloat(quantity)))).toFixed(2);
        total_price_with_out_shipping = parseFloat(parseFloat(total_price_with_out_shipping) + parseFloat(price * quantity)).toFixed(2);
        if (coupon != null && coupon.type == 'Product' && coupon_products.indexOf(id + '') > -1) {
            if (coupon.discount_type == 'Amount') {
                discount_value += coupon.discount * quantity;
            } else {
                discount_value += parseFloat((parseFloat(price) * parseFloat(quantity))) * (coupon.discount / 100);
            }
        }

    });
    total_shipping = 0;

    if (coupon != null) {
        $("#coupon_card").hide();
        $("#coupon_row").css('display', 'flex');

        if (coupon.type == 'Order') {
            if (coupon.discount_type == 'Amount') {
                discount_value = coupon.discount;
            } else {
                discount_value = total_price_with_out_shipping * (coupon.discount / 100);
            }
        }
        // if (over_products) {
        //     discount_value = 0;
        // }
        $("#result_discount_value").text(parseFloat(discount_value.toFixed(2)) * currency_rate).addClass('number_price').data('price', discount_value.toFixed(2));
    } else {
        $("#coupon_row").css('display', 'none');
    }


    $("#result_sub_total").text(parseFloat(parseFloat(total_price_with_out_shipping).toFixed(2) * currency_rate).toFixed(2)).addClass('number_price').data('price', total_price_with_out_shipping);
    if (parseFloat(seller_free_shipping_cost) > parseFloat(total_price_with_out_shipping) && coupon_free_shipping == 0) {
        $("#result_shipping_total").text(0);
        total_shipping = 0;
        var data_shipping = [];
        var shipping_method = $("#shipping_method").val();
        var address = $("#address").val();
        $(".product_price").each(function () {
            var id = $(this).data('id');
            var quantity = $("#quantity_" + id).val();
            data_shipping.push({'sku': id, 'quantity': quantity});
        });
        $("#result_shipping_total").html('<i class="fa fa-spinner fa-pulse text-success  fa-fw"></i>');
        $("#result_total").html('<i class="fa fa-spinner fa-pulse text-success  fa-fw"></i>');
        $.ajax({
            url: create_new_order_route.get_shipping_cost,
            method: "post",
            data: {
                "_token": order_token,
                'products': data_shipping,
                'shipping_method': shipping_method,
                'address': address,
            },
            success: function (response) {
                $("#result_shipping_total").text(parseFloat(parseFloat(response.data.shipping.toFixed(2)) * parseFloat(currency_rate)).toFixed(2)).addClass('number_price').data('price', parseFloat(response.data.shipping.toFixed(2)));
                $("#result_total").text(parseFloat(parseFloat(parseFloat(parseFloat(total_price_with_out_shipping) + parseFloat(response.data.shipping.toFixed(2)) - parseFloat(discount_value)) * currency_rate).toFixed(2)).toFixed(2)).addClass('number_price').data('price', parseFloat(parseFloat(total_price_with_out_shipping) + parseFloat(response.data.shipping.toFixed(2)) - parseFloat(discount_value)).toFixed(2));
            }, error: function () {

            }
        })
    } else {
        $("#result_shipping_total").text(parseFloat(0).toFixed(2)).data('price', 0).addClass('number_price');
        $("#result_total").text(parseFloat(parseFloat(parseFloat(parseFloat(total_price_with_out_shipping) + parseFloat(0) - parseFloat(discount_value)).toFixed(2)) * currency_rate).toFixed()).addClass('number_price').data('price', parseFloat(parseFloat(total_price_with_out_shipping) + parseFloat(total_shipping) - parseFloat(discount_value)));
    }
    $(".minimum_shopping").each(function () {
        let price = $(this).data('amount');
        $(this).text(parseFloat(parseFloat(currency_rate) * parseFloat(price)).toFixed(2))
    });
    $(".currency").text(symbol);
    var all_numbers = $(".number_price");
    $.each(all_numbers, function (index, item) {
        var base_price = $(item).data('price');
        var number = parseFloat(parseFloat(base_price) * currency_rate).toFixed(2);
        $(item).text(number + '');
    });


}

$(document).on('submit', "#form_order", function (event) {
    event.preventDefault();
    var route = $("#form_order").attr('action');
    var order_uuid = $("#order_uuid").val();
    $("#create_order_button").html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
    $("#create_order_button").attr("disabled", 'disabled');
    var formData = new FormData(this);
    $.ajax({
        url: route,
        method: "post",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#create_order_button").removeAttr("disabled");
            $("#create_order_button").html(tarnslate_order.save);
            // if (order_uuid != "") {
            //     $("#create_order_button").html(tarnslate_order.save);
            //     $("#create_order_button").removeAttr("disabled", 'disabled');
            // } else {


            var html = '';
            html += '<div class="row"><div class="col text-center"><h1 class="text-success"><i class="las la-check-circle" style="font-size: 100px"></i></h1><h4>' + tarnslate_order.successfully_order + '</h4></div></div>';

            if (typeof response.data.order !== 'undefined' && typeof response.data.order !== undefined && typeof response.data.order.stripe_url !== undefined && response.data.order.stripe_url !== '' && typeof response.data.order.stripe_url !== 'undefined' && response.data.order.payment_method == 'stripe_link') {

                html += '<div class="row"><div class="col"> <div class="form-group mb-7">' +
                    '<label for="floatingInput_order">' + tarnslate_order.order + '(' + response.data.order.uuid + ')</label>' +
                    '<div class="input-group">' +
                    '<input type="text" disabled class="form-control form-control-solid" id="floatingInput_order" value="' + response.data.order.stripe_url + '"/>' +
                    '<button class="btn btn-light-primary"   id="floatingInput_clipboard_order" type="button"><i class="las la-copy"></i> </button>' +
                    '</div></div></div>' +
                    '</div>'

            }
            if (typeof response.data.waiting_order !== 'undefined' && typeof response.data.waiting_order !== undefined && response.data.waiting_order.payment_method == 'stripe_link') {
                html += '<div class="row"><div class="col"><div class="form-group mb-7">' +
                    '<label for="floatingInput_waiting">' + tarnslate_order.waiting_order + '(' + response.data.waiting_order.uuid + ')</label>' +
                    '<div class="input-group">' +
                    '<input type="text"  disabled class="form-control form-control-solid" id="floatingInput_waiting" value="' + response.data.waiting_order.stripe_url + '"/>' +
                    '<button class="btn btn-light-primary"   id="floatingInput_clipboard_waiting" type="button"><i class="las la-copy"></i> </button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
            }

            $("#backend_global_download").attr('href', response.data.order.invoice_url)
            $("#successfully_order_content").html(html)
            $("#successfully_order").modal('show');
            $("#create_order_button").html(tarnslate_order.save);

            coupon = null;
            coupon_code = null;
            if (is_edit_page == false) {
                $("#remove_coupon").click()
                // }
                var currecny = $("#currecny").val();
                $("#result_shipping_total").text(0 + " " + currecny);
                $("#result_total").text(0 + " " + currecny);

                $(".remove_product_from_cart").each(function () {
                    $(this).click()
                })
                $("#users").val(-1).trigger('change');
                $("#shipment_description").val("");
                $("#shipment_value").val("");
                $("#note").val("");
                $("#status").val("on_hold").trigger('change');
                $("#payment_method").val("").trigger('change');

            }


        }
        , error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $("#create_order_button").html(tarnslate_order.save);
            $("#create_order_button").removeAttr("disabled");
        },
    })
});

$(document).on('change', "#payment_method", function () {

    if ($("#payment_method").val() == "transfer" && $("#type").val() == 'order') {
        $("#payment_files_div").show();
    } else {
        $("#payment_files_div").hide();
    }
});

//endregion

//region copy strip link
$(document).on('click', '#floatingInput_clipboard_order', function () {
    var text = $("#floatingInput_order").val();
    navigator.clipboard.writeText(text);
    success_message('Copied the text')
});

$(document).on('click', '#floatingInput_clipboard_waiting', function () {
    var text = $("#floatingInput_waiting").val();
    navigator.clipboard.writeText(text);
    success_message('Copied the text')
});
//endregion

//region apply coupon

$(document).on('click', '#apply_coupon', function (event) {
    event.preventDefault();
    coupon_code = $("#coupon_code").val();
    var user = $("#user").val();

    if (isBlank(coupon_code)) {
        error_message("please enter coupon code ... ")
    } else {
        $.ajax({
            url: create_new_order_route.apply_coupon,
            method: "post",
            data: {
                "_token": order_token,
                "coupon_code": coupon_code,
                'user_id': user,
            },
            success: function (response) {
                $("#coupon_card").hide();
                var currecny = $("#currecny").val();
                var discount_Type_symbol = "";
                if (response.data.coupon.discount_type == 'amount') {
                    discount_Type_symbol = currecny;
                } else {
                    discount_Type_symbol = "%";
                }
                $("#coupon_row").css('display', 'flex');
                success_message("applied coupon")
                coupon = response.data.coupon;
                coupon_products = response.data.products;
                coupon_free_shipping = response.data.coupon.free_shipping;
                if (response.data.note != "") {
                    $("#coupon_note").show()
                    $("#coupon_note_detailes").html(response.data.note)
                } else {
                    $("#coupon_note").hide()
                    $("#coupon_note_detailes").html("")

                }
                calculator_cart();
            }
            , error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message(response.message);
                calculator_cart();
            },
        })
    }
});

$(document).on('click', '#remove_coupon', () => {
    coupon_code = null;
    coupon = null;
    $("#coupon_code").val("");
    coupon_free_shipping = false;
    $("#coupon_note").hide()
    $("#coupon_note_detailes").html("")
    $("#coupon_row").css('display', 'none');
    $("#coupon_card").show();
    calculator_cart();
});

//endregion

//region update order
function update_order(order, old_order_products) {
    for (var i = 0; i < old_order_products.length; i++) {
        order_products.push(old_order_products[i].sku)
        add_old_product_to_cart(old_order_products[i])

    }
    calculator_cart()
    setTimeout(() => {
        if (order.payment_method == "transfer") {

            $("#payment_files_div").show();
        }

        is_edit = false;
    }, 3000)


}

// for edit order
function add_old_product_to_cart(product_data) {

    var option_data = $("option[value='" + product_data.sku + "']");

    var product_title = option_data.data('title');
    var quantity = parseInt(option_data.data('quantity')) + parseInt(product_data.quantity);
    var unit_price = option_data.data('price');
    var sku = option_data.data('sku');
    var id = option_data.data('sku');
    var image = option_data.data('image');
    var currecny = $("#currecny").val();


    var html = '<tr   class="border-bottom border-bottom-dashed" data-kt-element="item" id="row_product_' + id + '" >';
    var blocked_countries_names = $("option[value=" + id + "]").data('blockcn');

    //region product
    var product = '<td class="pe-7">' +
        '<div class="d-flex align-items-center">' +
        '<div class="symbol symbol-45px me-5">' +
        '<img src="' + image + '" onerror="this.src=product_image_default" alt="">' +
        '</div>' +
        '<div class="d-flex justify-content-start flex-column">' +
        '<a href="' + app_frontend + '/product/' + product_data.slug + '" class="text-dark fw-bolder text-hover-primary fs-6">' + sku + '</a>' +
        '<span class="text-muted fw-bold text-muted d-block fs-7">' + product_title + '</span>' +
        '<span class="badge badge-light-danger   fw-bold   d-block fs-7"> ' + blocked_countries_names + '     </span>' +

        '</div>' +
        '</div>';
    product += ' <input type="hidden" class="form-control form-control-sm form-control-solid mb-2"  name="orders_item[]" value="' + id + '">';
    product += ' <input type="hidden" class="form-control form-control-sm form-control-solid mb-2"  name="orders_sku[]" value="' + product_title + '" id="product_' + id + '"  disabled >';
    // product += '<select multiple class="form-control form-control-sm form-control-solid" id="product_attributes_' + id + '" name="product_attributes_' + id + '[] "></select>';
    product += "</td>";
    //endregion

    //region quantity
    var quantity = '<td>' +
        '<input class="form-control quantity_product form-control-sm form-control-solid" type="number" min="1" data-max="' + quantity + '" data-sku="' + id + '"  name="quantity_' + id + '"   id="quantity_' + id + '" placeholder="1" value="' + product_data.quantity + '" data-kt-element="quantity">' +
        '<b class="text-dark" style="font-size: xx-small"> ' + tarnslate_order.the_quantity_available_is + '<span id="text_quantity_' + id + '">' + quantity + '</span>' + '</b>' +
        '</td>';

    //endregion

    //region price
    var price = "<td>" +
        '<div class="input-group  input-group-sm input-group-solid mb-5">' +
        '    <span class="input-group-text bg-danger text-light">$</span>' +
        '<input type="number" class="form-control product_price form-control-sm form-control-solid text-end" id="product_price_' + id + '" data-id="' + id + '" data-shipping="' + product_data.shipping_cost + '"  data-quantity="' + product_data.quantity + '"  data-min="0" step="any" name="product_price_' + id + '" placeholder="0.00" value="' + parseFloat(product_data.price) + '"  >' +
        '</div><table class="w-100">' +
        '<tr style="padding:0;margin:0"><td  style="padding:0;margin:0"><h6  class="text-gray-900 p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.unit_price + '</h6> </td> <td  style="padding:0;margin:0; font-size: xx-small;text-align: right" class="p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_unit_price_' + id + '">' + '--' + currecny + '</span> </td> </tr>' +
        '<tr class="has_seller" style="padding:0;margin:0"><td  style="padding:0;margin:0"><h6  class="text-danger p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.min_unit_price + ' </h6> </td> <td  style="padding:0;margin:0; font-size: xx-small;text-align: right" class="p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_min_unit_price_' + id + '">' + '--' + currecny + '</span> </td> </tr>' +
        '</table>' +
        "</td>";

    //endregion
    //region total

    var total = '<td class="pt-8 text-end text-nowrap"><span class="number_price" data-price="' + parseFloat(parseFloat(product_data.price) + parseFloat(product_data.shipping_cost)) + '" id="product_price_label_' + id + '" data-kt-element="total">' + parseFloat(parseFloat(product_data.price) + parseFloat(product_data.shipping_cost)) + '</span>' + '<span class="currency">' + currecny + '</span>' +
        '<br><table class="w-100 mt-5">' +
        '<input type="hidden" class="product_shipping_value_price" id="product_shipping_value_price_' + id + '"> </td> </tr>' +
        '<tr style="padding:0;margin:0"><td style="padding:0;margin:0"><b  class="text-gray-900 p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.total_price + ' </b></td><td style="padding:0;margin:0; font-size: xx-small;text-align: right" class="  p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900">' + '<span class="number_price"  id="product_price_without_shipping_' + id + '">--</span>' + ' <span class="currency">' + currecny + '</span>' + '</span> </td> </tr>' +
        '<tr class="has_seller" style="padding:0;margin:0"><td style="padding:0;margin:0"><b  class="text-danger p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.min_total_price + '</b></td><td style="padding:0;margin:0; font-size: xx-small;text-align: right" class="  p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" >' + '<span class="number_price" id="product_min_total_price_' + id + '">--</span>' + ' <span class="currency">' + currecny + '</span>' + '</span> </td> </tr>' +
        '</table>' +
        '</td>';
    //endregion

    //region delete button
    var delete_button = '<td class="pt-5 text-end"> <button data-id="' + id + '" type="button" class="btn btn-sm btn-icon btn-active-color-primary remove_product_from_cart"   data-kt-element="remove-item" ><i class="las la-trash"></i></button></td>'
    //endregion

    html += product;
    html += quantity;
    html += price;
    html += total;
    html += delete_button;

    html += "</tr>";

    $("#product_table").append(html);
    var address = $("#address").val();

    $("#quantity_" + id).change()
    // calculator_cart()
}

//endregion

//region add product to cart
function add_product_to_cart(id, currecny, product_title, unit_price, image, sku, quantity, slug = null) {
    if ($("#address").val() == "" || $("#address").val() == null || $("#address").val() === undefined || $("#address").val() === "undefined") {
        error_message('please select address')
    } else if ($("#payment_method").val() == "" || $("#payment_method").val() == null || $("#payment_method").val() === undefined || $("#payment_method").val() === "undefined") {
        error_message('please select payment method')
    } else if (order_products.includes(id)) {
        error_message('added previously')
    } else {
        order_products.push(id);
        var blocked_countries_names = $("option[value=" + id + "]").data('blockcn');
        var html = '<tr   class="border-bottom border-bottom-dashed" data-kt-element="item" id="row_product_' + id + '" >';

        //region product
        var product = '<td class="pe-7">' +
            '<div class="d-flex align-items-center">' +
            '<div class="symbol symbol-45px me-5">' +
            '<img src="' + image + '" onerror="this.src=product_image_default" alt="">' +
            '</div>' +
            '<div class="d-flex justify-content-start flex-column">' +
            '<a href="' + app_frontend + '/product/' + slug + '" class="text-dark fw-bolder text-hover-primary fs-6">' + sku + '</a>' +
            '<span class="text-muted fw-bold text-muted d-block fs-7">' + product_title + '</span>' +
            '<span class="badge badge-light-danger   fw-bold   d-block fs-7"> ' + blocked_countries_names + '     </span>' +
            '</div>' +
            '</div>';
        product += ' <input type="hidden" class="form-control form-control-sm form-control-solid mb-2"  name="orders_item[]" value="' + id + '">';
        product += ' <input type="hidden" class="form-control form-control-sm form-control-solid mb-2"  name="orders_sku[]" value="' + product_title + '" id="product_' + id + '"  disabled >';
        // product += '<select multiple class="form-control form-control-sm form-control-solid" id="product_attributes_' + id + '" name="product_attributes_' + id + '[] "></select>';
        product += "</td>";
        //endregion

        //region quantity
        var quantity = '<td>' +
            '<input class="form-control quantity_product form-control-sm form-control-solid" type="number" min="1" data-max="' + quantity + '" data-sku="' + id + '"  name="quantity_' + id + '"   id="quantity_' + id + '" placeholder="1" value="1" data-kt-element="quantity">' +
            '<b class="text-dark" style="font-size: xx-small"> ' + tarnslate_order.the_quantity_available_is + '<span id="text_quantity_' + id + '">' + quantity + '</span>' + '</b>' +
            '</td>';

        //endregion
        //region price
        var price = "<td>" +
            '<div class="input-group  input-group-sm  input-group-solid mb-5">' +
            '<span class="input-group-text bg-danger text-light">$</span>' +
            '<input type="number" class="form-control product_price border-0 form-control-sm form-control-solid text-end" id="product_price_' + id + '" data-id="' + id + '" data-shipping="0"  data-quantity="' + $("option[value=" + id + "]").data('quantity') + '"  data-min="0" step="any" name="product_price_' + id + '" placeholder="0.00" value="' + unit_price + '"  >' +
            '</div>' +
            '<table class="w-100">' +
            '<tr style="padding:0;margin:0"><td  style="padding:0;margin:0"><h6  class="text-gray-900 p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.unit_price + '</h6> </td> <td  style="padding:0;margin:0; font-size: xx-small;text-align: right" class="p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_unit_price_' + id + '">' + '--' + currecny + '</span> </td> </tr>' +
            '<tr class="has_seller" style="padding:0;margin:0"><td  style="padding:0;margin:0"><h6  class="text-danger p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.min_unit_price + ' </h6> </td> <td  style="padding:0;margin:0; font-size: xx-small;text-align: right" class="p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_min_unit_price_' + id + '">' + '--' + currecny + '</span> </td> </tr>' +
            '</table>' +
            "</td>";

        //endregion
        //region total
        var total = '<td class="pt-8 text-end text-nowrap"><span  class="number_price" data-price="' + unit_price + '" id="product_price_label_' + id + '" data-kt-element="total">' + unit_price + '</span><span class="currency">' + currecny + '</span>' +
            '<br><table class="w-100 mt-5">' +
            // '<tr style="padding:0;margin:0"><td style="padding:0;margin:0"><b  class="text-primary p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.shipping_price + '</b></td><td style="padding:0;margin:0; font-size: xx-small;text-align: right" class="  p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_shipping_price_' + id + '">' + '--' + currecny + '</span><input type="hidden" class="product_shipping_value_price" id="product_shipping_value_price_' + id + '"> </td> </tr>' +
            '<tr style="padding:0;margin:0"><td style="padding:0;margin:0"><b  class="text-gray-900 p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.total_price + ' </b></td><td style="padding:0;margin:0; font-size: xx-small;text-align: right" class="  p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900" id="product_price_without_shipping_' + id + '"></span> <span class="currency"></span> </td> </tr>' +
            '<tr  class="has_seller" style="padding:0;margin:0"><td style="padding:0;margin:0"><b  class="text-danger p-0 m-0"   style="padding:0;margin:0 ; font-size: xx-small">' + tarnslate_order.min_total_price + '</b></td><td style="padding:0;margin:0; font-size: xx-small;text-align: right" class="  p-0 m-0"><span   style="padding:0;margin:0" class="p-0 m-0 text-gray-900"> ' + '<span class="number_price" id="product_min_total_price_' + id + '">--</span>' + ' <span class="currency"></span> </span> </td> </tr>' +
            '</table>' +
            '</td>';
        //endregion

        //region delete button
        var delete_button = '<td class="pt-5 text-end"> <button data-id="' + id + '" type="button" class="btn btn-sm btn-icon btn-active-color-primary remove_product_from_cart"   data-kt-element="remove-item" ><i class="las la-trash"></i></button></td>'
        //endregion

        html += product;
        html += quantity;
        html += price;
        html += total;
        html += delete_button;

        html += "</tr>";

        $("#product_table").append(html);

        get_price(id, 1, $("#address").val())

    }
}

//endregion

//region currency
$(document).on('change', '#currency', function () {
    calculator_cart()
})
//endregion

//region when select type is proforma
$(document).on('change', '#type', function () {
    select_type()
});

function select_type() {
    var type = $("#type").val();

    if (type == 'order') {
        $("#div_order_status").show();
        $(".payment_div").show();

    } else {
        $("#div_order_status").hide();
        $(".payment_div").hide();
        $("#payment_method").val('transfer').trigger('change');
    }
}

//endregion
