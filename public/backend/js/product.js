/*
var element = document.querySelector("#kt_stepper_example_clickable");
var stepper = new KTStepper(element);
stepper.on("kt.stepper.click", function (stepper) {
    if (stepper.getClickedStepIndex() <= stepper.getCurrentStepIndex()) {
        stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
    }
});
stepper.on("kt.stepper.next", function (stepper) {
    // alert(stepper.getCurrentStepIndex())
    var check_data = [];
    var years_steps = true;
    if (stepper.getCurrentStepIndex() == 1) {
        for (var i = 0; i < languages.length; i++) {
            check_data.push('title_' + languages[i].code);
            check_data.push('description_' + languages[i].code);
        }
        check_data.push('sku');
        check_data.push('slug');
        check_data.push('priority');
        check_data.push('category');
        check_data.push('weight');

    } else if (stepper.getCurrentStepIndex() == 2) {
        check_data = ['image']
    } else if (stepper.getCurrentStepIndex() == 3) {
        var years = [];
        var total = 0;
        $('select[name="years[]"]').each(function () {
            var item_years = $(this).select2('val');
            for (let index = 0; index < item_years.length; index++) {
                years.push(item_years[index])
            }
        });
        if (years.length == 0) {
            years_steps = false;
        } else {
            years_steps = true;
        }

    } else if (stepper.getCurrentStepIndex() == 4) {
        check_data = ['price', 'quantity']
    } else if (stepper.getCurrentStepIndex() == 6) {
        for (var i = 0; i < languages.length; i++) {
            check_data.push('meta_title_' + languages[i].code);
            check_data.push('meta_description_' + languages[i].code);
        }
    }

    if (check_validation(check_data) && years_steps) {
        stepper.goNext(); // go next step

    }

});
stepper.on("kt.stepper.previous", function (stepper) {
    stepper.goPrevious(); // go previous step
});

 */
$(document).on('change', '#slug', function () {
    var slug = $("#slug").val();
    var token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        url: products_routes.check_slug,
        method: "post",
        data: {
            '_token': token,
            slug: slug
        }, success: function (response) {
            $("#message_slug").html("<b class='text-success'> <i class='fa  fa-check-circle'></i> " + response.data.message + "</b>")
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $("#message_slug").html("<b class='text-danger'> <i class='fa fa-times-circle'></i> " + response.message + "</b>")


        },
    })
});
$(document).on('change', '#sku', function () {
    var sku = $("#sku").val();
    var token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        url: products_routes.check_sku,
        method: "post",
        data: {
            '_token': token,
            sku: sku
        }, success: function (response) {
            $("#error_sku").html("<b class='text-success'> <i class='fa  fa-check-circle'></i> " + response.data.message + "</b>")
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $("#error_sku").html("<b class='text-danger'> <i class='fa fa-times-circle'></i> " + response.message + "</b>")
        },
    })

});

var optionFormat = function (item) {
    if (!item.id) {
        return item.text;
    }

    var span = document.createElement('span');
    var imgUrl = 'https://dummyimage.com/400x400/' + item.element.getAttribute('data-color').replace('#', '') + '/0011ff.png&text=' + item.text;
    var template = '';

    template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
    template += item.text;

    span.innerHTML = template;

    return $(span);
}


$('#color').select2({
    templateSelection: optionFormat,
    templateResult: optionFormat
});


function remove_video(id) {
    $("div[data-row='" + id + "']").remove();
}

function add_video() {
    var uuid = uuidv4();
    var add_videos = '  <div class="row mt-3" data-row="' + uuid + '" >' +
        // '<div class="col-5 col-md-5">' +
        // '<label class="form-label" for="videos_type_' + uuid + '">' + keys.video_type + '</label>' +
        // '<select name="videos_provider[]" class="form-control videos_provider" required data-control="select2" id="videos_type_' + uuid + '">' +
        // '<option value="youtube">' + keys.youtube + '</option>' +
        // '<option value="vimeo">' + keys.vimeo + '</option>' +
        // '</select>' +
        // '</div>' +
        '<div class="col-11 col-md-11">' +
        '<label  class="form-label"  for="videos_type_' + uuid + '">' + keys.videos_value + '</label>' +

        '<input name="video_url[]" id="video_url_' + uuid + '" type="text" class="form-control" required >' +
        '</div>' +
        '<div class="col-1 col-md-1 mt-8 ">' +
        '<button type="button" onclick="remove_video(' + "'" + uuid + "'" + ')"  class="btn btn-icon btn-danger"> <i class="fa fa-times"></i> </button>' +
        '</div>' +
        '</div>';
    $("#add_videos").removeClass('d-none');
    $("#add_videos").append(add_videos);
    $(".videos_provider").select2()
}

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function getBrand(itemId, brand, model = null) {
    var token = $("meta[name=csrf-token]").attr('content');
    var without = [];
    var models = document.getElementsByClassName("models");
    // for (var i = 0; i < models.length; i++) {
    //     if ($(models[i]).val() != "" && $(models[i]).val() != null) {
    //         // without.push($(models[i]).val());
    //     }
    // }
    $.ajax({
        url: products_routes.brands,
        method: "post",
        data: {
            '_token': token,
            brand: brand,
            model: model,
            without: without,
        }, success: function (response) {
            $("#" + itemId).empty();
            if (model == null) {
                $("#" + itemId).append("<option  readonly selected value=''>All</option>")

                $.each(response.data.models, function (key, value) {
                    $("#" + itemId).append("<option value='" + key + "'>" + value + "</option>");
                });

            } else {
                $("#" + itemId).append("<option  readonly selected value=''>All</option>")
                $.each(response.data.years, function (key, value) {
                    $("#" + itemId).append("<option value='" + key + "'>" + value + "</option>");
                });
            }
        }


    })

}

function add_new_brand() {

    var uuid = uuidv4();

    var new_model = "<div class='col-12'><div class='row' id='row_brand_" + uuid + "'><div class='col-11 col-md-2'><label for='brand_" + uuid + "'>" + keys.brand + "</label><select name='brand[]' id='brand_" + uuid + "' data-uuid='" + uuid + "' data-control='select2' class='brands form-control'>";
    for (var i = 0; i < brands.length; i++) {
        new_model += '<option value="' + brands[i].id + '">' + brands[i].make['en'] + ' </option>'
    }
    new_model += "</select></div>" +
        "<div class='col-11 col-md-3'><label for='models_" + uuid + "'>" + keys.model + "</label><select name='models[]'  id='models_" + uuid + "' data-uuid='" + uuid + "' data-control='select2' class='models form-control'><option  readonly disabled  selected value=''>please select brand</option></select></div>" +
        "<div class='col-11 col-md-3'>" +
        "<label for='year_from'>" + keys.year + "</label><select  id='year_from_" + uuid + "' data-uuid='" + uuid + "' name='years_from[]' data-control='select2' class='years form-control'><option  readonly   >please select model</option></select>" +
        "</div>" +
        "<div class='col-11 col-md-3'>" +
        "<label for='year_to'>" + keys.year + "</label><select  id='year_to_" + uuid + "' data-uuid='" + uuid + "' name='years_to[]' data-control='select2' class='years form-control'><option  readonly   >please select model</option></select>" +
        "</div>" +
        "<div class='col-1 col-md-1'><button type='button' onclick='$(" + '"#row_brand_' + uuid + '"' + ").remove()' class='btn btn mt-5 btn-icon btn-danger btn-hover-scale   me-5'><i class='fas fa-trash fs-4'></i></button></div></div></div>";
    $("#brands_models").append(new_model)
    $("#brand_" + uuid).select2().change();
    $("#models_" + uuid).select2();
    $("#year_from_" + uuid).select2();
    $("#year_to_" + uuid).select2();


}

$(document).on('change', '.models', function () {
    var uuid = $(this).data('uuid');
    var brandItme = $("#brand").val();
    var model = $("#models_" + uuid).val();
    getBrand('year_from_' + uuid, brandItme, model)
    getBrand('year_to_' + uuid, brandItme, model)
});


$(document).on('click', '#add_new_offer', function () {

    var uuid = uuidv4();
    var html = "<tr data-row='" + uuid + "'>" +
        "<td><input type='number' required class='form-control' name='from[]'></td>" +
        "<td><input type='number' required class='form-control' name='to[]'></td>" +
        "<td><input   type='number' step='0.01' class='form-control ' name='packages_price[]'></td>" +
        "<td><button type='button' data-uuid='" + uuid + "' class='btn btn-danger btn-icon btn-sm btn-hover-scale    remove-row'><i class='fa fa-times'></i></button></td>" +
        "</tr>";
    $("#offer_table").append(html)
});
$(document).on('click', '#add_serial_number', function () {

    var uuid = uuidv4();
    var html = "<tr data-row='" + uuid + "'>" +
        "<td><input type='text' required class='form-control' name='serial_number[]'></td>" +
        "<td><button type='button' data-uuid='" + uuid + "' class='btn btn-danger btn-icon btn-sm remove-row'><i class='fa fa-times'></i></button></td>" +
        "</tr>";

    $("#serial_numbers_table").append(html)
    $('select[name="offer_type[]"]').select2();
});
$(document).on('click', ".remove-row", function () {
    var uuid = $(this).data('uuid');
    $("tr[data-row='" + uuid + "']").remove();
});

function check_validation(data = []) {
    var fields = [];
    var return_value = true;
    ``
    for (var i = 0; i < data.length; i++) {


        if ($("#" + data[i]).val() == "" || $("#" + data[i]).val() == "undefined" || $("#" + data[i]).val() == undefined || $("#" + data[i]).val() == null) {
            return_value = false;
            $("#" + data[i]).addClass('is-invalid').removeClass('is-valid')
            $("#error_" + data[i]).html("The field is required.");
        } else {
            $("#" + data[i]).addClass('is-valid').removeClass('is-invalid')
            $("#error_" + data[i]).html("");
        }
    }
    if (!return_value) {
        error_message('please check input required ')
    }
    return return_value;

}

//region document is ready (select2 ,token  .. )
$(document).ready(function () {

    var token = $("meta[name=csrf-token]").attr('content');

    $('#blocked_countries').select2()
    $("#accessories").select2({
        ajax: {
            url: products_routes.get_product,
            method: "post",
            data: function (params) {
                var query = {
                    key: params.term,
                    '_token': token
                }
                return query;
            }, processResults: function (reponse) {
                return {
                    results: $.map(reponse.data.products, function (item) {
                         return {
                            text: item.title,
                            id: item.id
                        }
                    })
                }
            }
        }
    });
    $("#bundles").select2({
        ajax: {
            url: products_routes.get_product,
            method: "post",
            data: function (params) {
                var query = {
                    key: params.term,
                    '_token': token
                }
                return query;
            }, processResults: function (reponse) {
                return {
                    results: $.map(reponse.data.products, function (item) {
                         return {
                            text: item.title,
                            id: item.id
                        }
                    })
                }


            }
        }
    });

});
//endregion

//region brands
$(document).on('change', '.brands', function () {

    var uuid = $(this).data('uuid')
    var brandItem = $("#brand_" + uuid).val();
    console.table({
        brandItem: brandItem,
        uuid: uuid
    })
    getBrand('models_' + uuid, brandItem)
});

//endregion
//manufacturers

$(document).on('change', '#manufacturer', function () {
    var value = $(this).val();
    var token = $("meta[name=csrf-token]").attr('content');

    $.ajax({
            url: products_routes.manufacturer,
            method: 'post',
            data: {
                '_token': token,
                manufacturer: value
            },
            success: function (response) {
                if (response.data.token === 1 && response.data.software === 1) {
                    $('#manufacturer_type_wrapper').removeClass('d-none');
                } else {
                    $('#manufacturer_type_wrapper').addClass('d-none');
                }
            }
        }
    )
});

//end manufacturers
function reloadBrand(id, brand, model, years = [], years_to = []) {
    var token = $("meta[name=csrf-token]").attr('content');

    $.ajax({
        url: products_routes.brands,
        method: "post",
        data: {
            '_token': token,
            brand: brand,
            model: null,
        }, success: function (response) {

            $("#models_" + id).empty();
            $("#models_" + id).append("<option selected value=''>All</option>");
            $.each(response.data.models, function (key, value) {
                if (model + "" === key + "") {
                    $("#models_" + id).append("<option selected value='" + key + "'>" + value + "</option>");
                    $.ajax({
                        url: products_routes.brands,
                        method: "post",
                        data: {
                            '_token': token,
                            brand: brand,
                            model: model,
                            years: years,
                        }, success: function (response2) {
                            // $("#year_from_" + id).append("<option selected value=''>All</option>");
                            // $("#year_to_" + id).append("<option selected value=''>All</option>");
                            var year_from_selected = false;
                            var year_to_selected = false;
                            $.each(response2.data.years, function (key, value) {
                                var year_from_selected = false;
                                var year_to_selected = false;
                                if (years.toString() === key) {
                                    $("#year_from_" + id).append("<option selected value='" + key + "'>" + value + "</option>");
                                    year_from_selected = true;
                                }
                                if (years_to.toString() === key) {
                                    $("#year_to_" + id).append("<option selected value='" + key + "'>" + value + "</option>");
                                    year_to_selected = true;
                                }
                                if (!year_to_selected) {
                                    $("#year_to_" + id).append("<option value='" + key + "'>" + value + "</option>");
                                }
                                if (!year_from_selected) {
                                    $("#year_from_" + id).append("<option value='" + key + "'>" + value + "</option>");
                                }
                            });
                        }
                    })
                } else {
                    $("#models_" + id).append("<option value='" + key + "'>" + value + "</option>");
                }
            });
        }
    })
}

//region  main attribute
$(document).on("change", "#main_attribute", function () {
    var main_attributes = $("#main_attribute").val();

    $(".attr").each(function () {
        var id = $(this).data('id');
        if ($("#div_attr_" + id).hasClass('d-none') && (main_attributes.includes(id + ""))) {
            $("#div_attr_" + id).removeClass('d-none');
        } else if (main_attributes.includes(id + "") == false) {
            $("#attr_" + id).val(null).trigger("change");
            ;
            $("#div_attr_" + id).addClass('d-none');

        }
    })
});
//endregion

//region discount reange
$("#discount_range").daterangepicker({
    minDate: moment().startOf('hour'),
    dateFormat: 'yyyy-mm-dd'

});
$('#discount_range').on('apply.daterangepicker', function (ev, picker) {

    $("#discount_range_start").val(picker.startDate);
    $("#discount_range_end").val(picker.endDate);
});
//endregion

//region bundles
$(document).on('change', "#bundles", function () {
    var b = $("#bundles").val();
    var sku = $("#sku").val();
    if (b == '') {
        sku = sku.replace('TLB', "TL")
    } else {
        if (!(sku.indexOf('TLB') > -1)) {
            sku = sku.replace('TL', "TLB")
        }
    }
    $("#sku").val(sku)

});
//endregion

//region competitors
const add_competitors = function () {
    var uuid = uuidv4();

    var add_videos = '  <tr class="" data-row="' + uuid + '" >' +
        '<td>' +
        '<input name="competitors_url[]" id="competitors_url' + uuid + '" type="text" class="form-control" required >' +
        '</td>' +
        '<td>' +
        '<select name="competitors_selector[]" id="competitors_selector_' + uuid + '" required data-control="select2" type="text" class="form-control competitor_selector" required >' +
        '<option value="id">' + keys.id + '</option>' +
        '<option value="class">' + keys.class + '</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input name="competitors_name[]" id="competitors_name' + uuid + '" type="text" class="form-control" required >' +
        '</td>' +
        '<td>' +
        '<input name="competitors_html_type[]" id="competitors_html_type' + uuid + '" type="text" class="form-control" required >' +
        '</td>' +
        '<td>' +
        '<input name="competitors_price[]" id="competitors_price' + uuid + '" type="text" class="form-control" >' +
        '</td>' +
        '<td>' +
        '<button type="button" onclick="remove_competitor(' + "'" + uuid + "'" + ')"  class="btn btn-icon btn-danger"><i class=\'fa fa-times\'></i> </button>' +
        '</td>' +
        '</tr>';
    $("#add_competitors").removeClass('d-none');
    $("#add_competitors").append(add_videos);
    $(".competitor_selector").select2();
}

const remove_competitor = function (uuid) {
    $("tr[data-row='" + uuid + "']").remove();

}
//endregion

//region price
//check price ...
function update_price() {
    var price = $("#price").val();
    var sale_price = $("#sale_price").val();
    if (sale_price != "" && parseFloat(sale_price) > parseFloat(price)) {
        $("#sale_price").val(price);
    }
}

$(document).on('change', "#price", function () {
    update_price()
});
$(document).on('change', "#sale_price", function () {
    update_price()
});
$(document).on('keyup', "#price", function () {
    update_price()
});
$(document).on('keyup', "#sale_price", function () {
    update_price()
});
//endregion

//region discount type /custom or unlimited/
$(document).on('change', '#date_type', function () {
    var date_type = $(this).val();
    if (date_type == 'custom_date') {
        $("#div_date_type").show();
    } else {
        $("#div_date_type").hide();
    }
});
//endregion

//region close and open form new attributes
$(document).on('click', '#add_new_attributes', function () {
    $("#add_new_attributes").hide();
    $("#create_new_attribute_body").show();
});
$(document).on('click', '#close_new_attributes', function () {
    $("#add_new_attributes").show();
    $("#create_new_attribute_body").hide();
});
//endregion

//region store new attribute
$(document).on('click', '#save_new_attributes', function () {

    var names = [];
    var medias = [];
    var token = $("meta[name=csrf-token]").attr('content');
    var fd = new FormData();
    fd.append('_token', token);
    for (var i = 0; i < languages.length; i++) {
        if ($("#new_attr_" + languages[i].code).val() != "") {
            fd.append("new_attr_" + languages[i].code, $("#new_attr_" + languages[i].code).val())
        }
        if ($("#new_attr_image_" + languages[i].code).val() != "") {
            fd.append("media_" + languages[i].code, $("#new_attr_image_" + languages[i].code).val())
        }
    }
    $("#save_new_attributes").html('<i class="fa fa-spinner fa-pulse   fa-fw"></i>');
    $("#save_new_attributes").attr('disabled', 'disabled');
    $.ajax({
        url: products_routes.create_attribute,
        method: "post",
        processData: false,
        contentType: false,
        data: fd,
        success: function (response) {
            attributes = response.data.attributes;
            update_attributes();
            $("#save_new_attributes").html('<i class="fa fa-save"></i>');
            $("#save_new_attributes").removeAttr('disabled');
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $("#save_new_attributes").html('<i class="fa fa-save"></i>');
            $("#save_new_attributes").removeAttr('disabled');
        }
    })


});
//endregion

//region update attrubutes
function update_attributes() {
    for (var i = 0; i < attributes.length; i++) {
        var name = attributes[i].name.en;
        var id = attributes[i].id;
        if ($("#attr_" + id).length == 0) {
            var html = ' <div class="col-12  d-none  attr" data-id="' + id + '" id="div_attr_' + id + '">' +
                '<div class="row">' +
                '<div class="col-11">' +
                '<div class="form-group">' +
                '<label class="form-label" for="attr_' + id + '">' + name + '</label>' +
                '<select multiple data-control="select2" name="attribute[]" class="form-control"  id="attr_' + id + '"></select>' +
                '</div>' +
                '</div>' +
                '<div class="col-1">' +
                '<button class="btn btn-sm btn-secondary add_sub_attributes btn-icon mt-9"   data-id="' + id + '" type="button"><i class="fa fa-plus"></i></button>' +
                '</div>' +
                '</div>' +
                '                        <div class="row d-none" id="div_form_add_sub_attribute_' + id + '"></div>' +
                '</div>';
            $("#attribute_fileds").append(html);
            $("#attr_" + id).select2();

            $("#main_attribute").append('<option value="' + id + '">' + name + '</option>');
            $("#main_attribute").select2();
        }


    }
    //check sub attributes
    for (var i = 0; i < attributes.length; i++) {
        var sub_attribute = attributes[i].sub_attributes;
        for (var j = 0; j < sub_attribute.length; j++) {
            if ($('#attr_' + attributes[i].id).find("option[value='" + sub_attribute[j].id + "']").length) {
            } else {
                // var newOption = new Option(sub_attribute[j].value.en, sub_attribute[j].id, false, false);

                $('#attr_' + attributes[i].id).append("<option value='" + sub_attribute[j].id + "'>" + sub_attribute[j].value.en + "</option>");


            }
        }

    }
}

//endregion

//region clear for create new attribute
function clear_input_attribute() {
    for (var i = 0; i < languages.length; i++) {
        $("#new_attr_" + languages[i].code).val("")
        $("span[data-id='img_new_attr_ar" + languages[i].code + "']").click()
    }

}

//endregion

//region create form new sub attributes
$(document).on('click', ".add_sub_attributes", function () {
    var id = $(this).data('id');
    var token = $("meta[name=csrf-token]").attr('content');
    $(".add_sub_attributes[data-id='" + id + "']").html('<i class="fa fa-spinner fa-pulse   fa-fw"></i>');
    $(".add_sub_attributes[data-id='" + id + "']").attr('disabled', 'disabled');
    $.ajax({
        url: products_routes.create_sub_attribute,
        method: "post",
        data: {
            _token: token,
            id: id,
        },
        success: function (response) {
            $("#div_form_add_sub_attribute_" + id).removeClass('d-none');
            $("#div_form_add_sub_attribute_" + id).html(response.data.view);
            $(".add_sub_attributes[data-id='" + id + "']").html('<i class="fa fa-plus"></i>');
            $(".add_sub_attributes[data-id='" + id + "']").removeAttr('disabled');
            $(".add_sub_attributes[data-id='" + id + "']").hide();
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $(".add_sub_attributes[data-id='" + id + "']").html('<i class="fa fa-plus"></i>');
            $(".add_sub_attributes[data-id='" + id + "']").removeAttr('disabled');
            $(".add_sub_attributes[data-id='" + id + "']").show();
        }
    });
});
//endregion

//region close form create new sub attribute
$(document).on('click', '.close_new_sub_attribute', function () {
    //sub  attribute id
    var id = $(this).data('id');
    $("#div_form_add_sub_attribute_" + id).html("");
    $(".add_sub_attributes[data-id='" + id + "']").show();
});
//endregion

//region send data to backend and store sub attribute  ,,,
$(document).on('click', ".save_new_sub_attribute", function () {
    //sub attribute id
    var id = $(this).data('id');
    $("#save_new_sub_attributes_" + id).html('<i class="fa fa-spinner fa-pulse   fa-fw"></i>');
    $("#save_new_sub_attributes_" + id).attr('disabled', 'disabled');
    var token = $("meta[name=csrf-token]").attr('content');
    var fd = new FormData();
    fd.append('_token', token);
    fd.append('attr_id', id);
    for (var i = 0; i < languages.length; i++) {
        if ($("#new_sub_attr_lang_" + id + "_" + languages[i].code).val() != "") {
            fd.append('sub_attr_' + languages[i].code, $("#new_sub_attr_lang_" + id + "_" + languages[i].code).val());
        }
    }
    if ($("#message_error_new_attr_" + id).val() != "") {
        fd.append('image', $("#img_new_sub_attr_" + id).val());
    }
    $.ajax({
        url: products_routes.store_sub_attribute,
        method: "post",
        data: fd,
        processData: false,
        contentType: false,
        success: function (response) {
            attributes = response.data.attributes;
            $("#close_new_sub_attributes_" + id).click();
            update_attributes();

        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText);
            error_message(response.message);
            $("#save_new_sub_attributes_" + id).html('<i class="fa fa-save"></i>');
            $("#save_new_sub_attributes_" + id).removeAttr('disabled');
        }
    })

});
//endregion
