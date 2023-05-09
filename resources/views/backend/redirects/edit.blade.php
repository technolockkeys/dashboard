<div class="modal-header">
    <h3 class="modal-title">{{trans('backend.redirect.edit')}}</h3>

    <!--begin::Close-->
    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
         aria-label="Close">
        <span class="svg-icon svg-icon-1"></span>
    </div>
    <!--end::Close-->
</div>
<form action="{{route('backend.redirects.update',$redirect->id)}}" method="post" id="edit_redirect_form" class="edit_redirect_form">
    @csrf
    <div class="modal-body">
        <div class="row my-6">
            <label for="old_url"
                   class="col-lg-12 col-form-label required fw-bold fs-6">{{trans('backend.redirect.old_url')}}</label>
            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                <input type="text" id="old_url" name="old_url"
                       class="form-control form-control-lg form-control-solid"
                       placeholder="{{trans('backend.redirect.old_url')}}"
                       value="{{old('old_url', $redirect->old_url)}}">
                <b class="text-danger" id="old_url_error"> </b>

                @error('old_url')<b class="text-danger"><i
                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
            </div>
        </div>
        <div class="row my-6">
            <div class="col-lg-12 fv-row fv-plugins-icon-container">
                <label for="new_url"
                       class="col-lg-12 col-form-label required fw-bold fs-6">{{trans('backend.redirect.new_url')}}</label>
                <input type="text" id="new_url" name="new_url"
                       class="form-control form-control-lg form-control-solid"
                       placeholder="{{trans('backend.redirect.new_url')}}"
                       value="{{old('app_url', $redirect->new_url)}}">
                <b class="text-danger" id="new_url_error"> </b>

                @error('new_url')<b class="text-danger"><i
                            class="las la-exclamation-triangle"></i> {{$message}}</b> @enderror
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-light" data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
        <button type="submit" class="btn btn-primary">{{trans('backend.global.save')}}</button>
    </div>
</form>
