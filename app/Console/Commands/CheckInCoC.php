<?php

namespace App\Console\Commands;

use App\Attendant;
use App\Coc;
use App\ReadMateri;
use App\User;
use Illuminate\Console\Command;

class CheckInCoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coc:checkin-nas {nip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check In CoC Nasional';

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
        // get user
        $user = User::where('nip', $this->argument('nip'))->first();

        // get organisasi 
        $arr_org_user = $user->getArrOrgLevel();

        // get coc nasional
        $coc_local = Coc::where('scope', 'local')
            ->whereDate('tanggal_jam', '=', date('Y-m-d'))
            ->where('jenis_coc_id', 1)
            ->where('status', '!=', 'CANC')
            ->whereIn('orgeh', $arr_org_user)
            ->first();

        if($coc_local!=null){
            
            // checkin coc
            Attendant::create([
                'coc_id' => $coc_local->id,
                'user_id' => $user->id,
                'check_in' => date('Y-m-d H:i:s'),
                'status_checkin_id' => 1,
            ]);

            // read materi
            ReadMateri::create([
                'username' => $user->username,
                'pernr' => $user->pa0032->pernr,
                'nip' => $user->nip,
                'materi_id' => $coc_local->materi_id,
                'tanggal_jam' => date('Y-m-d H:i:s'),
                'coc_id' => $coc_local->id,
                'admin_id' => $coc_local->admin_id,
                'user_id' => $user->id,
                'rate_star' => 3,
            ]);
        }

    }
}
