<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;

class PA0032 extends Model
{
    protected $table = 'pa0032';
//    protected $table = 'pa0032_tmp';

    protected $primaryKey = 'pernr';
    public $timestamps = false;

    public function pa0001(){
        return $this->hasOne('App\PA0001','pernr','pernr');
    }

    public function setNipAttribute($attrValue){
        $this->attributes['nip'] = (string) $attrValue;
    }

    public function getNipAttribute($attrValue){
       return (string) $attrValue;
    }

    public function setPernrAttribute($attrValue){
        $this->attributes['pernr'] = (string) $attrValue;
    }

    public function getPernrAttribute($attrValue){
       return (string) $attrValue;
    }

    public static function importFromTmp(){
        $hrp1008 = PA0032::orderBy('pernr', 'asc')->get(['pernr']);
        $flat = $hrp1008->pluck('pernr');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = Pa0032Tmp::orderBy('pernr', 'asc')->get(['pernr']);
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
            $source = Pa0032Tmp::where('pernr',$data)->first();
            $target = new PA0032();

//            PERNR	NIP

            $target->pernr = $source->pernr;
            $target->nip = $source->nip;
            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }

    public function user(){
        return $this->hasOne('App\User','nip','nip');
    }
}
