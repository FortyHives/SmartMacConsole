<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  OutletCategory extends Model
{
    //protected $table = 'some_table'
    protected $dateFormat = 'Y-m-d H:i:s';
    use HasFactory;
}
