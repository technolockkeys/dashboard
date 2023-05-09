function error_message(message) {
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toastr-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr.error(message);
}

function success_message(message) {
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toastr-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr.success(message);
}
function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

//region shipping
$(document).on('click', '#get-shipping-price', function () {

    var button = $('#get-shipping-price');
    button.html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>')
    button.attr('disabled', true)

    var country = $('#shipping-country').val();
    var weight = $('#shipping-weight').val();
    $.ajax({
        url: calculate_shipping_cost_route,
        method: "post",
        data: {
            "_token": csrf_token,
            'shipping_country': country,
            'shipping_weight': weight
        }, success: (response) => {
            $('#shipping-country-error').text('');
            $('#shipping-weight-error').text('');
            $('#display-shipping-table tr').remove();
            $('#display-shipping').removeClass('d-none')
            $("#shipping-prices").val(response.data.shipping);
            var table = document.createElement('table');
            shipping_array = Object.keys(response.data.shipping);
            shipping_array_values = Object.values(response.data.shipping);
            console.log(shipping_array)
            var data = '';
            shipping_array.forEach(function (item, key) {
                data = data + '<tr class=\'fw-semibold fs-6 text-gray-800 border-bottom border-gray-200\' style="width: 100%">' +
                    '<th style="width: 50%">' + item + '</th><td style="width: 50%">' + shipping_array_values[key] + '</td> </tr>';
            });
            $('#display-shipping-table').append(data);
            button.attr('disabled', false)
            button.html(messages_translate.get_shipping_price)


        }, error: (response) => {
            button.attr('disabled', false)
            button.html(messages_translate.get_shipping_price);
            if (response.responseJSON.errors){
                $('#shipping-country-error').text(response.responseJSON.errors.shipping_country);

                $('#shipping-weight-error').text(response.responseJSON.errors.shipping_weight);
            }else{
                $('#shipping-country-error').text(response.responseJSON.message);
            }
        }
    });
});
//endregion
