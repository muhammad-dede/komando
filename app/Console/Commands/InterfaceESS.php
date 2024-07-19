<?php

namespace App\Console\Commands;

use App\InterfaceLog;
use App\MailLog;
use App\Notification;
use App\PA0032;
use App\Role;
use App\StrukturJabatan;
use App\StrukturOrganisasi;
use App\StrukturPosisi;
use App\StrukturPosisiTmp;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;

class InterfaceESS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interface:ess
                            {--all : Update all data} 
                            {--strukjab : Update data Struktur Jabatan}
                            {--strukpos : Update data Struktur Posisi}
                            {--strukorg : Update data Struktur Organisasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Interface ESS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function truncateThenCopy($tbl_target, $tbl_source)
    {
        // empty table
        DB::statement('TRUNCATE TABLE ' . $tbl_target);
        // copy table
        DB::statement('INSERT INTO ' . $tbl_target . ' (SELECT * from ' . $tbl_source . ')');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('all')){
            $strukjab = true;
            $strukpos = true;
            $strukorg = true;
        }
        else{
            $strukjab = $this->option('strukjab');
            $strukpos = $this->option('strukpos');
            $strukorg = $this->option('strukorg');
        }
        
        // Backup table
        $this->info('Backup Table...');
        if ($strukjab) {
            $this->truncateThenCopy('m_struktur_jabatan_bak', 'm_struktur_jabatan');
        }
        if ($strukpos) {
            $this->truncateThenCopy('m_struktur_posisi_bak', 'm_struktur_posisi');
        }
        if ($strukorg) {
            $this->truncateThenCopy('m_struktur_organisasi_bak', 'm_struktur_organisasi');
        }

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

        $this->info('Searching File Interface...');
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

        if ($strukjab) {
            $this->info('Updating Data Struktur Jabatan...');
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
                            if ($orgeh != '00000000') {
                                //if($pernr == '73931401'){
                                $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                                if ($strukjab == null) {
                                    $strukjab = new StrukturJabatan();
                                    // echo 'New -> ';
                                    $this->line('[New]');
                                    //$pa0032 = PA0032::where('pernr', $pernr)->first();
                                    //$strukjab->nip = strtoupper(@$pa0032->nip);
                                    //$strukjab->pernr = $pernr;
                                    //$strukjab->save();
                                }

                                //$exclude = $strukjab->excludeInterface;

                                //$tanggal_sekarang = Carbon::now();

                                //jika tidak ada exclude atau ada exclude tapi sudah exp
                                //if ($exclude == null || ($exclude != null && $exclude->endda < $tanggal_sekarang)) {
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
                                // echo $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '|' . $strukjab->plans . '|' . $strukjab->orgeh . '<br>';
                                $this->line($strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '|' . $strukjab->plans . '|' . $strukjab->orgeh);
                            /*} else {
                            echo 'Exclude -> ' . $strukjab->nip . '|' . $strukjab->pernr . '|' . $strukjab->cname . '<br>';
                            }*/
                                //	if($pernr=='73931401') die($strukjab);
                            } else {
                                // echo 'Not Active -> ' . $pernr . '|' . $cname . '|' . $orgeh . '<br>';
                                $this->line('[Not Active] ' . $pernr . '|' . $cname . '|' . $orgeh);
                            }
                        }
                    }

                    $this->info('Upadate Struktur Jabatan Finished!');

                    InterfaceLog::log($file_strukjab, 'Interface ESS', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_strukjab, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_strukjab, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($strukpos) {
            $this->info('Updating Data Struktur Posisi...');
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
                            // echo $strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
                            $this->line($strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2);
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
                    $this->info('Update Data Struktur Posisi Finished!');
                    InterfaceLog::log($file_strukpos, 'Interface ESS', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_strukpos, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_strukpos, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($strukorg) {
            $this->info('Updating Data Struktur Organisasi...');
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
                                // echo 'New -> ';
                                $this->line('[New]');
                                $strukorg->objid = $objid;
                                $strukorg->stext = $stext;
                                $strukorg->relat = $relat;
                                $strukorg->sobid = $sobid;
                                $strukorg->stxt2 = $stxt2;
                                //                        dd($strukorg);
                                $strukorg->save();

                                // push to array
                                array_push($arr_strukorg_baru, $objid . ' : ' . $stext);

                                if ($count_new == 0) {
                                    $str_obj_new = $objid;
                                } else {
                                    $str_obj_new = $str_obj_new . ', ' . $objid;
                                }

                                // echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';
                                $this->line($strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2);

                                $count_new++;

                            //                    }
                            } else {
                                $strukorg->objid = $objid;
                                $strukorg->stext = $stext;
                                $strukorg->relat = $relat;
                                $strukorg->sobid = $sobid;
                                $strukorg->stxt2 = $stxt2;
                                //                        dd($strukorg);
                                $strukorg->save();
                                // echo $strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2 . '<br>';
                                $this->line($strukorg->objid . '|' . $strukorg->stext . '|' . $strukorg->sobid . '|' . $strukorg->stxt2);

                                $count_update++;
                            }
                        }
                    }
                    InterfaceLog::log($file_strukorg, 'Interface ESS : Updated(' . $count_update . ') ; New(' . $count_new . ') : ' . $str_obj_new, 'FINISHED');

                    $this->info('Update Data Struktur Organisasi Finished!');
                    // notifikasi ke user root jika ada struktur organisasi baru dan delete organisasi lama
                    if ($count_new > 0) {
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

        $this->info('Interface ESS Finished!');

        return 'FINISH';
    }

    public function notifOrganisasiBaru($arr_strukorg_baru = '')
    {
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

    public function deleteOraganisasiLama()
    {
        // $lastdate_interface = Carbon::parse('20200701');
        $lastdate_interface = Carbon::now()->format('Ymd');
        $strukorg_list_delete = StrukturOrganisasi::where('status', 'ACTV')->whereDate('updated_at', '<', $lastdate_interface)->orderBy('updated_at', 'asc')->get();
        // dd($strukorg_list_delete);
        $count = 0;
        $str_org_del = '';
        foreach ($strukorg_list_delete as $data) {
            $data->status = 'INAC';
            $data->save();

            if ($count == 0) {
                $str_org_del = $data->objid;
            } else {
                $str_org_del = $str_org_del . ', ' . $data->objid;
            }

            $count++;
        }

        InterfaceLog::log('Non-aktif organisasi lama', 'Interface ESS : INAC(' . $count . ') : ' . $str_org_del, 'FINISHED');

        return 'FINISH';
    }
}
