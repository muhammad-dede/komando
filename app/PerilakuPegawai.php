<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PerilakuPegawai extends Model
{
    protected $table = 'perilaku_pegawai';

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function pedomanPerilaku(){
        return $this->belongsTo('App\PedomanPerilaku', 'pedoman_perilaku_id', 'id');
    }

    public static function getNextKomitmenUser($tahun=''){
        if($tahun == '') $tahun = date('Y');
        if( Cache::has('pedoman_perilaku_id') ) {
            $pedoman_perilaku = Cache::get('pedoman_perilaku_id');
        }
        else {
//            $pedoman_perilaku = PedomanPerilaku::all('id');
            $pedoman_perilaku = PLNTerbaik::all('id');
            Cache::forever( 'pedoman_perilaku_id', $pedoman_perilaku);
        }
//        $pedoman_perilaku = PedomanPerilaku::all('id');
//        $pedoman_perilaku = PedomanPerilaku::orderBy('nomor_urut')->get(['id']);
//        dd($pedoman_perilaku);
        $arr_pedoman = [];
        foreach($pedoman_perilaku as $perilaku){
            array_push($arr_pedoman, $perilaku->id);
        }
//        dd($arr_pedoman);

        $perilaku_pegawai = PerilakuPegawai::where('user_id', Auth::user()->id)->where('tahun', $tahun)->get(['pedoman_perilaku_id']);

        $arr_perilaku = [];
        foreach($perilaku_pegawai as $perilaku){
            array_push($arr_perilaku, $perilaku->pedoman_perilaku_id);
        }

//        dd($arr_perilaku);

        $diff = array_diff($arr_pedoman, $arr_perilaku);

        if(count($diff)==0) return null;
        // cek nomor urut
//        $pedoman = PedomanPerilaku::whereIn('id',$diff)->orderBy('nomor_urut', 'asc')->first();
//        $pedoman = PedomanPerilaku::whereIn('id',$diff)->orderBy('urutan', 'asc')->first();
        $pedoman = PLNTerbaik::whereIn('id',$diff)->orderBy('urutan', 'asc')->first();

//        dd($pedoman->id);
        
        return $pedoman->id;
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function setPedomanPerilakuIdAttribute($attrValue){
        $this->attributes['pedoman_perilaku_id'] = (string) $attrValue;
    }
}
