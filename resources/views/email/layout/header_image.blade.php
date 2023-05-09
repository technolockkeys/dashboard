@if(!empty($type))
<table class="es-content" cellspacing="0" cellpadding="0" align="center"
       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
    <tr style="border-collapse:collapse">
        <td align="center" style="padding:0;Margin:0">
            <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
                   align="center"
                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                <tr style="border-collapse:collapse">
                    <td align="left"
                        style="padding:0;Margin:0;padding-left:35px;padding-right:35px;padding-top:40px">
                        <table width="100%" cellspacing="0" cellpadding="0"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                            <tr style="border-collapse:collapse">
                                <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                    <table width="100%" cellspacing="0" cellpadding="0"
                                           role="presentation"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                       @if(!empty($image))
                                            <tr style="border-collapse:collapse">
                                                <td align="center"
                                                    style="Margin:0;padding-top:25px;padding-bottom:25px;padding-left:35px;padding-right:35px;font-size:0">
                                                    <a target="_blank" href="{{asset('/')}}"
                                                       style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#ED8E20;font-size:16px"><img
                                                            src="{{$image}}" alt
                                                            style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                            width="50%"></a></td>
                                            </tr>
                                       @endif

                                        <tr style="border-collapse:collapse">
                                            <td align="center"
                                                style="padding:0;Margin:0;padding-bottom:10px">
                                                <h2
                                                    style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:30px;font-style:normal;font-weight:bold;color:#333333">
                                                    @if($type == "create")
                                                        {{trans('backend.order.mail.than_you_for_your_order')}}
                                                    @elseif($type=='processing' && !empty($order))
                                                        {{trans('backend.order.mail.your_order_is_in_processing_status')}}
                                                        @if(!empty($order->tracking_number))
                                                            <h4>
                                                                {{trans('backend.order.mail.tracking_number')}}
                                                                {{$order->tracking_number}}
                                                            </h4>
                                                        @endif
                                                    @elseif($type =='completed')
                                                        {{trans('backend.order.mail.your_order_is_in_completed_status')}}
                                                    @elseif($type =='failed')
                                                        {{trans('backend.order.mail.your_order_is_in_failed_status')}}

                                                    @elseif($type == 'statement')
                                                        {{trans('backend.order.mail.statement.payment_information')}}
                                                    @elseif($type == 'feedback')
                                                        {{trans('backend.order.mail.statement.feedback')}}
                                                    @endif

                                                </h2>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse">
                                            <td align="left"
                                                style="padding:0;Margin:0;padding-top:15px;padding-bottom:20px">
                                                @if(!in_array($type,['statement','feedback']) && !empty($order))
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#777777;font-size:16px">
                                                        {{trans('backend.order.mail.order_content' , ['name'=>$order->user->name  , 'order_uuid'=>$order->uuid])}}</p>
                                                @endif
                                                @if($type == "completed")

                                                    <h3 style="color:red">
                                                        <b>{{trans('backend.order.mail.please_go_to_each_product_and_rate_it')}}</b>
                                                    </h3>

                                                @elseif($type == 'statement' && !empty($order))
                                                    @switch($data_statement['type']  )
                                                        @case('amount')
                                                        {{trans('backend.order.mail.statement.amount',['order'=>$order->uuid ,'name'=>$order->user->name , 'amount'=>$data_statement['amount'] , 'balance'=>$data_statement['balance_order']])}}
                                                        @break
                                                        @case('order')
                                                        {{trans('backend.order.mail.statement.amount',['order'=>$order->uuid ,'name'=>$order->user->name , 'amount'=>$data_statement['amount'] , 'balance'=>$data_statement['balance_order']])}}
                                                        @break
                                                        @case('withdraw')
                                                        {{trans('backend.order.mail.statement.withdraw',['order'=>$order->uuid ,'name'=>$order->user->name , 'amount'=>$data_statement['amount'] , 'balance'=>$data_statement['balance_order']])}}
                                                        @break
                                                        @case('refund')
                                                        {{trans('backend.order.mail.statement.refund',['order'=>$order->uuid ,'name'=>$order->user->name , 'amount'=>$data_statement['amount'] , 'balance'=>$data_statement['balance_order']])}}
                                                        @break
                                                    @endswitch
                                                @elseif($type=='feedback' && !empty($order))
                                                    <h3>
                                                        {{trans('backend.order.mail.statement.feedback_statement',['name'=>$order->user->name ])}}

                                                    </h3>


                                                @endif

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
