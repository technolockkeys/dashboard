<script>
    function check_slug_{{$name}}() {
        var slug = $("#{{$name}}").val()
        var id = $("#{{$name}}").data('id');
        var from = $("#{{$name}}").data('from');
        $.ajax({
            url: "{{$route}}",
            data: {
                "_token": "{{csrf_token()}}",
                "slug": slug,
                "id": id,
            },
            method: "post",
            success: function (response) {
                $("#message_{{$name}}").html("<b class='text-success'> <i class='fa  fa-check-circle'></i> " + response.data.message + "</b>")
            },
            error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message(response.message);
                $("#message_{{$name}}").html("<b class='text-danger'> <i class='fa fa-times-circle'></i> " + response.message + "</b>")
            },
        });
    }

    $(document).on('change', "#{{$name}}", function () {
        check_slug_{{$name}}();
    });
    $(document).on('keyup', "#{{$name}}", function () {
        check_slug_{{$name}}();
    });
    @if(!empty($from))
    $(document).on('keyup', "#{{$from}}", function () {
        console.log( $("#{{$from}}").val())
        var from = $("#{{$from}}").val();
        from = from.replace(/ /g, '-')
        $("#{{$name}}").val(from);
        check_slug_{{$name}}()
    });
    @endif
</script>
