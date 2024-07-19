<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiTmp extends Model
{
//    protected $table = 'm_struktur_organisasi';
    protected $primaryKey = 'objid';
    protected $table = 'm_struktur_organisasi_tmp';

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
        return $arr_parent;
    }

    public function getJmlPegawai(){
        $orgeh = $this->orgeh;

        return $orgeh;
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
}
