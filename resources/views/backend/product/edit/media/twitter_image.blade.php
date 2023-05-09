<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2><label for="twitter_image" class="">{{trans('backend.product.twitter_image')}}</label></h2>
            </div>
        </div>
        <div class="card-body text-center pt-0">
            {!! single_image('twitter_image' , media_file(old('twitter_image',$product->twitter_image) ), old('twitter_image',$product->twitter_image)) !!}
            <br>
            <b class="text-danger" id="error_image">    @error('twitter_image'){{ $message }}@enderror</b>
        </div>

    </div>

</div>
