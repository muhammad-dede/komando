<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PA0001 extends Model
{
    protected $table = 'pa0001';
//    protected $table = 'pa0001_tmp';

    //protected $connection = 'sap_rr';

    protected $primaryKey = 'pernr';
    public $timestamps = false;

    public function businessArea()
    {
        return $this->belongsTo('App\BusinessArea', 'gsber', 'business_area');
    }

    public function jabatan()
    {
        return $this->belongsTo('App\StrukturJabatan', 'pernr', 'pernr');
    }

    public function hrp1513(){
        return $this->hasMany('App\HRP1513', 'objid', 'plans');
    }

    public function levelUnit(){
        
    }

    public function pa0032(){
        return $this->hasOne('App\PA0032','pernr','pernr');
    }

    public function setPernrAttribute($attrValue){
        $this->attributes['pernr'] = (string) $attrValue;
    }

    public function getPernrAttribute($attrValue){
       return (string) $attrValue;
    }

    public static function importFromTmp(){
        $hrp1008 = PA0001::orderBy('pernr', 'asc')->get(['pernr']);
        $flat = $hrp1008->pluck('pernr');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = Pa0001Tmp::orderBy('pernr', 'asc')->get(['pernr']);
        $flat_tmp = $hrp1008_tmp->pluck('pernr');

//        foreach($flat_tmp as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $diff = $flat_tmp->diff($flat);

//        dd($diff->count());

        foreach($diff as $data){
            echo $data.', ';
            $source = Pa0001Tmp::where('pernr',$data)->first();
            $target = new PA0001();

//            MANDT	PERNR	ENDDA	BEGDA	BUKRS	WERKS	PERSG	GSBER	BTRTL	ORGEH	PLANS	SNAME

            $target->mandt = $source->mandt;
            $target->pernr = $source->pernr;
            $target->begda = $source->begda;
            $target->endda = $source->endda;
            $target->bukrs = $source->bukrs;
            $target->werks = $source->werks;
            $target->persg = $source->persg;
            $target->gsber = $source->gsber;
            $target->btrtl = $source->btrtl;
            $target->orgeh = $source->orgeh;
            $target->plans = $source->plans;
            $target->sname = $source->sname;

            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }

    public function excludeInterface(){
        return $this->hasOne('App\EksepsiInterface', 'pernr', 'pernr');
    }

    public function getDefinitive(){
        $delegation = ZPDelegation::where('position_2', $this->plans)
            ->where('endda', '>=', Carbon::now()->format('Ymd'))
            ->where('begda', '<=', Carbon::now()->format('Ymd'))
            ->orderBy('endda', 'desc')->first();

        if($delegation!=null)
            return $delegation->position_1;
        else
            return $this->plans;
    }

    public function getPositionDefinitive(){
        $delegation = ZPDelegation::where('position_2', $this->plans)
            ->where('endda', '>=', Carbon::now()->format('Ymd'))
            ->orderBy('endda', 'desc')->first();

        if($delegation!=null)
            return $delegation->position_1;
        else
            return $this->plans;
    }
}
