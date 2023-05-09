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
                                                    style="padding:0;Margin:0;padding-bottom:10px">
                                                    <h4
                                                        style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif">
                                                        Customer Details</h4>
                                                    name : {{$user->name}} ({{$user->uuid}}
                                                    )<br>
                                                    phone : <a
                                                        href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                                    <br>
                                                    mail : <a
                                                        href="mailto:{{$user->email}}">{{$user->email}}</a>


                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
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
                                                    Order No
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    Date
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    Type
                                                </td>
                                                <td bgcolor="#eeeeee" align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    status
                                                </td>
                                                <td bgcolor="#eeeeee" align="right"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    amount
                                                </td>
                                            </tr>
                                            @php
                                                $total = 0 ;

                                            @endphp
                                            @foreach($wallets as $wallet)
                                                @include('email.account_statement_row')
                                                @php
                                                    $total += $wallet->amount;

                                                @endphp
                                            @endforeach
                                            <tr style="border-top:  1px solid #eee ; margin-top: 2px ; padding-top: 10px;padding-bottom: 10px">
                                                <td colspan="3"></td>
                                                <td colspan="1" align="left"><b>Total</b></td>
                                                <td colspan="1" align="right"><b>{{currency($total)}}</b></td>
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
@endsection
