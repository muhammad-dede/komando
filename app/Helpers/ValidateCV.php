<?php

namespace App\Helpers;

use App\Activity;
use App\KelengkapanCV;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ValidateCV
{
    public function validate(User $user)
    {
        // jika belum ada session validatecv
        if(empty(session('validatecv'))){

            // cek selain jenjang jabatan F5, F6 atau Spv Dasar
            // get mgrp dan sgrp
            $mgrp = @$user->strukturJabatan->mgrp;
            $sgrp = @$user->strukturJabatan->sgrp;

            // F5 = mgrp:5, sgrp:5
            // F6 = mgrp:5, sgrp:6
            // Spv Dasar = mgrp:4, sgrp:5

            // validasi selain F5, F6 dan Spv Dasar
            if(!(($mgrp==5 && $sgrp==5) || ($mgrp==5 && $sgrp==6) || ($mgrp==4 && $sgrp==5))){
                // cek kelengkapan cv
                $validasi_1 = $user->validasiCV()->where('kelengkapan_id', 1)->first();
                $validasi_2 = $user->validasiCV()->where('kelengkapan_id', 2)->first();
                $validasi_3 = $user->validasiCV()->where('kelengkapan_id', 3)->first();
                
                $list_validasi_cv = array();
                if($validasi_1!=null){
                    $list_validasi_cv[1] = ['jumlah'=>$validasi_1->jumlah, 'progress'=>$validasi_1->progress, 'status'=>$validasi_1->status];
                }else{
                    $list_validasi_cv[1] = ['jumlah'=>0, 'progress'=>0, 'status'=>0];
                }
                if($validasi_2!=null){
                    $list_validasi_cv[2] = ['jumlah'=>$validasi_2->jumlah, 'progress'=>$validasi_2->progress, 'status'=>$validasi_2->status];
                }else{
                    $list_validasi_cv[2] = ['jumlah'=>0, 'progress'=>0, 'status'=>0];
                }
                if($validasi_3!=null){
                    $list_validasi_cv[3] = ['jumlah'=>$validasi_3->jumlah, 'progress'=>$validasi_3->progress, 'status'=>$validasi_3->status];
                }else{
                    $list_validasi_cv[3] = ['jumlah'=>0, 'progress'=>0, 'status'=>0];
                }

                //set session validasi_pegawai
                session()->put('list_validasi_cv', $list_validasi_cv);

                // dd(session('validasi_pegawai')[2]['jumlah']);

                if(empty($validasi_1) || empty($validasi_2) || empty($validasi_3)){
                    // jika belum ada validasi cv, lolos validasi
                    // set session validatecv
                    $valid = true;
                }
                elseif($validasi_1->status == '1' && $validasi_2->status == '1' && $validasi_3->status == '1'){
                    // jika sudah ada validasi cv
                    // set session validatecv
                    $valid = true;
                }
                elseif($validasi_1->status == '0' || $validasi_2->status == '0' || $validasi_3->status == '0'){
                    // jika ada validasi cv yang belum selesai
                    // set session validatecv
                    $valid = false;
                }
                else{
                    // jika lainnya, lolos validasi
                    // set session validatecv
                    $valid = true;
                }

                // jika cv lengkap
                if($valid){
                    // set session validatecv
                    session()->put('validatecv', 'valid');
                }
                else{
                    // set session validatecv
                    session()->put('validatecv', 'invalid');
                }

                $list_kelengkapan = KelengkapanCV::all();
                // set session list_kelengkapan
                session()->put('list_kelengkapan', $list_kelengkapan);

            }
            else{
                session()->put('validatecv', 'valid');
            }

        }
    }
}