<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class FormatDateIndonesia
{
    public function tanggal_indo($tanggal, $cetak_hari = false, $hide_year = false)
    {
        $hari = array ( 1 =>    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                );
                
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split 	  = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            if($hide_year){
                return $hari[$num] . ', ' . $split[2] . ' ' . $bulan[ (int)$split[1] ];
            }
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
    }
}