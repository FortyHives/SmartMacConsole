<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Planogram extends Model
{
    //protected $table = 'some_table';
    protected $casts = [
      'comparison_products_id' => 'array'
];
    protected $dateFormat = 'Y-m-d H:i:s';
    use HasFactory;
}
