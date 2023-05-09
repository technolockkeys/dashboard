 <tr  style="border-top:  1px solid #eee ; margin-top: 2px ; padding-top: 10px;padding-bottom: 10px">
     <td>{{$wallet->order->uuid}}</td>
     <td>{{$wallet->created_at}}</td>
     <td>{{$wallet->type}}</td>
     <td>{{$wallet->status}}</td>
     <td align="right">{{currency($wallet->amount)}}</td>

 </tr>
