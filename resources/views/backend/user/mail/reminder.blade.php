@extends('email.layout.mail')
@section('content')
    <table width="100%" cellspacing="0" cellpadding="0"
           role="presentation"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
        <tr style="border-collapse:collapse">
            <td align="center"
                style="Margin:0;padding-top:25px;padding-bottom:25px;padding-left:35px;padding-right:35px;font-size:0">
                <a target="_blank" href="{{asset('/')}}"
                   style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;color:#ED8E20;font-size:16px"><img
                        src="{{@$image}}" alt
                        style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                        width="50%"></a></td>
        </tr>
        <tr style="border-collapse:collapse">
            <td align="center"
                style="padding:0;Margin:0;padding-bottom:10px">
                <h2
                    style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;font-size:30px;font-style:normal;font-weight:bold;color:#333333">
                    {{trans('email.reminder.'.$type ,['name'=>$user->name])}}

                </h2>
            </td>
        </tr>
        <tr style="border-collapse:collapse">
            <td align="left"
                style="padding:0;Margin:0;padding-top:15px;padding-bottom:20px">


            </td>
        </tr>
    </table>
    <table class="es-content" cellspacing="0" cellpadding="0" align="center"
           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
        <tr style="border-collapse:collapse">
            <td align="center" style="padding:0;Margin:0">
                <table class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
                       align="center"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">

                    <tr style="border-collapse:collapse">
                        <td align="left" style="padding:0;Margin:0;padding-left:35px;padding-right:35px">
                            <table width="100%" cellspacing="0" cellpadding="0"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr style="border-collapse:collapse">
                                    <td valign="top" align="center" style="padding:0;Margin:0;width:530px">
                                        <table width="100%" cellspacing="0" cellpadding="0"
                                               role="presentation"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                            <tr style="border-collapse:collapse">
                                                <td align="left"
                                                    style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px">
                                                    <table
                                                        style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:500px"
                                                        class="cke_show_border" cellspacing="1"
                                                        cellpadding="1" border="0" align="left"
                                                        role="presentation">
                                                        @foreach($products as $product)
                                                            @include('backend.user.mail.product')
                                                        @endforeach
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
            </td>
        </tr>
    </table>
@endsection
