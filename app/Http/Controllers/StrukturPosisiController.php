<?php

namespace App\Http\Controllers;

use App\StrukturPosisi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

class StrukturPosisiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $posisi_list = StrukturPosisi::all();
//        return view('master.posisi_list', compact('posisi_list'));
        return view('master.posisi_list');
    }

    public function serverProcessing(){

        return Datatables::of(StrukturPosisi::query())->make(true);
    }
}
