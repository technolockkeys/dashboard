<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2>        <label for="image" class="required">{{trans('backend.product.gallery')}}</label></h2>
            </div>
        </div>
        <div class="card-body text-center pt-0">
             {!! multi_images('gallery' , media_file(old('gallery' ) ), old('gallery' ) , 'image' ,true) !!}
            <br>
            @error('image')<b class="text-danger">{{ $message }}</b> @enderror
        </div>

    </div>

</div>
