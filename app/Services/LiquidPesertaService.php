<?php

namespace App\Services;

use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\LiquidPeserta;
use App\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class LiquidPesertaService
{
    public function getDetail(LiquidPeserta $peserta)
    {
        $detail = [
            'kelebihan' => [],
            'kekurangan' => [],
            'saran' => null,
            'harapan' => null,
        ];

        $detail['atasan'] = $peserta->liquid->peserta_snapshot[$peserta->atasan_id];
        $detail['foto_atasan'] = !empty($peserta->atasan->user->foto) ? $peserta->atasan->user->foto : null;
        $detail['unit'] = $peserta->liquid->businessAreas()
            ->where('m_business_area.business_area', $detail['atasan']['business_area'])
            ->first();

        if ($peserta->feedback()->exists()) {
            $detail['kelebihan'] = $peserta->feedback->getKelebihanAsArray();
            $detail['kekurangan'] = $peserta->feedback->getKekuranganAsArray();
            $detail['saran'] = $peserta->feedback->saran;
            $detail['harapan'] = $peserta->feedback->harapan;
        }

        $detail['penilaian'] = $peserta->getPenilaian();

        return $detail;
    }

    public function getDetailForAtasan(LiquidPeserta $liquidPeserta)
    {
        $name = 'detailForAtasan';

        if (config('app.isUsingCache') && Cache::has($name)) {
            return Cache::get($name);
        }

        $detail = [
            'saran' => [],
            'harapan' => [],
        ];

        $kelebihans = [];
        $kelebihanLabels = [];
        $kekurangans = [];
        $kekuranganLabels = [];

        $liquid = $liquidPeserta->liquid;

        $detail['atasan'] = $liquid->peserta_snapshot[$liquidPeserta->atasan_id];
        $detail['unit'] = $liquid->businessAreas()
            ->where('m_business_area.business_area', $detail['atasan']['business_area'])
            ->first();

        $listPeserta = $liquid
            ->peserta()
            ->with('feedback', 'pengukuranPertama', 'pengukuranKedua')
            ->where('atasan_id', $liquidPeserta->atasan_id)
            ->whereIn('bawahan_id', array_keys($liquid->peserta_snapshot[$liquidPeserta->atasan_id]['peserta']))
            ->get();

        foreach ($listPeserta as $peserta) {
            if ($peserta->feedback) {
                if (!empty($peserta->feedback->kelebihan)) {
                    $kelebihans = array_merge($kelebihans, $peserta->feedback->kelebihan);
                }
                if (!empty($peserta->feedback->kekurangan)) {
                    $kekurangans = array_merge($kekurangans, $peserta->feedback->kekurangan);
                }

                $detail['saran'][] = $peserta->feedback->saran;
                $detail['harapan'][] = $peserta->feedback->harapan;
            }
        }

        foreach ($kelebihans as $kelebihan) {
            $kelebihanLabels[] = KelebihanKekuranganDetail::withTrashed()
                ->find((int) $kelebihan)
                ->deskripsi_kelebihan;
        }

        foreach ($kekurangans as $kekurangan) {
            $kekuranganLabels[] = KelebihanKekuranganDetail::withTrashed()
                ->find((int) $kekurangan)
                ->deskripsi_kekurangan;
        }

        $detail['kelebihan'] = array_count_values($kelebihanLabels);
        $detail['kekurangan'] = array_count_values($kekuranganLabels);

        arsort($detail['kelebihan']);
        arsort($detail['kekurangan']);

        $detail['resolusi'] = app(LiquidService::class)->resolusi($liquidPeserta->atasan_id, $liquid);
        $detail['bawahan'] = $liquid->peserta_snapshot[$liquidPeserta->atasan_id]['peserta'];

        if (config('app.isUsingCache')) {
            return Cache::remember($name, 60, function () use ($detail) {
                return $detail;
            });
        }

        return $detail;
    }

    public function sendBawahanNotification($liquidPesertaId)
    {
        $peserta = LiquidPeserta::findOrFail((int) $liquidPesertaId);
        $userBawahan = $peserta->bawahan->user;
        $userAtasan = $peserta->atasan->user;
        $liquid = $peserta->liquid;

        $notification = new Notification();

        $notification->from = 'SYSTEM';
        $notification->user_id_from = auth()->user()->id;
        $notification->to = $userBawahan->username2;
        $notification->user_id_to = $userBawahan->getKey();
        $notification->subject = 'Jadwal '.request()->jenis_kegiatan;
        $notification->color = 'info';
        $notification->icon = 'fa fa-info';

        $notification->message = sprintf(
            '"Pelaksanaan "'.request()->jenis_kegiatan.'. Atasan: %s. NIP: %s. JABATAN: %s. KANTOR: %s - %s. Tanggal %s s/d %s.',
            $userAtasan->nama,
            $userAtasan->nip,
            $userAtasan->ad_title,
            $userAtasan->businessArea->business_area,
            $userAtasan->businessArea->description,
            $liquid->feedback_start_date->format('d-m-Y'),
            $liquid->feedback_end_date->format('d-m-Y')
        );

        // switch (request()->jenis_kegiatan) {
        //     case 'Feedback':
        //         $notification->url = route('feedback.create')."?liquid_peserta_id=".$liquidPesertaId."&liquid_id=".$peserta->liquid_id;
        //         break;
        //     case 'Pengukuran Pertama':
        //         $notification->url = route('penilaian.create')."?liquid_peserta_id=$liquidPesertaId";
        //         break;
        //     case 'Pengukuran Kedua':
        //         $notification->url = route('pengukuran-kedua.create')."?liquid_peserta_id=$liquidPesertaId";
        //         break;
        // }

        // Url menuju ke index masing2 jenis_kegiatan
        switch (request()->jenis_kegiatan) {
            case 'Feedback':
                $notification->url = route('feedback.index')."?liquid_id=$peserta->liquid_id";
                break;

            case 'Pengukuran Pertama':
                $notification->url = route('penilaian.index')."?liquid_id=$peserta->liquid_id";
                break;
            case 'Pengukuran Kedua':
                $notification->url = route('pengukuran-kedua.index');
                break;
        }

        $saved = $notification->save();

        if ($saved && collect($userBawahan)->has('email')) {
            Mail::queue(
                'emails.liquid_published',
                [
                    'kepada' => $userBawahan->name,
                    'pesan' => $notification->message,
                    'notif_id' => $notification->id,
                ],
                function ($message) use ($userBawahan, $notification) {
                    $message->to($userBawahan->email)
                        ->subject($notification->subject);
                }
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Notifikasi berhasil dikirimkan');
    }

    public static function convertSpiderToBar(Collection $collect)
    {
        $voter = $collect->sum('avg_pengukuran_pertama') + $collect->sum('avg_pengukuran_kedua');
        $labels = $collect->pluck('label')->toArray();

        $counter1 = [];
        $counter2 = [];

        $collect->each(function ($item) use (&$counter1, &$counter2) {
            $counter1[] = $item['avg_pengukuran_pertama'];
            $counter2[] = $item['avg_pengukuran_kedua'];
        });

        return [
            'voter' => $voter,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Pengukuran 1',
                        'data' => $counter1,
                        'backgroundColor' => '#FF6484',
                    ],
                    [
                        'label' => 'Pengukuran 2',
                        'data' => $counter2,
                        'backgroundColor' => '#36A2EB',
                    ],
                ],
            ],
        ];
    }
}
