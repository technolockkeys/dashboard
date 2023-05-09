<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2>      <label for="twitter_image" class="">{{trans('backend.product.pdf')}}</label></h2>
            </div>
        </div>
        <div class="card-body text-center pt-0">

            {!! multi_images('pdf' , media_file(old('pdf' ) ), old('pdf' ) , 'pdf') !!}

            <br>
            @error('pdf')<b class="text-danger">{{ $message }}</b> @enderror
        </div>

    </div>

</div>
