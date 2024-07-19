<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('liquid.dashboard.index');
    }
}
