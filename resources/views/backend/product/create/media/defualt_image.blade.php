<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2><label for="image" class="required">{{trans('backend.product.image')}}</label></h2>
            </div>
        </div>
        <div class="card-body text-center pt-0">
            {!! single_image('image' , media_file(old('image') ), old('image'), 'image',['watermark'=>'no' ]) !!}
            <br>
            <b class="text-danger" id="error_image">    @error('image'){{ $message }}@enderror</b>
        </div>
    </div>

</div>
