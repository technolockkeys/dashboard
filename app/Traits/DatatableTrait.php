<?php

namespace App\Traits;


trait DatatableTrait
{
    function create_script_datatable($route, $data_columns=[], $delete_all_route = null , $filters=[] ,$data_send = [] , $method ="post", $id = "datatable"  ,$status =true ,$search =true , $export= true , $name = '' ,$stop_search_column =[] )
    {
        return view('backend.shared.datatable' , compact('route','data_columns','delete_all_route','data_send','method','id' ,'status','filters' ,'search' , 'export' ,'name','stop_search_column'))->render();
    }

}
