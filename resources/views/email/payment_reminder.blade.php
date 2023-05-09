@extends('email.layout.mail')
@section('content')
    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center"   class="full" style="max-width:400px!important" >
        <tr>

            <td align="center" width="100%" valign="middle" class="em_pad">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                    <tr>
                        <td align="center" width="100%" valign="middle" bgcolor="#ffffff" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;">

                            <div class="sortable_inner">





                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
                                    <tr>
                                        <td align="center" width="600" valign="middle" class="em_pad">

                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                                                <tr>
                                                    <td width="100%" align="center" style="font-size:30px; color:#161619; font-family:'Lato',Arial, sans-serif; font-weight:400; line-height:34px;">
                                                        {{trans('email.payment_reminder_title', ['amount' => $user->wallet()->where('status', 'approve')->sum('amount')])}} </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                                    <tr>
                                        <td height="30" class="em_h30"><img src="images/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
                                    </tr>
                                </table>
                                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
                                    <tr>
                                        <td align="center" width="600" valign="middle" class="em_pad">

                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                                                <tr>
                                                    <td width="100%" align="center" style="font-size:15px; color:#ababab; font-family:'Lato',Arial, sans-serif; font-weight:400; line-height:23px;">
                                                    {{trans('email.payment_reminder',['name' => $user->name,])}}
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>




                            </div>

                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>
@endsection
