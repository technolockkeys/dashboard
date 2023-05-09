<script>
    $(document).on("change", '.{{$column}}', function () {
        var value = $(this).val();

        $.ajax({
            url: "{{$url}}",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": value,
                "column": "{{$column}}"

            }, success: function (respose) {
                success_message(respose.data.message);
                if (typeof dt !== "undefined") {
                    dt.ajax.reload(null, false);
                }
            }, error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message(response.message);
            },
        })
    });
</script>
