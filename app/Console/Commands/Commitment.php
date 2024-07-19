<?php

namespace App\Console\Commands;

use App\Jawaban;
use App\JawabanPegawai;
use App\KomitmenPegawai;
use App\Perilaku;
use App\PerilakuPegawai;
use App\Pertanyaan;
use App\PLNTerbaik;
use App\User;
use Illuminate\Console\Command;

class Commitment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commitment:nip {nip : NIP Pegawai} {tahun : Tahun Komitmen}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete Commitment Employee';

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
        $nip = $this->argument('nip');
        $tahun = $this->argument('tahun');

        $user = User::where('nip', $nip)->first();

        // loop perilaku
        $perilaku_list = PLNTerbaik::all();

        foreach ($perilaku_list as $perilaku) {

            // save perilaku
            $perilaku_peg = new PerilakuPegawai();
            $perilaku_peg->user_id = $user->id;
            $perilaku_peg->pedoman_perilaku_id = $perilaku->id;
            $perilaku_peg->do = '1';
            $perilaku_peg->dont = '1';
            $perilaku_peg->tahun = $tahun;
            $perilaku_peg->save();

            // get pertanyaan perilaku
            $pertanyaan = Pertanyaan::where('pedoman_perilaku_id', $perilaku->id)->where('status', 'ACTV')->first();

            // get jawaban pertanyaan
            $jawaban = Jawaban::where('pertanyaan_id', $pertanyaan->id)->where('benar', '1')->where('status', 'ACTV')->first();

            // save jawaban
            $jawaban_pegawai                        = new JawabanPegawai();
            $jawaban_pegawai->user_id               = $user->id;
            $jawaban_pegawai->orgeh                 = $user->strukturJabatan->orgeh;
            $jawaban_pegawai->plans                 = $user->strukturJabatan->plans;
            $jawaban_pegawai->pedoman_perilaku_id   = $perilaku->id;
            $jawaban_pegawai->pertanyaan_id         = $pertanyaan->id;
            $jawaban_pegawai->jawaban_id            = $jawaban->id;
            $jawaban_pegawai->benar                 = '1';
            $jawaban_pegawai->tahun                 = $tahun;
            $jawaban_pegawai->save();
        }

        // insert komitmen
        $jml_pedoman = env('JML_PEDOMAN', 14);

        $tahun = $tahun;

        $jml_perilaku_pegawai = $user->perilakuPegawai()->where('tahun', $tahun)->get()->count();

        if ($jml_perilaku_pegawai == $jml_pedoman) {
            $jabatan_pegawai = $user->strukturJabatan;
            $komitmen_pegawai = new KomitmenPegawai();
            $komitmen_pegawai->user_id = $user->id;
            $komitmen_pegawai->orgeh = $jabatan_pegawai->orgeh;
            $komitmen_pegawai->plans = $jabatan_pegawai->plans;
            $komitmen_pegawai->setuju = 1;
            $komitmen_pegawai->tahun = $tahun;
            $komitmen_pegawai->save();

            // return redirect('/')->with('success', 'Komitmen Pegawai ' . $tahun . ' berhasil disimpan. Terimakasih.');
            // echo 'Finished!';
            $this->info('Finished!');
        } else {
            //     // return redirect('/')->with('warning', 'Anda belum membaca semua Pedoman Perilaku');
            // echo 'Error!';
            $this->error('ERROR!');
        }
    }
}
