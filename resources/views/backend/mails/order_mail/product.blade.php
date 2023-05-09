@php
    $product_url = implode('/',[get_setting('app_url') , 'product' ,$product->product->slug]);
@endphp
<tr style="border-collapse:collapse">
    <td style="padding:5px 10px 5px 0;Margin:0" width="80%" align="left">
        <p
            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">

        <table>
            <tr>
                <td><a href="{{$product_url}}" target="_blank"><img width="75px" height="75px" data-image="{{$product->product->image}}"
                                        src="{{media_file($product->product->image)}}" alt=""></a></td>
                <td>
                    <a href="{{$product_url}}" target="_blank">{{$product->product->sku}}<br>
                        {{$product->product->title}} ( X {{$product->quantity}} ) </a>
                </td>
            </tr>
        </table>


        </p></td>
    <td style="padding:5px 0;Margin:0" width="20%" align="left"><a href="{{$product_url}}" target="_blank"><p
                style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">{{currency($product->price)}}</p></a>
    </td>
</tr>
