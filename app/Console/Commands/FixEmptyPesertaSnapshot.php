<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixEmptyPesertaSnapshot extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'liquid:fix-empty-peserta-snapshot';
    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Isi snapshot peserta yang masih null';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $liquidPesertaToBeFixed = \App\Models\Liquid\LiquidPeserta::query()
            ->whereNull('snapshot_nama_atasan')
            ->orWhereNull('snapshot_nip_atasan')
            ->orWhereNull('snapshot_jabatan2_atasan')
            ->get();

        echo("Empty snapshot to be fixed: ".$liquidPesertaToBeFixed->count() . "\n");

        foreach ($liquidPesertaToBeFixed as $peserta) {
            $snapshotAtasan = DB::table('v_snapshot_pegawai')->where('pernr', $peserta->atasan_id)->first();
            $snapshotBawahan = DB::table('v_snapshot_pegawai')->where('pernr', $peserta->bawahan_id)->first();

            if ($snapshotAtasan) {
                if ($peserta->snapshot_nama_atasan === null) {
                    $peserta->snapshot_nama_atasan = $snapshotAtasan->nama;
                }
                if ($peserta->snapshot_nip_atasan === null) {
                    $peserta->snapshot_nip_atasan = $snapshotAtasan->nip;
                }
                if ($peserta->snapshot_jabatan2_atasan === null) {
                    $peserta->snapshot_jabatan2_atasan = $snapshotAtasan->jabatan;
                }
                if ($peserta->snapshot_unit_code === null) {
                    $peserta->snapshot_unit_code = $snapshotAtasan->unit_code;
                }
                if ($peserta->snapshot_unit_name === null) {
                    $peserta->snapshot_unit_name = $snapshotAtasan->unit_name;
                }
                if ($peserta->snapshot_plans === null) {
                    $peserta->snapshot_plans = $snapshotAtasan->plans;
                }
                if ($peserta->snapshot_orgeh_1 === null) {
                    $peserta->snapshot_orgeh_1 = $snapshotAtasan->orgeh1;
                }
                if ($peserta->snapshot_orgeh_2 === null) {
                    $peserta->snapshot_orgeh_2 = $snapshotAtasan->orgeh2;
                }
                if ($peserta->snapshot_orgeh_3 === null) {
                    $peserta->snapshot_orgeh_3 = $snapshotAtasan->orgeh3;
                }
            }

            if ($snapshotBawahan) {
                if ($peserta->snapshot_nama_bawahan === null) {
                    $peserta->snapshot_nama_bawahan = $snapshotBawahan->nama;
                }
                if ($peserta->snapshot_nip_bawahan === null) {
                    $peserta->snapshot_nip_bawahan = $snapshotBawahan->nip;
                }
                if ($peserta->snapshot_jabatan2_bawahan === null) {
                    $peserta->snapshot_jabatan2_bawahan = $snapshotBawahan->jabatan;
                }
            }
            $peserta->save();
        }
    }
}
