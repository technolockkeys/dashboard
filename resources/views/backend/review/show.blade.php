@extends('backend.layout.app')
@section('title',trans('backend.menu.reviews').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')

    <div class="card mb-5 mb-xl-8">
        <div class="card-header">
            <h3 class="card-title"> {{$review->product->title}}</h3>
            <div class="card-toolbar flex">
                <button data-route="{{route('backend.reviews.destroy',$review->id)}}"
                        data-token="{{csrf_token()}}"
                        class="btn btn-bg btn-danger text-lg btn-delete btn-icon btn-hover-rise me-1"
                        data-message="{{trans('backend.global.ara_you_sure_to_delete', ['name' => $review->product->title])}}"

                ><i
                        class="fs-2x la la-trash"></i></button>
                @php $back_route = request('from') == 'user'? "javascript:history.go(-1)": route('backend.reviews.index') @endphp
                <a href="{{$back_route}}" class="btn btn-info">
                    <i class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
            </div>
        </div>

        <div class="card-body pb-0 ">
            <form action="{{route('backend.reviews.update',['review' => $review->id])}}" method="post">
                @csrf

                <div class="d-flex align-items-center mb-3">
                    <!--begin::User-->
                    <div class="d-flex align-items-center flex-grow-1">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-45px me-5">
                            <img src="{{$review->user->avatar}}"
                                 onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'" alt="">
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column">
                            <a href="{{route('backend.users.show' , $review->user->id)}}"
                               class="text-gray-900 text-hover-primary fs-6 fw-bolder">{{$review->user->name}}</a>
                            <span class="text-gray-400 fw-bold">{{$review->created_at}}</span>
                        </div>
                        <!--end::Info-->
                    </div>

                    <div class="my-0">
                        <div type="button" class="">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                            <span class="badge
{{--                          {{ $class }}--}}
                                text-uppercase"> {{$review->product->title}}
                        </span>


                            <!--end::Svg Icon-->
                        </div>
                        <!--begin::Menu 2-->
                        <div class="" data-kt-menu="true">

                        </div>
                        <!--end::Menu 2-->
                    </div>
                    <!--end::User-->
                </div>

                <div class="mb-7">


                    <div class="row mb-6">

                        <label for="comment" class="form-label">{{trans('backend.review.comment')}}</label>
                        <textarea id="comment" name="comment" type="text"
                                  class="form-control">{{$review->comment}}</textarea>
                    </div>
                    <div class="row mb-6">
                        <label for="rating" class="form-label">{{trans('backend.review.rating')}}</label>
                        <input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="form-control"
                               value="{{old('rating', $review->rating)}}"/>
                    </div>
                    <div class="row mb-6">
                        <label for="order" class="form-label">{{trans('backend.review.order')}}</label>
                        <input id="order" name="order" type="number" step="0.1" min="0" max="5" class="form-control"
                               value="{{old('order', $review->order)}}"/>
                    </div>

                    <div class="form-check form-switch form-check-custom form-check-solid me-10 mb-10">
                        <input class="form-check-input h-20px w-30px" @if(old('status',$review->status == 1)) checked
                               @endif type="checkbox" value="1"
                               name="status" id="status"/>
                        <label class="form-check-label" for="status">
                            {{trans('backend.review.status')}}
                        </label>
                    </div>

                </div>

                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">  {{trans('backend.global.save')}} </button>
                </div>
                <!--edit::Reply input-->
            </form>
        </div>

    </div>
    <div class="card">
        <div class="card-body">
            @foreach($reviewReplies as $reviewReply)
                <div class="mb-7 ps-10">
                    <!--begin::Reply-->
                    <div class="d-flex mb-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-45px me-5">
                            <img src="assets/media/avatars/300-14.jpg"
                                 onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'" alt="">
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column flex-row-fluid">
                            <!--begin::Info-->
                            <div class="d-flex align-items-center flex-wrap mb-1">
                                @if(($reviewReply->user_type == \App\Models\Admin::class))
                                    <a href="#"
                                       class="text-gray-800 text-hover-primary fw-bolder me-2">{{\App\Models\Admin::find($reviewReply->user_id)->name}}</a>

                                @else
                                    @php
                                        $user = \App\Models\User::find($reviewReply->user_id);
                                    @endphp
                                    @if(!empty($user))
                                        <a href="{{route('backend.users.show',$user->id)}}"
                                           class="text-gray-800 text-hover-primary fw-bolder me-2">{{\App\Models\User::find($reviewReply->user_id)->name}}</a>
                                    @endif
                                @endif
                                <span
                                    class="text-gray-400 fw-bold fs-7">{{$reviewReply->created_at->format('Y-m-d H:i:s')}}</span>

                            </div>
                            <span class="text-gray-800 fs-7 fw-normal pt-1">{{$reviewReply->comment}}</span>
                            <span class="text-gray-800 fs-7 fw-normal pt-1">
                                 @foreach(json_decode($reviewReply->files , true) as $file)
                                    <a href="{{asset($file['path'] .$file['hashed_name'])}}" target="_blank"
                                       class="btn btn-sm btn-light btn-color-muted btn-active-light-danger px-4 py-2">
														<span class="svg-icon svg-icon-3 mb-3">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3" d="M4.425 20.525C2.525 18.625 2.525 15.525 4.425 13.525L14.825 3.125C16.325 1.625 18.825 1.625 20.425 3.125C20.825 3.525 20.825 4.12502 20.425 4.52502C20.025 4.92502 19.425 4.92502 19.025 4.52502C18.225 3.72502 17.025 3.72502 16.225 4.52502L5.82499 14.925C4.62499 16.125 4.62499 17.925 5.82499 19.125C7.02499 20.325 8.82501 20.325 10.025 19.125L18.425 10.725C18.825 10.325 19.425 10.325 19.825 10.725C20.225 11.125 20.225 11.725 19.825 12.125L11.425 20.525C9.525 22.425 6.425 22.425 4.425 20.525Z" fill="currentColor"></path>
																	<path d="M9.32499 15.625C8.12499 14.425 8.12499 12.625 9.32499 11.425L14.225 6.52498C14.625 6.12498 15.225 6.12498 15.625 6.52498C16.025 6.92498 16.025 7.525 15.625 7.925L10.725 12.8249C10.325 13.2249 10.325 13.8249 10.725 14.2249C11.125 14.6249 11.725 14.6249 12.125 14.2249L19.125 7.22493C19.525 6.82493 19.725 6.425 19.725 5.925C19.725 5.325 19.525 4.825 19.125 4.425C18.725 4.025 18.725 3.42498 19.125 3.02498C19.525 2.62498 20.125 2.62498 20.525 3.02498C21.325 3.82498 21.725 4.825 21.725 5.925C21.725 6.925 21.325 7.82498 20.525 8.52498L13.525 15.525C12.325 16.725 10.525 16.725 9.32499 15.625Z" fill="currentColor"></path>
																</svg>
															</span>
                                        {{$file['image_data']}}
                                        </a>
                                @endforeach
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
        <div class="card-footer">
            <form class="position-relative mb-6" method="post" enctype="multipart/form-data"
                  action="{{route('backend.reviews.store.replay')}}">
                @csrf
                <textarea id="reply" rows="4" style="resize: none" name="reply"
                          class="mb-4 form-control">{!! old('reply') !!}</textarea>
                {{--                <input type="hidden" name="reply" required value="{{old('reply')}}">--}}

                @error('reply')<b class="text-danger">{{ $message }}</b> @enderror
                <div class="mb-3">
                    <label for="formFileSm" class="form-label">{{trans('backend.ticket.files')}}</label>
                    <input class="form-control form-control-sm" id="formFileMultiple" name="files[]" type="file"
                           multiple>
                </div>
                <input type="hidden" name="review_id" value="{{$review->id}}">
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">{{trans('backend.global.save')}}</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
    {!! editor_script() !!}


    <script>
        $(document).on("change", '#status', function () {
            // alert('asdasd')
            $.ajax({
                url: "{{route('backend.reviews.change.status')}}",
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}",
                    id: {{$review->id}},
                }, success: function (response) {
                    $("#edit").html(response.data.view);
                }
            })
        });


    </script>
@endsection
