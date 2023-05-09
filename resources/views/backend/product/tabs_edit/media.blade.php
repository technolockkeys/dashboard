<div class="row">
    <div class="col-12 col-md-12">
        <div class="form-group text-left">
            <label for="image" class="required">{{trans('backend.product.gallery')}}</label>
            <br>
            {!! multi_images('gallery' , old('gallery', $product->gallery) , old('gallery', $product->gallery)) !!}
            <br>
            @error('image')<b class="text-danger">{{ $message }}</b> @enderror
        </div>
    </div>
    <div class="col-6 col-md-4  text-left">
        <div class="form-group">
            <label for="image" class="required">{{trans('backend.product.image')}}</label>
            <br>
            {!! single_image('image' , media_file (old('image' , $product->image))  , old('image', $product->image)) !!}
            <br>
            <b class="text-danger" id="error_image">    @error('image'){{ $message }}@enderror</b>
        </div>
    </div>
    <div class="col-6 col-md-4  text-left">
        <div class="form-group">
            <label for="twitter_image" class="">{{trans('backend.product.twitter_image')}}</label>
            <br>
            {!! single_image('twitter_image' ,media_file( old('twitter_image' , $product->twitter_image) ) , old('twitter_image')) !!}
            <br>
            @error('twitter_image')<b class="text-danger">{{ $message }}</b> @enderror
        </div>
    </div>
    <div class="col-6 col-md-4  text-left">
        <div class="form-group">
            <label for="twitter_image" class="">{{trans('backend.product.pdf')}}</label>
            <br>
            {!! single_image('pdf' ,media_file( old('pdf' , $product->pdf)) , old('pdf') , 'pdf') !!}
            <br>
            @error('twitter_image')<b class="text-danger">{{ $message }}</b> @enderror
        </div>
    </div>

    <div class="col-12 col-md-12 mt-3">
        <div id="add_videos" @if(empty(old('videos_provider', json_decode($product->videos))) ) class="d-none" @endif>
            <div class="row">
                <div class="col-5 col-md-5">
                    <b>{{trans('backend.product.videos_type')}}</b>
                </div>
                <div class="col-6 col-md-6">
                    <b>{{trans('backend.product.videos_value')}}</b>
                </div>
            </div>
            @php
            $videos  =json_decode($product->videos);
            $video_providers =  [] ;
            $video_url =  [] ;
            foreach ($videos as $video ){
                $video_providers[]= $video->provider ;
                $video_url[]= $video->link ;
            }

            @endphp
            @if(!empty(old('videos_provider[]',$video_providers)) )
                @foreach(old('videos_provider[]',$video_providers) as $key=>$item)
                    <div class="row mt-3" data-row="{{$key}}">
                        <div class="col-5 col-md-5">
                            <select name="videos_provider[]" class="form-control videos_provider" required
                                    data-control="select2" id="">
                                <option @if($item == 'youtube') selected
                                        @endif value="youtube">{{trans('backend.product.youtube')}}</option>
                                <option @if($item == 'vimeo') selected
                                        @endif value="vimeo">{{trans('backend.product.vimeo')}}</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-6">
                            <input name="video_url[]"
                                   value="{{!empty(old('video_url[]',$video_url)[$key]) ? old('video_url[]',$video_url)[$key] :""}}"
                                   type="text" class="form-control" required>
                        </div>
                        <div class="col-1 col-md-1 mb-1">
                            <button type="button" onclick="remove_video('{{$key}}')" class="btn btn-icon btn-danger"><i
                                    class="fa fa-exclamation-triangle"></i></button>
                        </div>
                    </div>


                @endforeach
            @endif
        </div>
        <div class="row mt-3">
            <div class="col">
                <button type="button" onclick="add_video()"
                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary"><i
                        class="fa fa-plus"></i> {{trans('backend.product.add_video')}}
                </button>

            </div>
        </div>
    </div>
</div>
