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
                                <h1  bgcolor="#fff" width="600 ">Please reset your password</h1>
                                <p  bgcolor="#fff" width="600 ">
                                    Hello,
                                    <br>
                                    We have sent you this email in response to your request to reset your password on company name.
                                    <br>
                                    To reset your password, please follow the link below:
                                </p>      <div width="600" align="center" style="padding: 30px">
                                    <a href="{{$route}}"
                                       style="padding: 10px ;color: #Fff; background: #e0a800">Reset Password</a>


                                </div>

                            </div>

                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>


@endsection
