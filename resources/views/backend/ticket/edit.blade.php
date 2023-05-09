<div class="modal-header">
    <h5 class="modal-title">{{trans('tickets.reply.edit')}}</h5>
    <!--begin::Close-->
    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
         aria-label="Close">
        <span class="svg-icon svg-icon-2x"></span>
    </div>
    <!--end::Close-->
</div>
<form action="{{route('backend.replies.update',['id'=> $reply->id])}}" method="post" id="edit_reply"
      enctype="multipart/form-data">

    <div class="modal-body">

        @csrf
        <input type="hidden" name="edit_reply" id="edit_reply_{{$reply->id}}" required
               value="{{old('reply', $reply->reply)}}">


        <textarea id="edit_reply_text_editor{{$reply->id}}" name="edit_reply"class="mb-6">{!! $reply->reply !!}</textarea>
        @foreach(json_decode($reply->files, true) as $index => $file)
            <div class="input-group flex mb-2">
                <div class="form-control dev{{$index}}" aria-label="Username"
                     aria-describedby="basic-addon1">{{$file['image_data']}}</div>
                <span class="input-group-text  dev{{$index}}"
                      id="basic-addon2">
                <a href="{{asset($file['path'])}}/{{$file['image_data']}}" target="_blank">
             <i class="fas fa-envelope-open-text fs-4"></i>
        </a>
            </span>
                <span class="input-group-text removefile  dev{{$index}} " data-row="{{$index}}"
                      id="remove_{{$index}}">
                    <input type="hidden" value="{{$file['image_data']}}" name="old_files[]">
             <i class="fas fa-trash fs-4"></i>

            </span>
            </div>
        @endforeach
        <input class="form-control form-control-sm mt-5" id="formFileMultiple" name="files[]" type="file"
               multiple>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>

</form>
<script>
    CKEDITOR.replace(
        document.querySelector('#edit_reply_text_editor{{$reply->id}}'))

        {{--var edit_quill{{$reply->id}} = new Quill('#edit_reply_text_editor{{$reply->id}}', {--}}
    {{--    modules: {--}}
    {{--        toolbar: [--}}
    {{--            [{--}}
    {{--                header: [1, 2, false]--}}
    {{--            }],--}}
    {{--            ['bold', 'italic', 'underline'],--}}
    {{--            ['code-block']--}}
    {{--        ]--}}
    {{--    },--}}
    {{--    placeholder: 'Type your text here...',--}}
    {{--    theme: 'snow', // or 'bubble',--}}
    {{--});--}}

    {{--edit_quill{{$reply->id}}.on('text-change', function (delta, oldDelta, source) {--}}
    {{--    var about = document.getElementById('edit_reply_{{$reply->id}}');--}}


    {{--    console.log(edit_quill{{$reply->id}}.container.firstChild.innerHTML)--}}
    {{--    about.value = edit_quill{{$reply->id}}.container.firstChild.innerHTML;--}}
    {{--});--}}


    $(document).on('click', ".removefile", function () {
        var id = $(this).data('row');
        $(".dev" + id).remove();
    })

</script>