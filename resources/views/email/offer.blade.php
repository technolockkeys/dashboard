@extends('email.layout.mail')
@section('content')
    <table width="100%" border="0" cellpadding="0" cellspacing="0"
           align="center" class="em_wrapper" object="drag-module-small">
        <tr>
            <td align="center" width="100%" valign="middle">

                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                       align="center"
                       style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                       class="fullCenter">
                    <tr>
                        <td width="100%" align="center"
                            style="font-size:16px; color:#aaaaaa; font-family:'Lato',Arial, sans-serif; font-weight:300;">
                            {{trans('email.thank_you_for_choosing_us')}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0"
           align="center" class="em_wrapper" object="drag-module-small">
        <tr>
            <td align="center" width="100%" valign="middle">

                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                       align="center"
                       style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                       class="fullCenter">
                    <tr>
                        <td width="100%" align="center"
                            style="font-size:16px; color:#aaaaaa; font-family:'Lato',Arial, sans-serif; font-weight:300;">
                            You have a new coupon:
                            {{$details['coupon']->discount}} {{$details['coupon']->type == \App\Models\Coupon::$Amount? '$': '%'}}
                            Discount on your next order
                            {{\Carbon\Carbon::parse($details['coupon']->ends_at)->format('Y-M-D')}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0"
           align="center" class="full" object="drag-module-small">
        <tr>
            <td align="center" width="100%" valign="middle">

                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                       align="center"
                       style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                       class="fullCenter">
                    <tr>
                        <td width="100%" align="center"
                            style="font-size:16px; color:#000000; font-family:'Lato',Arial, sans-serif; font-weight:400; line-height:26px;">
                            A {{$details['coupon']->discount}} {{$details['coupon']->type == \App\Models\Coupon::$Amount? '$': '%'}}
                            DISCOUNT FOR YOU
                            @if($details['coupon']->free_shipping )
                                + Free Shipping
                            @endif
                            <h1>{{$details['coupon']->code}} </h1>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <table width="100%" border="0" cellpadding="0" cellspacing="0"
           align="center" class="full" object="drag-module-small">
        <tr>
            <td align="center" width="100%" valign="middle">

                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                       align="center"
                       style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                       class="fullCenter">
                    <tr>
                        <td width="100%" align="center"
                            style="font-size:16px; color:#000000; font-family:'Lato',Arial, sans-serif; font-weight:400; line-height:26px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table align="center" width="100%" border="0" cellspacing="0"
           cellpadding="0" object="drag-module-small">
        <tr>
            <td height="35"><img src="images/spacer.gif" width="1" alt=""
                                 height="1" border="0" style="display:block;"/>
            </td>
        </tr>
    </table>
    <table align="center" width="570" border="0" cellspacing="0" cellpadding="0"
           class="full" object="drag-module-small">
        @foreach($details['products'] as $product)

            <tr>
                <td height="15" bgcolor="#fafafa"><img
                        src="{{media_file($product->image)}}"
                        width="1" alt=""
                        height="1" border="0"
                        style="display:block;"/>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center" bgcolor="#fafafa"
                    class="em_pad">

                    <table align="center" width="500" border="0" cellspacing="0"
                           cellpadding="0" class="full">
                        <tr>
                            <td valign="middle" align="left" width="63%"
                                style="font-size:13px; color:#aaaaaa; font-family:'Lato',Arial, sans-serif; font-weight:400;">
                                <a href="{{rtrim(get_setting('app_url'),'/')}}/product/{{$product->slug}}"
                                   style="text-decoration: none">

                                    {{$product->short_title}} <br/>
                                    <span
                                        style="font-size:15px; color:#f17805; font-family:'Lato',Arial, sans-serif; font-weight:700;">{{ $product->title}} </span>
                                </a>


                            </td>
                            <td width="25%" align="left"
                                style="font-size:13px; color:#aaaaaa; font-family:'Lato',Arial, sans-serif; font-weight:400;">{{currency($product->price)}}
                                <br/>
                                @php

                                    $product_price = $details['coupon']->discount_type == 'Amount'? $product->price - $details['coupon']->discount:
                                    $product->price - (($product->price / 100)* $details['coupon']->discount)
                                @endphp
                                <span
                                    style="font-size:15px; color:#aaaaaa; font-family:'Lato',Arial, sans-serif; font-weight:700;"> {{currency($product_price)}}</span>
                            </td>
                            <td><img src="{{media_file($product->image)}}"
                                     width="58" alt="" border="0"/></td>
                        </tr>
                    </table>

                </td>
            </tr>
        @endforeach

    </table>

@endsection
