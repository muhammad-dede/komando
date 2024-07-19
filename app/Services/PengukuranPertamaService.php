<?php

namespace App\Services;

use App\Models\Liquid\BusinessArea;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\PengukuranPertama;
use App\User;
use Illuminate\Http\Request;

class PengukuranPertamaService
{
    public function index(User $user, Liquid $liquid)
    {
        $pesertaSnapshot = app(LiquidService::class)->listPeserta($liquid);
        $pernrBawahan = $user->strukturJabatan->pernr;

        $atasanLangsung = collect($pesertaSnapshot)
            ->filter(function ($atasan) use ($pernrBawahan) {
                return array_key_exists($pernrBawahan, $atasan['peserta']);
            });

        $liquidPeserta = LiquidPeserta::query()
            ->where('liquid_id', $liquid->getKey())
            ->whereIn('atasan_id', $atasanLangsung->keys())
            ->where('bawahan_id', $pernrBawahan)
            ->get();

        return $liquidPeserta->transform(function ($item) use ($pesertaSnapshot) {
            $nip = array_get($pesertaSnapshot, "$item->atasan_id.nip");
            $data = [
                'id_lp' => $item->getKey(),
                'pengukuran_pertama' => $item->pengukuranPertama,
                'atasan' => [
                    'nama' => array_get($pesertaSnapshot, "$item->atasan_id.nama"),
                    'nip' => $nip,
                    'jabatan' => array_get($pesertaSnapshot, "$item->atasan_id.jabatan"),
                    'unit' => BusinessArea::where('business_area', array_get($pesertaSnapshot, "$item->atasan_id.business_area"))->value('description'),
                    'foto' => app_user_avatar($nip),
                ],
            ];

            return $data;
        })->toArray();
    }

    public function create($idLiquidPeserta)
    {
        $resolusi = [];
        $liquidPeserta = LiquidPeserta::find(
            $idLiquidPeserta
        );
        $dataAtasan = $liquidPeserta
            ->atasan
            ->user;

        $dataResolusi = $liquidPeserta
            ->liquid
            ->penyelarasan()
            ->where('atasan_id', $liquidPeserta->atasan_id)
            ->first();

        if ($dataAtasan !== null) {
            foreach ($dataResolusi->resolusi as $item) {
                if (is_string($item)) {
                    $resolusi[] = $item;
                } else {
                    $resolusi[] = KelebihanKekuranganDetail::withTrashed()
                        ->findOrFail($item)
                        ->deskripsi_kelebihan;
                }
            }
        }

        return [
            $dataAtasan,
            $resolusi,
        ];
    }

    public function store(Request $request, LiquidPeserta $liquidPeserta)
    {
        $resolusi = [];
        $alasan = [];

        $penyelarasan = $liquidPeserta
            ->liquid
            ->penyelarasan()
            ->where('atasan_id', $liquidPeserta->atasan_id)
            ->first();

        foreach ($penyelarasan->resolusi as $index => $res) {
            $resolusi[] = $res.':'.$request->{'resolusi_'.($index + 1)};
            if ((int) $request->{'resolusi_'.($index + 1)} === 10) {
                $alasan[] = $res.':'.$request->{'resolusi_alasan_'.($index + 1)};
            }
        }

        $pengukuranPertama = new PengukuranPertama(
            [
                'resolusi' => $resolusi,
                'alasan' => $alasan,
                'status' => 'TESTING',
            ]
        );

        $liquidPeserta->pengukuranPertama()
            ->save($pengukuranPertama);

        return $pengukuranPertama;
    }

    public function show($id)
    {
        $resolusi = [];
        $penilaian = PengukuranPertama::findOrFail($id);

        $dataAtasan = $penilaian
            ->liquidPeserta
            ->atasan
            ->user;

        $dataResolusi = $penilaian
            ->liquidPeserta
            ->liquid
            ->penyelarasan()
            ->where(
                'atasan_id',
                $penilaian->liquidPeserta->atasan_id
            )
            ->first();

        foreach ($dataResolusi->resolusi as $item) {
            if (is_string($item)) {
                $resolusi[] = $item;
            } else {
                $resolusi[] = KelebihanKekuranganDetail::withTrashed()
                    ->findOrFail($item)
                    ->deskripsi_kelebihan;
            }
        }

        return [
            $penilaian,
            $dataAtasan,
            $resolusi,
        ];
    }

    public function update(Request $request, $id)
    {
        $resolusi = [];
        $alasan = [];
        $pengukuranPertama = PengukuranPertama::findOrFail($id);
        $penyelarasan = $pengukuranPertama
            ->liquidPeserta
            ->liquid
            ->penyelarasan()
            ->where(
                'atasan_id',
                $pengukuranPertama->liquidPeserta->atasan_id
            )
            ->first();

        foreach ($penyelarasan->resolusi as $index => $res) {
            $resolusi[] = $res.':'.$request->{'resolusi_'.($index + 1)};
            if ((int) $request->{'resolusi_'.($index + 1)} === 10) {
                $alasan[] = $res.':'.$request->{'resolusi_alasan_'.($index + 1)};
            }
        }

        $pengukuranPertama->resolusi = $resolusi;
        $pengukuranPertama->alasan = $alasan;
        $pengukuranPertama->save();

        return $pengukuranPertama;
    }

    public function destroy($id)
    {
        PengukuranPertama::findOrFail($id)
            ->delete();
    }
}
