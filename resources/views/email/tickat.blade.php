@extends('email.layout.mail')
@section('content')

    <table width="600" class="full" border="0"
           cellspacing="0" cellpadding="0" align="center" bgcolor="#fff"
           object="drag-module-small">
        <tr>
            <td valign="top" align="center"
                style="font-family:'Lato', Arial, sans-serif; font-weight:400; font-size:15px; color:#ababab; line-height:23px;"
                class="em_center">
                {{$details['user']->name}} posted a ticket
                <br>
                {!! $details['content'] !!}

            </td>
        </tr>
    </table>

    <table   border="0" cellspacing="0"
           cellpadding="0" width="600"  align="center" bgcolor="#fff"
           object="drag-module-small">
        <tr>
            <td height="30" class="em_h40">&nbsp;
            </td>
        </tr>
    </table>

@endsection
