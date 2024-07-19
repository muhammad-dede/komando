<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;

class HRP1513 extends Model
{
    protected $table = 'hrp1513';
//    protected $table = 'hrp1513_tmp';
    //protected $connection = 'sap_rr';

    protected $primaryKey = 'objid';
    public $timestamps = false;

    public function pa0001(){
        return $this->belongsTo('App\PA0001', 'objid', 'plans');
    }

    public static function importFromTmp(){
        $hrp1008 = HRP1513::orderBy('objid', 'asc')->get(['objid']);
        $flat = $hrp1008->pluck('objid');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = Hrp1513Tmp::orderBy('objid', 'asc')->get(['objid']);
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
            $source = Hrp1513Tmp::where('objid',$data)->first();
            $target = new HRP1513();

//            MANDT	OBJID	BEGDA	ENDDA	MGRP	SGRP

            $target->mandt = $source->mandt;
            $target->objid = $source->objid;
            $target->begda = $source->begda;
            $target->endda = $source->endda;
            $target->mgrp = $source->mgrp;
            $target->sgrp = $source->sgrp;

            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }
}
