<div class="modal fade" tabindex="-1" id="modal_transfer_order">
    <div class="modal-dialog modal-dialog-centered">
        <form id="transfer_type_form">
            @csrf
            <input type="hidden" name="id" id='transfer_id_form' value="">
            <div class="modal-content">


                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('backend.global.created_at')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control form-control-solid" readonly id="modal_order_created_at">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('seller.user.order')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control form-control-solid" readonly id="modal_order_price">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('backend.wallet.order_balance')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control form-control-solid" readonly
                                   id="modal_order_balance">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('backend.wallet.amount')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control form-control-solid" readonly
                                   id="modal_order_amount">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('backend.order.payment_method')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control form-control-solid" readonly
                                   id="modal_order_payment_method">
                        </div>
                    </div>
                    <div class="row mt-2" id="transfer_type_div">
                        <div class="col-3 pt-3">
                            <label for="transfer_type">{{trans('seller.user.type')}}</label>
                        </div>
                        <div class="col-9">
                            <select name="transfer_type" id="transfer_type" class="form-control form-control-solid"
                                    data-control="select2">
                                <option value="credit">     {{trans('backend.wallet.credit')}}</option>
{{--                                <option value="part_credit">{{trans('backend.wallet.part_credit')}}</option>--}}
                                <option value="total">      {{trans('backend.wallet.total')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2" id="row_filess">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('seller.user.files')}}</label>
                        </div>
                        <div class="col-9" id="files_modal">
                        </div>
                    </div>

                    <div class="row mt-2" id="row_balance_modal">
                        <div class="col-3 pt-3">
                            <label for="order_id">{{trans('backend.wallet.balance')}}</label>
                        </div>
                        <div class="col-9">
                            <input type="number" step="any" class="form-control form-control-solid"
                                   name="value_transfer"
                                   id="modal_value">
                        </div>
                    </div>

                </div>

                <div class="modal-footer" id="footer_transfer">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                    <button type="submit" id="transfer_data_save"
                            class="btn btn-primary">{{trans('backend.global.save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
