<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;

class RekapPartisipanController extends Controller
{
    public function index()
    {
        $nav = "partisipan";

        return view('liquid.dashboard-admin.rekap-partisipan', compact('nav'));
    }
}
