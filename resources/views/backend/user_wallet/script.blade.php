<script>
    $(document).on('click', '.approve_order_payment', function () {
        var id = $(this).data('id');
        $(this).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>');
        $.ajax({
            url: "{{route('backend.orders.order.payment.approve')}}",
            method: "post",
            data: {
                id: id,
                _token: "{{csrf_token()}}"
            },
            success: function (response) {
                success_message(response.data.message);
                if (typeof dt !== "undefined") {
                    dt.ajax.reload(null, false);
                }
            }
        });

    });
    $(document).on('click', '.show_transfer_order', function () {
        var id = $(this).data('id');

        $("#row_balance_modal").hide();
        var btn = $(this);
        $(this).html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>').addClass('btn-icon').attr('disabled', 'disabled');
        $.ajax({
            @if(auth('admin')->check())
            url: "{{route('backend.orders.order.payment.show')}}",
            @else
            url: "{{route('seller.orders.order.payment.show')}}",
            @endif
            method: "post",
            data: {
                _token: "{{csrf_token()}}",
                id: id
            },
            success: function (response) {
                $("#modal_order_price").val(response.data.order.total)
                $("#modal_order_balance").val(response.data.order.wallet)
                $("#modal_order_payment_method").val(response.data.order.payment_method)
                $("#modal_order_amount").val(response.data.wallet.amount)
                $("#modal_order_created_at").val(response.data.wallet.created_at)
                if (response.data.wallet.status == "{{\App\Models\UserWallet::$approve}}" || response.data.wallet.status == "{{\App\Models\UserWallet::$cancelled}}") {
                    $("#transfer_type_div").hide();
                    $("#footer_transfer").hide();

                } else {
                    $("#transfer_type_div").show()
                    $("#footer_transfer").show()
                    if (response.data.order.payment_method == '{{  trans('backend.order.stripe_link' ) }}') {

                        $("option[value='part_credit']").remove();
                    } else {
                        $("option[value='part_credit']").remove();
                        $("#transfer_type").append("<option value='part_credit'>{{trans('backend.wallet.part_credit')}}</option>")

                    }

                    $("#transfer_type").val('credit').trigger('change');
                }

                $("#modal_value").val(0);
                $("#row_balance_modal").hide();
                $("#transfer_id_form").val(id)

                // $("#modal_value").attr('max', parseFloat(parseFloat(response.data.order.wallet) * -1))
                $("#files_modal").html('');
                if (response.data.wallet.files.length == 0) {
                    $("#row_filess").hide()
                } else {
                    $("#row_filess").show()
                }
                for (var i = 0; i < response.data.wallet.files.length; i++) {
                    $("#files_modal").append('<a class="form-control form-control-solid w-100 mt-3" href="' + response.data.wallet.files[i] + '" target="_blank"><i class="fa fa-file"></i> ' + (i + 1) + ' file</a>')
                }

                btn.html('<i class="la la-wallet"></i> {{trans('backend.global.show')}}').removeClass('btn-icon').removeAttr('disabled');
                if (response.data.wallet.status == 'approve') {
                    $("#transfer_data_save").attr('disabled', 'disabled')
                } else {
                    $("#transfer_data_save").removeAttr('disabled')
                }
                $("#modal_transfer_order").modal('show')
            }
        });

    });
    $(document).on('change', '#transfer_type', function () {
        var transfer_type = $("#transfer_type").val();
        if (transfer_type == 'part_credit') {
            $("#row_balance_modal").show();
        } else {
            $("#row_balance_modal").hide();

        }
    });
    $(document).on('click', '.payment_method', function () {
        navigator.clipboard.writeText($(this).data('url'));
        success_message('Copied the text: ' + $(this).data('url'))
    });
    $(document).on('keyup', '#modal_value', function () {
        var max = $(this).attr('max');

        if (parseFloat($(this).val()) > parseFloat(max)) {
            $('#modal_value').val(max)
        }
        if (parseFloat($(this).val()) < 0) {
            $('#modal_value').val(0)
        }

    });
    $(document).on('change', '#modal_value', function () {
        var max = $(this).attr('max');

        if (parseFloat($(this).val()) > parseFloat(max)) {
            $('#modal_value').val(max)
        }
        if (parseFloat($(this).val()) < 0) {
            $('#modal_value').val(0)
        }
    });
    $(document).on('submit', '#transfer_type_form', function (event) {
        event.preventDefault();
        $("#transfer_data_save").html('<i class="fa fa-spinner fa-pulse  fa-fw"></i>').attr('disabled', 'disabled');

        $.ajax({
            @if(auth('admin')->check())
            url: "{{route('backend.orders.order.payment.update')}}",
            @else
            url: "{{route('seller.orders.order.payment.update')}}",
            @endif
            method: "post",
            data: $("#transfer_type_form").serialize(),
            success: function (response) {
                success_message(response.data.message);
                @if(auth('admin')->check())
                dt.ajax.reload();
                @else
                dt_payment.ajax.reload();
                @endif
                $("#transfer_data_save").html('{{trans('backend.global.save')}}').removeAttr('disabled');
                $("#modal_transfer_order").modal('hide');
            }, error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message(response.message);
                $("#transfer_data_save").html('{{trans('backend.global.save')}}').removeAttr('disabled');
                $("#modal_transfer_order").modal('hide');
            }
        })


    });
</script>
