<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasi extends Model
{
    protected $table = 'm_struktur_organisasi';
    protected $primaryKey = 'objid';
//    protected $table = 'm_struktur_organisasi_tmp';
//    public $timestamps = false;
    protected $arr_org = [];
    protected $created_at = '';
    protected $updated_at = '';

    public function setCreatedAt($value)
    {
        // Do nothing.
        ;
    }

    public function setUpdatedAt($value)
    {
        // Do nothing.
        ;
    }

    public function strukturJabatan(){
        return $this->hasMany('App\StrukturJabatan', 'orgeh', 'objid');
    }

    public function parent()
    {
        return $this->belongsTo('App\StrukturOrganisasi', 'sobid', 'objid');
    }

    public function children()
    {
        return $this->hasMany('App\StrukturOrganisasi', 'objid', 'sobid');
    }

    public function getAncestor(){
        $arr_ancestor =[];
//        dd($this);
        $parent = $this->parent;
//        dd($parent);
        if($parent==null)
            return $this;
        if($parent->sobid=='15000000'){
            array_push($arr_ancestor, $this);
            array_push($arr_ancestor, $parent);
        }
        else {
//        dd($this);
            do {
                array_push($arr_ancestor, $parent);
                $parent = $parent->parent;
            } while ($parent != null);
        }
//        dd(array_reverse($arr_ancestor));
        return array_reverse($arr_ancestor);
    }

    public function getDivisi(){
        $arr_ancestor =[];
        $parent = $this->parent;
        if($parent==null)
            return null;

        if(starts_with($this->stext, 'DIV') ||
            starts_with($this->stext, 'DIT') ||
            starts_with($this->stext, 'IPOD') ||
            starts_with($this->stext, 'IPAD') ||
            starts_with($this->stext, 'SETPER') ||
            starts_with($this->stext, 'SPI') ||
            starts_with($this->stext, 'DEP') ||
            starts_with($this->stext, '(ANAK PERUSAHAAN)')){

            return $this->stext;
        }

        if($parent->sobid=='15000000'){
            array_push($arr_ancestor, $this);
            array_push($arr_ancestor, $parent);
        }
        else {
            do {
                if(starts_with($parent->stext, 'DIV') ||
                    starts_with($parent->stext, 'DIT') ||
                    starts_with($parent->stext, 'IPOD') ||
                    starts_with($parent->stext, 'IPAD') ||
                    starts_with($parent->stext, 'SETPER') ||
                    starts_with($parent->stext, 'SPI') ||
                    starts_with($parent->stext, 'DEP') ||
                    starts_with($parent->stext, '(ANAK PERUSAHAAN)')) {
                    return $parent->stext;
                }
                array_push($arr_ancestor, $parent);
                $parent = $parent->parent;
            } while ($parent != null);
        }
        return null;
    }

    public function getKodeDivisi(){
        $arr_ancestor =[];
        $parent = $this->parent;
        if($parent==null)
            return null;

        if(starts_with($this->stext, 'DIV') ||
            starts_with($this->stext, 'DIT') ||
            starts_with($this->stext, 'IPOD') ||
            starts_with($this->stext, 'IPAD') ||
            starts_with($this->stext, 'SETPER') ||
            starts_with($this->stext, 'SPI') ||
            starts_with($this->stext, 'DEP') ||
            starts_with($this->stext, '(ANAK PERUSAHAAN)')){

            return $this->objid;
        }

        if($parent->sobid=='15000000'){
            array_push($arr_ancestor, $this);
            array_push($arr_ancestor, $parent);
        }
        else {
            do {
                if(starts_with($parent->stext, 'DIV') ||
                    starts_with($parent->stext, 'DIT') ||
                    starts_with($parent->stext, 'IPOD') ||
                    starts_with($parent->stext, 'IPAD') ||
                    starts_with($parent->stext, 'SETPER') ||
                    starts_with($parent->stext, 'SPI') ||
                    starts_with($parent->stext, 'DEP') ||
                    starts_with($parent->stext, '(ANAK PERUSAHAAN)')) {
                    return $parent->objid;
                }
                array_push($arr_ancestor, $parent);
                $parent = $parent->parent;
            } while ($parent != null);
        }
        return null;
    }

    public function posisi(){
        return $this->hasMany('App\StrukturPosisi', 'sobid', 'objid');
    }

    public function jawabanPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'orgeh', 'objid');
    }

    public function komitmenPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'orgeh', 'objid');
    }

    public function hrp1008(){
        return $this->hasOne('App\Hrp1008', 'objid', 'objid');
    }

    public function getKantor(){
        $parent = $this->parent;
        do{
            if($parent->hrp1008 !=null)
                break;
            $parent = $parent->parent;
        }while($parent!=null);

        return $parent;
    }

    public function getOrgLevel(){
//        dd($this);
        if($this->level!=null)
            return $this;
        $parent = $this->parent;
        do{
            if($parent->level !=null)
                break;
            $parent = $parent->parent;
        }while($parent!=null);

        return $parent;
    }

    public function getArrOrgLevel(){
        $arr_parent = [];
        array_push($arr_parent, $this->objid);

        // jika bukan di bawah organisasi manager/GM langsung
        if($this->level==null){

            array_push($arr_parent, $this->objid);
            $parent = $this->parent;
            if($parent!=null) {
                array_push($arr_parent, $parent->objid);
                do {
                    if ($parent->level != null)
                        break;
                    $parent = $parent->parent;
                    array_push($arr_parent, $parent->objid);
                } while ($parent != null);
            }
        }

        // remove duplicate
        $arr_parent = array_unique($arr_parent);

        return $arr_parent;
    }

    public function getJmlPegawai(){
        $orgeh = $this->orgeh;

        return $orgeh;
    }

    public function getTotalPegawai(){

        // get arr orgeh
        $arr_org = [$this->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->objid));

        $jml_pegawai = 0;

        // Split the array into chunks of 1000 items
        $chunks = array_chunk($arr_org, 1000);
        
        foreach ($chunks as $chunk) {
            // Execute the query for each chunk
            $jml_pegawai += User::where('status', 'ACTV')->whereIn('orgeh', $chunk)->pluck('nip')->count();
        }

        return $jml_pegawai;
    }

    public function getAllPegawaiOrganisasi(){
        // get arr orgeh
        $arr_org = [$this->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->objid));

        $list_pegawai = User::where('status', 'ACTV')->whereIn('orgeh', $arr_org)->get();

        return $list_pegawai;

    }

    public function getArrNIPPegawai(){
        // get arr orgeh
        $arr_org = [$this->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->objid));

        $list_pegawai = User::where('status', 'ACTV')->whereIn('orgeh', $arr_org)->pluck('nip')->toArray();

        return $list_pegawai;
    }

    public function getArrChildOrgeh($orgeh)
    {
        $arr_org = array();
        $org = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->get();
        foreach($org as $o){
            $arr_org[] = $o->objid;
            $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($o->objid));
        }

        // remove duplicate
        $arr_org = array_unique($arr_org);

        return $arr_org;
    }

    public function getJmlPegawaiBacaMateri($materi_id){

        // get arr orgeh
        $arr_org = [$this->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->objid));

        $jml_pegawai = 0;

        // Split the array into chunks of 1000 items
        $chunks = array_chunk($arr_org, 1000);
        
        foreach ($chunks as $chunk) {
            // Execute the query for each chunk
            $jml_pegawai += User::where('status', 'ACTV')
                                    ->whereIn('orgeh', $chunk)
                                    ->whereHas('readMateri', function ($query) use ($materi_id){
                                        return $query->where('materi_id', $materi_id);
                                    })
                                    ->count();
        }

        return $jml_pegawai;

    }

    public function getChildren()
    {
        $orgeh = $this->objid;
        // lazy mode
        $arr_orgeh = [$orgeh];
        $loop1 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->whereNull('level')->get();
        if ($loop1 != null) {
            foreach ($loop1 as $orgeh1) {
                array_push($arr_orgeh, $orgeh1->objid);
                $loop2 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh1->objid)->whereNull('level')->get();
                if ($loop2 != null) {
                    foreach ($loop2 as $orgeh2) {
                        array_push($arr_orgeh, $orgeh2->objid);
                        $loop3 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh2->objid)->whereNull('level')->get();
                        if ($loop3 != null) {
                            foreach ($loop3 as $orgeh3) {
                                array_push($arr_orgeh, $orgeh3->objid);
                                $loop4 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh3->objid)->whereNull('level')->get();
                                if ($loop4 != null) {
                                    foreach ($loop4 as $orgeh4) {
                                        array_push($arr_orgeh, $orgeh4->objid);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return array_unique($arr_orgeh);
    }

    public function getChildren1Level()
    {
        $orgeh = $this->objid;
        // lazy mode
        $arr_orgeh = [$this];
        $loop1 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->whereNull('level')->orderBy('objid','asc')->get();
        if ($loop1 != null) {
            foreach ($loop1 as $orgeh1) {
                array_push($arr_orgeh, $orgeh1);
                // $loop2 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh1->objid)->whereNull('level')->get();
                // if ($loop2 != null) {
                //     foreach ($loop2 as $orgeh2) {
                //         array_push($arr_orgeh, $orgeh2->objid);
                //         $loop3 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh2->objid)->whereNull('level')->get();
                //         if ($loop3 != null) {
                //             foreach ($loop3 as $orgeh3) {
                //                 array_push($arr_orgeh, $orgeh3->objid);
                //                 $loop4 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh3->objid)->whereNull('level')->get();
                //                 if ($loop4 != null) {
                //                     foreach ($loop4 as $orgeh4) {
                //                         array_push($arr_orgeh, $orgeh4->objid);
                //                     }
                //                 }
                //             }
                //         }
                //     }
                // }
            }
        }

        return array_unique($arr_orgeh);
    }
    
//    public function getAllOrganisasi(){
//        foreach ($this->children() as $org){
//            array_push($this->arr_org, $org->objid);
//        }
//        
//        return $this->arr_org;
//    }
//
//    public function recursiveChildren() {
//        return $this->children()->with('recursiveChildren');
//        //It seems this is recursive
//    }
//
//    public function getAllChild()
//    {
//    }

public static function deleteLevel1ChildrenSHAP()
{
    // // get group PLN with company_code = 1200 and 1300
    $group_pln = GroupPLN::where('company_code', '1200')->orWhere('company_code', '1300')->get();
    
    $arr_orgeh = [];
    foreach($group_pln as $shap){
        $orgeh = $shap->orgeh;
        // $orgeh = $orgeh;
        // lazy mode
        $loop1 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->get();
        if ($loop1 != null) {
            foreach ($loop1 as $orgeh1) {
                if($orgeh1->level==1){
                    array_push($arr_orgeh, $orgeh1->objid);
                    // delete level
                    $orgeh1->level = null;
                    $orgeh1->jenjang_id = null;
                    $orgeh1->company_code = null;
                    $orgeh1->save();
                }
                $loop2 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh1->objid)->get();
                if ($loop2 != null) {
                    foreach ($loop2 as $orgeh2) {
                        if($orgeh2->level==1){
                            array_push($arr_orgeh, $orgeh2->objid);
                            // delete level
                            $orgeh2->level = null;
                            $orgeh2->jenjang_id = null;
                            $orgeh2->company_code = null;
                            $orgeh2->save();
                        }
                        $loop3 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh2->objid)->get();
                        if ($loop3 != null) {
                            foreach ($loop3 as $orgeh3) {
                                if($orgeh3->level==1){
                                    array_push($arr_orgeh, $orgeh3->objid);
                                    // delete level
                                    $orgeh3->level = null;
                                    $orgeh3->jenjang_id = null;
                                    $orgeh3->company_code = null;
                                    $orgeh3->save();
                                }
                                $loop4 = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh3->objid)->get();
                                if ($loop4 != null) {
                                    foreach ($loop4 as $orgeh4) {
                                        if($orgeh4->level==1){
                                            array_push($arr_orgeh, $orgeh4->objid);
                                            // delete level
                                            $orgeh4->level = null;
                                            $orgeh4->jenjang_id = null;
                                            $orgeh4->company_code = null;
                                            $orgeh4->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return array_unique($arr_orgeh);
}
}
