<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2><label for="secondary_image" class="">{{trans('backend.product.secondary_image')}}</label></h2>
            </div>
        </div>
        <div class="card-body text-center pt-0">
            {!! single_image('secondary_image' , media_file(old('secondary_image',$product->secondary_image) ), old('twitter_image',$product->secondary_image), 'image',['watermark'=>'no' ]) !!}
            <br>
            <b class="text-danger" id="error_image">    @error('secondary_image'){{ $message }}@enderror</b>
        </div>

    </div>

</div>
