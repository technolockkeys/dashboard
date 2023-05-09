<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements  ToModel
{


    /**
     * @param array $row
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        return  $row;
     }
}
