<?php

namespace App\Http\Controllers;

use App\BusinessArea;
use App\StrukturOrganisasi;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StrukturJabatan;
use Yajra\Datatables\Facades\Datatables;

class StrukturOrganisasiController extends Controller
{

    private $arr_struk = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $organisasi_list = StrukturOrganisasi::all();
//        return view('master.organisasi_list', compact('organisasi_list'));
        return view('master.organisasi_list');
    }

    public function serverProcessing(){

        return Datatables::of(StrukturOrganisasi::query())->make(true);
    }

    public function updateBusinessArea(){
        foreach(StrukturOrganisasi::where('status','ACTV')->whereIn('level', [1,2])->get() as $org){

            if($org->hrp1008!=null){
                if($org->hrp1008->gsber!=' ') {
                    $ba = $org->hrp1008->gsber;
                    $cc = $org->hrp1008->bukrs;
                    if($cc=='')
                        $cc = $org->parent->hrp1008->bukrs;

                    $business_area = BusinessArea::where('business_area',$ba)->first();
                    if($business_area!=null) {
//                        echo $business_area->business_area . "<br><br>";
//                    if(){
//
//                    }
                    }
                    else{
                        echo $org->objid." - ".$org->stext." - ".$ba . " - " .$cc." - ".$org->level . "<br>";
//                        $busa = new BusinessArea();
//                        $busa->
                    }
                }
            }
        }
    }

    public function getTree()
    {
//        dd($request->get('id'));
        $struktur = '[
            {
                "id":1,
                "text":"PT PLN (PERSERO)",
                "children":[
                    {
                        "id":2,
                        "text":"Child node 2",
                        "children":[
                            {
                                "id":21,
                                "text":"Child node 21"
                            },
                            {
                                "id":22,
                                "text":"Child node 22"
                            }
                        ]
                    },
                    {
                        "id":3,
                        "text":"Child node 3",
                        "children":[
                            {
                                "id":31,
                                "text":"Child node 31"
                            },
                            {
                                "id":32,
                                "text":"Child node 32"
                            }
                        ]
                    }
                ]
            }
        ]';



//        dd($this->addChild('15000000'));
//        dd($this->addChild('15630001'));
//        $id = $request->get('id');
        return response()->json($this->addChild('15630001'));
//        return response()->json($this->addChild($id));

        $arr = array();
        $arr["id"] = '15000000';
        $arr["text"] = 'PT PLN (PERSERO)';
        $arr["children"] = $this->arr_struk;
//        dd($this->arr_struk);
        return response()->json($arr);
    }

    public function addChild($parent_id){
        $arr_child = array();
        $strukorg = StrukturOrganisasi::where('status','ACTV')->where('sobid',$parent_id)->orderBy('objid','asc')->get();
        foreach($strukorg as $org){
            $arr = array();
            $arr["id"] = $org->objid;
            $arr["text"] = $org->stext;
            $arr["children"] = $this->addChild($org->objid);
//            array_push($this->arr_struk,$arr);
            array_push($arr_child,$arr);
        }

        return $arr_child;

//        array_push($this->arr_struk,$arr);
    }

    public function getTreeLazy(Request $request)
    {
        $id = $request->get('id');
        $arr_child = array();
        if($id == '' || $id == '#'){
            // $id = '15000000';
            $strukorg = StrukturOrganisasi::where('status','ACTV')
                    ->whereIn('objid',['10096379','10091860'])
                    ->orderBy('objid','desc')->get();
        }
        else{
            $strukorg = StrukturOrganisasi::where('status','ACTV')->where('sobid',$id)->orderBy('objid','asc')->get();
        }
        
        // $strukorg = StrukturOrganisasi::where('status','ACTV')
                    // ->whereIn('objid',[$arr])
                    // ->orderBy('objid','asc')
                    // ->get();
        foreach($strukorg as $org){
            $arr = array();
            $arr["id"] = $org->objid;
            $arr["text"] = $org->stext;
            $arr["children"] = $this->isHaveChildren($org->objid);
            array_push($arr_child,$arr);
        }

        return response()->json($arr_child);
    }

    public function isHaveChildren($objid){
        $strukorg = StrukturOrganisasi::where('status','ACTV')->where('sobid',$objid)->orderBy('objid','asc')->get();
        if($strukorg->count()>1)
            return true;
        else
            return false;
    }

//    public function ajaxGetTextOrgeh($orgeh){
//        $org = StrukturOrganisasi::where('objid',$orgeh)->first();
////        return response()->json($coc);
//        return $org->stext;
//    }

    public function getChildren($orgeh){
    // lazy mode
        $arr_orgeh = [$orgeh];
//        echo $orgeh.'<br>';
        $loop1 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->whereNull('level')->get();
        if($loop1!=null) {
            foreach ($loop1 as $orgeh1) {
//                echo '|<br>';
//                echo '+- L1. '.@$orgeh1->objid.' - '.@$orgeh1->stext.'<br>';
                array_push($arr_orgeh, $orgeh1->objid);
                $loop2 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh1->objid)->whereNull('level')->get();
                if($loop2!=null){
                    foreach ($loop2 as $orgeh2) {
//                        echo '&nbsp;&nbsp;&nbsp;|<br>';
//                        echo '&nbsp;&nbsp;&nbsp;+- L2. '.@$orgeh2->objid.' - '.@$orgeh2->stext.'<br>';
                        array_push($arr_orgeh, $orgeh2->objid);
                        $loop3 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh2->objid)->whereNull('level')->get();
                        if($loop3!=null){
                            foreach ($loop3 as $orgeh3) {
//                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|<br>';
//                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+- L3. '.@$orgeh3->objid.' - '.@$orgeh3->stext.'<br>';
                                array_push($arr_orgeh, $orgeh3->objid);
                            }
                        }
                    }
                }
            }
        }

        return array_unique($arr_orgeh);
    }

    public function getStrukturOrganisasi(Request $request)
    {
        $kode_organisasi = $request->get('kode_organisasi');

        $organisasi = StrukturOrganisasi::where('objid', $kode_organisasi)->first();

        return $organisasi;
    }
    
    public function getDataPegawai(Request $request){
        $kode_organisasi = $request->get('kode_organisasi');
        // '17100756';

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $kode_organisasi)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        // cari nip dari organisasi2 di baawah orgeh coc
        $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->orderBy('cname','asc')->get(['nip','pernr','cname','plans','orgeh','werks']);

        return $arr_nip;
    }
  
    public function getDataPegawai1Level(Request $request){
        $kode_organisasi = $request->get('kode_organisasi');
        // '17100756';

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $kode_organisasi)->first();
        $orgeh = $coc_orgeh->objid;

        // cari nip dari organisasi2 di baawah orgeh coc
        $arr_nip = StrukturJabatan::where('orgeh', $orgeh)->orderBy('cname','asc')->get(['nip','pernr','cname','plans','orgeh','werks']);

        return $arr_nip;
    }

    public function getStrukturOrganisasi1Level(Request $request)
    {
        $kode_organisasi = $request->get('kode_organisasi');

        $organisasi = StrukturOrganisasi::where('objid', $kode_organisasi)->first();

        $child = $organisasi->getChildren1Level();

        return $child;
    }
}
