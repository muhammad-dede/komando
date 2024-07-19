<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hrp1008 extends Model
{
    protected $table = 'hrp1008';
//    protected $table = 'hrp1008_tmp';

    protected $primaryKey = 'objid';
    public $timestamps = false;

    public function businessArea(){
        return $this->belongsTo('App\BusinessArea', 'gsber', 'business_area');
    }

    public function companyCode(){
        return $this->belongsTo('App\CompanyCode', 'bukrs', 'company_code');
    }

    public function organisasi(){
        return $this->belongsTo('App\StrukturOrganisasi', 'objid', 'objid');
    }

    public function t001p(){
        return $this->belongsTo('App\T001p', 'btrtl', 'btrtl');
    }

    public function t500p(){
        return $this->belongsTo('App\T500p', 'persa', 'persa');
    }

    public static function importFromTmp(){
        $hrp1008 = Hrp1008::orderBy('objid', 'asc')->get(['objid']);
        $flat = $hrp1008->pluck('objid');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = Hrp1008Tmp::orderBy('objid', 'asc')->get(['objid']);
        $flat_tmp = $hrp1008_tmp->pluck('objid');

//        foreach($flat_tmp as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $diff = $flat_tmp->diff($flat);

//        dd($diff->count());

        foreach($diff as $data){
            echo $data.', ';
            $source = Hrp1008Tmp::where('objid',$data)->first();
            $target = new Hrp1008();

//            MANDT	OBJID	BEGDA	ENDDA	BUKRS	GSBER	WERKS	PERSA	BTRTL

            $target->mandt = $source->mandt;
            $target->objid = $source->objid;
            $target->begda = $source->begda;
            $target->endda = $source->endda;
            $target->bukrs = $source->bukrs;
            $target->gsber = $source->gsber;
            $target->werks = $source->werks;
            $target->persa = $source->persa;
            $target->btrtl = $source->btrtl;

            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }
}
