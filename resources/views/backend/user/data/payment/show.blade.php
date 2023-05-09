<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-striped   table-hover">
            <tbody>
            <tr>
                <td class="text-start ps-4 w-50"><b>{{trans('backend.wallet.amount')}}        </b></td>
                <td class="text-end pe-4"><span
                        class="badge badge-light-@if($wallet->amount > 0 ){{'primary'}} @else{{'danger'}} @endif"> {{currency($wallet->amount)}}  </span>
                </td>
            </tr>

            <tr>
                <td class="text-start ps-4"><b>{{trans('backend.wallet.type')}}          </b></td>
                <td class="text-end pe-4">
                    @php
                        if ($wallet->type == \App\Models\UserWallet::$order){
                            $class_type = 'primary';
                        }elseif($wallet->type == \App\Models\UserWallet::$refund){
                            $class_type = 'danger';
                        }else{
                            $class_type = 'info';
                        }

                    @endphp


                    <span class="badge badge-light-{{$class_type}}"> {{trans('backend.wallet.'.$wallet->type)}}</span>
                </td>
            </tr>
            <tr>
                <td class="text-start ps-4"><b>{{trans('backend.wallet.status')}}        </b></td>
                <td class="text-end pe-4"><span class="badge badge-light-info"> {{$wallet->status}}</span></td>
            </tr>
            @if(!empty($wallet->order_id) && !empty($order))
                <tr>
                    <td class="text-start ps-4"><b>{{trans('backend.wallet.order')}}         </b></td>
                    <td class="text-end pe-4"><b class="badge badge-info">#{{$order->uuid}}</b> / <span
                            class="badge badge-light-primary">{{currency($order->total)}}</span></td>
                </tr>
            @endif
            @if(!empty($wallet->order_payment_id) && !empty($order_payment))
                <tr>
                    <td class="text-start ps-4"><b>{{trans('backend.wallet.order_payment')}} </b></td>
                    <td class="text-end pe-4"><span
                            class="badge badge-light-primary ">{{$order_payment->payment_method}}</span>
                        @if($order_payment->payment_method == \App\Models\OrderPayment::$transfer && !empty($order_payment->files))
                            <br>
                            @foreach(json_decode($order_payment->files) as $item)
                                <a href="{{asset('storage/'.$item)}}" target="_blank"> <img
                                        src="{{asset('storage/'.$item)}}" alt="" width="40">

                                </a>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endif

            @if(!empty($wallet_create)  && $wallet_create )
            <tr>
                <td class="text-start ps-4"><b>{{trans('backend.wallet.created_by')}}    </b></td>
                <td class="text-end pe-4 ">
                    <span class="badge badge-light-dark"> {{class_basename($wallet_create)}} : {{$wallet_create->name}}</span>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title=""
                         data-bs-original-title="">
                        <img onerror="this.src='{{asset('backend/media/avatars/blank.png')}}'"
                             src="{{asset($wallet_create->avatar)}}">
                    </div>
                </td>
            </tr>
            @endif
            @if(!empty(json_decode($wallet->files)))
                <tr>
                    <td class="text-start ps-4"><b>{{trans('backend.wallet.files')}}    </b></td>
                    <td class="text-end pe-4 ">
                        @foreach(json_decode($wallet->files) as $file)
                            <a href="{{asset('storage/'.$file)}}" target="_blank"><img width="20"
                                                                                       onerror="this.src='{{asset('backend/media/svg/files/doc.svg')}}'"
                                                                                       src="{{asset($file)}}"
                                                                                       alt=""></a>
                        @endforeach
                    </td>
                </tr>
            @endif


            </tbody>
            <tfoot>
            @if($wallet->status ==\App\Models\UserWallet::$pending && permission_can('change status wallet' ,'admin'))
                <tr>
                    <td>
                        <button data-type="approve" data-id="{{$wallet->id}}" type="button"
                                class="btn btn-primary w-100 change_status_payment"><i
                                class="la la-check"></i>{{trans('backend.wallet.approve')}}</button>
                    </td>
                    <td>
                        <button data-type="reject" data-id="{{$wallet->id}}" type="button"
                                class="btn btn-danger w-100 change_status_payment"><i
                                class="la la-times"></i> {{trans('backend.wallet.reject')}}</button>
                    </td>
                </tr>
            @endif
            </tfoot>
        </table>
    </div>
</div>






