<?php

namespace App\Http\Controllers;

use App\CompanyCode;
use App\Hrp1008;
use App\Hrp1008Tmp;
use App\HRP1513;
use App\Hrp1513Tmp;
use App\InterfaceLog;
use App\JenjangJabatan;
use App\PA0001;
use App\Pa0001Tmp;
use App\PA0032;
use App\Pa0032Tmp;
use App\PerilakuPegawai;
use App\StrukturJabatan;
use App\StrukturJabatanTmp;
use App\StrukturOrganisasi;
use App\StrukturOrganisasiTmp;
use App\StrukturPosisi;
//use App\StrukturPosisiRev;
use App\StrukturPosisiTmp;
use App\User;
use App\ZPDelegation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\EksepsiInterface;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MailLog;
use App\Notification;
use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use PDO;

class InterfaceController extends Controller
{
    public function updateDataFromSAP()
    {
        // set maintenance mode

        // update HRP1008
        $this->truncateThenCopy('HRP1008', 'HRP1008_TMP');

        // update HRP1513
        $this->truncateThenCopy('HRP1513', 'HRP1513_TMP');

        // update M_STRUKJAB_SAP

        // update M_STRUKPOS_SAP

        // update PA0001
        $this->truncateThenCopy('PA0001', 'PA0001_TMP');

        // update PA0032
        $this->truncateThenCopy('PA0032', 'PA0032_TMP');

        // update T001P
//        $this->truncateThenCopy('T001P', 'T001P_TMP');

        // update T500P
//        $this->truncateThenCopy('T500P', 'T500P_TMP');

        // unset maintenance mode

        return 'OK';
    }

    public function truncateThenCopy($tbl_target, $tbl_source)
    {
        // empty table
        DB::statement('TRUNCATE TABLE ' . $tbl_target);
        // copy table
        DB::statement('INSERT INTO ' . $tbl_target . ' (SELECT * from ' . $tbl_source . ')');
    }

    public function copyTable($db_source, $db_target, $table)
    {

//        $c1 = DB("db_dev")->select("SELECT * from ".$tbl_source);
//
//        foreach($c1 as $record){
//
//            DB("db_trn")->table($tbl_target)->insert(get_object_vars($record));
//
//        }
        // truncate table
        DB::connection($db_target)->statement('TRUNCATE TABLE ' . $table);
        $c1 = DB::connection($db_source)->select("SELECT * from " . $table);

        foreach ($c1 as $record) {

            DB::connection($db_target)->table($table)->insert(get_object_vars($record));

        }

//        // copy table
//        DB::setFetchMode(PDO::FETCH_ASSOC);
//        $table_records = DB::connection($db_source)->select("SELECT * from ".$table);
//        DB::setFetchMode(PDO::FETCH_CLASS);
//
//        DB::connection($db_target)->table($table)->insert($table_records);

    }

    public function migrateFromDevToTrn()
    {

        // update HRP1008
        $this->copyTable('dev', 'trn', 'HRP1008');

//        // update HRP1513
        $this->copyTable('dev', 'trn', 'HRP1513');
//
//        // update M_STRUKJAB_SAP
        $this->copyTable('dev', 'trn', 'M_STRUKJAB_SAP');
//
//        // update M_STRUKPOS_SAP
        $this->copyTable('dev', 'trn', 'M_STRUKPOS_SAP');
//
//        // update PA0001
        $this->copyTable('dev', 'trn', 'PA0001');
//
//        // update PA0032
        $this->copyTable('dev', 'trn', 'PA0032');
//
//        // update T001P
        $this->copyTable('dev', 'trn', 'T001P');
//
//        // update T500P
        $this->copyTable('dev', 'trn', 'T500P');

        // unset maintenance mode

        return 'FINISH';

    }

    public function migrateFromTrnToDev()
    {

    }

    public function updateHrp1008()
    {
        Hrp1008::importFromTmp();
    }

    public function updateHrp1513()
    {
        HRP1513::importFromTmp();
    }

    public function updatePa0001()
    {
        PA0001::importFromTmp();
    }

    public function updatePa0032()
    {
        PA0032::importFromTmp();
    }

    public function updateStrukjab()
    {
        StrukturJabatan::importFromTmp();
    }

    public function updateStrukpos()
    {
        StrukturPosisi::importFromTmp();
    }

    public function updateDataESS()
    {
    ini_set('max_execution_time', 60000);
	$strukjab = true;
	$strukpos = true;
	$strukorg = true;

        // Backup table
	if($strukjab) $this->truncateThenCopy('m_struktur_jabatan_bak', 'm_struktur_jabatan');
        if($strukpos) $this->truncateThenCopy('m_struktur_posisi_bak', 'm_struktur_posisi');
        if($strukorg) $this->truncateThenCopy('m_struktur_organisasi_bak', 'm_struktur_organisasi');

//        $contents = Storage::disk('ftp_sap')->get('STRUKJAB_TBL_20180308030758.TXT');
        $directory = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE';
//        $file = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_20180308030758.TXT';
        $contents = Storage::disk('ftp_sap')->files($directory);
//        $contents = Storage::disk('ftp_sap')->lastModified($file);
//        $contents = Storage::disk('ftp_sap')->exists($file);
//        $contents = Storage::disk('ftp_sap')->get($file);

        $file_strukjab = '';
        $file_strukpos = '';
        $file_strukorg = '';

        $tanggal = date('Ymd');
//        $tanggal = '20180831';
        foreach ($contents as $file) {
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_' . $tanggal)) {
                $file_strukjab = $file;
//                dd($file_strukjab);
            }
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKPOS_TBL_' . $tanggal)) {
                $file_strukpos = $file;
            }
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKORG_TBL_' . $tanggal)) {
                $file_strukorg = $file;
            }
        }

        if (!Storage::disk('ftp_sap')->exists($file_strukjab)) {
            $directory2 = 'IFPR1100/OUTBOUND/PROMISE';
            $contents2 = Storage::disk('ftp_sap')->files($directory2);

            $file_strukjab2 = '';
            $file_strukpos2 = '';
            $file_strukorg2 = '';
            foreach ($contents2 as $file) {
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKJAB_TBL_' . $tanggal)) {
                    $file_strukjab2 = $file;
//                dd($file_strukjab);
                }
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKPOS_TBL_' . $tanggal)) {
                    $file_strukpos2 = $file;
                }
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKORG_TBL_' . $tanggal)) {
                    $file_strukorg2 = $file;
                }
            }

//            if (!Storage::disk('ftp_sap')->exists($file_strukjab)) {
            $file_strukjab = $file_strukjab2;
//            }
//            if (!Storage::disk('ftp_sap')->exists($file_strukpos)) {
            $file_strukpos = $file_strukpos2;
//            }
//            if (!Storage::disk('ftp_sap')->exists($file_strukorg)) {
            $file_strukorg = $file_strukorg2;
//            }
        }

//        dd($file_strukjab);

	if($strukjab){
//        STRUKJAB
//        NIP	PERNR	CNAME	PLANS	ORGEH	WERKS	BTRTL	MGRP	SGRP	SPEBE	KOSTL	PERNR_AT	PLANS_AT

        if (Storage::disk('ftp_sap')->exists($file_strukjab)) {
            try {
                $content_strukjab = Storage::disk('ftp_sap')->get($file_strukjab);
                $convert = nl2br($content_strukjab);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                foreach ($arr_content as $line) {
//                echo $line . '<br>';
                    $arr_obj = explode('|', $line);
                    if (count($arr_obj) == 12) {
                        $pernr = trim($arr_obj[0]);
                        $cname = trim($arr_obj[1]);
                        $plans = trim($arr_obj[2]);
                        $orgeh = trim($arr_obj[3]);
                        $werks = trim($arr_obj[4]);
                        $btrtl = trim($arr_obj[5]);
                        $mgrp = trim($arr_obj[6]);
                        $sgrp = trim($arr_obj[7]);
                        $spebe = trim($arr_obj[8]);
                        $kostl = trim($arr_obj[9]);
                        $pernr_at = trim($arr_obj[10]);
                        $plans_at = trim($arr_obj[11]);

//                        $pernr = '85118600';

                        // update data
                        if($orgeh!='00000000') {
			//if($pernr == '73931401'){
		            $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                            if ($strukjab == null) {
                                $strukjab = new StrukturJabatan();
                                echo 'New -> ';
                                //$pa0032 = PA0032::where('pernr', $pernr)->first();
                                //$strukjab->nip = strtoupper(@$pa0032->nip);
                                //$strukjab->pernr = $pernr;
                                //$strukjab->save();
                            }

                            $exclude = $strukjab->excludeInterface;

                            //$tanggal_sekarang = Carbon::now();

                            //jika tidak ada exclude atau ada exclude tapi sudah exp
                            // if ($exclude == null || ($exclude != null && $exclude->endda < $tanggal_sekarang)) {
                            if ($exclude == null) {
				                $pa0032 = PA0032::where('pernr', $pernr)->first();
                                $strukjab->nip = strtoupper(@$pa0032->nip);
                                $strukjab->pernr = $pernr;

                                $strukjab->cname = strtoupper($cname);
                                $strukjab->plans = $plans;
                                $strukjab->orgeh = $orgeh;
                                $strukjab->werks = $werks;
                                $strukjab->btrtl = $btrtl;
                                $strukjab->mgrp = $mgrp;
                                $strukjab->sgrp = $sgrp;
                                $strukjab->spebe = $spebe;
                                $strukjab->kostl = $kostl;
                                $strukjab->pernr_at = $pernr_at;
                                $strukjab->plans_at = $plans_at;
                                $strukjab->save();
                                echo $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '|'.$strukjab->plans.'|'.$strukjab->orgeh.'<br>';
                            } else {
                                echo 'Exclude -> ' . $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '<br>';
                            }
			//	if($pernr=='73931401') die($strukjab);
                        }
                        else{
                            echo 'Not Active -> ' . $pernr . '|' . $cname . '|' . $orgeh . '<br>';
                        }

                    }
                }

                InterfaceLog::log($file_strukjab, 'Interface ESS', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_strukjab, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_strukjab, 'FILE NOT FOUND', 'ERROR');
        }

	}

	if($strukpos){
//        STRUKPOS
//        OBJID	STEXT	RELAT	SOBID	STXT2	STARTDATE	ENDDATE
	DB::statement('TRUNCATE TABLE m_struktur_posisi_tmp');
	
        if (Storage::disk('ftp_sap')->exists($file_strukpos)) {
            try {
                $content_strukpos = Storage::disk('ftp_sap')->get($file_strukpos);
                $convert = nl2br($content_strukpos);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd($arr_obj);

                    if (count($arr_obj) == 11) {
                        $objid = trim($arr_obj[0]);
                        $stext = trim($arr_obj[2]);
                        $relat = trim($arr_obj[4]);
                        $sobid = trim($arr_obj[5]);
                        $stxt2 = trim($arr_obj[7]);
			

			//if($objid == '37413180'){
                        // update data
                        /*$strukpos = StrukturPosisi::where('objid', $objid)->first();
//                    dd($strukjab);
                        if ($strukpos == null) {*/
                            $strukpos = new StrukturPosisiTmp();
//                            echo 'New -> ';

                            $strukpos->objid = $objid;
                            $strukpos->stext = $stext;
                            $strukpos->relat = $relat;
                            $strukpos->sobid = $sobid;
                            $strukpos->stxt2 = $stxt2;
                            $strukpos->save();
                            echo $strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
//                    dd($strukjab);
//                    }
//                        }
//                        else{
//                            $strukpos->objid = $objid;
//                            $strukpos->stext = $stext;
//                            $strukpos->relat = $relat;
//                            $strukpos->sobid = $sobid;
//                            $strukpos->stxt2 = $stxt2;
//                            $strukpos->save();
//                            echo '[UPDATE] '.$strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
//                        }

			//	break;
			//}
                    }

                }

		        $this->truncateThenCopy('m_struktur_posisi', 'm_struktur_posisi_tmp');
		
                InterfaceLog::log($file_strukpos, 'Interface ESS', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_strukpos, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_strukpos, 'FILE NOT FOUND', 'ERROR');
        }
	}

	if($strukorg){
//        STRUKORG
//        OBJID	STEXT	RELAT	SOBID	STXT2	level
        // $struktur_baru = false;
        $arr_strukorg_baru = [];
        if (Storage::disk('ftp_sap')->exists($file_strukorg)) {
            try {
                $content_strukorg = Storage::disk('ftp_sap')->get($file_strukorg);
                $convert = nl2br($content_strukorg);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                $count_update = 0;
                $count_new = 0;
                $str_obj_new = '';
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd($arr_obj);

                    if (count($arr_obj) == 5) {
                        $objid = trim($arr_obj[0]);
                        $stext = trim($arr_obj[1]);
                        $relat = trim($arr_obj[2]);
                        $sobid = trim($arr_obj[3]);
                        $stxt2 = trim($arr_obj[4]);

                        // update data
                        $strukorg = StrukturOrganisasi::where('objid', $objid)->first();
//                    dd($strukorg);
                        if ($strukorg == null) {
                            // $struktur_baru = true;

                            $strukorg = new StrukturOrganisasi();
                            echo 'New -> ';
                            $strukorg->objid = $objid;
                            $strukorg->stext = $stext;
                            $strukorg->relat = $relat;
                            $strukorg->sobid = $sobid;
                            $strukorg->stxt2 = $stxt2;
//                        dd($strukorg);
                            $strukorg->save();

                            // push to array
                            array_push($arr_strukorg_baru, $objid.' : '.$stext);

                            if($count_new==0) $str_obj_new = $objid;
                            else $str_obj_new = $str_obj_new.', '.$objid;

                            echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';
                            
                            $count_new++;

//                    }
                        }
                        else{
                            $strukorg->objid = $objid;
                            $strukorg->stext = $stext;
                            $strukorg->relat = $relat;
                            $strukorg->sobid = $sobid;
                            $strukorg->stxt2 = $stxt2;
//                        dd($strukorg);
                            $strukorg->save();
                            echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';

                            $count_update++;
                        }
                    }

                }
                InterfaceLog::log($file_strukorg, 'Interface ESS : Updated('.$count_update.') ; New('.$count_new.') : '.$str_obj_new, 'FINISHED');

                 // notifikasi ke user root jika ada struktur organisasi baru dan delete organisasi lama
                 if($count_new>0){
                    $this->notifOrganisasiBaru($arr_strukorg_baru);
                    $this->deleteOraganisasiLama();
                 }

            } catch (Exception $e) {
                InterfaceLog::log($file_strukorg, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_strukorg, 'FILE NOT FOUND', 'ERROR');
        }

	}

//        dd($contents);
//        $file = $file_strukjab.';'.$file_strukpos.';'.$file_strukorg;
//        InterfaceLog::log($file, 'Interface ESS', 'FINISHED');

        return 'FINISH';
    }

    public function deleteOraganisasiLama(){
        // $lastdate_interface = Carbon::parse('20200701');
        $lastdate_interface = Carbon::now()->format('Ymd');
        $strukorg_list_delete = StrukturOrganisasi::where('status','ACTV')->whereDate('updated_at','<',$lastdate_interface)->orderBy('updated_at', 'asc')->get();
        // dd($strukorg_list_delete);
        $count = 0;
        $str_org_del = '';
        foreach($strukorg_list_delete as $data){
            $data->status = 'INAC';
            $data->save();

            if($count==0) $str_org_del = $data->objid;
            else $str_org_del = $str_org_del.', '.$data->objid;

            $count++;
        }

        InterfaceLog::log('Non-aktif organisasi lama', 'Interface ESS : INAC('.$count.') : '.$str_org_del, 'FINISHED');

        return 'FINISH';
    }

    public function updateDataESSLive()
    {
        $strukjab = false;
        $strukpos = true;
        $strukorg = false;

        // Backup table
//        if($strukjab) $this->truncateThenCopy('m_struktur_jabatan_bak', 'm_struktur_jabatan');
//        if($strukpos) $this->truncateThenCopy('m_struktur_posisi_bak', 'm_struktur_posisi');
//        if($strukorg) $this->truncateThenCopy('m_struktur_organisasi_bak', 'm_struktur_organisasi');

//        $contents = Storage::disk('ftp_sap')->get('STRUKJAB_TBL_20180308030758.TXT');
        $directory = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE';
//        $file = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_20180308030758.TXT';
        $contents = Storage::disk('ftp_sap')->files($directory);
//        $contents = Storage::disk('ftp_sap')->lastModified($file);
//        $contents = Storage::disk('ftp_sap')->exists($file);
//        $contents = Storage::disk('ftp_sap')->get($file);

        $file_strukjab = '';
        $file_strukpos = '';
        $file_strukorg = '';

        $tanggal = date('Ymd');
//        $tanggal = '20180831';
        foreach ($contents as $file) {
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_' . $tanggal)) {
                $file_strukjab = $file;
//                dd($file_strukjab);
            }
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKPOS_TBL_' . $tanggal)) {
                $file_strukpos = $file;
            }
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKORG_TBL_' . $tanggal)) {
                $file_strukorg = $file;
            }
        }

        if (!Storage::disk('ftp_sap')->exists($file_strukjab)) {
            $directory2 = 'IFPR1100/OUTBOUND/PROMISE';
            $contents2 = Storage::disk('ftp_sap')->files($directory2);

            $file_strukjab2 = '';
            $file_strukpos2 = '';
            $file_strukorg2 = '';
            foreach ($contents2 as $file) {
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKJAB_TBL_' . $tanggal)) {
                    $file_strukjab2 = $file;
//                dd($file_strukjab);
                }
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKPOS_TBL_' . $tanggal)) {
                    $file_strukpos2 = $file;
                }
                if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/STRUKORG_TBL_' . $tanggal)) {
                    $file_strukorg2 = $file;
                }
            }

//            if (!Storage::disk('ftp_sap')->exists($file_strukjab)) {
            $file_strukjab = $file_strukjab2;
//            }
//            if (!Storage::disk('ftp_sap')->exists($file_strukpos)) {
            $file_strukpos = $file_strukpos2;
//            }
//            if (!Storage::disk('ftp_sap')->exists($file_strukorg)) {
            $file_strukorg = $file_strukorg2;
//            }
        }

//        dd($file_strukjab);

        if($strukjab){
//        STRUKJAB
//        NIP	PERNR	CNAME	PLANS	ORGEH	WERKS	BTRTL	MGRP	SGRP	SPEBE	KOSTL	PERNR_AT	PLANS_AT

            if (Storage::disk('ftp_sap')->exists($file_strukjab)) {
                try {
                    $content_strukjab = Storage::disk('ftp_sap')->get($file_strukjab);
                    $convert = nl2br($content_strukjab);
                    $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                    foreach ($arr_content as $line) {
//                echo $line . '<br>';
                        $arr_obj = explode('|', $line);
                        if (count($arr_obj) == 12) {
                            $pernr = trim($arr_obj[0]);
                            $cname = trim($arr_obj[1]);
                            $plans = trim($arr_obj[2]);
                            $orgeh = trim($arr_obj[3]);
                            $werks = trim($arr_obj[4]);
                            $btrtl = trim($arr_obj[5]);
                            $mgrp = trim($arr_obj[6]);
                            $sgrp = trim($arr_obj[7]);
                            $spebe = trim($arr_obj[8]);
                            $kostl = trim($arr_obj[9]);
                            $pernr_at = trim($arr_obj[10]);
                            $plans_at = trim($arr_obj[11]);

//                        $pernr = '85118600';

                            // update data
                            if($orgeh!='00000000') {
                                //if($pernr == '73931401'){
                                $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                                if ($strukjab == null) {
                                    $strukjab = new StrukturJabatan();
                                    echo 'New -> ';
                                    //$pa0032 = PA0032::where('pernr', $pernr)->first();
                                    //$strukjab->nip = strtoupper(@$pa0032->nip);
                                    //$strukjab->pernr = $pernr;
                                    //$strukjab->save();
                                }

                                $exclude = $strukjab->excludeInterface;

                                //$tanggal_sekarang = Carbon::now();

                                //jika tidak ada exclude atau ada exclude tapi sudah exp
                                //if ($exclude == null || ($exclude != null && $exclude->endda < $tanggal_sekarang)) {
                                if ($exclude == null) {
                                $pa0032 = PA0032::where('pernr', $pernr)->first();
                                $strukjab->nip = strtoupper(@$pa0032->nip);
                                $strukjab->pernr = $pernr;

                                $strukjab->cname = strtoupper($cname);
                                $strukjab->plans = $plans;
                                $strukjab->orgeh = $orgeh;
                                $strukjab->werks = $werks;
                                $strukjab->btrtl = $btrtl;
                                $strukjab->mgrp = $mgrp;
                                $strukjab->sgrp = $sgrp;
                                $strukjab->spebe = $spebe;
                                $strukjab->kostl = $kostl;
                                $strukjab->pernr_at = $pernr_at;
                                $strukjab->plans_at = $plans_at;
                                $strukjab->save();
                                echo $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '|'.$strukjab->plans.'|'.$strukjab->orgeh.'<br>';
                                } else {
                                    echo 'Exclude -> ' . $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '<br>';
                                }
                                //	if($pernr=='73931401') die($strukjab);
                            }
                            else{
                                echo 'Not Active -> ' . $pernr . '|' . $cname . '|' . $orgeh . '<br>';
                            }

                        }
                    }

                    InterfaceLog::log($file_strukjab, 'Interface ESS', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_strukjab, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_strukjab, 'FILE NOT FOUND', 'ERROR');
            }

        }

        if($strukpos){
//        STRUKPOS
//        OBJID	STEXT	RELAT	SOBID	STXT2	STARTDATE	ENDDATE
//            DB::statement('TRUNCATE TABLE m_struktur_posisi_tmp');

            if (Storage::disk('ftp_sap')->exists($file_strukpos)) {
                try {
                    echo $file_strukpos.'<br>';
                    $content_strukpos = Storage::disk('ftp_sap')->get($file_strukpos);
                    $convert = nl2br($content_strukpos);
                    $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                    foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                        $arr_obj = explode('|', $line);
//                dd($arr_obj);

                        if (count($arr_obj) == 11) {
                            $objid = trim($arr_obj[0]);
                            $stext = trim($arr_obj[2]);
                            $relat = trim($arr_obj[4]);
                            $sobid = trim($arr_obj[5]);
                            $stxt2 = trim($arr_obj[7]);


//                            if($objid == '37411863'){
                                        // update data
                          $strukpos = StrukturPosisi::where('objid', $objid)->first();
                //                    dd($strukjab);
                          if ($strukpos == null) {
                            $strukpos = new StrukturPosisiTmp();
                            echo 'New -> ';

                            $strukpos->objid = $objid;
                            $strukpos->stext = $stext;
                            $strukpos->relat = $relat;
                            $strukpos->sobid = $sobid;
                            $strukpos->stxt2 = $stxt2;
                            $strukpos->save();
                            echo '[NEW] '.$strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
//                    dd($strukjab);
//                    }
                            }
                            else{
                                $strukpos->objid = $objid;
                                $strukpos->stext = $stext;
                                $strukpos->relat = $relat;
                                $strukpos->sobid = $sobid;
                                $strukpos->stxt2 = $stxt2;
                                $strukpos->save();
                                echo '[UPDATE] '.$strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
                            }

                            //	break;
//                            }
//                            echo $strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
                        }

                    }

//                    $this->truncateThenCopy('m_struktur_posisi', 'm_struktur_posisi_tmp');

                    InterfaceLog::log($file_strukpos, 'Interface ESS', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_strukpos, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_strukpos, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if($strukorg){
//        STRUKORG
//        OBJID	STEXT	RELAT	SOBID	STXT2	level

            if (Storage::disk('ftp_sap')->exists($file_strukorg)) {
                try {
                    $content_strukorg = Storage::disk('ftp_sap')->get($file_strukorg);
                    $convert = nl2br($content_strukorg);
                    $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                    foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                        $arr_obj = explode('|', $line);
//                dd($arr_obj);

                        if (count($arr_obj) == 5) {
                            $objid = trim($arr_obj[0]);
                            $stext = trim($arr_obj[1]);
                            $relat = trim($arr_obj[2]);
                            $sobid = trim($arr_obj[3]);
                            $stxt2 = trim($arr_obj[4]);

                            // update data
                            $strukorg = StrukturOrganisasi::where('objid', $objid)->first();
//                    dd($strukorg);
                            if ($strukorg == null) {
                                $strukorg = new StrukturOrganisasi();
                                echo 'New -> ';
                                $strukorg->objid = $objid;
                                $strukorg->stext = $stext;
                                $strukorg->relat = $relat;
                                $strukorg->sobid = $sobid;
                                $strukorg->stxt2 = $stxt2;
//                        dd($strukorg);
                                $strukorg->save();
                                echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';

//                    }
                            }
                            else{
                                $strukorg->objid = $objid;
                                $strukorg->stext = $stext;
                                $strukorg->relat = $relat;
                                $strukorg->sobid = $sobid;
                                $strukorg->stxt2 = $stxt2;
//                        dd($strukorg);
                                $strukorg->save();
                                echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';
                            }
                        }

                    }

                    InterfaceLog::log($file_strukorg, 'Interface ESS', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_strukorg, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_strukorg, 'FILE NOT FOUND', 'ERROR');
            }

        }

//        dd($contents);
//        $file = $file_strukjab.';'.$file_strukpos.';'.$file_strukorg;
//        InterfaceLog::log($file, 'Interface ESS', 'FINISHED');

        return 'FINISH';
    }

    public function updateDataSAP()
    {
        $hrp1008 = true;
        $hrp1513 = true;
        $pa0001	= true;
        $pa0032 = true;
        $zpdelegation = false;
        $bacc = true;

        // Backup table
        if($hrp1008) $this->truncateThenCopy('hrp1008_bak', 'hrp1008');
        if($hrp1513) $this->truncateThenCopy('hrp1513_bak', 'hrp1513');
        if($pa0001) $this->truncateThenCopy('pa0001_bak', 'pa0001');
        if($pa0032) $this->truncateThenCopy('pa0032_bak', 'pa0032');
        if($zpdelegation) $this->truncateThenCopy('zpdelegation_bak', 'zpdelegation');

//        $file_hrp1008 = 'HRP1008.txt';
//        $file_hrp1513 = 'HRP1513.txt';
//        $file_pa0001 = 'PA0001.txt';
	    $tgl_file = date('Ymd');
        $file_hrp1008 = 'HRP1008_'.$tgl_file.'.txt';
        $file_hrp1513 = 'HRP1513_'.$tgl_file.'.txt';
        $file_pa0001 = 'PA0001_'.$tgl_file.'.txt';


        $file_pa0032 = 'PA0032.txt';
        $file_zpdelegation = 'ZPDELEGATION.txt';

	if($hrp1008){
        //        HRP1008
//        OBJID	BEGDA	ENDDA	BUKRS	GSBER	WERKS	PERSA	BTRTL

//        dd(Storage::disk('ftp_interface')->exists($file_hrp1008));

        if (Storage::disk('ftp_interface')->exists($file_hrp1008)) {
            try {
                $content_hrp1008 = Storage::disk('ftp_interface')->get($file_hrp1008);
                $convert = nl2br($content_hrp1008);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd($arr_obj);

                    if (count($arr_obj) == 8) {
                        $objid = trim($arr_obj[0]);
                        $begda = trim($arr_obj[1]);
                        $endda = trim($arr_obj[2]);
                        $bukrs = trim($arr_obj[3]);
                        $gsber = trim($arr_obj[4]);
                        $werks = trim($arr_obj[5]);
                        $persa = trim($arr_obj[6]);
                        $btrtl = trim($arr_obj[7]);

                        // update data
                        $obj = Hrp1008::where('objid', $objid)->first();
//                    dd($obj);
                        if ($obj == null) {
                            $obj = new Hrp1008();
                            echo 'New -> ';

//                    }
                        }
                        $obj->objid = $objid;
                        $obj->begda = $begda;
                        $obj->endda = $endda;
                        $obj->bukrs = $bukrs;
                        $obj->gsber = $gsber;
                        $obj->werks = $werks;
                        $obj->persa = $persa;
                        $obj->btrtl = $btrtl;
//                        dd($obj);
                        $obj->save();
                        echo $obj->objid . '|' . $obj->bukrs . '|' . $obj->gsber . '|' . $obj->persa . '|' . $obj->btrtl . '<br>';
                    }

                }
                echo $file_hrp1008 . ' FINISHED<br>';
                InterfaceLog::log($file_hrp1008, 'Interface SAP', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_hrp1008, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_hrp1008, 'FILE NOT FOUND', 'ERROR');
        }

	}

	if($hrp1513){

        //        HRP1513
//        OBJID	BEGDA	ENDDA	MGRP	SGRP

        if (Storage::disk('ftp_interface')->exists($file_hrp1513)) {
            try {
                $content_hrp1513 = Storage::disk('ftp_interface')->get($file_hrp1513);
                $convert = nl2br($content_hrp1513);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd($arr_obj);

                    if (count($arr_obj) == 5) {
                        $objid = trim($arr_obj[0]);
                        $begda = trim($arr_obj[1]);
                        $endda = trim($arr_obj[2]);
                        $mgrp = trim($arr_obj[3]);
                        $sgrp = trim($arr_obj[4]);

                        // update data
                        $obj = HRP1513::where('objid', $objid)->first();
//                    dd($obj);
                        if ($obj == null) {
                            $obj = new HRP1513();
                            echo 'New -> ';

//                    }
                        }

                        $obj->objid = $objid;
                        $obj->begda = $begda;
                        $obj->endda = $endda;
                        $obj->mgrp = $mgrp;
                        $obj->sgrp = $sgrp;
//                        dd($obj);
                        $obj->save();
                        echo $obj->objid . '|' . $obj->begda . '|' . $obj->endda . '|' . $obj->mgrp . '|' . $obj->sgrp . '<br>';
                    }

                }
                echo $file_hrp1513 . ' FINISHED<br>';
                InterfaceLog::log($file_hrp1513, 'Interface SAP', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_hrp1513, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_hrp1513, 'FILE NOT FOUND', 'ERROR');
        }

	}

	if($pa0001){

//        PA0001
//        PERNR	ENDDA	BEGDA	BUKRS	WERKS	PERSG	GSBER	BTRTL	ORGEH	PLANS	SNAME

//        dd(Storage::disk('ftp_interface')->exists($file_pa0001));
        if (Storage::disk('ftp_interface')->exists($file_pa0001)) {
            try {
                $content_pa0001 = Storage::disk('ftp_interface')->get($file_pa0001);
                $convert = nl2br($content_pa0001);
                $arr_content = explode('<br />', $convert);
//                dd($arr_content);
//            echo count($arr_content).'<br />';
                $count_peg_baru = 0;
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd(count($arr_obj));

                    if (count($arr_obj) == 12) {
                        $pernr = trim($arr_obj[0]);
                        $endda = trim($arr_obj[1]);
                        $begda = trim($arr_obj[2]);
                        $bukrs = trim($arr_obj[3]);
                        $werks = trim($arr_obj[4]);
                        $persg = trim($arr_obj[5]);
                        $persk = trim($arr_obj[6]);
                        $gsber = trim($arr_obj[7]);
                        $btrtl = trim($arr_obj[8]);
                        $orgeh = trim($arr_obj[9]);
                        $plans = trim($arr_obj[10]);
                        $sname = trim($arr_obj[11]);

//                        if($orgeh!='00000000') {
//dd($arr_obj);
//			if($pernr == '64023402'){
                            // update data
                            $obj = PA0001::where('pernr', $pernr)->first();
//                    dd($obj);
                            if ($obj == null) {
                                $obj = new PA0001();
                                echo 'New -> ';
                                $count_peg_baru++;
//                    }
                            }

                            $exclude = $obj->excludeInterface;

                            //$tanggal_sekarang = Carbon::now();

                            //jika tidak ada exclude atau ada exclude tapi sudah exp
                            //if ($exclude == null || ($exclude != null && $exclude->endda < $tanggal_sekarang)) {
                            if ($exclude == null) {
                                $obj->pernr = $pernr;
                                $obj->endda = $endda;
                                $obj->begda = $begda;
                                $obj->bukrs = $bukrs;
                                $obj->werks = $werks;
                                $obj->persg = $persg;
                                $obj->persk = $persk;
                                $obj->gsber = $gsber;
                                $obj->btrtl = $btrtl;
                                $obj->orgeh = $orgeh;
                                $obj->plans = $plans;
                                $obj->sname = $sname;
//                        dd($obj);
                                $obj->save();

                                // update data ESS
                                $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                                if ($strukjab != null) {
                                    $strukjab->plans = $plans;
                                    $strukjab->orgeh = $orgeh;
                                    $strukjab->save();
                                }

                                echo $obj->pernr . '|' . $obj->sname . '|' . $obj->gsber . '|' . $obj->bukrs . '|' . $obj->btrtl . '<br>';
                            } else {
                                echo 'Exclude -> ' . $obj->pernr . '|' . $obj->sname . '|' . $obj->gsber . '|' . $obj->bukrs . '|' . $obj->btrtl . '<br>';
                            }
				//if($pernr == '88117601')dd('OK');
//                        }
//                        else{
                            // IF PENSIUN OR ELSE UPDATE STATUS USER
                            if(!($persg=='1' || $persg=='0' || $persg=='8')){
                                //cari NIP
                                $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                                if ($strukjab != null) {
                                    // update status User
                                    $user = User::where('nip', $strukjab->nip)->first();
                                    if($user!=null){
                                        $user->status = 'INAC';
                                        $user->save();
                                    }
                                }

                                echo 'Not Active -> ' . $pernr . '|' . $sname . '|' . $orgeh . '<br>';
                            }


//                        } // if pernr == XXXX
                    }

                }
                echo $file_pa0001 . ' FINISHED<br>';
                InterfaceLog::log($file_pa0001, 'Interface SAP : Pegawai Baru('.$count_peg_baru.')', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_pa0001, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_pa0001, 'FILE NOT FOUND', 'ERROR');
        }

	}

	if($pa0032){

//        PA0032
//        PERNR	NIP

        if (Storage::disk('ftp_interface')->exists($file_pa0032)) {
            try {
                $content_pa0032 = Storage::disk('ftp_interface')->get($file_pa0032);
                $convert = nl2br($content_pa0032);
                $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                    $arr_obj = explode('|', $line);
//                dd($arr_obj);

                    if (count($arr_obj) == 2) {
                        $pernr = trim($arr_obj[0]);
                        $nip = trim($arr_obj[1]);

                        // update data
                        $obj = PA0032::where('pernr', $pernr)->first();
//                    dd($obj);
                        if ($obj == null) {
                            $obj = new PA0032();
                            echo 'New -> ';
                            $obj->pernr = $pernr;
                            $obj->nip = strtoupper($nip);
//                        dd($obj);
                            $obj->save();
                            echo $obj->pernr . '|' . $obj->nip . '<br>';

//                    }
                        }
                        else{
                            $obj->pernr = $pernr;
                            $obj->nip = strtoupper($nip);
//                        dd($obj);
                            $obj->save();
                            echo $obj->pernr . '|' . $obj->nip . '<br>';
                        }
                    }

                }
                echo $file_pa0032 . ' FINISHED<br>';
                InterfaceLog::log($file_pa0032, 'Interface SAP', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log($file_pa0032, $e->getMessage(), 'ERROR');
            }
        } else {
            InterfaceLog::log($file_pa0032, 'FILE NOT FOUND', 'ERROR');
        }

	}

        if($zpdelegation){

//        ZPDELEGATION
//        POSITION_1	BEGDA   ENDDA   POSITION_2

            if (Storage::disk('ftp_interface')->exists($file_zpdelegation)) {
                try {
                    $content_zpdelegation = Storage::disk('ftp_interface')->get($file_zpdelegation);
                    $convert = nl2br($content_zpdelegation);
                    $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
                    foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                        $arr_obj = explode('|', $line);
//                dd($arr_obj);

                        if (count($arr_obj) == 4) {
                            $position_1 = trim($arr_obj[0]);
                            $begda = trim($arr_obj[1]);
                            $endda = trim($arr_obj[2]);
                            $position_2 = trim($arr_obj[3]);

                            // update data
                            $obj = ZPDelegation::where('position_1', $position_1)
                                ->where('begda', $begda)
                                ->where('endda', $endda)
                                ->where('position_2', $position_2)
                                ->first();
//                    dd($obj);
                            if ($obj == null) {
                                $obj = new ZPDelegation();
                                echo 'New -> ';
                                $obj->position_1 = $position_1;
                                $obj->begda = $begda;
                                $obj->endda = $endda;
                                $obj->position_2 = $position_2;
//                        dd($obj);
				$obj->timestamps = false;
                                $obj->save();
				//dd($obj);
                                echo $obj->position_1 . '|' . $obj->begda. '|' . $obj->endda. '|' . $obj->position_2 . '<br>';

//                    }
                            }
                        }

                    }
                    echo $file_zpdelegation . ' FINISHED<br>';
                    InterfaceLog::log($file_zpdelegation, 'Interface SAP', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_zpdelegation, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_zpdelegation, 'FILE NOT FOUND', 'ERROR');
            }

        }

	if($bacc){

//      Update Business Area dan Company Code User
        $users = User::whereNotNull('nip')->get();

        $exclude = EksepsiInterface::select(['nip'])->get();
        $exclude_nip = [];
        foreach ($exclude as $exc) {
            $exclude_nip[]=$exc->nip;
        }
            
        try {
            foreach ($users as $user) {
                if(!in_array($user->nip,$exclude_nip)){
                    if ($user->pa0032 != null) {
                        $user->business_area = $user->pa0032->pa0001->gsber;
                        $user->company_code = $user->pa0032->pa0001->bukrs;
    //                dd($user->pa0032);
                        echo $user->nip . '|' . $user->business_area . '|' . $user->company_code . '<br>';
                        $user->save();
                    }
                }
            }
            echo 'Update Business Area & Company Code FINISHED<br>';
            InterfaceLog::log('Update Business Area & Company Code User', 'Interface SAP', 'FINISHED');
        } catch (Exception $e) {
            InterfaceLog::log('Update Business Area & Company Code User', $e->getMessage(), 'ERROR');
        }

	}

//        dd('FINISH');

//        $file = $file_hrp1008.';'.$file_hrp1513.';'.$file_pa0001.';'.$file_pa0032;
//        InterfaceLog::log($file, 'Interface SAP', 'FINISHED');
    }

    public function updateJenjangStrukOrg()
    {
//      ID JENJANG :  2,3,5
        $company_code = CompanyCode::all();
        foreach ($company_code as $cc) {
            $jenjang_id = 2;
            $jenjang = JenjangJabatan::find($jenjang_id);
            $level = $jenjang->level;
            $co_code = $cc->company_code;
            $pejabat = JenjangJabatan::getListPejabat($cc->company_code, $jenjang_id);
            foreach ($pejabat as $personel) {
                $orgeh = $personel->orgeh;
                echo $orgeh . '|' . $jenjang_id . '|' . $level . '|' . $co_code . '|' . $personel->sname . '<br>';
                $strukorg = StrukturOrganisasi::where('objid', $orgeh)->first();
                $strukorg->level = $level;
                $strukorg->jenjang_id = $jenjang_id;
                $strukorg->company_code = $co_code;
                $strukorg->save();
//                dd($strukorg);
            }
        }
//        dd($pejabat);
    }

    public function resetCommitment(){

        for($tahun=2019;$tahun>=2017;$tahun--) {
            $tahun_reset = $tahun + 7900;

//        $perilaku = PerilakuPegawai::where('tahun', $tahun)->where('user_id','75176')->count();
//        $perilaku = DB::table('perilaku_pegawai')
//            ->select('user_id', DB::raw('count(*) as total'))
//            ->groupBy('user_id')
//            ->take(10)->pluck('user_id','tahun','total')->all();
//        dd($perilaku);

            $user_list = User::with([
                'perilakuPegawai' => function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                }])->where('nip', '!=', 'null')->where('status', 'ACTV')->orderBy('id', 'asc')->get();
//            dd($user_list);
            foreach ($user_list as $user) {
//            dd($user->perilakuPegawai->count());
                $jml_perilaku = $user->perilakuPegawai->count();
                // jika jumlah perilaku 1-17
                if ($jml_perilaku > 0 && $jml_perilaku < 18) {
                    // set year to 9999
                    foreach ($user->perilakuPegawai as $perilaku) {
                        $perilaku->tahun = $tahun_reset;
                        $perilaku->save();
//                    echo $perilaku->tahun.'<br>';
                    }
                    echo 'TAHUN : '.$tahun.'->'.$tahun_reset.' | ID :' . $user->id . ' | JML : ' . $jml_perilaku . '<br>';
                }
            }
        }
    }

    public function checkStatus(){
        $date = Carbon::now();
        // $date = Carbon::yesterday();
        // $date = Carbon::parse('2020-04-06');

        // $tgl_file = date('Ymd');
        $tgl_file = $date->format('Ymd');
        $file_hrp1008 = 'HRP1008_'.$tgl_file.'.txt';
        $file_hrp1513 = 'HRP1513_'.$tgl_file.'.txt';
        $file_pa0001 = 'PA0001_'.$tgl_file.'.txt';
        $file_pa0032 = 'PA0032.txt';
        $file_bacc = 'Update Business Area & Company Code User';
        
        $interface_log = InterfaceLog::whereDate('created_at', '=', $date->format("Y-m-d"))->orderBy('id','asc')->get();

        $i = 0;
        $err = 0;
        $arr_interface = ['HRP1008', 'HRP1513', 'PA0001', 'PA0032', 'Update Business Area & Company Code User', 
                            'STRUKJAB', 'STRUKPOS', 'STRUKORG'];
        $arr_interface_run = [];
        $arr_interface_error = [];
        foreach($interface_log as $log){
            $file = $log->file;

            // cek interface yang jalan
            // cek status HRP1008
            if($file==$file_hrp1008){
                array_push($arr_interface_run, 'HRP1008');
            }
            // cek status HRP1513
            if($file==$file_hrp1513){
                array_push($arr_interface_run, 'HRP1513');
            }
            // cek status PA0001
            if($file==$file_pa0001){
                array_push($arr_interface_run, 'PA0001');
            }
            // cek status PA0032
            if($file==$file_pa0032){
                array_push($arr_interface_run, 'PA0032');
            }
            // cek status Update Business Area and Company Code
            if($file==$file_bacc){
                array_push($arr_interface_run, 'Update Business Area & Company Code User');
            }
            // cek status STRUKJAB
            if(strpos($file, 'STRUKJAB_TBL_' . $tgl_file) !== false){
                array_push($arr_interface_run, 'STRUKJAB');
            }
            // cek status STRUKPOS
            if(strpos($file, 'STRUKPOS_TBL_' . $tgl_file) !== false){
                array_push($arr_interface_run, 'STRUKPOS');
            }
            // cek status STRUKORG
            if(strpos($file, 'STRUKORG_TBL_' . $tgl_file) !== false){
                array_push($arr_interface_run, 'STRUKORG');
            }

            // cek status error
            if($log->status=='ERROR'){
                array_push($arr_interface_error, $log->message.' : '.$file);
                $err++;
            }

            $i++;
        }

        // jika jml interface kurang
        $interface_diff = array_diff($arr_interface, $arr_interface_run);
        if(count($interface_diff)>0){
            foreach($interface_diff as $diff){
                array_push($arr_interface_error, 'FAILED RUN : '.$diff);
            }
        }

        // jika ada error / jml interface kurang kirim email ke root
        if(count($interface_diff)>0 || count($arr_interface_error)>0){

            $err_msg = '';
            foreach($arr_interface_error as $msg){
                $err_msg = $err_msg.$msg.', ';
            }

            $role_root = Role::find(1);
            $root_list = $role_root->users;
            // $root_list = $role_root->users()->where('id','1')->get();
            $color = 'danger';

            foreach ($root_list as $user) {

                $notif = new Notification();
                $notif->from = 'SYSTEM';
                $notif->to = $user->username2;
                $notif->user_id_from = '1';
                $notif->user_id_to = $user->id;
                $notif->subject = 'Error Interface SAP';
                $notif->color = $color;
                $notif->icon = 'fa fa-times';
                $notif->message = $err_msg;
                $notif->url = 'coc';
                $notif->save();

                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Error Interface SAP';
                $mail->file_view = 'emails.error_interface';
                $mail->message = $err_msg;
                $mail->status = 'CRTD';
                $mail->parameter = '{"msg_error":' . json_encode($arr_interface_error) . ',"tanggal":"' . $date->format('d-m-Y') . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '666';

                $mail->save();
            }

            
            return count($arr_interface_error).' error found.';
        }

        return 'FINISHED';
    }

    public function notifOrganisasiBaru($arr_strukorg_baru = ''){
        $date = Carbon::now();
            $role_root = Role::find(1);
            $root_list = $role_root->users;
            // $root_list = $role_root->users()->where('id','1')->get();
            $color = 'warning';

            foreach ($root_list as $user) {

                $notif = new Notification();
                $notif->from = 'SYSTEM';
                $notif->to = $user->username2;
                $notif->user_id_from = '1';
                $notif->user_id_to = $user->id;
                $notif->subject = 'Struktur Organisasi Baru di SAP';
                $notif->color = $color;
                $notif->icon = 'fa fa-warning';
                $notif->message = 'Struktur organisasi baru di SAP. Mohon untuk melakukan update data level, jenjang_id dan company_code pada tabel M_STRUKTUR_ORGANISASI';
                $notif->url = 'coc';
                $notif->save();

                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Struktur Organisasi Baru di SAP';
                $mail->file_view = 'emails.organisasi_baru_sap';
                $mail->message = $notif->message;
                $mail->status = 'CRTD';
                $mail->parameter = '{"info_msg":' . json_encode($arr_strukorg_baru) . ',"tanggal":"' . $date->format('d-m-Y') . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '555';

                $mail->save();
            }

            
        return 'FINISHED';
    }
}
