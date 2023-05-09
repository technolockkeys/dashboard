<script>
    $(document).on("change", '.{{$class}}', function () {
        var status_id = $(this).val();

        $.ajax({
            url: "{{$url}}",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": status_id
            }, success: function (response) {

                    success_message(response.data.message)


                dt.ajax .reload(null, false);

            }, error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message( response.message);
            },
        })
    });
</script>
