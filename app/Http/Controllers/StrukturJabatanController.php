<?php

namespace App\Http\Controllers;

use App\Pa0032;
use App\StrukturJabatan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

class StrukturJabatanController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(StrukturJabatan::query()->where('pernr_at', '!=', '0'));
        //
//        $jabatan_list = StrukturJabatan::all();
//        return view('master.jabatan_list', compact('jabatan_list'));
        return view('master.jabatan_list');
    }

    public function serverProcessing(){

//        dd(StrukturJabatan::query());
//        dd(Datatables::of(StrukturJabatan::query())->make(true));

        return Datatables::of(StrukturJabatan::query()->where('pernr_at', '!=', '0'))->make(true);
//        $jabatan_list = StrukturJabatan::take(5)->get(['nip', 'email', 'pernr', 'cname', 'plans', 'orgeh']);

//        return $jabatan_list;
    }

    public function updateNIP(){
//        dd(StrukturJabatan::all());
        foreach(StrukturJabatan::all() as $data){
//        foreach(Pa0032::all() as $data){
//            $user = StrukturJabatan::where('pernr', $data->pernr)->first();
            if($data!=null) {
                if ($data->nip == null || $data->nip=='') {
                    $pa0032 = Pa0032::where('pernr', $data->pernr)->first();
                    if($pa0032!=null) {
                        $data->nip = $pa0032->nip;
                        $data->save();
//            $nip = Pa0032::where('pernr', $jabatan->pernr)->first();
//            $jabatan->nip = $nip;
//            $jabatan->save();
                        echo $data->nip . " - " . $data->cname . "<br>";
                    }
                }
            }
        }
    }

    public function updateKantor(){
        foreach(StrukturJabatan::where('orgeh','<>','0')
                    ->where('plans','<>','0')
                    ->where('plans','<>','99999999')
                    ->orderBy('orgeh', 'desc')
                    ->take(5)->get() as $data){
            if($data!=null) {
                echo $data->nip."<br>";
//                print_r($data->strukturOrganisasi->getOrgLevel()->toArray());
//                print_r($data->strukturOrganisasi->getAncestor());
                foreach($data->strukturOrganisasi->getAncestor() as $org){
                    echo $org->stext." - ".$org->level."<br>";
                }
                echo "<br><br>";
//                if ($data->nip == null) {
//                    $pa0032 = Pa0032::where('pernr', $data->pernr)->first();
//                    if($pa0032!=null) {
//                        $data->nip = $pa0032->nip;
//                        $data->save();
//                        echo $data->nip . " - " . $data->cname . "<br>";
//                    }
//                }
            }
        }
    }

    public function updateEmail()
    {
        $peg_list = StrukturJabatan::whereNull('email')->get();

//        dd($peg_list->count());
        foreach($peg_list as $pegawai){
            if($pegawai->email==null || $pegawai=='') {
                if ($pegawai->usr21 != null) {
                    if ($pegawai->usr21->adr6 != null) {
                        $pegawai->email = strtolower($pegawai->usr21->adr6->email);
                        $pegawai->save();
                        echo $pegawai->cname . " - " . $pegawai->email . "<br>";
                    }
                }
            }
        }
    }
}
