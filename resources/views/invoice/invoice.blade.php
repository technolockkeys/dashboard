<?php
if (!function_exists("is_rtl")) {

    function is_rtl($lang = true)
    {
        //true is ltr
        if ($lang == true) {
            echo "text-align:left;";
        } else {
            echo "text-align:right;";
        }
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        * {
            font-family: Sans-serif;

        }
    </style>


</head>
<body style="font-family: Sans-serif;">
<header>

    <table style="width: 110%; margin:-24px -25px ; text-align:center; align:center;" cellpadding="0">
        <tbody>
        <tr style="height: 50px;">
            <th style="height: 60px; width: 19.5%;  font-size:20px;@if($order->type == 'proforma') background-color:#bfbfbf; @elseif($order->type == 'order')  background-color:#4CBB17; @endif ">{{trans('invoice.'.$order->type)}}</th>
            <th style="height: 60px; width: 60%; font-size:20px;"> {{trans('invoice.title')}}
            </th>
            <th style="height: 60px; width: 14%;  "><img style="width: 110px;" src="https://www.tlkeys.com/tlk-logo.png"
                                                         alt="#"></th>
        </tr>
        </tbody>
    </table>
    <br>
</header>


<table
    style="width: 119.5%; margin:0 -25px ; border-spacing: 0px; align:center; border-top: 8px solid #ED7D31; border-bottom: 8px solid #ED7D31;"
    cellpadding="2">

    <tbody>


    <tr style="height: 36px;">
        <td style="width: 18%;     ; height: 26px; background-color: #ED7D31; color:white; font-weight:bold; line-height: 1; text-align:center; font-size:13px;"
            rowspan="2">
            {{trans('backend.order.customer_details')}}
            <br>
            {{$user->name}}
            <br>
            {{$user->uuid}}
        </td>
        <td style="width: 24.346%; ; height: 26px; <?php is_rtl()?> font-size:16px;" rowspan="2">
            {{$order->address}}
            <br>
            {{trans('invoice.email')}}: {{$user->email}}
            <br>
            {{trans('invoice.phone')}}: {{$user->phone}}
        </td>
        <td style="width: 25.654%; ; height: 26px; <?php is_rtl()?> font-size:16px;" rowspan="2">
            {{$order->country?->name}}
            <br>
            {{$order->city}}
            <br>
            {{$order->address}}
        </td>
        <td style="width: 10%;    padding-bottom: -5px;  height: 26px; text-align:center; font-size:13px; color: #203764;">
            {{trans('invoice.date')}}
        </td>
        <td style="width: 10%;    padding-bottom: -5px;  height: 26px; text-align:center; font-size:13px; color: #203764;">
            {{trans('invoice.order_number')}}
        </td>
    </tr>
    <tr style="height: 26px;">
        <td style=" width: 10%; height: 26px; text-align:center; font-size:16px; background-color:#BFBFBF;	">
            {{$order->created_at}}
        </td>
        <td style="width: 10%; height: 26px; text-align:center; font-size:16px; background-color:#BFBFBF;">
            @php
                $qr= str_replace ('<?xml version="1.0" encoding="UTF-8"?>' ,'',strval($qr));
            @endphp
            {!!  $qr !!}


            {{$order->uuid}}</td>


    </tr>


    </tbody>
</table>
<br>


<table style="width: 105%; margin:0 -25px ; border-spacing: 0; " border="0">


    <tbody>
    <tr>
        <td style="width: 20%; font-size: 10px; text-align:center; color: #203764;"
            colspan="2">{{trans('invoice.no')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.sku')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.product_name')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.image')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.price')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.QTY')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.total')}}</td>
    </tr>
    @foreach($products as $key => $product)
        <tr>
            <td style="border: 1px solid #BFBFBF;  width: 20%; text-align:center; font-size: 10px;"
                colspan="2">{{$key + 1}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size: 10px;">{{$product->sku}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 25%; text-align:center; font-size: 10px;">{{$product->title}}
            </td>
            <td style="border: 1px solid #BFBFBF;  width: 18.9726%; text-align:center; ">
                @if(env('app_env')== 'local')
                    <img style="width: 80px;"
                         src="https://demotlk.esg-s.com/storage/Mercedes-Benz%20-FBS4%20-PCB-OEM-board-TL33321-min.jpg">
                @else
                    <img style="width: 80px;"
                         src="{{media_file($product?->image)}}">

                @endif
            </td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size:10px;">{{  exc_currency($product->pivot->price/$product->pivot->quantity, $order->exchange_rate , $currency->symbol )}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size:10px;">{{$product->pivot->quantity}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 15%; text-align:center; font-size:10px;">{{exc_currency($product->pivot->price  , $order->exchange_rate , $currency->symbol )}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="7" style="width: 10%; background-color:#BFBFBF; font-size:16px; text-align:right;"></td>
        <td style="width: 15%;background-color:#BFBFBF; text-align:center; font-size:16px;">{{exc_currency($order->total - $order->shipping+$order->coupon_value , $order->exchange_rate , $currency->symbol)}}</td>
    </tr>

    <tr>
        <td style="vertical-align: top; " colspan="2">
            <table width="100%">
                @if($order->type == \App\Models\Order::$proforma)
                    <tr>
                        <td style=" font-size:8px; <?php is_rtl() ?> border-bottom: 2px solid #BFBFBF; color: #203764; font-weight: bold;">
                            {{trans('invoice.shipment_description')}}
                        </td>
                    </tr>
                    <tr>
                        <td style=" font-size:12px; <?php is_rtl() ?> ">{{$order->shipment_description}}</td>
                    </tr>
                    <tr>
                        <td style=" font-size:8px; <?php is_rtl() ?> border-bottom: 2px solid #BFBFBF; color: #203764; font-weight: bold;">
                            {{trans('invoice.shipment_value')}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size:12px; <?php is_rtl() ?>">{{$order->shipment_value}}</td>
                    </tr>
                @else

                    <tr>
                        <td style=" font-size:8px; <?php is_rtl() ?> border-bottom: 2px solid #BFBFBF; color: #203764; font-weight: bold;">
                            {{trans('backend.order.note')}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size:12px; <?php is_rtl() ?>">{{$order->note}}</td>
                    </tr>
                @endif
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td style=" font-size:13px; text-align: center;"> </td>
                </tr>
            </table>
        </td>
        <td style="vertical-align: top;" colspan="3">
            <table width="100%">
                <tr>
                    <td rowspan="3">

                    </td>
                    <td>
                <tr>
                    <td style=" font-size:12px; <?php is_rtl() ?> border-bottom: 2px solid #BFBFBF; color: #203764; font-weight: bold; ">
                        {{trans('invoice.terms_and_conditions')}}
                    </td>
                </tr>
                <tr>
                    <td style=" font-size:12px; <?php is_rtl() ?>">Please make check payable to Your Company Name.</td>
                </tr>
                </td>

                </tr>
                @if($order->seller != null)
                    <tr>
                        <td style="font-size:12px; <?php is_rtl(); ?>  padding: 5px; background-color:#BFBFBF; "
                            colspan="2">{{trans('invoice.seller_info')}}
                            :
                        </td>
                    </tr>
                    <tr>

                        <td style=" font-size:12px; <?php is_rtl() ?> ">{{$order->seller?->name}}</td>
                        <td style="font-size:12px; <?php is_rtl() ?> letter-spacing: 2px;"><a
                                href="{{$order->seller?->email}}">{{$order->seller?->email}}</a></td>

                    </tr>
                    <tr>
                        <td style=" font-size:12px; <?php is_rtl() ?> ">{{$order->seller?->phone}}</td>
                        <td style="font-size:12px; <?php is_rtl() ?> letter-spacing: 2px;"><a
                                href="{{get_setting('site_url')}}">{{get_setting('site_url')}}</a></td>
                    </tr>
                @endif
            </table>
        </td>


        <td style="vertical-align: top; text-align:right;" colspan="3">
            <table width="100%">
                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6;">{{trans('invoice.subtotal')}}</td>
                    <td style="width: 50%; border-bottom: 2px solid #BFBFBF; <?php is_rtl(true) ?> font-size:12px;  text-align: center; line-height: 1.6"
                        colspan="2">{{exc_currency($order->total- $order->shipping+$order->coupon_value , $order->exchange_rate , $currency->symbol)}}
                    </td>
                </tr>
                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6;">{{trans('invoice.discount')}}
                    </td>
                    <td style=" width: 50%; border-bottom: 2px solid #BFBFBF; <?php is_rtl(true) ?> font-size:12px;  text-align: center; line-height: 1.6"
                        colspan="2">
                        {{exc_currency($order->coupon_value , $order->exchange_rate , $currency->symbol)}}
                    </td>
                </tr>

                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6;">{{trans('invoice.shipping')}}
                    </td>
                    <td style="width: 50%; border-bottom: 2px solid #BFBFBF; <?php is_rtl(true) ?> font-size:12px;  text-align: center; line-height: 1.6"
                        colspan="2">
                        {{exc_currency($order->shipping, $order->exchange_rate , $currency->symbol)}}
                    </td>
                </tr>
                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6;">
                        {{trans('invoice.total_after_discount')}}
                    </td>
                    <td style="width: 50%; border-bottom: 2px solid #BFBFBF; <?php is_rtl(true) ?> font-size:12px;  text-align: center;  line-height: 1.6"
                        colspan="2">
                        {{exc_currency($order->total, $order->exchange_rate , $currency->symbol)}}
                    </td>
                </tr>
                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6; padding-top: 7px;">
                        {{trans('invoice.total')}}
                    </td>
                    <td style="width: 50%; background-color: #00B050; font-size: 15px; text-align: center; color:white; font-weight: bold; line-height: 1.6 "
                        colspan="2">
                        {{exc_currency($order->total, $order->exchange_rate , $currency->symbol)}}
                    </td>
                </tr>
            </table>
        </td>


    </tr>

    <tr style="background-color:#ED7D31; ">
        <td colspan="8" style="  color:white; <?php is_rtl(); ?> font-size: 10px; text-align: center;">
            <u><i>{{trans('invoice.footer')}}</i> </u></td>
    </tr>

    </tbody>
</table>


</body>
</html>






