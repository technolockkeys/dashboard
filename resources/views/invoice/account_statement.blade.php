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
            <th style="height: 60px; width: 19.5%;  font-size:20px;  background-color:#4CBB17; ">{{trans('invoice.account_statement')}}</th>
            <th style="height: 60px; width: 60%; font-size:20px;"> {{trans('invoice.title')}}
            </th>
            <th style="height: 60px; width: 14%;  "><img style="width: 110px;" src="https://www.tlkeys.com/tlk-logo.png"
                                                         alt="logo"></th>
        </tr>
        </tbody>
    </table>
    <br>
</header>


<table style="width: 119.5%; margin:0 -25px ; border-spacing: 0px; align:center; border-top: 8px solid #ED7D31; border-bottom: 8px solid #ED7D31;"
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
            <br>
            {{trans('invoice.email')}}: {{$user->email}}
            <br>
            {{trans('invoice.phone')}}: {{$user->phone}}
        </td>
        <td style="width: 25.654%; ; height: 26px; <?php is_rtl()?> font-size:16px;" rowspan="2">

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
            {{\Carbon\Carbon::now()->format('Y-M-D')}}
        </td>
        <td style="width: 10%; height: 26px; text-align:center; font-size:16px; background-color:#BFBFBF;">



{{--            {{$w->uuid}}</td>--}}


    </tr>


    </tbody>
</table>
<br>


<table style="width: 105%; margin:0 -25px ; border-spacing: 0; " border="0">


    <tbody>
    <tr>
        <td style="width: 20%; font-size: 10px; text-align:center; color: #203764;"
            >{{trans('invoice.no')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;" colspan="2">{{trans('invoice.date')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;">{{trans('invoice.type')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;" colspan="2">{{trans('invoice.order')}}</td>
        <td style="width: 10%; font-size: 10px; text-align:center; color: #203764;" colspan="2">{{trans('invoice.amount')}}</td>
    </tr>
    @foreach($wallets as $key => $wallet)
        <tr>
            <td style="border: 1px solid #BFBFBF; height: 25px; width: 20%; text-align:center; font-size: 10px;"
               >{{$key + 1}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size: 10px;" colspan="2">{{$wallet->created_at}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 25%; text-align:center; font-size: 10px;" colspan="2">{{$wallet->type}}
            </td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size:10px;">{{$wallet->order->uuid}}</td>
            <td style="border: 1px solid #BFBFBF;  width: 10%; text-align:center; font-size:10px;" colspan="2">{{currency($wallet->amount)}}</td>
        </tr>
    @endforeach


    <tr>
        <td style="vertical-align: top; " colspan="2">
            <table width="100%">

                <tr>
                    <td style=" font-size:13px; text-align: center;">{{trans('invoice.thank_you')}}</td>
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

            </table>
        </td>


        <td style="vertical-align: top; text-align:right;" colspan="3">
            <table width="100%">

                <tr>
                    <td style=" font-size:12px; <?php is_rtl(true) ?>  line-height:1.6; padding-top: 7px;">
                        {{trans('invoice.total')}}
                    </td>
                    <td style="width: 50%; background-color: #00B050; font-size: 15px; text-align: center; color:white; font-weight: bold; line-height: 1.6 "
                        colspan="2">
                        {{currency($user->wallet()->sum('amount'))}}
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






