<div class="col-md-12 col-12 ">
    <div class="card card-flush py-4 mt-3">
        <div class="card-header">
            <div class="card-title">
                <h2>
                    <label for="image">{{trans('backend.product.videos')}}</label>
                    <sub class="text-gray-700">Go to youtube and right click copy video link</sub>
                </h2>
            </div>
        </div>

        <div class="card-body">
            <div class="col-12 col-md-12 mt-3">
                <div id="add_videos" @if(empty(old('video_url   ',[])) ) class="d-none" @endif>

                    @if(!empty(old('video_url[]',[])) )
                        @foreach(old('video_url[]',[]) as $key=>$item)
                            <div class="row mt-3" data-row="{{$key}}">
                                {{--                            <div class="col-12 col-md-5">--}}
                                {{--                                <select name="videos_provider[]" class="form-control videos_provider" required--}}
                                {{--                                        data-control="select2" id="">--}}
                                {{--                                    <option @if($item == 'youtube') selected @endif value="youtube">{{trans('backend.product.youtube')}}</option>--}}
                                {{--                                    <option @if($item == 'vimeo') selected @endif value="vimeo">{{trans('backend.product.vimeo')}}</option>--}}
                                {{--                                </select>--}}
                                {{--                            </div>--}}
                                <div class="col-11 col-md-11">
                                    <input name="video_url[]"
                                           value="{{!empty(old('video_url[]')[$key]) ? old('video_url[]')[$key] :""}}"
                                           type="text" class="form-control" required>
                                </div>
                                <div class="col-1 col-md-1 ">
                                    <button type="button" onclick="remove_video('{{$key}}')"
                                            class="btn btn-icon btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                            </div>


                        @endforeach
                    @endif
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <button type="button" onclick="add_video()"
                                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                            <i
                                class="fa fa-plus"></i> {{trans('backend.product.add_video')}}
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
