@php
    $product_url = implode('/',[get_setting('app_url') , 'product' ,$product?->slug]);
@endphp
<tr style="margin-top: 3px">
    <td  align="center"><a href="{{$product_url}}" target="_blank"><img width="75px" height="75px" data-image="{{$product?->image}}"
                                                        src="{{check_image(media_file($product?->image)) ? media_file($product?->image) : media_file(get_setting('default_images'))}}" alt=""></a></td>
    <td  align="left">
        <a href="{{$product_url}}" target="_blank">{{$product?->sku}}<br>
            {{$product?->title}}


            @if(!empty($prod) && !empty($prod->serial_number))
                <br>
                serial numbers :
                {!! implode(',' , json_decode($prod->serial_number , true)) !!}
            @endif


        </a>

    </td>
    @if($product?->quantity)
    <td  align="center"> {{$product?->quantity}}</td>
    @endif
    <td  align="center">

        @if(!$product?->hide_price )
            <a href="{{$product_url}}" target="_blank"><p
                    style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#333333;font-size:16px">{{currency($product?->price)}}</p></a>
        @endif
      </td>
</tr>


