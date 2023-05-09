var selected_images_array_file_manger_media = [];
var selected_images_array_file_manger_media_file = [];
var TYPE;
var is_open_model = false;
var myDropzone;
var Page = 1;
var drop_down_is_open = false;
var CURRENT_PAGE = 1;
var WATERMARK = true;
var WIDTH = 600;
var HEIGHT = 600;
var check_delete_folder = ''
let last_search = '';
let run_change_search = true;


// const firebaseConfig = {
//     apiKey: config.apiKey,
//     authDomain: config.authDomain,
//     projectId: config.projectId,
//     storageBucket: config.storageBucket,
//     messagingSenderId: config.messagingSenderId,
//     appId: config.appId,
//     measurementId: config.measurementId,
// };


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

function copyToClipboard(text) {
    var copyText = text;

    navigator.clipboard.writeText(text);

    // var $temp = $("<input>");
    // $("body").append($temp);
    // $temp.val(text).select();
    // document.execCommand("copy");
    // $temp.remove();
}

$(document).on('click', ".btn-delete", function () {
    var message = $(this).data('message');
    var route = $(this).data('route');
    var token = $(this).data('token');
    var deleted = $(this).data('deleted');
    Swal.fire({
        html: message,
        icon: "warning",
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: 'No',
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: 'btn btn-danger'
        }
    },).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({

                url: route,
                method: "delete",
                data: {
                    _token: token,
                },
                success: function (respose) {
                    success_message(respose.data.message)
                    if (typeof dt !== "undefined") {
                        dt.ajax.reload();
                    } else {

                    }
                }, error: function (xhr, status, error) {
                    var response = JSON.parse(xhr.responseText)
                    error_message(response.message);
                },
            })
        }
    });
});


$(document).ready(function () {
    var drawerElement = document.querySelector("#drawer_media");
    var drawer = KTDrawer.getInstance(drawerElement);
    drawer.on("kt.drawer.hide", function () {
        var pageURL = $(location).attr("href");
        if (media_route.media_route != pageURL)
            $("#FileMangerModel").modal('show')
    });
    draggable_refresh()
    $("#FileMangerModelBodyReview").hide()


});

function draggable_refresh() {
    var containers = document.querySelectorAll(".draggable-zone");


    if (containers.length === 0) {
        return false;
    }

    var swappable = new Sortable.default(containers, {
        draggable: ".draggable",
        handle: ".draggable .draggable-handle",
        mirror: {
            // appendTo: selector,
            appendTo: "body",
            constrainDimensions: true
        },

    })
    swappable.on('sortable:stop', (e, x, z) => {
        // document.querySelectorAll("div" , '.draggable')
        var item_name = e.data.newContainer.dataset.name;
        var sort_data = [];

        $(".draggable_" + item_name).each((index, item) => {
            // if (sort_data.includes($(item).data('id')) == -1) {
            if (sort_data.includes($(item).data('id')) == false) {
                sort_data.push($(item).data('id'))
            }
            // }
        });
        $("#" + item_name).val(JSON.stringify(sort_data))

    });
}

function MediaFileMangerGet(page, type) {
    TYPE = type;
    Page = page;
    close_cut();
    var key = $("#media_search").val();
    $("#media_search").data('type', type);
    $('meta[name="storage_path"]').attr('content', storage_path);
    var InputName = $("#FileMangerIdInput").val();
    $("#FileManager_" + InputName).data('path', storage_path);
    var data_input = $("#" + InputName).val();
    if (Array.isArray(data_input)) {
        data_input = JSON.stringify(data_input);
    }
    $.ajax({
        url: MediaFileManger.get,
        method: "post",
        data: {
            _token: files_token,
            page: page,
            type: type,
            data_input: data_input,
            search: key,
            storage_path: storage_path
        },
        success: (response) => {
            var pagination = MediaFileMangerPagination(response.data.pagination, response.data.count_files, response.data.count_files_at_page, type);
            var html = '';
            var array_path = storage_path.split('/');
            if (array_path.length != 2) {
                html += media_folder_back();

            }
            // for (var j = 0; j < response.data.folders.length; j++) {
            //
            //     html += media_folder(response.data.folders[j]);
            // }
            $.each(response.data.folders, function (index, value) {
                html += media_folder(index, value)
            })


            for (var i = 0; i < response.data.files.length; i++) {

                selected_images_array_file_manger_media_file["" + response.data.files[i].id] = {
                    id: response.data.files[i].id,
                    title: response.data.files[i].title,
                    dis_title: response.data.files[i].dis_title,
                    path: response.data.files[i].path,
                    extension: response.data.files[i].extension,
                };
                html += media_card(response.data.files[i]);


            }
            if (response.data.data_selected.length != 0) {

                for (var i = 0; i < response.data.data_selected.length; i++) {

                    if (selected_images_array_file_manger_media_file.includes("" + response.data.data_selected[i].id) != -1) {
                        selected_images_array_file_manger_media_file["" + response.data.data_selected[i].id] = {
                            id: response.data.data_selected[i].id,
                            title: response.data.data_selected[i].title,
                            dis_title: response.data.data_selected[i].dis_title,
                            path: response.data.data_selected[i].path,
                            extension: response.data.data_selected[i].extension,
                        };

                    }

                }

            }

            $("#FileMangerModelBody").html(html);
            $("#file_manager_items_counter").html(response.data.count_files);
            $("#FileMangerModelPagination").html(pagination);
            $("#media_pagination").html(pagination);
            var array_path = storage_path.split('/');
            $("#file_manager_items_path").html("")
            storage_part_path = '/';
            if (array_path.length != 2) {
                $("#file_manager_items_path").append('<span style="cursor: pointer" data-path="' + storage_part_path + '"  class="svg-icon svg-icon-2 change_path svg-icon-primary mx-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
                    '<path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor"></path>\n' +
                    '</svg>home</span> ');

                for (var i = 0; i < array_path.length; i++) {
                    if (array_path[i] != "") {
                        storage_part_path += array_path[i] + '/'
                        $("#file_manager_items_path").append('<span style="cursor: pointer" data-path="' + storage_part_path + '"  class="svg-icon change_path svg-icon-2 svg-icon-primary mx-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
                            '<path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor"></path>\n' +
                            '</svg>' + array_path[i] + '</span>');
                    }

                }

            } else {
                $("#file_manager_items_path").append('<span class="svg-icon svg-icon-2 svg-icon-primary mx-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
                    '<path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor"></path>\n' +
                    '</svg></span> home');
            }
            set_seleted_images()
        }
    })
    $("#FileMangerModelBodyReview").hide()


}

function MediaFileMangerCard(media_file) {
    var url = null;
    if (media_file.extension == 'pdf') {
        url = asset + 'backend/media/svg/files/pdf.svg';
    } else {
        url = asset_path + media_file.path + 'thumbnail/' + media_file.title
    }
    const index = selected_images_array_file_manger_media.indexOf(media_file.id);
    var active = '';
    if (index > -1) {
        active = 'active';

    }


    var html = '<input type="checkbox" class="btn-check " name="media[]" value="' + media_file.id + '" data-id="' + media_file.id + '"/>' +
        '<div class="col-md-4 col-6 col-lg-2 col-xl-2 mt-2 MediaFileMangerFile"  data-id="' + media_file.id + '"   data-extension="' + media_file.extension + '"   data-title="' + media_file.title + '" data-dis-title="' + media_file.display_title + '" data-path="' + media_file.path + '">' +
        '<label class="btn btn-outline btn-outline-dashed btn-outline-default  ' + active + '  d-flex align-items-center" data-id="' + media_file.id + '"   for="media_' + media_file.id + '">' +
        '<div class="card w-100 " style="background-color: rgba(255,255,255,0)">' +

        '<div class="card-body p-0 d-flex justify-content-center text-center flex-column ">' +
        '<span class="text-gray-800 text-hover-primary d-flex flex-column">' +
        '<div class="symbol symbol-75px mb-5">' +
        '<img style="z-index: 100" class="image_modal" src="' + url + '" alt="">' +
        '</div>' +
        '<div   class="fs-5 fw-bolder mb-2">' + media_file.display_title + '</div>' +
        '</span></div></div></label></div>';
    $('.dropdown-toggle_' + media_file.id).dropdown()
    return html;
}

function MediaFileMangerPagination(current_page, total_items, count_item_at_page, type) {
    var has_per = false;
    var has_next = false;
    TYPE = type;
    CURRENT_PAGE = current_page;
    has_next = (parseInt(current_page) * parseInt(count_item_at_page)) < parseInt(total_items);
    has_per = (parseInt(current_page) * parseInt(count_item_at_page)) > parseInt(count_item_at_page);

    var html = '<div class="btn-group mr-2" role="group" aria-label="First group">';
    if (has_per && (parseInt(current_page) - 1) > 1) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) - 2) + ' ,' + "'" + type + "'" + ')" type="button" data-id="' + (parseInt(current_page) - 2) + '"  class="btn pagination_media btn-info   "><i class="fa fa-angle-left"></i> </button>';
    }
    if (has_per) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) - 1) + ' ,' + "'" + type + "'" + ')" type="button" data-id="' + (parseInt(current_page) - 1) + '"  class="btn pagination_media btn-info   ">' + (parseInt(current_page) - 1) + '</button>';
    }
    html += '  <button onclick="MediaFileMangerGet(' + parseInt(current_page) + ' ,' + "'" + type + "'" + ')" type="button" data-id="' + parseInt(current_page) + '"  class="btn pagination_media btn-info active ">' + parseInt(current_page) + '</button>';
    if (has_next) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) + 1) + ' ,' + "'" + type + "'" + ')" type="button" data-id="' + (parseInt(current_page) + 1) + '"  class="btn pagination_media btn-info  ">' + (parseInt(current_page) + 1) + '</button>';
    }
    if (has_next && ((parseInt(current_page) + 1) * parseInt(count_item_at_page)) < parseInt(total_items)) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) + 2) + ' ,' + "'" + type + "'" + ')" type="button" data-id="' + (parseInt(current_page) + 2) + '"  class="btn pagination_media btn-info  "><i class="fa fa-angle-right"></i> </button>';
    }
    html += '</div>';

    return html;
}

$(document).on('click', '.single_media', function () {
    selected_images_array_file_manger_media = [];
    storage_path = '/';
    storage_path = $(this).data('path')
    $("#FileMangerModel").modal('show');
    $("#close_image_preview").click();
    var datatype = $(this).data('type');
    TYPE = datatype;

    var autoClose = $(this).data('auto-close');
    var InputName = $(this).data('input-name');
    selected_images_array_file_manger_media.push($("#" + InputName).val());
    WATERMARK = $(this).data('watermark');
    WIDTH = $(this).data('width');

    WIDTH = (WIDTH !== 'undefined' && WIDTH != '') ? WIDTH : 600
    HEIGHT = $(this).data('height');
    HEIGHT = (HEIGHT !== 'undefined' && HEIGHT != '') ? HEIGHT : 600
    // (WATERMARK)
    $('meta[name="watermark"]').attr('content', WATERMARK + '')
    var CountImage = $(this).data('count-image');
    $("#FileMangerCountImage").val(CountImage);
    $("#FileMangerAutoClose").val(autoClose);
    $("#FileMangerIdInput").val(InputName);
    MediaFileMangerGet(1, datatype)
});

function MediaFileMangerSelected(id, path, title, dis_title, type) {

    var CountImage = $("#FileMangerCountImage").val();
    var autoClose = $("#FileMangerAutoClose").val();
    var InputName = $("#FileMangerIdInput").val();
    var imageUrl = asset_path + path + title;
    if (type == 'pdf') {
        imageUrl = asset + 'backend/media/svg/files/pdf.svg';
    }
    var obj = "#FileManager_" + InputName;
    $(obj).attr('style', 'background-image:url("' + imageUrl + '");background-size:  100% 100%');
    $("#" + InputName).val(id);
    $("#remove_item_single_" + InputName).css('display', 'grid')
    if (autoClose == 1 && drop_down_is_open == false) {
        $("#FileMangerModel").modal('hide');
    }
    // $("#" + InputName).val(id);
    drop_down_is_open = false;


}

$(document).on('click', '.MediaFileMangerFile', function () {
    if (drop_down_is_open == false) {
        // $(this).children().addClass('active');
        var id = $(this).data('id');
        var path = $(this).data('path');
        var extension = $(this).data('extension');
        var title = $(this).data('title');
        var dis_title = $(this).data('dis-title');
        var CountImage = $("#FileMangerCountImage").val();
        var autoClose = $("#FileMangerAutoClose").val();
        var InputName = $("#FileMangerIdInput").val();

        WATERMARK = $(this).data('watermark');
        // (WATERMARK)
        $('meta[name="watermark"]').attr('content', WATERMARK + '')

        var type = $("#media_search").data('type');
        TYPE = type;

        if (autoClose == 1) {
            MediaFileMangerSelected(id, path, title, dis_title, extension);
        }
        if (selected_images_array_file_manger_media.includes(id) == false) {
            selected_images_array_file_manger_media.push(id);
            selected_images_array_file_manger_media_file["" + id] = {
                id: id,
                title: title,
                dis_title: dis_title,
                path: path,
                extension: extension,
            };
            // $(this).children().addClass('active');
            // $(this).children().addClass('active');
            $("#media_" + id).attr('checked', 'checked');


        } else {
            var index = selected_images_array_file_manger_media.indexOf(id);

            if (index > -1) {
                selected_images_array_file_manger_media.splice(index, 1); // 2nd parameter means remove one item only
                $(this).children().removeClass('active');
                $("#media_" + id).removeAttr('checked');
            }

        }
        $("#media_" + id).trigger('change');

    }
    drop_down_is_open = false;
});

$(document).on('click', '.MediaFileMangerMultiImage', function () {
    $("#FileMangerModel").modal('show');
    $("#close_image_preview").click();
    storage_path = '/';
    var InputName = $(this).data('input-name');
    storage_path = $("#FileManager_" + InputName).data('path');

    var type = $(this).data('type');
    TYPE = type;
    var data = $("#" + InputName).val();
    var is_array = Array.isArray(data);
    if (data !== undefined) {
        if (!is_array) {
            var media_data = JSON.parse(data);
        } else {
            var media_data = data;
        }
    } else {
        var media_data = [];
    }

    selected_images_array_file_manger_media = media_data;


    var autoClose = 0;
    var CountImage = -1;
    $("#FileMangerCountImage").val(CountImage);
    $("#FileMangerAutoClose").val(autoClose);
    $("#FileMangerIdInput").val(InputName);

    MediaFileMangerGet(1, TYPE)


});

$(document).on('click', '#MediaFileMangerSaveMultiMedia', function () {
    var InputName = $("#FileMangerIdInput").val();
    var html = "<div class='row draggable-zone w-100' data-name='" + InputName + "'>";
    var size = $("#FileManager_" + InputName).data('size');
    for (var i = 0; i < selected_images_array_file_manger_media.length; i++) {
        if (typeof selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]] !== "undefined") {
            var id = selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]].id;
            var title = selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]].title;
            var dis_title = selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]].dis_title;
            var path = selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]].path;
            var extension = selected_images_array_file_manger_media_file[selected_images_array_file_manger_media[i]].extension;
            console.log("InputName " + InputName)
            html += selected_media_card(id, title, dis_title, path, extension, InputName, size)
        }
    }
    html += upload_media_card(InputName, size)
    html += "</div>";
    $("#media_search").data('type', "");

    var CountImage = $("#FileMangerCountImage").val();
    var autoClose = $("#FileMangerAutoClose").val();
    var InputName = $("#FileMangerIdInput").val();
    $("#FileManager_" + InputName).html(html).data('input-name', InputName);
    draggable_refresh()
    $("#" + InputName).val(JSON.stringify(selected_images_array_file_manger_media));
    $("#" + InputName).data('path', storage_path);
    $("#FileMangerModel").modal('hide');

});
$(document).on('click', '.remove-media', function (event) {
    var id = $(this).data('id');
    var inputNameMedia = $(this).data('input-name');
    var InputName = $("#FileMangerIdInput").val();
    var data = $("#" + inputNameMedia).val();
    selected_images_array_file_manger_media = JSON.parse(data);
    const index = selected_images_array_file_manger_media.indexOf(id);
    if (index > -1) {
        selected_images_array_file_manger_media.splice(index, 1);
    }
    $("#card_media_" + id + "_" + inputNameMedia).remove();
    if (selected_images_array_file_manger_media.length == 0 || selected_images_array_file_manger_media.length == "0") {
        $("#FileManager_" + InputName).html(empty_media_card(InputName));
    }
    $("#" + inputNameMedia).val(JSON.stringify(selected_images_array_file_manger_media));
});
$(document).on('click', '.remove_media_single', function () {
    var id = $(this).data('id');
    $("#remove_item_single_" + id).css('display', 'none')
    $("#" + id).val("");
})
$(document).on('change', "#media_search", function () {
    var type = $(this).data('type');
    TYPE = type;
    var key = $("#media_search").val();
    if (key != last_search && run_change_search === true) {
        key = last_search;
        MediaFileMangerGet(1, type)
    }
});
$(document).on('keyup', "#media_search", function () {
    var type = $(this).data('type');
    TYPE = type;
    var key = $("#media_search").val();
    if ((key != last_search || key == '') && run_change_search === true) {
        key = last_search;
        MediaFileMangerGet(1, type)
    }
});

function selected_media_card(id, title, dis_title, path, extension, InputName, size = 'large') {

    var url = null;
    if (extension == 'pdf') {
        url = asset + 'backend/media/svg/files/pdf.svg';
    } else {
        url = asset_path + path + title;
    }
    var class_style = 'col-1';
    if (size == 'small') {
        class_style = 'col-4';
    }

    return '<div class="' + class_style + ' mt-9 draggable draggable_' + InputName + ' " data-id="' + id + '" id="card_media_' + id + "_" + InputName + '">' +
        '<div class="image-input image-input-outline w-100 mh-100" data-kt-image-input="true" style="  aspect-ratio : 1 / 1 !important;    background-size: cover;background-position: center;background-image: url(' + "'" + url + "'" + ')">' +
        '<a data-auto-close="1"   data-count-image="1" type="button" class=" draggable-handle btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow single_media" data-id="logo" data-type="image" data-watermark="true" data-wa2termark="true" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" ><i class="bi bi-arrows-move"></i></a>' +
        '<div class="image-input-wrapper w-100 mh-100" style=" height:unset !important;  aspect-ratio : 1 / 1 !important;    background-size: cover;  background-position: center; background-image: url(' + "'" + url + "'" + ')"></div>' +
        '<button type="button" style="z-index: 21"  data-input-name="' + InputName + '" class="btn btn-icon btn-circle btn-active-color-primary remove-media w-25px h-25px bg-body shadow" data-id="' + id + '" data-kt-image-input-action="remove"  ><i class="bi bi-x fs-2"></i></button>' +
        '</div>' +

        '</div>';
}

function empty_media_card(name) {
    var html = "<div class='row MediaFileMangerMultiImage' data-input-name='" + name + "'>" +
        "<div class='col-12'>" +
        "<img src='" + asset + 'backend/media/svg/files/upload.svg' + "' class='mb-5' alt=''>" +
        "<a href='#' class='text-hover-primary fs-5 fw-bolder mb-2'>" + messageFileManger.select_media + "</a>" +
        "<div class='fs-7 fw-bold text-gray-400'>" + messageFileManger.click_to_select_files_here + "</div>" +
        "</div>" +
        "</div>";
    return html;
}

function upload_media_card(name, size = 'large') {
    var class_style = 'col-1';
    if (size == 'small') {
        class_style = 'col-4';

    }

    var html =
        '<div class="' + class_style + '  mt-9 MediaFileMangerMultiImage"  data-input-name="' + name + '"   >' +
        '<label class="btn d-flex align-items-center"   style="width: 100% !important;padding: 0px;"   >' +
        '<div class="card w-100 " style="background-color: rgba(255,255,255,0)">' +
        '<div class="card-body p-0 d-flex justi MediaFileMangerMultiImage fy-content-center text-center flex-column " data-input-name="' + name + '"  >' +
        '<span class="text-gray-800 text-hover-primary d-flex flex-column">' +
        '<div class="    mb-5">' +
        '<img width="100%" src="' + asset + 'backend/media/icons/duotune/files/fil005.svg' + '" alt="">' +
        '</div>' +
        '<div style="font-size: small"  class="  fw-bolder mb-2">' + messageFileManger.select_media + '</div>' +
        '</span></div></div></label></div>';

    return html;

}

myDropzone = new Dropzone("#dropzonejs", {
    url: media_route.upload, // Set the url for your upload script location
    paramName: "file", // The name that will be used to transfer the file
    // maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        "storage_path": $('meta[name="storage_path"]').attr('content'),
    },


    accept: function (file, done) {

        if (file.name == "wow.jpg") {
            done("Naha, you don't.");
        } else {
            done();
            success_message(message_route.successful_upload);

            setTimeout(function () {
                MediaFileMangerGet(1, TYPE)
                // MediaFileMangerGet(1);
            }, 1000)
        }
    },

});
myDropzone.on('sending', function (file, xhr, formData) {
    formData.append('storage_path', storage_path);
    formData.append('watermark', WATERMARK);
    formData.append('width', WIDTH);
    formData.append('height', HEIGHT);
});
myDropzone.on("complete", function (file) {
    setTimeout(function () {
        myDropzone.removeFile(file);
    }, 3000)
});

function managerFile_update_files(page) {

    var key = $("#media_search").val();

    $('meta[name="storage_path"]').attr('content', storage_path);
    $.ajax({
        url: media_route.files_route,
        method: "post",
        data: {
            _token: files_token,
            page: page,
            search: key,
            "storage_path": storage_path
        },
        success: (response) => {
            var pagination = pagination_html(response.data.pagination, response.data.count_files, response.data.count_files_at_page)
            var html = '';
            var array_path = storage_path.split('/');
            if (array_path.length != 2) {
                html += media_folder_back();

            }
            $.each(response.data.folders, function (index, value) {
                html += media_folder(index, value)
            })


            for (var i = 0; i < response.data.files.length; i++) {
                html += media_card(response.data.files[i]);
            }


            $("#container_media").html(html);
            $("#media_pagination").html(pagination);
            $("#file_manager_items_counter").html(response.data.count_files)


        }
    })

}

function pagination_html(current_page, total_items, count_item_at_page) {
    var has_per = false;
    var has_next = false;

    has_next = (parseInt(current_page) * parseInt(count_item_at_page)) < parseInt(total_items);
    has_per = (parseInt(current_page) * parseInt(count_item_at_page)) > parseInt(count_item_at_page);
    var html = '<div class="btn-group mr-2" role="group" aria-label="First group">';
    if (has_per && (parseInt(current_page) - 1) > 1) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) - 2) + ')" type="button" data-id="' + (parseInt(current_page) - 2) + '"  class="btn pagination_media btn-info   "><i class="fa fa-angle-left"></i> </button>';
    }
    if (has_per) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) - 1) + ')" type="button" data-id="' + (parseInt(current_page) - 1) + '"  class="btn pagination_media btn-info   ">' + (parseInt(current_page) - 1) + '</button>';
    }
    html += '  <button onclick="MediaFileMangerGet(' + parseInt(current_page) + ')" type="button" data-id="' + parseInt(current_page) + '"  class="btn pagination_media btn-info active ">' + parseInt(current_page) + '</button>';
    if (has_next) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) + 1) + ')" type="button" data-id="' + (parseInt(current_page) + 1) + '"  class="btn pagination_media btn-info  ">' + (parseInt(current_page) + 1) + '</button>';
    }
    if (has_next && ((parseInt(current_page) + 1) * parseInt(count_item_at_page)) < parseInt(total_items)) {
        html += '  <button onclick="MediaFileMangerGet(' + (parseInt(current_page) + 2) + ')" type="button" data-id="' + (parseInt(current_page) + 2) + '"  class="btn pagination_media btn-info  "><i class="fa fa-angle-right"></i> </button>';
    }
    html += '</div>';
    return html;
}

function media_card(media_file) {
    $("#FileManager_" + $("#FileMangerIdInput").val()).val();

    var html =
        '<div class="col-md-3 mt-1 col-6 col-lg-2 col-xl-2 MediaFileMangerFile  "  data-id="' + media_file.id + '"   data-extension="' + media_file.extension + '"   data-title="' + media_file.title + '" data-dis-title="' + media_file.display_title + '" data-path="' + media_file.path + '">' +
        '<input type="checkbox" class=" btn-check  media" name="media[]" value="' + media_file.id + '" id="media_' + media_file.id + '"/>' +
        '<label class="btn btn-outline btn-outline-dashed btn-outline-default   d-flex align-items-center"  for="media_' + media_file.id + '" id="lebal_media_' + media_file.id + '">' +
        '<div class="card w-100 " style="background-color: rgba(255,255,255,0)">' +
        '<div class="dropdown" style="text-align: end ;z-index: 100"><button type="button" class="btn btn-light-primary btn-icon btn-sm dropdown-toggle" data-toggle="dropdown">' +
        '</button>' +
        '<div class="dropdown-menu">';
    if (media_permission.edit == 1) {
        html += '<span class="dropdown-item media_details" data-id="' + media_file.id + '">' + message_route.details + '</span>';
    }
    if (media_permission.delete == 1) {
        html += '<span class="dropdown-item media_delete" data-id="' + media_file.id + '">' + message_route.delete + '</span>';
    }
    if (media_permission.delete == 1) {
        html += '<span class="dropdown-item cut_to" data-id="' + media_file.id + '">' + message_route.cut + '</span>';
    }

    html += '<span class="dropdown-item media_copy_link " data-id="' + media_file.id + '" data-title="' + media_file.title + '" data-path="' + media_file.path + '">' + message_route.copy_like + '</span>';
    var meida_image = '';
    if (media_file.extension == 'pdf') {
        meida_image = asset + 'backend/media/svg/files/pdf.svg';
    } else {
        meida_image = asset_path + media_file.path + media_file.title
    }
    html += '</div>' +
        '</div>' +
        '<div class="card-body p-0 d-flex justify-content-center text-center flex-column ">' +
        '<span class="text-gray-800 text-hover-primary d-flex flex-column">' +
        '<div class="symbol symbol-75px ">' +
        '<img style="z-index: 100; " class="image_modal"  onerror="this.src=' + "'" + error_image + "'" + '" src="' + meida_image + '" alt="">' +
        '</div>' +
        '<div   class="  small fw-bolder mb-1">' + media_file.display_title + '</div>' +
        '</span></div></div></label></div>';
    $('.dropdown-toggle_' + media_file.id).dropdown()
    return html;
}

//region folder

function media_folder(folder_name, path) {
    var html = '<input type="checkbox" data-path="' + path + '" class="btn-check folder" name="folder[]" value="' + encodeURI(folder_name) + '" id="media_' + encodeURI(folder_name) + '"/>' +
        '<div class="col-md-3 col-6 col-lg-2 col-xl-2 mt-1">' +
        '<label class="btn btn-outline btn-outline-dashed btn-outline-default   d-flex align-items-center"  for="media_' + encodeURI(folder_name) + '">' +
        '<div class="card w-100 " style="background-color: rgba(255,255,255,0);z-index: 1">' +
        '<div class="dropdown" style="text-align: end"><button type="button" class="btn btn-light-primary btn-icon btn-sm dropdown-toggle dropdown-toggle_media_' + encodeURI(folder_name) + '" data-toggle="dropdown">' +
        '</button>' +
        '<div class="dropdown-menu">';

    if (media_permission.delete == 1) {
        html += '<span class="dropdown-item delete_folder"  style="z-index: 112684312653561243412563412543;" data-path="' + path + '" data-folder="' + folder_name + '">' + message_route.delete + '</span>';
    }
    var meida_image = '';
    meida_image = asset + 'backend/media/svg/files/folder-document.svg';

    html += '</div>' +
        '</div>' +
        '<div class="card-body p-0 d-flex justify-content-center text-center flex-column " style="z-index: 1;">' +
        '<span class="text-gray-800 text-hover-primary d-flex flex-column">' +
        '<div class="symbol symbol-75px">' +
        '<img src="' + meida_image + '" alt="">' +
        '</div>' +
        '<div   class="fs-5 fw-bolder mb-1" style="font-size: small !important;">' + folder_name + '</div>' +
        '</span></div></div></label></div>';

    return html;
}

function media_folder_back() {
    var folder_name = 'Go Back'
    var html = '<input type="checkbox" class="btn-check folder_back" name="folder[]" value="' + encodeURI(folder_name) + '" id="media_' + encodeURI(folder_name) + '"/>' +
        '<div class="col-md-3 col-6 col-lg-2 col-xl-2 mt-1">' +
        '<label class="btn btn-outline btn-outline-dashed btn-outline-default   d-flex align-items-center"  for="media_' + encodeURI(folder_name) + '">' +
        '<div class="card w-100 " style="background-color: rgba(255,255,255,0)">' +
        '<div class="dropdown p-2" style="text-align: end">' +
        '<div class="dropdown-menu">';

    if (media_permission.delete == 1) {
        // html += '<span class="dropdown-item media_delete" data-id="' + encodeURI(folder_name) + '">' + message_route.delete + '</span>';
    }
    var meida_image = '';


    meida_image = asset + 'backend/media/icons/duotune/arrows/arr077.svg';

    html += '</div>' +
        '</div>' +
        '<div class="card-body p-0 d-flex justify-content-center text-center flex-column ">' +
        '<span class="text-gray-800 text-hover-primary d-flex flex-column">' +
        '<div class="symbol symbol-75px mb-5">' +
        '<img src="' + meida_image + '" alt="">' +
        '</div>' +
        '<div   class="small fw-bolder mb-2">' + folder_name + '</div>' +
        '</span></div></div></label></div>';
    // $('.dropdown-toggle').dropdown()
    // $('.dropdown-toggle_' +encodeURI(folder_name)  ).dropdown()
    return html;
}

//event create new folder
$(document).on('click', '#btn_create_new_folder', function () {
    $("#create_new_folder").removeClass('d-none');
    $("#create_new_folder").show();
    $("#base_control").addClass('d-none');
    $("#base_control").hide();
});
//close create folder
$(document).on('click', "#close_create_new_folder", function () {
    $("#create_new_folder").addClass('d-none');
    $("#base_control").removeClass('d-none');
    $("#create_new_folder").hide();

    $("#base_control").show();
});

$(document).on('submit', '#form_create_new_folder', function (event) {
    event.preventDefault();
    $.ajax({
        url: media_route.create_new_folder,
        method: "post",
        data: {
            _token: files_token,
            path: storage_path,
            folder_name: $("#folder_name").val()
        }, success: function (response) {
            success_message("created folder");
            $("#folder_name").val("")
            MediaFileMangerGet(1);
            // managerFile_update_files(1);
            $("#close_create_new_folder").click()
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);


        },
    })
});
$(document).on('click', '.delete_folder', function () {
    var path = $(this).data('path');
    console.log("delete url " + media_route.delete_folder)
    console.log("folder name " + folder)
    console.log("path " + path)
    var folder = $(this).data('folder');
    check_delete_folder = folder;
    $.ajax({
        url: media_route.delete_folder,
        method: "post",
        data: {
            _token: files_token,
            path: path,
            folder_name: folder
        },
        success: function (response) {
            success_message("successfully deleted")
            MediaFileMangerGet(Page, TYPE)
        }
    });
})

$(document).on('click', '#btn_create_new_folder', function () {

    $("#create_new_folder").removeClass('d-none');
});
$(document).on('click', ".folder", function () {
    $(this).removeClass('folder')
    if (check_delete_folder != $(this).val()) {
        storage_path += $(this).val() + '/';
        var new_path = $(this).data('path').split('/');
        storage_path = '/';
        $.each(new_path, function (index, value) {
            storage_path += value + '/';

        })
        // storage_path  = $(this).data('path');

        $('meta[name="storage_path"]').attr('content', storage_path);

        if (typeof TYPE === 'undefined') {
            TYPE = null;
        }
        last_search = "";
        run_change_search = false;
        $("#media_search").val("")
        MediaFileMangerGet(1, TYPE);
        run_change_search = true;

    }

});
$(document).on('click', ".folder_back", function () {
    var array_path = storage_path.split('/');
    if (array_path.length == 2) {
        storage_path = '/';
    } else {
        var array_filter = [];


        storage_path = '/';
        for (var i = 0; i < array_path.length - 2; i++) {
            if (array_path[i] != '') {
                array_filter.push(array_path[i]);
            }
        }
        if (array_filter.length != 0) {
            storage_path = "/" + array_filter.join('/') + "/";
        } else {
            storage_path = "/";
        }
    }
    $('meta[name="storage_path"]').attr('content', storage_path);

    MediaFileMangerGet(1, TYPE);
});
//endregion

//region media details..

$(document).on('click', '.dropdown-toggle', function () {
    $('.dropdown-toggle').dropdown('hide')
    drop_down_is_open = true;
    $(this).dropdown('toggle')
});

$(document).on('submit', "#media_edit_form", function (event) {
    event.preventDefault();
    $("#model_save_btn_model").html('<i class="fa fa-spinner fa-spin fa-fw"></i> ' + message_route.loading).attr("disabled", 'disabled');
    var pageURL = $(location).attr("href");
    ;
    var close_model = (pageURL === media_route.media_route);
    $.ajax({
        url: media_route.update,
        method: "post",
        data: $("#media_edit_form").serialize(),
        success: function (response) {
            $("#details_model").modal('hide');
            var drawerElement = document.querySelector("#drawer_media");
            var drawer = KTDrawer.getInstance(drawerElement);
            drawer.hide();
            success_message(response.data.message)
            MediaFileMangerGet(CURRENT_PAGE);

            if (close_model == false) {
                $("#FileMangerModel").modal('show')
            }

            $('.dropdown-toggle').dropdown('hide')
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            if (close_model == false) {
                $("#FileMangerModel").modal('show')
            }

        },

    })
});

$(document).on('click', '.media_details', function () {
    var id = $(this).data('id');
    var pageURL = $(location).attr("href");
    if (pageURL != media_route.media_route) {
        is_open_model = true;
    }

    $("#FileMangerModel").modal('hide')
    $.ajax({
        url: media_route.details,
        method: "post",
        data: {
            _token: files_token,
            id: id,
        }, success: function (response) {
            // $("#media_modal_body").html(response.data.view);
            $("#drawer_media").html(response.data.view);
            var drawerElement = document.querySelector("#drawer_media");
            var drawer = KTDrawer.getInstance(drawerElement);

            drawer.show();
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
        },
    })
});
//endregion

//region copy
var cut_file = null;
var cut_storage_path = null;
let default_value = '';
$(document).on('click', '.cut_to', function () {
    //file id ..
    if (cut_storage_path == null) {
        cut_storage_path = '/';
    }
    cut_file = $(this).data('id');
    $("#model_footer_file_manger").hide();
    $("#base_control").hide();
    $("#create_new_folder").hide();
    $("#dev_gallery").hide();
    $("#hide_when_cut").attr('style','display:none !important');;
    get_cut_storage_path('/');
});

function get_cut_storage_path(name) {
    drop_down_is_open = true;
    if (name == '../') {
        var array_path = cut_storage_path.split('/');
        if (array_path.length == 2) {
            cut_storage_path = '/';
        } else {
            var array_filter = [];
            cut_storage_path = '/';
            for (var i = 0; i < array_path.length - 2; i++) {
                if (array_path[i] != '') {
                    array_filter.push(array_path[i]);
                }
            }
            if (array_filter.length != 0) {
                cut_storage_path = "/" + array_filter.join('/');
            } else {
                cut_storage_path = "/";
            }
        }
    }

    $.ajax({
        url: media_route.cut_get_folder_route,
        method: "post",
        data: {
            _token: files_token,
            path: cut_storage_path,
            file_name: name
        },
        success: function (response) {
            var folders = response.data.folders;
            default_value = response.data.default_image;
            var files = response.data.files;
            var html = '';
            for (var i = 0; i < folders.length; i++) {
                html += cut_media_folder(folders[i], 'folder', '');
            }
            for (var i = 0; i < files.length; i++) {
                html += cut_media_folder(files[i].title, files[i].type, cut_storage_path + name + '/');
            }
            $("#table_cut").html(html);
            if (name != '../' && name != '/') {
                cut_storage_path += name + '/';

            }
            $("#div_cut").show();
            $(".file_manger_content").hide();
        }
    })

}

function cut_media_folder(name, type = 'folder', path) {
    var folder_item = '';
    if (type == 'folder') {
        folder_item = '<tr class="cut_open_dir" style="cursor: pointer;" data-name="' + name + '" ><td><div class="d-flex align-items-center">' +
            '<span class="svg-icon svg-icon-2x svg-icon-primary me-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"></path><path d="M9.2 3H3C2.4 3 2 3.4 2 4V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H12L10.4 3.60001C10.2 3.20001 9.7 3 9.2 3Z" fill="currentColor"></path></svg></span>' +
            '<b class="text-gray-800 text-hover-primary">' + name + '</b></div></td></tr>';
    } else if (type == 'file') {
        folder_item = '<tr  style="cursor: pointer;" ><td><div class="d-flex align-items-center">' +
            '<span class="svg-icon svg-icon-2x svg-icon-primary me-4"><img src="' + asset + 'storage' + path + name + '" onerror="this.src='+"'" +default_value+"'"+'" width="20" alt=""></span>' +
            '<b class="text-gray-800 text-hover-primary">' + name + '</b></div></td></tr>';
    }
    return folder_item;
}

$(document).on('click', '.cut_open_dir', function () {
    var name = $(this).data('name');
    get_cut_storage_path(name);
});

function close_cut() {
    cut_storage_path = null;
    cut_file = null;
    $('.dropdown-toggle').dropdown('hide')
    $("#div_cut").hide();
    $("#base_control").show();
    $("#create_new_folder").hide();
    $("#dev_gallery").show();
    $("#hide_when_cut").attr('style','display: flex  !important');
    $(".file_manger_content").show();
    $("#model_footer_file_manger").show()
    $("#close_image_preview").click();
}

$(document).on('click', '.paste', function () {
    $.ajax({
        url: media_route.cut_set_route,
        method: "post",
        data: {
            _token: files_token,
            path: cut_storage_path,
            file_id: cut_file
        },
        success: function (response) {
            close_cut();
            MediaFileMangerGet(1, TYPE);
        }

    })
});

//endregion

//region change path
$(document).on('click', '.change_path', function () {
    storage_path = $(this).data('path')
    if (run_change_search == true) {
        MediaFileMangerGet(1);
    }
});
//endregion


//region preview
$(document).on('click', ".image_modal", function () {
    drop_down_is_open = true;
    var src = $(this).attr('src');
    $("#image_preview").attr('src', src);
    $("#FileMangerModelBody").hide();
    $("#dev_gallery").hide();
    $("#model_footer_file_manger").hide();
    $("#file_manger_content_languages").hide();
    $("#FileMangerModelBodyReview").show()
});
$(document).on('click', "#close_image_preview", function () {
    drop_down_is_open = true;
    // var src = $(this).attr('src');
    // $("#image_preview").attr('src' , src)
    $("#FileMangerModelBody").show();
    $("#dev_gallery").show();
    $("#model_footer_file_manger").show();
    $("#file_manger_content_languages").show();

    $("#FileMangerModelBodyReview").hide()
});
//endregion

//region read notification
$(document).on('click', '.read', function () {
    var id = $(this).data('id');
    $.ajax({
        url: read_notifications_route,
        method: "post",
        data: {
            '_token': csrf_token,
            'id': id,
        }, success: (response) => {
        }
    });
});
//endregion

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
            if (response.responseJSON.errors) {
                $('#shipping-weight-error').text(response.responseJSON.errors.shipping_weight);
                $('#shipping-country-error').text(response.responseJSON.errors.shipping_country);
            } else
                $('#shipping-country-error').text(response.responseJSON.message);

        }
    });
});

//endregion

function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

$(document).on('change', '.media', function () {
    var id_changed = $(this).val();
    if ($(this).is(':checked')) {
        $('#lebal_media_' + id_changed).addClass('active');
        const index = selected_images_array_file_manger_media.indexOf(parseInt(id_changed));

        if (index === -1) {
            selected_images_array_file_manger_media.push(parseInt(id_changed));
        }
    } else {
        $('#lebal_media_' + id_changed).removeClass('active');

    }

});

function set_seleted_images() {

    for (var i = 0; i < selected_images_array_file_manger_media.length; i++) {
        $("#media_" + selected_images_array_file_manger_media[i]).attr('checked', 'checked').trigger('change').change();
        // $('#lebal_media_' + selected_images_array_file_manger_media[i] ).addClass('active');
    }

}


$(document).on('click', '.media_delete', function () {
    var id = $(this).data('id');
    var ids = [id]
    var old_v = $("#FileMangerAutoClose").val();
    $("#FileMangerAutoClose").val(0);
    drop_down_is_open = false;
    $.ajax({
        url: media_route.delete,
        method: "post",
        data: {
            _token: files_token,
            ids: ids,
        },
        success: function (response) {
            success_message(response.data.message)
            var current_page = $('.pagination_media .active').text()
            MediaFileMangerGet(current_page, TYPE)
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
            $("#FileMangerAutoClose").val(old_v);
        },
    });

});
$(document).on('click', '.media_copy_link', function () {
    var path = $(this).data('path');
    var title = $(this).data('title');

    var full_path = asset_path;
    if (path != '/') {
        full_path += path;
    }
    full_path += title;
    copyToClipboard(full_path);
    success_message(message_route.copy_like + " : " + full_path)
});

