@extends('email.layout.mail')
@section('content')
    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full"
           style="max-width:600px!important">
        <tr>

            <td align="center" width="100%" valign="middle" class="em_pad">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full"
                       style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                    <tr>
                        <td align="center" width="100%" valign="middle" bgcolor="#ffffff"
                            style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;">

                            <div class="sortable_inner" align="left" style="padding: 30px">
                                <h1><b>@lang('email.clickHereToVerify')</b></h1>
                                <p>hello {{@$name}}</p>
                                <p> Welcome to {{get_translatable_setting('system_name' ,'en')}}
                                </p>
                                <p>please click the button below to verify your email</p>

                                <p>if you did not sing up tp TLKEYS please ignore this email or contact us as
                                    info@tlkeys.com</p>
                                <div width="600" align="center" style="padding: 30px">
                                    <a href="{{$url}}"
                                       style="padding: 10px ;color: #Fff; background: #e0a800">@lang('email.clickHereToVerify')</a>


                                </div>

                            </div>

                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>

@endsection
