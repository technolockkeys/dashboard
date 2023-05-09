<div class="tab-pane fade" id="esg_customers_addresses" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">
        <div class="card-header border-0">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>{{trans('backend.user.addresses')}}</h2>

            </div>

            @if(permission_can('edit address seller user','seller'))
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-flex btn-light-primary"
                            id="create_new_address_for_customer">
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
                        {{trans('seller.orders.create_new_address')}}
                    </button>
                </div>
            @endif
        </div>
        <div class="card-body pt-0 pb-5">
            <div id="kt_table_customers_payment_wrapper"
                 class="dataTables_wrapper dt-bootstrap4 w-100 no-footer">
                <div class="table-responsive  w-100 ">
                    <table id="address_table"
                           class="table align-middle  w-100  table-row-dashed fs-6 text-gray-600 fw-bold gy-4 dataTable no-footer">
                        <thead class="border-bottom  w-100  border-gray-200">
                        <tr class="text-start text-muted  w-100  fw-bolder fs-7 text-uppercase gs-0">
                            <th></th>
                            <th>{{trans('seller.user.country')}}</th>
                            <th>{{trans('seller.user.state')}}</th>
                            <th>{{trans('seller.user.city')}}</th>
                            <th>{{trans('seller.user.addresses')}}</th>
                            <th>{{trans('seller.user.street')}}</th>
                            <th>{{trans('seller.user.postal_code')}}</th>
                            <th>{{trans('seller.user.phone')}}</th>
                            <th>{{trans('backend.global.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <!--end::Table-->
        </div>
    </div>
</div>
@if(permission_can('edit address seller user','seller'))
    <div class="modal fade" id="add_form_address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_new_address">
                    @csrf
                    <input type="hidden" value="{{$user->uuid}}" name="uuid">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">{{trans('seller.orders.create_new_address')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                        <input type="hidden" name="id" id="new_address_id" value="">
                        {{--                 country--}}
                        <div class="form-group">
                            <label class="form-label" for="new_address_country">{{trans('seller.user.country')}}</label>
                            <select name="country" id="new_address_country" data-control="select2" class="form-control">
                                @foreach($countries as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <b id="error_new_address_country" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new_address_state">{{trans('seller.user.state')}}</label>
                            <input type="text" class="form-control" name="state" id="new_address_state">
                            <b id="error_new_address_state" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new_address_city">{{trans('seller.user.city')}}</label>
                            <input type="text" class="form-control" name="city" id="new_address_city">
                            <b id="error_new_address_city" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new_address_street">{{trans('seller.user.street')}}</label>
                            <input type="text" class="form-control" name="street" id="new_address_street">
                            <b id="error_new_address_street" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                   for="new_address_full_address">{{trans('seller.user.full_address')}}</label>
                            <textarea name="full_address" class="form-control" id="new_address_full_address" rows="3"
                                      style="resize: none"></textarea>
                            <b id="error_new_address_street" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                   for="new_address_postal_code">{{trans('seller.user.postal_code')}}</label>
                            <input type="text" class="form-control" name="postal_code" id="new_address_postal_code">
                            <b id="error_new_address_postal_code" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="new_address_phone">{{trans('seller.user.phone')}} </label>
                            <input type="text" class="form-control" name="phone" id="new_address_phone">
                            <b id="error_new_address_phone" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px" type="checkbox" name="default_address"
                                       value="1"
                                       id="add_as_default_address"/>
                                <label class="form-check-label" for="add_as_default_address">
                                    {{trans('backend.address.add_as_default_address')}}
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                        <button type="submit" id="btn_new_address" class="btn btn-primary"><i
                                    class="fa fa-save"></i> {{trans('backend.global.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_form_address_model" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="edit_form_address">
                    @csrf
                    <input type="hidden" value="{{$user->uuid}}" name="uuid">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">{{trans('seller.user.edit_address')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                        <input type="hidden" id="edit_address_id" name="id">
                        {{--                 country--}}
                        <div class="form-group">
                            <label class="form-label"
                                   for="edit_address_country">{{trans('seller.user.country')}}</label>
                            <select name="country" id="edit_address_country" data-control="select2"
                                    class="form-control">
                                @foreach($countries as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <b id="error_edit_address_country" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_address_state">{{trans('seller.user.state')}}</label>
                            <input type="text" class="form-control" name="state" id="edit_address_state">
                            <b id="error_edit_address_state" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_address_city">{{trans('seller.user.city')}}</label>
                            <input type="text" class="form-control" name="city" id="edit_address_city">
                            <b id="error_edit_address_city" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_address_street">{{trans('seller.user.street')}}</label>
                            <input type="text" class="form-control" name="street" id="edit_address_street">
                            <b id="error_edit_address_street" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                   for="edit_address_full_address">{{trans('seller.user.full_address')}}</label>
                            <textarea name="full_address" class="form-control" id="edit_address_full_address" rows="3"
                                      style="resize: none"></textarea>
                            <b id="error_edit_address_street" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label"
                                   for="edit_address_postal_code">{{trans('seller.user.postal_code')}}</label>
                            <input type="text" class="form-control" name="postal_code" id="edit_address_postal_code">
                            <b id="error_edit_address_postal_code" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_address_phone">{{trans('seller.user.phone')}} </label>
                            <input type="text" class="form-control" name="phone" id="edit_address_phone">
                            <b id="error_edit_address_phone" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10">
                                <input class="form-check-input h-20px w-30px" type="checkbox" name="default_address"
                                       value="1"
                                       id="edit_as_default_address"/>
                                <label class="form-check-label" for="edit_as_default_address">
                                    {{trans('backend.address.add_as_default_address')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{trans('backend.global.close')}}</button>
                        <button type="submit" id="btn_edit_address" class="btn btn-primary"><i
                                    class="fa fa-save"></i> {{trans('backend.global.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif


