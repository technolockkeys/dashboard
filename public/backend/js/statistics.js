$(document).on('change', '.change_statistic', function () {
    $("#content").html('<div class="container text-center"><div>')
    var route = $('#reports').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if (route !== '') {
        $("#content").html('<div class="container text-center"><i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i></div>')
        $.ajax({
            url: route,
            method: "get",
            data: {
                "_token": csrf_token,
                "start_date": start_date,
                "end_date": end_date
            }, success: (response) => {
                $("#content").html(response.data.view);
            }
        });
    }
});


$(document).on('change', '#select_product', function () {
    $("#product_chart_wrapper").html('');
    var route = $('#route').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    // let product = $('#select_product').val();
    // $("#product_chart_wrapper").html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>');
    $.ajax({
        url: route,
        method: "get",
        data: {
            "_token": csrf_token,
            "start_date": start_date,
            "end_date": end_date,
            "product": $('#select_product').val()
        }, success: (response) => {
            $("#product_chart_wrapper").html(response.data.view);
        }
    });

});

$(document).on('change', '.get_category', function () {
    $("#chart_wrapper").html('')
    var route = $('#reports').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var category = $('#category').val()
    if (route !== '') {
        $("#chart_wrapper").html('<i class="fa fa-spinner fa-pulse fa-3x  fa-fw"></i>')
        $.ajax({
            url: routes.get_category,
            method: "get",
            data: {
                "_token": csrf_token,
                "start_date": start_date,
                "end_date": end_date,
                "category": category,
            }, success: (response) => {
                $("#chart_wrapper").html(response.data.view);
            }
        });
    }
});
