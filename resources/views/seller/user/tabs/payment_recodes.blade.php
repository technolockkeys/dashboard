<div class="tab-pane show active" id="kt_customer_view_payment_recodes" role="tabpanel">
    <div class="row">

        @foreach($statistics as $element)
            @php
                $svg = $element['svg'] ;
                $number= $element['number'];
                $name=$element['name'];
                $sum=$element['sum'];
            @endphp
            <div class="col-xl-3 col-6   mb-1">
                <!--begin::Statistics Widget 5-->
                <div  class="card bg-body  card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                        <span class="svg-icon svg-icon-primary   ms-n1">
		       <img style='width: 20%' src="{{$svg}}"/>
												</span>
                        <!--end::Svg Icon-->
                        <div class="text-gray-900 fw-bolder fs-2 mb-2 mt-5">{{$sum}}</div>
                        <div class="fw-bold text-gray-400">{{$name}}
                            @if($number != '-')
                                <span class="badge badge-light-success fs-base">
                            {{$number}}</span>
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Statistics Widget 5-->
            </div>

            {{--        <div class=" col-12 col-md mb-6">--}}

            {{--            <div class="card h-lg-80">--}}
            {{--                <!--begin::Body-->--}}
            {{--                <div class="card-body d-flex justify-content-between align-items-start flex-column">--}}
            {{--                    <!--begin::Icon-->--}}
            {{--                    <div class="w-50 h-25">--}}
            {{--                        <img style='width: 50%' src="{{$svg}}"/>--}}
            {{--                    </div>--}}
            {{--                    <div class="d-flex flex-column my-7">--}}
            {{--                        <span class="fw-semibold fs-3x text-gray-800 lh-1 ls-n2">{{$sum}}</span>--}}
            {{--                        <div class="m-0">--}}
            {{--                            <span class="fw-semibold fs-6 text-gray-400">{{$name}}</span>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    --}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        @endforeach

    </div>
    <div class="card pt-4 mb-6 mb-xl-9">
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>{{trans('seller.user.payment_recodes')}}</h2>
            </div>
            @if(permission_can('edit payment recodes seller user' , 'seller'))
                <div class="card-toolbar">
                    <button type="button"
                            class="btn btn-sm btn-primary change_balance_wallet me-1" type="button"
                            data-user="{{$user->id}}"  >
                        {{trans('seller.user.create_new_payment_recode')}}
                                         <span class="svg-icon svg-icon-3">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none">
																	<rect opacity="0.3" x="2" y="2" width="20"
                                                                          height="20" rx="5" fill="currentColor"></rect>
																	<rect x="10.8891" y="17.8033" width="12" height="2"
                                                                          rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                                          fill="currentColor"></rect>
																	<rect x="6.01041" y="10.9247" width="12" height="2"
                                                                          rx="1" fill="currentColor"></rect>
																</svg>
															</span>

                    </button>

                    <button type="button" class="btn btn-sm btn-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                        <span class="mx-2">{{trans('backend.user.send')}} </span>

                        <i class="las la-paper-plane"></i>

                        <!--end::Svg Icon-->
                    </button>
                    <!--begin::Menu 2-->
                    <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 mt-1">

                            <span type="button" id="send-reminder"
                                  class="btn menu-link  w-100 btn-flex  text-xl  btn-sm ">
                                <span class="mx-2">{{trans('backend.user.send_reminder')}} </span>
                            </span>
                        </div>
                        <div class="menu-item px-3 w-100 mt-1">

                            <button type="button" id="send-account-statement"
                                    class="btn menu-link  w-100 btn-flex  text-xl  btn-sm ">
                                <span class="me-2">{{trans('backend.user.send_account_statement')}} </span>
                            </button>
                        </div>

                    </div>


                </div>
                @if(false)
                    <div class="modal fade" id="add_payment_recodes" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="form_payment_recode" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{$user->uuid}}" name="uuid">
                                    {{--                                <input type="hidden" value="" id="edit_address_id" name="id">--}}
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">{{trans('seller.user.create_new_payment_recode')}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="user_id" id="form_payment_recode_user_id"
                                               value="{{$user->id}}">
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="add_payment_balance">{{trans('seller.user.balance')}}</label>
                                            <input type="text" class="form-control" name="balance"
                                                   id="add_payment_balance"
                                                   disabled>
                                            <b id="error_add_payment_balance" class="text-danger"></b>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="add_payment_orders">{{trans('seller.user.orders')}}</label>
                                            <select name="orders_id" id="form_payment_recode_orders_id"
                                                    class="form-control"
                                                    data-control="select2">
                                                <option value="-1">no selected</option>
                                                @foreach($orders as $item)
                                                    <option value="{{$item->uuid}}"><b> ({{$item->uuid}}
                                                            / {{currency($item->total)}} )</b> {{ $item->created_at }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <b id="error_order" class="text-danger"></b>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="add_payment_amount">{{trans('seller.user.amount')}}</label>
                                            <input type="number" class="form-control" name="amount"
                                                   id="add_payment_amount" step="any">
                                            <b id="error_add_payment_amount" class="text-danger"></b>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="add_payment_files">{{trans('seller.user.files')}}</label>
                                            <input type="file" multiple class="form-control" name="files[]"
                                                   id="add_payment_files">
                                            <b id="error_add_payment_files" class="text-danger"></b>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="add_payment_note">{{trans('seller.user.notes')}}</label>
                                            <textarea type="text" class="form-control" name="note"
                                                      id="add_payment_note"></textarea>
                                            <b id="error_add_payment_note" class="text-danger"></b>
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_save_payment" type="submit"
                                                    class="btn w-100 mt-3 btn-light-success"><i
                                                    class="la la-save"></i> {{ trans('backend.global.save') }}</button>
                                        </div>


                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="modal fade" id="create_new_payment_modal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="exampleModalLabel">{{trans('backend.wallet.change_balance')}}</h5>

                            </div>
                            <div class="modal-body" id="info_payment_body2">


                                {{--                <form id="create_new_payment" enctype="multipart/form-data">--}}
                                @csrf
                                <div class="form-group">
                                    <label for="user">{{trans('backend.wallet.user')}}</label>
                                    <input type="text" class="form-control bg-secondary" readonly value=""
                                           id="user_new_payment"
                                           data-id="">
                                    <input type="hidden" value="" name="user_id" id="create_new_payment_user_id">
                                </div>
                                <div class="form-group">
                                    <label for="balance">{{trans('backend.wallet.balance')}}</label>
                                    <input type="text" class="form-control bg-secondary" readonly value="" id="balance"
                                           data-id="">
                                </div>
                                <div class="form-group">
                                    <label for="amount">{{trans('backend.wallet.amount')}}</label>
                                    <input type="number" id="create_new_payment_amount" name="amount"
                                           class="form-control">
                                    <b id="create_new_payment_amount_error" class="text-danger"></b>

                                </div>

                                <div class="form-group">
                                    <label for="create_new_payment_type">{{trans('backend.wallet.type')}}</label>
                                    <select name="type" class="form-control" data-control="select2"
                                            id="create_new_payment_type">
                                        <option selected
                                                value="{{\App\Models\UserWallet::$order}}">{{\App\Models\UserWallet::$order}}</option>
                                        <option
                                            value="{{\App\Models\UserWallet::$withdraw}}">{{\App\Models\UserWallet::$withdraw}}</option>
                                    </select>
                                    <b id="create_new_payment_type_error" class="text-danger"></b>
                                </div>
                                <div class="form-group" id="div_create_new_payment_order2">
                                    <input type="hidden" id="create_new_payment_type"
                                           value="{{\App\Models\UserWallet::$order}}">
                                    <label for="create_new_payment_order">{{trans('backend.wallet.order')}}</label>
                                    <select name="order_id" class="form-control" data-control="select2"
                                            id="create_new_payment_order">
                                    </select>
                                    <b id="create_new_payment_order_error" class="text-danger"></b>
                                </div>
                                <div class="form-group">
                                    <label for="create_new_payment_note">{{trans('backend.order.note')}}</label>
                                    <textarea name="note" id="create_new_payment_note" class="form-control"
                                              style="resize: none"
                                              rows="3"></textarea>
                                    <b id="create_new_payment_note_error" class="text-danger"></b>
                                </div>
                                <div class="form-group">
                                    <label for="create_new_payment_files">{{trans('backend.wallet.files')}}</label>
                                    <input class="form-control" name="files[]" type="file" multiple
                                           id="create_new_payment_files">
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary w-100 mt-2"
                                            id="button_create_new_payment"><i
                                            class="la la-check"></i> {{trans('backend.global.save')}}</button>
                                </div>
                                {{--                </form>--}}

                            </div>

                        </div>
                    </div>
                </div>


            @endif
        </div>
        <div class="card-body pt-0 pb-5">
            <div id="kt_table_customers_payment_wrapper"
                 class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed gy-5 dataTable no-footer"
                           id="user_payment">
                        <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                        <tr>
                            <th></th>
                            <th>{{trans('seller.user.type')}}</th>
                            <th>{{trans('seller.user.order')}}</th>
                            <th>{{trans('seller.user.amount')}}  </th>
                            <th>{{trans('seller.user.status')}}  </th>
                            <th>{{trans('seller.user.created_at')}}  </th>
                            <th>{{trans('seller.user.notes')}}  </th>
                            <th>{{trans('backend.global.show')}}  </th>
                        </tr>
                        </thead>


                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@include('backend.user_wallet.show_transfer')
