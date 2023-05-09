$(document).on('click', '.tab-btn', function () {

    $("#content").html('<div class="container text-center"><i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i></div>')

    $('.nav-link').removeClass('show active');
    $(this).addClass('show active');
    route = $(this).data('route');
    $.ajax({
        url: route,
        method: "post",
        data: {
            "_token": token,
            'user_id': user.user_id
        }, success: (response) => {
            $("#content").html(response.data.view);
            $("#create_new_payment_type").select2({
                dropdownParent: $('#create_new_payment_modal')
            });
            $("#create_new_payment_order").select2({
                dropdownParent: $('#create_new_payment_modal')
            });

        }
    });
});

//delete addresses
$(document).on('click', '.destroy-address', function () {

    $(this).html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>')
    var url = $(this).attr('href');
    $(this).addClass('btn-icon')
    $(this).attr('disabled', 'disabled')
    $.ajax({
        url: url,
        method: "delete",
        data: {
            "_token": token
        },
        success: function () {
            $('#addresses').click();
        }
    })
});

$(document).on('click', '.show-coupon-usage', function () {

    var button = $(this);
    var route = button.data('route');

    $(this).addClass('btn-icon');
    $(this).html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>');
    $(this).attr('disabled', 'disabled');
    var coupon_id = $(this).data('coupon_id')

    $.ajax({
        url: route,
        method: "post",
        data: {
            "_token": token,
            "coupon_id": coupon_id,
        },
        success: function (response) {
            button.attr('disabled', false)
            button.html('<i class="las la-eye"></i>')

            document.getElementById('modal_show_coupon_usage').innerHTML = response.data.view;
            $("#modal_coupon_usage").modal('show');
        }
    });

});
//set default address
$(document).on('click', '.set-default', function () {

    $(this).html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>')
    var url = $(this).attr('href');
    $(this).addClass('btn-icon')
    $(this).attr('disabled', 'disabled')
    $.ajax({
        url: url,
        method: "post",
        data: {
            "_token": token
        },
        success: function () {
            $('#addresses').click();
        }
    })
});

//update and create addresses
$(document).on('submit', '.fire-address', function (event) {
    event.preventDefault();
    var button = $(this).find(":submit");
    button.attr('disabled', true);
    $('#country_error').text('');
    $('#city_error').text('');
    $('#address_error').text('');
    var url = $(this).attr('action')
    $.ajax({
        url: url,
        method: "post",
        data: $(this).serialize(),
        success: function (response) {
            button.removeAttr('disabled', false)
            document.getElementById('modal_edit_content').innerHTML = '';
            document.getElementById('model_new_address_content').innerHTML = '';
            $("#modal_edit_address").modal('hide');
            $("#modal_new_address").modal('hide');
            $('#addresses').click();

        },
        error: function (response) {
            $('#country_error').text(response.responseJSON.errors.country);
            $('#city_error').text(response.responseJSON.errors.city);
            $('#address_error').text(response.responseJSON.errors.address);
            button.attr('disabled', false);


        }
    })
})

//on ready

$(document).ready(function () {
    $('#overview').click();
    $("#create_new_payment_type").select2({
        dropdownParent: $('#create_new_payment_modal')
    });
    $("#create_new_payment_order").select2({
        dropdownParent: $('#create_new_payment_modal')
    });
})

//get edit address page modal

$(document).on('click', '#create-address', function () {

    var url = $(this).attr('href');
    var button = $(this);
    button.attr('disabled', true)
    document.getElementById('modal_edit_content').innerHTML = '';
    document.getElementById('model_new_address_content').innerHTML = '';


    $.ajax({
        url: url,
        method: "post",
        data: {
            "_token": token,
            "user_id": user.user_id,
        },
        success: function (response) {
            button.attr('disabled', false);

            $("#model_new_address_content").html(response.data.view);
            $("#modal_new_address").modal('show');
            $("#country").select2({
                dropdownParent: $('#modal_new_address')
            });
        }
    })
});

$(document).on('click', '.edit-address', function () {

    var button = $(this);
    button.html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>')
    button.attr('disabled', true)
    document.getElementById('modal_edit_content').innerHTML = '';
    document.getElementById('model_new_address_content').innerHTML = '';

    var url = $(this).data('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
            "_token": token
        },
        success: function (response) {
            button.attr('disabled', false)
            button.html('<i class="las la-highlighter"></i>')

            document.getElementById('modal_edit_content').innerHTML = response.data.view;
            $("#modal_edit_address").modal('show');

            $("#modal_edit_address").modal('show');
            var country = $("#country").val();
            var edit = document.getElementById('country');
            $("#country").select2({
                dropdownParent: $('#modal_edit_address')
            });

            edit.value = country;

            var create_phone = document.querySelector("#phone");
            window.intlTelInput(create_phone, {
                initialCountry: "tr",
                hiddenInput: 'phone',
                utilsScript: "{{asset('backend/plugins/custom/intltell/js/utils.js')}}",
            });
        }
    })
});

//region wallet

$(document).on('click', '.show_payment', function () {
    var id = $(this).data('id');
    $.ajax({
        url: user_routes.payment_info,
        method: "post",
        data: {
            _token: token,
            id: id
        }, success: function (response) {
            $("#info_payment_body").html(response);
            $("#info_payment").modal('show');

        }
    });
});

$(document).on('click', '.change_status_payment', function () {
    var btn = this;
    var html = $(btn).html();
    var id = $(this).data('id');
    var type = $(this).data('type');
    $(btn).html("<i class='fa fa-spinner fa-pulse  fa-fw'></i>").attr('disabled', 'disabled')
    $.ajax({
        url: user_routes.payment_change,
        method: "post",
        data: {
            _token: token,
            id: id,
            type: type
        }, success: function (response) {
            $("#info_payment").modal('hide');
            $("#wallet").click();


        }
    })
});
var orders_balance = [];
$(document).on('click', '.change_balance_wallet', function () {
    var id = $(this).data('user');
    $.ajax({
        url: user_routes.payment_get,
        method: "post",
        data: {
            _token: token,
            user: id
        }, success: function (response) {
            // $("#info_payment_body").html(response);
            $("#create_new_payment_user_id").val(response.data.user.id)
            $("#user_new_payment").val(response.data.user.name)
            $("#balance").val(response.data.wallet_balance);
            $("#create_new_payment_order").empty();
            orders_balance = response.data.orders;
            // create_new_payment_type();
            $("#create_new_payment_amount").val("");
            $("#create_new_payment_note").val("");
            $("#create_new_payment_files").empty();
            $("#create_new_payment_note").html("");
            $("#balance").val(response.data.wallet_balance)
            $("#create_new_payment_modal").modal('show');
            $(".orders").show();
            $(".orders[data-sorderstatus='refund']").hide();
            create_new_payment_type()
        }
    })

});


$(document).on('click', '#button_create_new_payment', function () {
    var html_btn = $("#button_create_new_payment").html();
    $("#button_create_new_payment").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>');
    $("#button_create_new_payment").attr('disabled', 'disabled');

    var formData = new FormData();
    formData.append('user_id', $("#create_new_payment_user_id").val());
    formData.append('amount', $("#create_new_payment_amount").val());
    formData.append('type', $("#create_new_payment_type").val());
    formData.append('order_id', $("#create_new_payment_order").val());
    formData.append('note', $("#create_new_payment_note").val());
    var ins = document.getElementById('create_new_payment_files').files.length;
    for (var x = 0; x < ins; x++) {
        formData.append("files[]", document.getElementById('create_new_payment_files').files[x]);
    }
    formData.append('_token', token)
    $.ajax({
        url: user_routes.payment_set,
        method: "post",
        cache: false,
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            $("#create_new_payment_modal").modal('hide');
            $("#wallet").click();
            $("#button_create_new_payment").html(html_btn);
            $("#button_create_new_payment").removeAttr('disabled');
            if (typeof (dt_payment) != "undefined" && dt_payment !== null) {
                dt_payment.ajax.reload()
            }

        },
        error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);

            $("#button_create_new_payment").html(html_btn);
            $("#button_create_new_payment").removeAttr('disabled');

        }
    })

});

function create_new_payment_type() {
    var $example = $("#create_new_payment_order").select2();
    var type = $("#create_new_payment_type").val();
    $("#create_new_payment_order").empty();
    for (var i = 0; i < orders_balance.length; i++) {
        var has_print = false;
        if (type == 'order' && parseFloat(orders_balance[i].balance) != 0 && orders_balance[i].status != 'refunded') {
            has_print = true;
        } else if (type == "withdraw" && (orders_balance[i].status == 'refunded' || parseFloat(orders_balance[i].balance) > 0)) {
            has_print = true;
        }
        if (has_print) {
            var classes = 'orders ' + orders_balance[i].status
            $("#create_new_payment_order").append("<option class='" + classes + "' value='" + orders_balance[i].id + "'>" + orders_balance[i].uuid + '( total =' + orders_balance[i].total + '/ balance = ' + orders_balance[i].balance + ') ' + orders_balance[i].status_trans + "</option>");
        }
    }
    // if (type == 'order') {
    //     $('.processing').removeAttr('disabled');
    //     $('.completed').removeAttr('disabled');
    //     $('.on_hold').removeAttr('disabled');
    //     $('.pending_payment').removeAttr('disabled');
    //
    //     $('.refunded').attr('disabled', 'disabled');
    // } else {
    //     $('.processing').attr('disabled', 'disabled');
    //     $('.completed').attr('disabled', 'disabled');
    //     $('.on_hold').attr('disabled', 'disabled');
    //     $('.pending_payment').attr('disabled', 'disabled');
    //
    //     $('.refunded').removeAttr('disabled');
    // }
    $("#create_new_payment_order").select2({
        dropdownParent: $('#create_new_payment_modal'),
        placeholder: 'Select an option',
    }).val(null).trigger("change");

}

$(document).on('change', '#create_new_payment_type', function () {

    create_new_payment_type();

});
$(document).on('click', '#send_reminder', function () {
    var url = user_routes.send_reminder;
    $.ajax({
        url: url,
        method: "post",
        data: {
            _token: token,
            id: user.user_id
        },
        success: function (response) {
            success_message(response.data.message);
        },
        error: function (response) {
            error_message(response.responseJSON.message)
        }
    })
});
$(document).on('click', '#send_account_statement', function () {
    var url = user_routes.send_account_statement;
    $.ajax({
        url: url,
        method: "post",
        data: {
            _token: token,
            id: user.user_id
        },
        success: function (response) {
            success_message(response.data.message);
        },
        error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
        }
    })
});
//endregion
