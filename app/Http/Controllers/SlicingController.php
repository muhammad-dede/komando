<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SlicingController extends Controller
{
    public function dod()
    {
        return view('slicing.dod');
    }

    public function editdod()
    {
        return view('slicing.edit-dod');
    }

    public function feedback()
    {
        return view('slicing.feedback');
    }

    public function detaildod()
    {
        return view('slicing.detail-dod');
    }

    public function penilaian()
    {
        return view('slicing.penilaian.penilaian');
    }

    public function penilaianEdit()
    {
        return view('slicing.penilaian.penilaian-edit');
    }

    public function penilaianCreate()
    {
        return view('slicing.penilaian.penilaian-create');
    }

    public function pengukuranKeduaCreate()
    {
        return view('slicing.pengukuran-kedua.pengukuran-kedua-create');
    }

    public function pengukuranKeduaEdit()
    {
        return view('slicing.pengukuran-kedua.pengukuran-kedua-edit');
    }

    public function dashboardAdminSatu()
    {
        return view('slicing.dashboard-admin.dashboard-admin-satu');
    }

    public function dashboardAdminDua()
    {
        return view('slicing.dashboard-admin.dashboard-admin-dua');
    }

    public function dashboardAtasanStatusLiquid()
    {
        return view('slicing.dashboard-atasan.dashboard-atasan-status-liquid');
    }

    public function dashboardAtasanKalendarLiquid()
    {
        return view('slicing.dashboard-atasan.dashboard-atasan-kalendar-liquid');
    }

    public function feedbackListAtasan()
    {
        return view('slicing.list-atasan.feedback-list-atasan');
    }

    public function pengukuranPertamaListAtasan()
    {
        return view('slicing.list-atasan.pengukuran-pertama-list-atasan');
    }

    public function pengukuranKeduaListAtasan()
    {
        return view('slicing.list-atasan.pengukuran-kedua-list-atasan');
    }

    public function pengukuranKedua()
    {
        return view('slicing.pengukuran-kedua.pengukuran-kedua');
    }

    public function penilaianAtasan()
    {
        return view('slicing.penilaian-atasan');
    }

    public function inputActivityLog()
    {
        return view('slicing.activity-log.input-activity-log');
    }

    public function activityLog()
    {
        return view('slicing.activity-log.list-activity-log');
    }

    public function penyelarasan()
    {
        return view('slicing.penyelarasan');
    }

    public function listPenyelarasan()
    {
        return view('slicing.list-penyelarasan');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
