<?php

namespace App\Services;

use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\PengukuranKedua;
use App\Models\Liquid\Penyelarasan;
use App\User;
use Illuminate\Http\Request;

class PengukuranKeduaService
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
            $data = [
                'peserta' => $item,
                'id_lp' => $item->getKey(),
                'pengukuran_kedua' => $item->pengukuranKedua,
                'atasan' => $item->getSnapshotAtasan()
            ];

            return $data;
        })->toArray();
    }

    public function create($idLiquidPeserta)
    {
        $resolusi = [];
        $liquidPeserta = LiquidPeserta::findOrFail(
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

        if ($dataResolusi !== null) {
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

        $pengukuranKedua = new PengukuranKedua(
            [
                'resolusi' => $resolusi,
                'alasan' => $alasan,
                'status' => 'TESTING',
            ]
        );

        $liquidPeserta->pengukuranKedua()->save($pengukuranKedua);

        return $liquidPeserta;
    }

    public function show($id)
    {
        $penilaian = PengukuranKedua::findOrFail($id);

        $dataAtasan = $penilaian->liquidPeserta->getSnapshotAtasan();

        $resolusi = Penyelarasan::getResolusiAsArray($penilaian->liquidPeserta->liquid, $penilaian->liquidPeserta->atasan_id)->toArray();

        return [
            $penilaian,
            $dataAtasan,
            $resolusi,
        ];
    }

    public function update(Request $request, PengukuranKedua $pengukuranKedua)
    {
        $resolusi = [];
        $alasan = [];
        $penyelarasan = $pengukuranKedua
            ->liquidPeserta
            ->liquid
            ->penyelarasan()
            ->where(
                'atasan_id',
                $pengukuranKedua
                    ->liquidPeserta
                    ->atasan_id
            )
            ->first();

        foreach ($penyelarasan->resolusi as $index => $res) {
            $resolusi[] = $res.':'.$request->{'resolusi_'.($res)};
            if ((int) $request->{'resolusi_'.($res)} === 10) {
                $alasan[] = $res.':'.$request->{'resolusi_alasan_'.($res)};
            }
        }

        $pengukuranKedua->resolusi = $resolusi;
        $pengukuranKedua->alasan = $alasan;
        $pengukuranKedua->save();

        return $pengukuranKedua->liquidPeserta;
    }

    public function destroy($id)
    {
        PengukuranKedua::findOrFail($id)
            ->delete();
    }
}
