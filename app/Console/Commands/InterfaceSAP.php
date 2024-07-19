<?php

namespace App\Console\Commands;

use App\Hrp1008;
use App\HRP1513;
use App\InterfaceLog;
use App\PA0001;
use App\PA0032;
use App\StrukturJabatan;
use App\User;
use App\ZPDelegation;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;

class InterfaceSAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interface:sap 
                            {--all : Update all data} 
                            {--pa0001 : Update data PA0001} 
                            {--pa0032 : Update data PA0032} 
                            {--hrp1008 : Update data HRP1008} 
                            {--hrp1513 : Update data HRP1513} 
                            {--bacc : Update data Business Area & Company Code User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run interface SAP';

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
            $hrp1008 = true;
            $hrp1513 = true;
            $pa0001 = true;
            $pa0032 = true;
            $bacc = true;
        }
        else{
            $hrp1008 = $this->option('hrp1008');
            $hrp1513 = $this->option('hrp1513');
            $pa0001 = $this->option('pa0001');
            $pa0032 = $this->option('pa0032');
            $bacc = $this->option('bacc');
        }

        $zpdelegation = false;

        // Backup table
        $this->info('Backup Table...');
        if ($hrp1008) {
            $this->truncateThenCopy('hrp1008_bak', 'hrp1008');
        }
        if ($hrp1513) {
            $this->truncateThenCopy('hrp1513_bak', 'hrp1513');
        }
        if ($pa0001) {
            $this->truncateThenCopy('pa0001_bak', 'pa0001');
        }
        if ($pa0032) {
            $this->truncateThenCopy('pa0032_bak', 'pa0032');
        }
        if ($zpdelegation) {
            $this->truncateThenCopy('zpdelegation_bak', 'zpdelegation');
        }

        //        $file_hrp1008 = 'HRP1008.txt';
        //        $file_hrp1513 = 'HRP1513.txt';
        //        $file_pa0001 = 'PA0001.txt';
        $tgl_file = date('Ymd');
        $file_hrp1008 = 'HRP1008_' . $tgl_file . '.txt';
        $file_hrp1513 = 'HRP1513_' . $tgl_file . '.txt';
        $file_pa0001 = 'PA0001_' . $tgl_file . '.txt';


        $file_pa0032 = 'PA0032.txt';
        $file_zpdelegation = 'ZPDELEGATION.txt';

        if ($hrp1008) {
            $this->info('Updating HRP1008...');
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
                                // echo 'New -> ';
                                $this->line('[New]');

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
                            // echo $obj->objid . '|' . $obj->bukrs . '|' . $obj->gsber . '|' . $obj->persa . '|' . $obj->btrtl . '<br>';

                            $this->line($obj->objid . '|' . $obj->bukrs . '|' . $obj->gsber . '|' . $obj->persa . '|' . $obj->btrtl);
                        }
                    }
                    // echo $file_hrp1008 . ' FINISHED<br>';
                    $this->info($file_hrp1008 . ' FINISHED');
                    InterfaceLog::log($file_hrp1008, 'Interface SAP', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_hrp1008, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_hrp1008, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($hrp1513) {
            $this->info('Updating HRP1513...');

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
                                // echo 'New -> ';
                                $this->line('[New]');

                                //                    }
                            }

                            $obj->objid = $objid;
                            $obj->begda = $begda;
                            $obj->endda = $endda;
                            $obj->mgrp = $mgrp;
                            $obj->sgrp = $sgrp;
                            //                        dd($obj);
                            $obj->save();
                            // echo $obj->objid . '|' . $obj->begda . '|' . $obj->endda . '|' . $obj->mgrp . '|' . $obj->sgrp . '<br>';
                            $this->line($obj->objid . '|' . $obj->begda . '|' . $obj->endda . '|' . $obj->mgrp . '|' . $obj->sgrp);
                        }
                    }
                    // echo $file_hrp1513 . ' FINISHED<br>';
                    $this->info($file_hrp1513 . ' FINISHED');
                    InterfaceLog::log($file_hrp1513, 'Interface SAP', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_hrp1513, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_hrp1513, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($pa0001) {
            $this->info('Updating PA0001...');

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
                                // echo 'New -> ';
                                $this->line('[New]');
                                $count_peg_baru++;
                                //                    }
                            }

                            //$exclude = $obj->excludeInterface;

                            //$tanggal_sekarang = Carbon::now();

                            //jika tidak ada exclude atau ada exclude tapi sudah exp
                            //if ($exclude == null || ($exclude != null && $exclude->endda < $tanggal_sekarang)) {
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

                            // echo $obj->pernr . '|' . $obj->sname . '|' . $obj->gsber . '|' . $obj->bukrs . '|' . $obj->btrtl . '<br>';
                            $this->line($obj->pernr . '|' . $obj->sname . '|' . $obj->gsber . '|' . $obj->bukrs . '|' . $obj->btrtl);
                            /*} else {
                                echo 'Exclude -> ' . $obj->pernr . '|' . $obj->sname . '|' . $obj->gsber . '|' . $obj->bukrs . '|' . $obj->btrtl . '<br>';
                            }*/
                            //if($pernr == '88117601')dd('OK');
                            //                        }
                            //                        else{
                            // IF PENSIUN OR ELSE UPDATE STATUS USER
                            if (!($persg == '1' || $persg == '0' || $persg == '8')) {
                                //cari NIP
                                $strukjab = StrukturJabatan::where('pernr', $pernr)->first();
                                if ($strukjab != null) {
                                    // update status User
                                    $user = User::where('nip', $strukjab->nip)->first();
                                    if ($user != null) {
                                        $user->status = 'INAC';
                                        $user->save();
                                    }
                                    //                                    $strukjab->user->status = 'INAC';
                                    //                                    $strukjab->orgeh = $orgeh;
                                    //                                    $strukjab->save();
                                }

                                // echo 'Not Active -> ' . $pernr . '|' . $sname . '|' . $orgeh . '<br>';
                                $this->line('[Not Active] ' . $pernr . '|' . $sname . '|' . $orgeh);
                            }


                            //                        } // if pernr == XXXX
                        }
                    }
                    // echo $file_pa0001 . ' FINISHED<br>';
                    $this->info($file_pa0001 . ' FINISHED');
                    InterfaceLog::log($file_pa0001, 'Interface SAP : Pegawai Baru(' . $count_peg_baru . ')', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_pa0001, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_pa0001, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($pa0032) {
            $this->info('Updating PA0032...');

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
                                // echo 'New -> ';
                                $obj->pernr = $pernr;
                                $obj->nip = strtoupper($nip);
                                //                        dd($obj);
                                $obj->save();
                                // echo $obj->pernr . '|' . $obj->nip . '<br>';
                                $this->line('[New] '.$obj->pernr . '|' . $obj->nip);

                            //                    }
                            } else {
                                $obj->pernr = $pernr;
                                $obj->nip = strtoupper($nip);
                                //                        dd($obj);
                                $obj->save();
                                // echo $obj->pernr . '|' . $obj->nip . '<br>';
                                $this->line($obj->pernr . '|' . $obj->nip);
                            }
                        }
                    }
                    // echo $file_pa0032 . ' FINISHED<br>';
                    $this->info($file_pa0032 . ' FINISHED');
                    InterfaceLog::log($file_pa0032, 'Interface SAP', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_pa0032, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_pa0032, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($zpdelegation) {
            $this->info('Updating ZPDELEGATION...');
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
                                // echo 'New -> ';
                                $obj->position_1 = $position_1;
                                $obj->begda = $begda;
                                $obj->endda = $endda;
                                $obj->position_2 = $position_2;
                                //                        dd($obj);
                                $obj->timestamps = false;
                                $obj->save();
                                //dd($obj);
                                // echo $obj->position_1 . '|' . $obj->begda . '|' . $obj->endda . '|' . $obj->position_2 . '<br>';
                                $this->line('[New] '.$obj->position_1 . '|' . $obj->begda . '|' . $obj->endda . '|' . $obj->position_2);

                                //                    }
                            }
                        }
                    }
                    // echo $file_zpdelegation . ' FINISHED<br>';
                    $this->info($file_zpdelegation . ' FINISHED');
                    InterfaceLog::log($file_zpdelegation, 'Interface SAP', 'FINISHED');
                } catch (Exception $e) {
                    InterfaceLog::log($file_zpdelegation, $e->getMessage(), 'ERROR');
                }
            } else {
                InterfaceLog::log($file_zpdelegation, 'FILE NOT FOUND', 'ERROR');
            }
        }

        if ($bacc) {
            $this->info('Updating Business Area & Company Code User...');
            //      Update Business Area dan Company Code User
            $users = User::whereNotNull('nip')->get();

            try {
                foreach ($users as $user) {
                    if ($user->pa0032 != null) {
                        // echo $user->nip . '|' . $user->business_area . '|' . $user->company_code . '<br>';
                        $this->line($user->nip . '|' . $user->business_area . '|' . $user->company_code);
                        $user->business_area = $user->pa0032->pa0001->gsber;
                        $user->company_code = $user->pa0032->pa0001->bukrs;
                        //                dd($user->pa0032);
                        $user->save();
                    }
                }
                // echo 'Update Business Area & Company Code FINISHED<br>';
                $this->info('Update Business Area & Company Code User FINISHED');
                InterfaceLog::log('Update Business Area & Company Code User', 'Interface SAP', 'FINISHED');
            } catch (Exception $e) {
                InterfaceLog::log('Update Business Area & Company Code User', $e->getMessage(), 'ERROR');
            }
        }


        $this->info('Interface SAP Finished');
    }
}
