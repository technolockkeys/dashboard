var path = '/' ;

$(document).on('click', '#upload_button', function () {
    $("#upload_tab").addClass('show active')
    $("#files_manger").removeClass('show active')
});

$(document).on('click', '#all_files', function () {
    $("#upload_tab").removeClass('show active')
    $("#files_manger").addClass('show active')
});


$(document).on('change', '.media', function () {

    var item = this;
    var id = $(item).val();
    var isChecked = $(item).is(":checked");

    if (isChecked) {
        $("label[for=media_" + id + "]").addClass('active');
    } else {
        $("label[for=media_" + id + "]").removeClass('active');
    }


});


$(document).on('click', function (e) {
    if (!$(e.target).hasClass('dropdown-toggle')) {
        $('.dropdown-toggle').dropdown("hide");
    }
});

$(document).on('click', '.dropdown-toggle', function () {
    $('.dropdown-toggle').dropdown("hide");
    $(this).dropdown("toggle");
});

$(document).ready(() => {
    MediaFileMangerGet(1);
});

$(document).on('keyup', '#media_search', function () {
    var key = $("#media_search").val();
    MediaFileMangerGet(1);
});

$(document).on('click', '#delete_media', function () {
    var ids = [];
    $('input[type=checkbox]').each(function () {
        var is_checked = $(this).is(":checked");
        if (is_checked) {
            ids.push($(this).val());
        }
    });
    if (ids.length == 0) {
         return;
    }
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
            MediaFileMangerGet(current_page)
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
         },
    });

});


$(document).on('keyup', '#media_title', function () {
    $("#error_media_form_title").html('<i class="fa fa-spinner fa-spin fa-fw"></i> ' + message_route.loading);
    var id = $('#model_media_id').val();
    var title = $('#media_title').val();
    $.ajax({
        url: media_route.check_file,
        method: "post",
        data: {
            _token: files_token,
            id: id,
            title: title,
        },
        success: function (response) {
            if (response.data.check) {
                $("#error_media_form_title").html('  ');

            } else {
                $("#error_media_form_title").html('<i class="fa fa-exclamation-triangle"></i> ' + message_route.you_cant_use_this_name);

            }
        }, error: function (xhr, status, error) {
            var response = JSON.parse(xhr.responseText)
            error_message(response.message);
        },
    })
});



// $(document).on('click', '.media_copy_link', function () {
//     var path = $(this).data('path');
//     var title = $(this).data('title');
//
//     var full_path = asset_path;
//     if (path != '/') {
//         full_path += path;
//     }
//     full_path += '/' + title;
//     copyToClipboard(full_path);
//     success_message(message_route.copy_like + " : " + full_path)
// });
//
