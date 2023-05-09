@extends('backend.layout.app')
@section('title',trans('backend.menu.tickets').' | '.get_translatable_setting('system_name', app()->getLocale()))

@section('content')
    <div class="card mb-5 mb-xl-8">
        <!--begin::Body-->
        <div class="card-header">
            <h3 class="card-title"> {{$ticket->subject}}</h3>
            <div class="card-toolbar">
                @php $back_route = request('from') == 'user'? "javascript:history.go(-1)": route('backend.tickets.index') @endphp
                <a href="{{$back_route}}" class="btn btn-info"><i
                            class="las la-redo fs-4 me-2"></i> {{trans('backend.global.back')}}</a>
            </div>
        </div>
        <div class="card-body pb-0 ">
            <!--begin::Header-->
            <div class="d-flex align-items-center mb-3">
                <!--begin::User-->
                <div class="d-flex align-items-center flex-grow-1">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-45px me-5">
                        <img onerror="this.onerror=null;this.src='{{media_file(get_setting('default_images'))}}';" src="{{$ticket->user->avatar}}" alt="">
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Info-->
                    <div class="d-flex flex-column">
                        <a href="{{route('backend.users.show', ['user' => $ticket->user->id])}}"
                           class="text-gray-900 text-hover-primary fs-6 fw-bolder">{{$ticket->user->name}}</a>
                        <span class="text-gray-400 fw-bold">{{$ticket->sent_at}}</span>
                        @php
                            $route_2 = ($ticket->type == 'order' && $ticket->model?->id != null?
                            route('backend.orders.edit', ['order' => $ticket->model?->id]) :
                            ($ticket->type == 'product' && $ticket->model?->id != null? route('backend.products.edit', ['product' => $ticket->model?->id]): null));
                        @endphp
                        @if($ticket->model_id )
                            <a href="{{$route_2}}"
                               class="text-gray-900 text-hover-primary fs-6 fw-bolder">{{$ticket->model?->sku?? $ticket->model?->uuid}}</a>
                        @endif
                    </div>
                    <!--end::Info-->
                </div>
                @php $class =\App\Models\Ticket::$PENDING == $ticket->status ?'badge-light-warning'  : (\App\Models\Ticket::$OPEN == $ticket->status?'badge-light-primary' : 'badge-light-success') @endphp

                <div class="my-0">
                    <div type="button" class="">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                        <span class="badge
                          {{ $class }}
                                text-uppercase"> {{$ticket->status}}
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
            <!--end::Header-->
            <!--begin::Post-->
            <div class="mb-7">
                <!--begin::Text-->
                <div class="text-gray-800 mb-5">{!!$ticket->details  !!}</div>
                <!--end::Text-->
                @if(!empty($ticket->files))
                    <table class="mb-6">

                        @foreach(json_decode($ticket->files, true) as $file)
                            <tbody>


                            <tr class="odd">

                                <td data-order="account">
                                    <div class="d-flex align-items-center">
                                            <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3"
                                                                          d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                                          fill="currentColor"></path>
																	<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                          fill="currentColor"></path>
																</svg>
															</span>

                                        <a href="{{asset($file['path'])}}/{{$file['hashed_name']}}"
                                           class="text-gray-800 text-hover-primary">{{$file['image_data']}}</a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>

                        @endforeach
                    </table>
                @endif

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center mb-5">
                    <div class="bg-light-dark rounded-2 px-4 py-1 me-4">
                        <!--begin::Svg Icon | path: icons/duotune/communication/com012.svg-->
                        <i class="las la-eye"></i>
                        <!--end::Svg Icon-->{{$ticket->viewed}}</div>

                    <!--end::Toolbar-->
                    <!--begin::Toolbar-->
                    <div class="bg-light-dark rounded-2 px-4 py-1 me-4">
                        <!--begin::Svg Icon | path: icons/duotune/communication/com012.svg-->
                        <i class="las la-eye"></i>
                        <!--end::Svg Icon-->{{$ticket->client_viewed}}</div>

                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Post-->
            <!--begin::Replies-->
            <div class="mb-7 ps-10">
                <!--begin::Reply-->
                @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-5">
                        <!--begin::Avatar-->

                        <div class="symbol symbol-45px me-5">
                            <img onerror="this.onerror=null;this.src='{{media_file(get_setting('default_images'))}}';" src="{{$reply->replyable->avatar}}" alt="">
                        </div>

                        <!--end::Avatar-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column flex-row-fluid">
                            <!--begin::Info-->

                            <div class="d-flex align-items-center flex-wrap mb-1">
                                <a href="#"
                                   class="text-gray-800 text-hover-primary fw-bolder me-2">{{$reply->replyable->name}}</a>
                                <span class="text-gray-400 fw-bold fs-7">{{$reply->created_at}}</span>
                                {{--                                <a href="#" class="ms-auto text-gray-400 text-hover-primary fw-bold fs-7">Reply</a>--}}
                            </div>
                            {!! $reply->reply !!}

                        </div>
                        @if($reply->replyable_type == get_class(auth('admin')->user()) && $reply->replyable_id == auth('admin')->id())

                            <div class="my-0">
                                <button type="button"
                                        class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                                    <span class="svg-icon svg-icon-2">
																<svg xmlns="http://www.w3.org/2000/svg" width="24px"
                                                                     height="24px" viewBox="0 0 24 24">
																	<g stroke="none" stroke-width="1" fill="none"
                                                                       fill-rule="evenodd">
																		<rect x="5" y="5" width="5" height="5" rx="1"
                                                                              fill="currentColor"></rect>
																		<rect x="14" y="5" width="5" height="5" rx="1"
                                                                              fill="currentColor" opacity="0.3"></rect>
																		<rect x="5" y="14" width="5" height="5" rx="1"
                                                                              fill="currentColor" opacity="0.3"></rect>
																		<rect x="14" y="14" width="5" height="5" rx="1"
                                                                              fill="currentColor" opacity="0.3"></rect>
																	</g>
																</svg>
															</span>
                                    <!--end::Svg Icon-->
                                </button>
                                <!--begin::Menu 2-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px"
                                     data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-6 text-dark fw-bolder px-3 py-4">{{trans('backend.global.actions')}}</div>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator mb-3 opacity-75"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">


                                    <span class="menu-link px-3 delete_file" data-row="{{$reply->id}}">
                                        delete Ticket
                                    </span>
                                    </div>
                                    <div class="menu-item px-3">
                                        <span data-id="{{$reply->id}}" class="menu-link px-3 reply">edit Ticket</span>
                                    </div>
                                    <form action="{{route('backend.replies.destroy', $reply->id)}}"
                                          class=" delete" id="delete_form_{{$reply->id}}"
                                          method="delete">
                                        @csrf
                                    </form>

                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu 2-->
                            </div>
                        @endif
                        <!--end::Info-->
                    </div>
                    @if(!empty($reply->files))
                        <table class="mb-6">

                            @foreach(json_decode($reply->files, true) as $file)
                                <tbody>


                                <tr class="odd">

                                    <td data-order="account">
                                        <div class="d-flex align-items-center">
                                            <span class="svg-icon svg-icon-2x svg-icon-primary me-4">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none">
																	<path opacity="0.3"
                                                                          d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                                          fill="currentColor"></path>
																	<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                          fill="currentColor"></path>
																</svg>
															</span>

                                            <a href="{{asset($file['path'])}}/{{$file['hashed_name']}}"
                                               class="text-gray-800 text-hover-primary">{{$file['image_data']}}</a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>

                            @endforeach
                        </table>
                    @endif

                    <div class="separator mb-4"></div>
                @endforeach
            </div>
            <div class="separator mb-4"></div>
            <form class="position-relative mb-6" method="post" enctype="multipart/form-data"
                  action="{{route('backend.replies.store',['ticket_id'=> $ticket->id])}}">
                @csrf
                <textarea id="reply" name="reply" class="mb-4">{!! old('reply') !!}</textarea>
                {{--                <input type="hidden" name="reply" required value="{{old('reply')}}">--}}

                @error('reply')<b class="text-danger">{{ $message }}</b> @enderror
                <div class="mb-3">
                    <label for="formFileSm" class="form-label">{{trans('backend.ticket.files')}}</label>
                    <input class="form-control form-control-sm" id="formFileMultiple" name="files[]" type="file"
                           multiple>
                </div>
                <div class="row col-12 col-md-6 float-end mx-6 mb-4">
                    <div class="col-6">
                        <select name="status" class="form-select" data-control="select2"
                                data-placeholder="Select an option">
                            <option value="{{\App\Models\Ticket::$PENDING}}" {{old('status', $ticket->status) == \App\Models\Ticket::$PENDING? 'selected':""}} >
                                Pending
                            </option>
                            <option value="{{\App\Models\Ticket::$OPEN}}" {{old('status', $ticket->status) == \App\Models\Ticket::$OPEN? 'selected':""}}>
                                Open
                            </option>
                            <option value="{{\App\Models\Ticket::$SOLVED}}" {{old('status', $ticket->status) == \App\Models\Ticket::$SOLVED? 'selected':""}}>
                                Solved
                            </option>
                        </select>
                    </div>
                    <div class="col-6">
                        <button type="submit"
                                class="btn btn-success text-end ">{{trans('backend.ticket.reply.send')}} </button>
                    </div>
                </div>
            </form>
            <!--edit::Reply input-->
        </div>
        <!--end::Body-->
    </div>

    <div class="modal  fade" tabindex="-1" id="modal_edit">
        <div class="modal-dialog modal-lg  ">
            <div class="modal-content">

                <div id="edit">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js"></script>
    {!! editor_script() !!}
    <script>

        $(document).ready(function () {
            bsCustomFileInput.init()
        })
        CKEDITOR.replace(
            document.querySelector('#reply'))

        // var quill = new Quill('#reply', {
        //     modules: {
        //         toolbar: [
        //             [{
        //                 header: [1, 2, false]
        //             }],
        //             ['bold', 'italic', 'underline'],
        //             ['code-block']
        //         ]
        //     },
        //     placeholder: 'Type your text here...',
        //     theme: 'snow', // or 'bubble',
        // });
        //
        // quill.on('text-change', function (delta, oldDelta, source) {
        //     var about = document.querySelector('input[name=reply]');
        //     about.value = quill.container.firstChild.innerHTML;
        // });
        $(document).on('click', '.reply', function () {
            var id = $(this).data('id');
            $("#modal_edit").modal('show');
            $.ajax({
                url: "{{route('backend.replies.edit')}}",
                data: {
                    "_token": "{{csrf_token()}}",
                    id: id,
                }, success: function (response) {
                    $("#edit").html(response.data.view);
                }
            })

        });


        $(document).on('click', '.delete_file', function () {
            // event.preventDefault();
            var id = $(this).data('row');
            $("#delete_form_" + id).submit();
            console.log('hi')
        })
        $(document).on('submit', '.delete', function (event) {
            console.log('submitted')
            event.preventDefault();
            var url = $(this).attr('action')
            $.ajax({
                url: url,
                method: "delete",
                data: $(this).serialize(),
                success: function (response) {
                    location.reload(true);
                }
            })
        })
        $(document).on('submit', '#edit_reply', function (event) {
            event.preventDefault();
            var button = $(this).find(":submit");
            // debugger
            button.attr('disabled', true);
            // $('#country_error').text('');
            var url = $(this).attr('action')
            console.log($(this).serialize());
            var form = $('#edit_reply')[0];
            var formData = new FormData(form)
            var data = $(this).serialize();
            $.ajax({
                url: url,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    button.removeAttr('disabled', false)
                    location.reload(true);
                    $("#modal_edit").modal('hide');
                },
                error: function (response) {
                    // $('#country_error').text(response.responseJSON.errors.country);
                    button.attr('disabled', false);

                }
            })
        })

    </script>
@endsection
