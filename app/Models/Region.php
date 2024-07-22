<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $casts = [
        'search_keywords' => 'array'
    ];
    protected $dateFormat = 'Y-m-d H:i:s';
    use HasFactory;
}
