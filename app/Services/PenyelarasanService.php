<?php

namespace App\Services;

use App\Activity;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\Penyelarasan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenyelarasanService
{
    public function store(Request $request, User $user)
    {
        $resolusi = [];
        $aksiNyata = [];
        $keterangan = [];

        $liquid = Liquid::findOrFail((int) $request->liquid_id);
        $feedbackData = $this->feedbackData($liquid, $user);

        foreach ($feedbackData['kk_details'] as $index => $kkDetails) {
            $i = $index + 1;

            $resolusi[] = (int) $request->{'resolusi_' . $i};
            $aksiNyata[] = $request->{'aksi_nyata_' . $i};
            $keterangan[] = $request->{'keterangan_' . $i};
        }

        $penyelarasan = new Penyelarasan(
            [
                'resolusi' => $resolusi,
                'catatan_kekurangan' => empty($feedbackData['kekurangan_notes'])
                    ? ''
                    : $feedbackData['kekurangan_notes'],
                'date_start' => Carbon::parse($request->date_start),
                'date_end' => Carbon::parse($request->date_start),
                'tempat' => $request->tempat_kegiatan,
                'keterangan' => $request->deskripsi,
                'aksi_nyata' => $aksiNyata,
                'keterangan_aksi_nyata' => $keterangan,
                'atasan_id' => auth()->user()->strukturJabatan->pernr
            ]
        );

        $liquid->penyelarasan()
            ->save($penyelarasan);

        Activity::log('[LIQUID] Input Penyelarasan', 'success');
    }

    public function feedbackData($liquid, $user)
    {
        $choosen = [];
        $threeMax = [];
        $kkDetails = [];
        $resolusiIds = [];
        $voter = [];
        $catatanKekurangan = [];

        $liquidPeserta = $liquid->peserta()
            ->where('atasan_id', $user->strukturJabatan->pernr)
            ->whereHas('feedback', function ($q) {
                $q->whereNotNull('liquid_peserta_id');
            })
            ->get()
            ->map(function ($item) {
                return $item->feedback;
            })
            ->toArray();

        foreach ($liquidPeserta as $feedback) {
            if (! empty($feedback)) {
                $choosen =
                        array_merge(
                            $choosen,
                            $feedback['kekurangan']
                        );

                if (is_string($feedback['new_kekurangan'])) {
                    $feedback['new_kekurangan'] = [$feedback['new_kekurangan']];
                }

                if (! empty($feedback['new_kekurangan'])) {
                    $catatanKekurangan = array_merge(
                        $catatanKekurangan,
                        $feedback['new_kekurangan']
                    );
                }

                foreach ($feedback['kekurangan'] as $kekurangan) {
                    $detail = KelebihanKekuranganDetail::withTrashed()
                        ->find($kekurangan);
                    if (! empty($detail)) {
                        $voter[] = $detail->deskripsi_kekurangan;
                    }
                }
            }
        }

        $voter = array_count_values($voter);
        $choosen = array_count_values($choosen);

        for ($i = 0; $i < 3; $i++) {
            if (! empty($choosen)) {
                $max = max($choosen);
                $index = array_search($max, $choosen);
                $threeMax[] = $index;
                unset($choosen[$index]);
            }
        }

        for ($i = 0; $i < count($threeMax); $i++) {
            $detail = KelebihanKekuranganDetail::withTrashed()
                ->find($threeMax[$i]);
            if (! empty($detail)) {
                $kkDetails[] = $detail;
                $resolusiIds[] = $threeMax[$i];
            }
        }

        return [
            'kk_details' => $kkDetails,
            'voter' => $voter,
            'kekurangan_notes' => $catatanKekurangan,
            'resolusi_ids' => $resolusiIds,
        ];
    }

    public function edit($liquid, $penyelarasan, User $user)
    {
        $feedbackData = $this->feedbackData($liquid, $user);

        $feedbackData['aksi_nyata'] = $penyelarasan->aksi_nyata;
        $feedbackData['keterangan_aksi_nyata'] = $penyelarasan->keterangan_aksi_nyata;

        $edit = $this->getResolusiForEdit($penyelarasan->resolusi);
        $feedbackData['kk_details'] = $edit->details;
        $feedbackData['resolusi_ids'] = $edit->ids;

        return $feedbackData;
    }

    public function update(Request $request, $penyelarasan)
    {
        $resolusi = [];
        $aksiNyata = [];
        $keterangan = [];

        for ($i = 0; $i < count($penyelarasan->resolusi); $i++) {
            $num = $i + 1;
            $resolusi[] = (int) $request->{'resolusi_' . $num};
            $aksiNyata[] = $request->{'aksi_nyata_' . $num};
            $keterangan[] = $request->{'keterangan_' . $num};
        }

        $penyelarasan->update(
            [
                'date_start' => Carbon::parse($request->date_start),
                'date_end' => Carbon::parse($request->date_end),
                'tempat' => $request->tempat_kegiatan,
                'keterangan' => $request->deskripsi,
                'resolusi' => $resolusi,
                'aksi_nyata' => $aksiNyata,
                'keterangan_aksi_nyata' => $keterangan,
            ]
        );

        Activity::log('[LIQUID] Edit Penyelarasan', 'success');
    }

    public function delete($penyelarasan)
    {
        $penyelarasan->delete();

        Activity::log('[LIQUID] Edit Penyelarasan', 'success');
    }

    private function getResolusiForEdit($penyelarasanResolusi)
    {
        $resolusi = [];
        $ids = [];

        foreach ($penyelarasanResolusi as $key => $value) {
            if (! is_string($value)) {
                $resolusi[] = KelebihanKekuranganDetail::withTrashed()
                    ->findOrFail($value)
                    ->deskripsi_kelebihan;
                $ids[] = $value;
            } else {
                $resolusi[] = $value;
            }
        }

        return (object) [
            'details' => $resolusi,
            'ids' => $ids,
        ];
    }

    public function getResolusiAsArray($penyelarasanResolusi)
    {
        $resolusi = [];
        foreach ($penyelarasanResolusi as $key => $value) {
            if (! is_string($value)) {
                $resolusi[] = KelebihanKekuranganDetail::withTrashed()
                    ->findOrFail($value)
                    ->deskripsi_kelebihan;
            } else {
                $resolusi[] = $value;
            }
        }

        return $resolusi;
    }
}
