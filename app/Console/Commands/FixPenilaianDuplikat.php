<?php

namespace App\Console\Commands;

use App\Models\Liquid\Feedback;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\PengukuranKedua;
use App\Models\Liquid\PengukuranPertama;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPenilaianDuplikat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquid:fix-penilaian-duplikat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing penilaian peserta double secara interaktif';

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
        $dataDuplikat = collect(DB::table('v_liquid_peserta_duplikat')->get())->groupBy(function ($item) {
            return sprintf('%s-%s-%s', $item->liquid_id, $item->atasan_id, $item->bawahan_id);
        });

        foreach ($dataDuplikat as $key => $data) {
            list($liquidId, $pernrAtasan, $pernBawahan) = explode('-', $key);
            $atasan = DB::table('v_snapshot_pegawai')->where('pernr', $pernrAtasan)->first();
            $bawahan = DB::table('v_snapshot_pegawai')->where('pernr', $pernBawahan)->first();
            $this->info(sprintf('Atasan: %s - %s - %s', $atasan->nama, $atasan->jabatan, $atasan->unit_name));
            $this->info(sprintf('Bawahan: %s - %s - %s', $bawahan->nama, $bawahan->jabatan, $bawahan->unit_name));

            $headers = [];
            foreach ($data as $item) {
                $headers[] = $item->id;
            }
            $rows = [];

            foreach ($data as $item) {
                $feedback = Feedback::find($item->feedback_id);
                if ($feedback) {
                    $text = "<info>Kelebihan</info> (Diinput pada {$feedback->created_at})\n";
                    $text .= $feedback->getKelebihanAsArray()->implode("\n");
                    $text .= "\n";
                    $text .= "<info>Kekurangan</info>\n";
                    $text .= $feedback->getKekuranganAsArray()->implode("\n");
                    $text .= "\n";
                    $text .= "<info>Harapan</info>\n";
                    $text .= $feedback->harapan;
                    $text .= "\n";
                    $text .= "<info>Saran</info>\n";
                    $text .= $feedback->saran;
                } else {
                    $text = '-';
                }
                $rows['feedback'][] = $text;
            }

            foreach ($data as $item) {
                $pengukuranPertama = PengukuranPertama::find($item->pengukuran_pertama_id);
                $text = "<info>Pengukuran Pertama</info>\n";
                if ($pengukuranPertama) {
                    $text .= "(Diinput pada {$pengukuranPertama->created_at})\n";
                    $text .= implode("\n", $pengukuranPertama->penilaian());
                } else {
                    $text .= '-';
                }
                $rows['pengukuran_pertama'][] = $text;
            }

            foreach ($data as $item) {
                $pengukuranKedua = PengukuranKedua::find($item->pengukuran_kedua_id);
                $text = "<info>Pengukuran Kedua</info>\n";
                if ($pengukuranKedua) {
                    $text .= "(Diinput pada {$pengukuranKedua->created_at})\n";
                    $text .= implode("\n", $pengukuranKedua->penilaian());
                } else {
                    $text .= '-';
                }
                $rows['pengukuran_kedua'][] = $text;
            }

            $this->table($headers, $rows);

            $headers[] = 'skip';
            $headers[] = 'exit';

            $idUntukDihapus = $this->choice('Silakan pilih ID yang akan dihapus:', $headers);

            if ($idUntukDihapus === 'exit') {
                exit;
            }

            if ($idUntukDihapus !== 'skip') {
                LiquidPeserta::where('id', $idUntukDihapus)->delete();
            }
        }
    }
}
