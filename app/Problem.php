<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Problem extends Model
{
    protected $table = 'problem';
    protected $dates = ['tgl_kejadian'];

    public function companyCode(){
        return $this->belongsTo('App\CompanyCode','company_code', 'company_code');
    }

    public function businessArea(){
        return $this->belongsTo('App\BusinessArea','bussiness_area', 'bussiness_area');
    }

    public function group(){
        return $this->belongsTo('App\ProblemGroup','grup_id', 'id');
    }

    public function server(){
        return $this->belongsTo('App\Server','server_id', 'id');
    }

    public function statusProblem(){
        return $this->belongsTo('App\ProblemStatus','status', 'id');
    }

    public function caseOwner(){
        return $this->belongsTo('App\User','user_id_pelapor', 'id');
    }

    public function setUserIdPelaporAttribute($attrValue){
        $this->attributes['user_id_pelapor'] = (string) $attrValue;
    }

    public function getFoto(){

        try {
//            $file = Storage::get('foto_pegawai/' . strtoupper($user->strukturJabatan->nip) . '_.jpg');
            $file = Storage::get($this->business_area . '/problem/' . $this->foto);
        }
        catch(\Exception $e){
            $file = file_get_contents(asset('assets/images/blank.png'));
        }

//        $img = Image::make($file)->resize(64, 64);
        $img = Image::make($file);

        return $img->response('jpg');

    }
}
