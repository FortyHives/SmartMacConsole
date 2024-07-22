<?php

namespace App\Http\Controllers\analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Activities extends Controller
{
  public function index()
  {
    return view('content.analytics.activities');
  }
}
