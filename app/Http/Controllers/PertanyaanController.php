<?php

namespace App\Http\Controllers;

use App\Jawaban;
use App\PedomanPerilaku;
use App\Pertanyaan;
use App\PLNTerbaik;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PertanyaanController extends Controller
{
    public function index()
    {
        $pertanyaan_list = Pertanyaan::orderBy('id', 'desc')->get();
        return view('master.pertanyaan_list', compact('pertanyaan_list'));
    }

    public function createPilihanGanda()
    {
//        $pedoman_list = PedomanPerilaku::all();
        $pedoman_list[0] = 'Pilih pedoman perilaku';
//        foreach (PedomanPerilaku::orderBy('id')->get() as $wa) {
        foreach (PLNTerbaik::orderBy('id')->get() as $wa) {
            $pedoman_list[$wa->id] = $wa->nomor_urut . ' ' . $wa->pedoman_perilaku;
        }
        return view('master.pilihan_ganda_create', compact('pedoman_list'));
    }

    public function storePilihanGanda(Request $request)
    {
//        dd($request);
        $pertanyaan = new Pertanyaan();
        $pertanyaan->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->jenis = 1;
        $pertanyaan->save();

        for ($x = 1; $x <= 4; $x++) {
            $jawaban = new Jawaban();
            $jawaban->pertanyaan_id = $pertanyaan->id;
            $jawaban->index = $x;
            $jawaban->jawaban = $request->get('jawaban_' . $x);
            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
            $jawaban->save();
        }

        if (isset($request->redirect))
            $redirect = $request->redirect;
        else
            $redirect = 'master-data/pertanyaan';

        return redirect($redirect)->with('success', 'Pertanyaan berhasil disimpan');
    }

    public function createPilihanGandaFromDisplay($id)
    {
//        $pedoman_list = PedomanPerilaku::all();
//        $pedoman_list[0] = 'Pilih pedoman perilaku';
//        foreach (PedomanPerilaku::orderBy('id')->get() as $wa) {
//            $pedoman_list[$wa->id] = $wa->nomor_urut.' '.$wa->pedoman_perilaku;
//        }
//        $pedoman = PedomanPerilaku::findOrFail($id);
        $pedoman = PLNTerbaik::findOrFail($id);
        return view('master.pilihan_ganda_create', compact('pedoman'));
    }

    public function storePilihanGandaFromDisplay(Request $request)
    {
//        dd($request);
        $pertanyaan = new Pertanyaan();
        $pertanyaan->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->jenis = 1;
        $pertanyaan->save();

        for ($x = 1; $x <= 4; $x++) {
            $jawaban = new Jawaban();
            $jawaban->pertanyaan_id = $pertanyaan->id;
            $jawaban->index = $x;
            $jawaban->jawaban = $request->get('jawaban_' . $x);
            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
            $jawaban->save();
        }
        return redirect('master-data/pertanyaan/pilihan-ganda/'.$request->pedoman_perilaku_id)->with('success', 'Pertanyaan berhasil disimpan');
    }

    public function editPilihanGanda($id){
        $pertanyaan = Pertanyaan::findOrFail($id);
        return view('master.pilihan_ganda_edit', compact('pertanyaan'));
    }

    public function updatePilihanGanda($id, Request $request)
    {
//        dd($request);
        $pertanyaan = Pertanyaan::findOrFail($id);
        $pertanyaan->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->save();

        for ($x = 1; $x <= 4; $x++) {
            $jawaban = Jawaban::where('pertanyaan_id',$id)->where('index',$x)->first();
            $jawaban->pertanyaan_id = $pertanyaan->id;
            $jawaban->jawaban = $request->get('jawaban_' . $x);
            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
            $jawaban->save();
        }
        return redirect($request->redirect)->with('success', 'Pertanyaan berhasil diubah');
    }

    public function delete(Request $request)
    {
//        dd($request);
        $pertanyaan = Pertanyaan::findOrFail($request->pertanyaan_id);
        $pertanyaan->status = 'DEL';
//        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->save();

//        for ($x = 1; $x <= 4; $x++) {
//            $jawaban = Jawaban::where('pertanyaan_id',$id)->where('index',$x)->first();
//            $jawaban->pertanyaan_id = $pertanyaan->id;
//            $jawaban->jawaban = $request->get('jawaban_' . $x);
//            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
//            $jawaban->save();
//        }
        return redirect($request->redirect)->with('success', 'Pertanyaan berhasil dihapus');
    }

    public function createBenarSalah()
    {
        return view('master.benar_salah_create');
    }

    public function createBenarSalahFromDisplay($id)
    {
        $pedoman = PedomanPerilaku::findOrFail($id);
        return view('master.benar_salah_create', compact('pedoman'));
    }

    public function storeBenarSalah(Request $request)
    {
//        dd($request);
        $pertanyaan = new Pertanyaan();
        $pertanyaan->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->jenis = 2;
        $pertanyaan->save();

        for ($x = 1; $x <= 2; $x++) {
            $jawaban = new Jawaban();
            $jawaban->pertanyaan_id = $pertanyaan->id;
            $jawaban->index = $x;
            $jawaban->jawaban = $request->get('jawaban_' . $x);
            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
            $jawaban->save();
        }

        if (isset($request->redirect))
            $redirect = $request->redirect;
        else
            $redirect = 'master-data/pertanyaan';

        return redirect($redirect)->with('success', 'Pertanyaan berhasil disimpan');
    }

    public function editBenarSalah($id){
        $pertanyaan = Pertanyaan::findOrFail($id);
        return view('master.benar_salah_edit', compact('pertanyaan'));
    }

    public function updateBenarSalah($id, Request $request)
    {
//        dd($request);
        $pertanyaan = Pertanyaan::findOrFail($id);
        $pertanyaan->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->save();

        for ($x = 1; $x <= 2; $x++) {
            $jawaban = Jawaban::where('pertanyaan_id',$id)->where('index',$x)->first();
            $jawaban->pertanyaan_id = $pertanyaan->id;
//            $jawaban->jawaban = $request->get('jawaban_' . $x);
            $jawaban->benar = ($request->benar == $x) ? 1 : 0;
            $jawaban->save();
        }
        return redirect($request->redirect)->with('success', 'Pertanyaan berhasil diubah');
    }

}
