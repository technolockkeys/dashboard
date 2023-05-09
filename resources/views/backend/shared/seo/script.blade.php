<script>
    $(document).ready(function () {
        @foreach(get_languages() as $item)
        $("#meta_title_{{$item->code}}").maxlength({
            threshold: 20,
            warningClass: "badge badge-primary",
            limitReachedClass: "badge badge-success"
        });
        $("#meta_description_{{$item->code}}").maxlength({
            threshold: 20,
            warningClass: "badge badge-primary",
            limitReachedClass: "badge badge-success"
        });
        @endforeach
    });
</script>
