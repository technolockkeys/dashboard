<div class="modal fade" id="create_new_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.wallet.change_balance')}}</h5>

            </div>
            <div class="modal-body" id="info_payment_body2">


                {{--                <form id="create_new_payment" enctype="multipart/form-data">--}}
                @csrf
                <div class="form-group">
                    <label for="user">{{trans('backend.wallet.user')}}</label>
                    <input type="text" class="form-control bg-secondary" readonly value="" id="user_new_payment"
                           data-id="">
                    <input type="hidden" value="" name="user_id" id="create_new_payment_user_id">
                </div>
                <div class="form-group">
                    <label for="balance">{{trans('backend.wallet.balance')}}</label>
                    <input type="text" class="form-control bg-secondary" readonly value="" id="balance" data-id="">
                </div>
                <div class="form-group">
                    <label for="amount">{{trans('backend.wallet.amount')}}</label>
                    <input type="number" id="create_new_payment_amount" name="amount" class="form-control">
                    <b id="create_new_payment_amount_error" class="text-danger"></b>

                </div>

                <div class="form-group">
                    <label for="create_new_payment_type">{{trans('backend.wallet.type')}}</label>
                    <select name="type" class="form-control" data-control="select2" id="create_new_payment_type">
                        <option selected
                                value="{{\App\Models\UserWallet::$order}}">{{\App\Models\UserWallet::$order}}</option>
                        <option
                            value="{{\App\Models\UserWallet::$withdraw}}">{{\App\Models\UserWallet::$withdraw}}</option>
                    </select>
                    <b id="create_new_payment_type_error" class="text-danger"></b>
                </div>
                <div class="form-group" id="div_create_new_payment_order2">
                    <input type="hidden" id="create_new_payment_type" value="{{\App\Models\UserWallet::$order}}">
                    <label for="create_new_payment_order">{{trans('backend.wallet.order')}}</label>
                    <select name="order_id" class="form-control" data-control="select2"
                            id="create_new_payment_order">
                    </select>
                    <b id="create_new_payment_order_error" class="text-danger"></b>
                </div>
                <div class="form-group">
                    <label for="create_new_payment_note">{{trans('backend.order.note')}}</label>
                    <textarea name="note" id="create_new_payment_note" class="form-control" style="resize: none"
                              rows="3"></textarea>
                    <b id="create_new_payment_note_error" class="text-danger"></b>
                </div>
                <div class="form-group">
                    <label for="create_new_payment_files">{{trans('backend.wallet.files')}}</label>
                    <input class="form-control" name="files[]" type="file" multiple id="create_new_payment_files">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary w-100 mt-2" id="button_create_new_payment"><i
                            class="la la-check"></i> {{trans('backend.global.save')}}</button>
                </div>
                {{--                </form>--}}

            </div>

        </div>
    </div>
</div>
