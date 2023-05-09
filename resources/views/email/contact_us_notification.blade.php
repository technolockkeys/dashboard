 @extends('email.layout.mail')
 @section('content')
     <table width="600" class="myfull" border="0" cellspacing="0" align="center" bgcolor="#fff"
            cellpadding="0">

         <tr>

             <td valign="top">
                 <div class="shortable_inner">
                     <table width="100%" border="0" cellspacing="0"
                            cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td height="47" class="em_h40">&nbsp;
                             </td>
                         </tr>
                     </table>

                     <table align="" width="100%" class="" border="0"
                            cellspacing="0" cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td valign="top" align="center"
                                 style="font-family:'Lato', Arial, sans-serif; font-weight:700; font-size:34px; color:#000000; line-height:38px;"
                                 class="heading">
                                 {{$details['title']}}
                             </td>
                         </tr>
                     </table>
                     <table width="100%" border="0" cellspacing="0"
                            cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td height="30" class="em_h20">&nbsp;
                             </td>
                         </tr>
                     </table>

                     <table width="570" class="full" border="0"
                            cellspacing="0" cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td valign="top" align="center"
                                 style="font-family:'Lato', Arial, sans-serif; font-weight:400; font-size:15px; color:#ababab; line-height:23px;"
                                 class="em_center">
                                 {{$details['contact']->name}} Send a request
                                 <br>
                                 {!! $details['content'] !!}

                             </td>
                         </tr>
                     </table>

                     <table width="570" border="0" cellspacing="0"
                            cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td height="30" class="em_h40">&nbsp;
                             </td>
                         </tr>
                     </table>
                     {{--                                                                        {!! json_encode($details) !!}--}}
                    <table width="570">
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
                                price
                            </td>
                        </tr>
                        @if(!empty($details['product']))
                            @php
                                $product =$details['product'];
                            @endphp
                            @include('email.product_row')
                        @endif
                    </table>

                     <table border="0" cellpadding="0"
                            cellspacing="0" align="center"
                            class="myfull"
                            object="drag-module-small">
                         <tr>
                             <td width="100%" align="center">
                                 <table border="0" cellpadding="0"
                                        cellspacing="0"
                                        align="center"
                                        style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                        class="mcenter">

                                 </table>
                             </td>
                         </tr>
                     </table>
                     <!-- End Button -->

                     <table width="100%" border="0" cellspacing="0"
                            cellpadding="0"
                            object="drag-module-small">
                         <tr>
                             <td height="50" class="em_h40">&nbsp;
                             </td>
                         </tr>
                     </table>
                 </div>

             </td>


         </tr>
     </table>
 @endsection
