@extends('email.layout.mail')
@section('content')
    <table class="es-content" cellspacing="0" cellpadding="0" align="center"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
        <tr style="border-collapse:collapse">
            <td align="center" style="padding:0;Margin:0">
                <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
                       align="center"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                    <tr style="border-collapse:collapse">
                        <td align="left"
                            style="padding:0;Margin:0;padding-top:20px;padding-left:35px;padding-right:35px">
                            <table width="100%" cellspacing="0" cellpadding="0"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr style="border-collapse:collapse">
                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                               role="presentation"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                            <tr style="border-collapse:collapse">
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    image
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    product name
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    quantity
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    price
                                                </td>
                                            </tr>
                                            @foreach($products as $prod)
                                                @php
                                                    $product= $prod->product;
                                                @endphp

                                                @include('email.product_row')
                                            @endforeach
                                            <tr>
                                                <td colspan="4" style="border:  1px solid #eeeeee"></td>
                                            </tr>
                                            @if(!empty($order->coupon_value))
                                                <tr>
                                                    <td colspan="1"></td>
                                                    <td colspan="2" align="right">
                                                        <p>{{trans('backend.order.coupon')}}</p></td>
                                                    <td colspan="1" align="right">
                                                        <p>{{currency($order->coupon_value)}}</p></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="1"></td>
                                                <td colspan="2" align="right">
                                                    <p>{{trans('backend.order.shipping')}}</p></td>
                                                <td colspan="1" align="right"><p>{{currency($order->shipping)}}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"></td>
                                                <td colspan="2" align="right"><p>{{trans('backend.order.total')}}
                                                        ({{ count($products).' item'}})</p>
                                                </td>
                                                <td colspan="1" align="right"><p>{{currency($order->total)}}</p></td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"></td>
                                                <td colspan="2" align="right"></td>
                                                <td colspan="1" align="right"
                                                    style="color: {{ $order->paid ==1 ? '#0BB783' : '#ff0000'}}"><b>

                                                        {{ $order->paid ==1 ? 'Paid' : 'Unpaid' }}
                                                        @if(!empty($order) && !empty($order->id) && !$order->paid && $order->payment_method == \App\Models\Order::$stripe_link)
                                                            @php
                                                                $order_payment = \App\Models\OrderPayment::query()->where('order_id', $order->id)->where('payment_method', \App\Models\Order::$stripe_link)->first();
                                                            @endphp
                                                            {!! !empty($order_payment) ? "<br/><a href='".$order_payment->stripe_url."'>pay now</a>" : ''  !!}
                                                        @endif


                                                    </b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <tr style="border-collapse:collapse">
                        <td align="left"
                            style="Margin:0;padding-left:35px;padding-right:35px;padding-top:40px;padding-bottom:40px">
                            <!--[if mso]>
                            <table style="width:530px" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:255px" valign="top"><![endif]-->
                            <table class="es-left" cellspacing="0" cellpadding="0" align="left"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
                                <tr style="border-collapse:collapse">
                                    <td class="es-m-p20b" align="left"
                                        style="padding:0;Margin:0;width:255px">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                               role="presentation"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                            <tr style="border-collapse:collapse">
                                                <td align="left"
                                                    style="padding:0;Margin:0;padding-bottom:15px"><h4
                                                        style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif">
                                                        Delivery Address</h4></td>
                                            </tr>
                                            <tr style="border-collapse:collapse">
                                                <td align="left"
                                                    style="padding:0;Margin:0;padding-bottom:10px"><p
                                                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">
                                                        {{$order->country->name}}</p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">
                                                        {{$order->city}}</p>
                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">
                                                        {{$order->address}}
                                                        <br>
                                                    <h4
                                                        style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif">
                                                        Billing Info</h4>
                                                    name : {{$order->user->name}} ({{$order->user->uuid}}
                                                    )<br>
                                                    phone : <a
                                                        href="tel:{{$order->user->phone}}">{{$order->user->phone}}</a>
                                                    <br>
                                                    mail : <a
                                                        href="mailto:{{$order->user->email}}">{{$order->user->email}}</a>
                                                    <br>
                                                    @if(!empty($order->note))
                                                        note :   {{$order->note}}
                                                        @endif
                                                        </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!--[if mso]></td>
                            <td style="width:20px"></td>
                            <td style="width:255px" valign="top"><![endif]-->
                            <table class="es-right" cellspacing="0" cellpadding="0" align="right"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
                                <tr style="border-collapse:collapse">
                                    <td align="left" style="padding:0;Margin:0;width:255px">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                               role="presentation"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                            @if(!empty($order->seller_id))
                                                <tr style="border-collapse:collapse">
                                                    <td align="right"
                                                        style="padding:0;Margin:0;padding-bottom:15px "><h4
                                                            style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif">
                                                            Seller Information<br></h4></td>
                                                </tr>
                                                <tr style="border-collapse:collapse">
                                                    <td align="right" style="padding:0;Margin:0"><p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">
                                                            {{$order->seller->name}}
                                                            <br>
                                                            <a href="mailto:{{$order->seller->email}}">{{$order->seller->email}}</a>
                                                            <br>
                                                            <a href="tel:{{$order->seller->phone}}">{{$order->seller->phone}}</a>

                                                        </p></td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!--[if mso]></td></tr></table><![endif]--></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
