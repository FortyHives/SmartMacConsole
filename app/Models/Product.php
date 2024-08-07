<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Product extends Model
{
    //protected $table = 'some_table';
    protected $casts = [
      'photo_urls' => 'array',
        ];

    protected $dateFormat = 'Y-m-d H:i:s';
    use HasFactory;
}
