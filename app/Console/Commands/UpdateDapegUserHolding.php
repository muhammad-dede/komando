<?php

namespace App\Console\Commands;

use App\InterfaceLog;
use App\StrukturJabatan;
use App\User;
use Exception;
use Illuminate\Console\Command;

class UpdateDapegUserHolding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:update-user-holding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Data orgeh, plans, personel_are, personel_subarea table users pegawai holding ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            // get data pegawai holding
            // $list_pegawai = StrukturJabatan::all();
            $list_pegawai = StrukturJabatan::where('plans','!=',99999999)
                                            ->where('plans','!=',0)
                                            // ->take(10)
                                            ->get();
            foreach ($list_pegawai as $pegawai) {

                if($pegawai->nip == null)
                    continue;

                // check if user exists
                $user = User::where('nip', $pegawai->nip)->where('status', 'ACTV')->first();
                // if user found, update data
                if ($user) {
                    // check if company_code is not 1200 or 1300 
                    if($user->company_code != '1200' && $user->company_code != '1300'){
                        $user->holding = 1;
                    }
                    else{
                        $user->holding = 0;
                    }
                    $user->orgeh = $pegawai->orgeh;
                    $user->plans = $pegawai->plans;
                    $user->personel_area = $pegawai->werks;
                    $user->personel_subarea = str_pad($pegawai->btrtl,4,'0',STR_PAD_LEFT);
                    $user->bidang = @$pegawai->strukturPosisi->stxt2;
                    $user->jabatan = @$pegawai->strukturPosisi->stext;
                    $user->save();

                    $this->line($user->nip." - ".$user->name." - ".$user->username." - ".$user->email." - ".$user->holding." - ".$user->orgeh." - ".$user->plans." - ".$user->personel_area." - ".$user->personel_subarea);
                }
            }

            $this->info('Update Data Pegawai Holding Success');
            InterfaceLog::log('hxms:update-user-holding', 'Update data user holding', 'FINISHED');
        } catch (Exception $e) {
            InterfaceLog::log('hxms:update-user-holding', 'Update data user holding: '.$e->getMessage(), 'ERROR');
        }
    }
}
