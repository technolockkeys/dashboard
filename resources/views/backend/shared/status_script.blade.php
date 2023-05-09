<script>
    $(document).on("change", '.{{$class}}', function () {
      var status_id = $(this).val();

        $.ajax({
            url: "{{$url}}",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": status_id
            }, success: function (respose) {
                success_message(respose.data.message);
                if(typeof  dt  !== "undefined"){
                    dt.ajax.reload(null , false);
                }
            }, error: function (xhr, status, error) {
                var response = JSON.parse(xhr.responseText)
                error_message( response.message);
            },
        })
    });
</script>
