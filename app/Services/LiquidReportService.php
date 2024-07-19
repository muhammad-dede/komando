<?php

namespace App\Services;

use App\BusinessArea;
use App\CompanyCode;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\Penyelarasan;
use App\Utils\BasicUtil;
use Box\Spout\Writer\Style\StyleBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LiquidReportService
{
    public function atasanWithPeserta(Liquid $liquid, $jabatan = null)
    {
        $pesertas = $liquid
            ->peserta();
        if ($jabatan) {
            $pesertas
                ->where('snapshot_jabatan_atasan', $jabatan);
        }
        return $pesertas
            ->get()
            ->groupBy('atasan_id');
    }
   
    protected function filterUnitAtasanWithPeserta(Liquid $liquid, $unit=null, $jabatan=null)
    {
        $pesertas = $liquid
            ->peserta();
        if($jabatan) {
            $pesertas->where('snapshot_jabatan_atasan', $jabatan);
        }

        if($unit) {
            $pesertas->where('snapshot_unit_code', $unit);
        }
        return $pesertas
            ->get()
            ->groupBy('atasan_id');
    }

    public function getAtasanUnit(Liquid $liquid, $businessArea)
    {
        return $liquid->businessAreas()
            ->where('m_business_area.business_area', $businessArea)
            ->exists()
                ? $liquid->businessAreas()
                ->where('m_business_area.business_area', $businessArea)
                ->first()
                ->description : '';
    }

    public function displayLiquidData(Liquid $liquid)
    {
        $dataPerAtasan = [];
        $atasanWithPeserta = $this->atasanWithPeserta($liquid);

        foreach ($atasanWithPeserta as $i => $pesertas) {
            $atasanKelebihan = [];
            $atasanKelebihanLabels = [];
            $pengukuranPertama = [];
            $pengukuranKedua = [];
            $jmlFeedback = 0;

            $dataPerAtasan[$i]['jumlah_bawahan'] = count($pesertas);
            $dataPerAtasan[$i]['atasan_snapshot'] = @$liquid->peserta_snapshot[$i];

            $pesertas->load(['feedback', 'pengukuranPertama', 'pengukuranKedua']);

            foreach ($pesertas as $key => $peserta) {
                $dataPerAtasan[$i]['jumlah_act_logbook'] = $liquid->has('logBook') ? $liquid
                    ->logBook
                    ->filter(function ($logbook) use ($peserta) {
                        return $logbook->atasan->nip == $peserta->atasan->nip;
                    })->count() : 0;

                if (!isset($dataPerAtasan[$i]['atasan_snapshot']['divisi'])) {
                    $dataPerAtasan[$i]['atasan_snapshot']['divisi'] = $peserta->getDivisiPusat(true);
                }

                if ($peserta->feedback) {
                    $atasanKelebihan = array_merge($atasanKelebihan, $peserta->feedback->kelebihan);
                }

                if ($peserta->pengukuranPertama) {
                    foreach ($peserta->pengukuranPertama->penilaian() as $key => $nilai) {
                        $pengukuranPertama[$key][] = $nilai;
                    }
                }

                if ($peserta->pengukuranKedua) {
                    foreach ($peserta->pengukuranKedua->penilaian() as $key => $nilai) {
                        $pengukuranKedua[$key][] = $nilai;
                    }
                }

                if (!is_null($peserta->feedback)) {
                    $jmlFeedback += 1;
                }
            }

            $atasanKelebihan = array_count_values($atasanKelebihan);
            arsort($atasanKelebihan);

            foreach ($atasanKelebihan as $id => $value) {
                $atasanKelebihanLabels[] = KelebihanKekuranganDetail::withTrashed()
                        ->find($id)
                        ->deskripsi_kelebihan;
            }
            $dataPerAtasan[$i]['atasan_id'] = $peserta->atasan_id;
            $dataPerAtasan[$i]['liquid_id'] = $peserta->liquid_id;
            $dataPerAtasan[$i]['kelebihan'] = $atasanKelebihanLabels;

            $dataPerAtasan[$i]['jumlah_feedback'] = $jmlFeedback;

            $dataPerAtasan[$i]['resolusi'] = Penyelarasan::getResolusiAsArray($liquid, $i)->values();

            foreach ($pengukuranPertama as $key => $nilais) {
                $dataPerAtasan[$i]['pengukuran_pertama']['rata_rata'][] = collect($nilais)->avg();
            }
            foreach ($pengukuranKedua as $key => $nilais) {
                $dataPerAtasan[$i]['pengukuran_kedua']['rata_rata'][] = collect($nilais)->avg();
            }
            $dataPerAtasan[$i]['pengukuran_pertama']['tanggal'] = $liquid->pengukuran_pertama_start_date
                ->format('d F Y');
            $dataPerAtasan[$i]['pengukuran_kedua']['tanggal'] = $liquid->pengukuran_kedua_start_date
                    ->format('d F Y');

            $dataPerAtasan[$i]['unit_name'] = $this->getAtasanUnit($liquid, $liquid->peserta_snapshot[$i]['business_area']);
        }

        return $dataPerAtasan;
    }

    public function setReportTitle($writer, $label, $header)
    {
        $headingStyle = (new StyleBuilder)
            ->setFontBold()
            ->setFontSize(18)
            ->build();
        $subHeadingStyle = (new StyleBuilder)
            ->setFontSize(16)
            ->build();

        $writer->addRowsWithStyle(
            [
                [''],
                [''],
                ['', 'PT PLN (PERSERO)'],
                ['', $header]
            ],
            $headingStyle
        );

        $writer->addRowsWithStyle(
            [
                ['', $label],
                [''],
                [''],
            ],
            $subHeadingStyle
        );
    }

    public function setReportTitleLabel(Request $request)
    {
        $companyCode = $request->get('company_code', auth()->user()->company_code);
        $unitCode = $request->get('unit_code', auth()->user()->business_area);
        $label = '';

        switch (true) {
            case $companyCode === '' || $companyCode == 1000:
                if ($unitCode === '') {
                    $label = 'DIVISI: ALL UNIT';
                } elseif ($unitCode == 1001) {
                    $label = 'DIVISI: KANTOR PUSAT';
                } else {
                    $unit = BusinessArea::query()
                        ->where('business_area', $unitCode)
                        ->value('description');

                    $label = 'UNIT PELAKSANA: '.strtoupper($unit);
                }
                break;
            default:
                $company = CompanyCode::query()
                    ->where('company_code', $companyCode)
                    ->value('description');

                if ($unitCode === '') {
                    $label = 'UNIT: '.strtoupper($company);
                } else {
                    $unit = BusinessArea::query()
                        ->where('business_area', $unitCode)
                        ->value('description');

                    $label = 'UNIT PELAKSANA: '.strtoupper($unit);
                }
                break;
        }

        return $label;
    }

    public function rekapFeedbackLainnya(Liquid $liquid)
    {
        $feedbackAtasan = [];
        $atasanWithPeserta = $this->atasanWithPeserta($liquid);

        foreach ($atasanWithPeserta as $atasanPernr => $pesertas) {
            $atasanKelebihanLainnya = [];
            $atasanKekuranganLainnya = [];
            $saran = [];
            $harapan = [];

            $atasanSnapshot = $liquid->peserta_snapshot[$atasanPernr];
            $feedbackAtasan[$atasanPernr]['nama'] = $atasanSnapshot['nama'];
            $feedbackAtasan[$atasanPernr]['unit_name'] = sprintf(
                '%s - %s',
                $atasanSnapshot['business_area'],
                $this->getAtasanUnit($liquid, $atasanSnapshot['business_area'])
            );
            $feedbackAtasan[$atasanPernr]['nip'] = $atasanSnapshot['nip'];
            $feedbackAtasan[$atasanPernr]['jabatan'] = $atasanSnapshot['jabatan'];
            $pesertas->load(['feedback']);

            foreach ($pesertas as $i => $peserta) {
                if (! is_null($peserta->feedback)) {
                    if (is_array($peserta->feedback->new_kelebihan)) {
                        $atasanKelebihanLainnya[] = array_first($peserta->feedback->new_kelebihan);
                    } elseif (is_string($peserta->feedback->new_kelebihan)) {
                        $atasanKelebihanLainnya[] = $peserta->feedback->new_kelebihan;
                    }

                    if (is_array($peserta->feedback->new_kekurangan)) {
                        $atasanKekuranganLainnya[] = array_first($peserta->feedback->new_kekurangan);
                    } elseif (is_string($peserta->feedback->new_kelebihan)) {
                        $atasanKekuranganLainnya = $peserta->feedback->new_kekurangan;
                    }

                    $saran[] = $peserta->feedback->saran;
                    $harapan[] = $peserta->feedback->harapan;
                }
            }

            $feedbackAtasan[$atasanPernr]['kelebihan_lainnya'] = $atasanKelebihanLainnya;
            $feedbackAtasan[$atasanPernr]['kekurangan_lainnya'] = $atasanKekuranganLainnya;
            $feedbackAtasan[$atasanPernr]['saran'] = $saran;
            $feedbackAtasan[$atasanPernr]['harapan'] = $harapan;
        }

        return $feedbackAtasan;
    }

    public function rekapKelebihan($liquids, $jabatan, $limit = null)
    {
        $name = 'rekapKelebihan';

        if (config('app.isUsingCache') && Cache::has($name)) {
            return Cache::get($name);
        }

        $kelebihanAtasan = [];

        foreach ($liquids as $liquid) {
            $atasanWithPeserta = $this->atasanWithPeserta($liquid, $jabatan);

            foreach ($atasanWithPeserta as $pesertas) {
                $pesertas->load(['feedback']);

                foreach ($pesertas as $peserta) {
                    if (isset($peserta->feedback->kelebihan)) {
                        $kelebihanAtasan = array_merge($kelebihanAtasan, $peserta->feedback->kelebihan);
                    }
                }
            }
        }

        $countedVal = array_count_values($kelebihanAtasan);
        $voter = collect($countedVal)->sum();

        arsort($countedVal);

        if (isset($limit)) {
            $countedVal = array_slice($countedVal, 0, $limit, true);
        }

        $kelebihanAtasanVal = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', array_keys($countedVal));

        if ($kelebihanAtasanVal->exists()) {
            $kelebihanAtasanVal = $kelebihanAtasanVal
                ->orderByRaw('DECODE(id, '.implode(',', array_keys($countedVal)).')')
                ->pluck('deskripsi_kelebihan')
                ->toArray();
        } else {
            $kelebihanAtasanVal = collect();
        }

        $config = (new BasicUtil)->getConfig();
        $format = '%s %s';

        $kelebihan = collect($kelebihanAtasanVal)->map(function ($item, $key) use ($config, $format) {
            $item;

            return sprintf($format, $config->lebihShort, $key + 1);
        });

        $sumCountedValue = 0;

        foreach ($countedVal as $value) {
            $sumCountedValue += $value;
        }

        $kelebihan_data = [];
        $index = 0;

        foreach ($countedVal as $value) {
            $kelebihan_data[] = [
                'jml_data' => $value,
                'kelebihan' => $kelebihanAtasanVal[$index++],
                'jumlah_total' => $sumCountedValue,
            ];
        }

        $result = [
            'kelebihan' => $kelebihan_data,
            'counter' => $countedVal,
            'voter' => $voter,
            'kelebihan_data' => $kelebihanAtasanVal,
            'kelebihan_labels' => $kelebihan->all()
        ];

        if (config('app.isUsingCache')) {
            return Cache::remember($name, 60, function () use ($result) {
                return $result;
            });
        }

        return $result;
    }

    public function rekapKekurangan($liquids, $jabatan, $limit = null)
    {
        $kekuranganAtasan = [];

        foreach ($liquids as $liquid) {
            $atasanWithPeserta = $this->atasanWithPeserta($liquid, $jabatan);

            foreach ($atasanWithPeserta as $pesertas) {
                $pesertas->load(['feedback']);

                foreach ($pesertas as $peserta) {
                    if (isset($peserta->feedback->kekurangan)) {
                        $kekuranganAtasan = array_merge($kekuranganAtasan, $peserta->feedback->kekurangan);
                    }
                }
            }
        }

        $countedVal = array_count_values($kekuranganAtasan);
        $voter = collect($countedVal)->sum();

        arsort($countedVal);

        if (isset($limit)) {
            $countedVal = array_slice($countedVal, 0, $limit, true);
        }

        $kekuranganAtasanVal = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', array_keys($countedVal));
        if ($kekuranganAtasanVal->exists()) {
            $kekuranganAtasanVal = $kekuranganAtasanVal
                ->orderByRaw('DECODE(id, '.implode(',', array_keys($countedVal)).')')
                ->pluck('deskripsi_kekurangan')
                ->toArray();
        } else {
            $kekuranganAtasanVal = collect();
        }

        $config = (new BasicUtil)->getConfig();
        $format = '%s %s';

        $kekurangan = collect($kekuranganAtasanVal)->map(function ($item, $key) use ($config, $format) {
            $item;

            return sprintf($format, $config->kurangShort, $key + 1);
        });
        $sumCountedValue = 0;

        foreach ($countedVal as $value) {
            $sumCountedValue += $value;
        }

        $kekurangan_data = [];
        $index = 0;

        foreach ($countedVal as $value) {
            $kekurangan_data[] = [
                'jml_data' => $value,
                'kekurangan' => $kekuranganAtasanVal[$index++],
                'jumlah_total' => $sumCountedValue,
            ];
        }

        return [
            'kekurangan' => $kekurangan_data,
            'counter' => $countedVal,
            'voter' => $voter,
            'kekurangan_data' => $kekuranganAtasanVal,
            'kekurangan_labels' => $kekurangan->all()
        ];
    }

    public function rekapProgressLiquid_example($liquids, $unit_code=null, $jabatan, $divisi=null, $offset = null, $limit = null) {
        $name = 'rekapProsessLiquid';

        if (config('app.isUsingCache') && Cache::has($name)) {
            return Cache::get($name);
        }

        $listDivisiPusat = app(LiquidService::class)->listDivisiPusat()->keys();
        $dataProgresses = null;

        foreach ($liquids as $liquid) {
            $dataPerAtasan = [];

            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {

                        // $divisi === "0", artinya memilih divisi LAINNYA,
                        // Ini adalah peserta tambahan diluar divisi pusat, ciri2nya:
                        // 1. snapshot_jabatan_atasan/snapshot_jabatan_bawahan (GM, EVP, dll) null
                        // 2. selain itu, kita harus mengecek apakah peserta ini orgehnya masuk ke list orget pusat atau tidak
                        // Note: orgeh ada 3 level dan kita tidak tahu seorang user ada di level mana, jadinya kita cek satu persatu ke setiap level orgeh
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else if($divisi === '1') {
                            
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    })
                    ->get();
                $atasanWithPeserta = $atasanWithPeserta->groupBy('atasan_id');
            } else {
                $atasanWithPeserta = $this->filterUnitAtasanWithPeserta($liquid, $unit_code, $jabatan);
            }
            // echo "<pre>";
            // print_r($atasanWithPeserta);
            // echo "</pre>";
            foreach ($atasanWithPeserta as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                $totalFeedback = 0;
                $totalPengukuranPertama = 0;
                $totalPengukuranKedua = 0;
                $jabatan = $liquid->peserta_snapshot[$i]['kelompok_jabatan'];
                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                if (empty($pesertaFiltered)) {
                    continue;
                }

                $pesertas->load('feedback', 'pengukuranPertama', 'pengukuranKedua', 'atasan');
                $penyelarasan = Penyelarasan::where('liquid_id', $liquid->id)->where('atasan_id', $i)->exists();
                $dataPerAtasan[$i]['penyelarasan'] = $penyelarasan;
                $dataPerAtasan[$i]['kelompok_jabatan'] = $jabatan;
                $dataPerAtasan[$i]['nip'] = $liquid->peserta_snapshot[$i]['nip'];
                $dataPerAtasan[$i]['name'] = $liquid->peserta_snapshot[$i]['nama'];
                $dataPerAtasan[$i]['peserta_count'] = count($pesertaFiltered);

                $arrDiv = array_diff_key(
                    array_flip($pesertas->pluck('bawahan_id')->toArray()),
                    $pesertaFiltered
                );

                // count peserta or bawahan
                foreach ($pesertas as $peserta) {
                    if (!array_has($arrDiv, $peserta->bawahan_id)) {
                        $dataPerAtasan[$i]['feedback_count'] = $totalFeedback += (int) ($peserta->feedback !== null);
                        $dataPerAtasan[$i]['pengukuran_pertama_count'] = $peserta->pengukuranPertama
                            ? $totalPengukuranPertama += 1
                            : $totalPengukuranPertama += 0;
                        $dataPerAtasan[$i]['pengukuran_kedua_count'] = $peserta->pengukuranKedua
                            ? $totalPengukuranKedua += 1
                            : $totalPengukuranKedua += 0;

                        // $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['nip'] = $peserta->snapshot_nip_bawahan;
                    }
                }
            }

            $data = collect($dataPerAtasan)->groupBy(function ($item, $key) {
                return $item['kelompok_jabatan'];
            })->toArray();
            if (!empty($data)) {
                $dataProgresses = $data;
            }
        }

        $resultProgress = [];
        if(!empty($dataProgresses)) {
            $i = 0;
            $class = "App\Enum\LiquidJabatan";
            foreach ($dataProgresses as $jabatan => $dataProgress) {
                $resultProgress[$i]['jabatan'] = trans("enum.{$class}.$jabatan");
                $resultProgress[$i]['jml_atasan'] = count($dataProgress);

                // count jumlah bawahan
                $jml_bahawan = 0;
                $jml_penyelarasan = 0;
                $jml_feedback = 0;
                $jml_pengukuran_1 = 0;
                $jml_pengukuran_2 = 0;
                foreach ($dataProgress as $value) {
                    $jml_bahawan = $jml_bahawan + $value['peserta_count'];
                    $jml_feedback = $jml_feedback + $value['feedback_count'];
                    $jml_pengukuran_1 = $jml_pengukuran_1 + $value['pengukuran_pertama_count'];
                    $jml_pengukuran_2 = $jml_pengukuran_2 + $value['pengukuran_kedua_count'];
                    if($value['penyelarasan']) {
                        $jml_penyelarasan++;
                    }
                }
                $resultProgress[$i]['jml_bawahan'] = $jml_bahawan;

                $resultProgress[$i]['has_feedback'] = $jml_feedback;
                $resultProgress[$i]['has_penyelarasan'] = $jml_penyelarasan;
                $resultProgress[$i]['has_pengukuran_1'] = $jml_pengukuran_1;
                $resultProgress[$i]['has_pengukuran_2'] = $jml_pengukuran_2;

                $persent_feedback = ($jml_feedback/$jml_bahawan)*100;
                $persent_penyelarasan = ($jml_penyelarasan/$resultProgress[$i]['jml_atasan'])*100;
                $persent_pengukuran_1 = ($jml_pengukuran_1/$jml_bahawan)*100;
                $persent_pengukuran_2 = ($jml_pengukuran_2/$jml_bahawan)*100;
                $resultProgress[$i]['persent_feedback'] = round($persent_feedback,1);
                $resultProgress[$i]['persent_penyelarasan'] = round($persent_penyelarasan,1);
                $resultProgress[$i]['persent_pengukuran_1'] = round($persent_pengukuran_1,1);
                $resultProgress[$i]['persent_pengukuran_2'] = round($persent_pengukuran_2,1);
                $i++;
            }
        }
        // dd($resultProgress);
        // die();

        if (config('app.isUsingCache')) {
            return Cache::remember($name, 60, function () use ($resultProgress) {
                return $resultProgress;
            });
        }

        return $resultProgress;
    }

    public function rekapProgressLiquid($liquids, $unit_code=null, $jabatan, $divisi=null, $offset = null, $limit = null) {
        $name = 'rekapProsessLiquid';

        if (config('app.isUsingCache') && Cache::has($name)) {
            return Cache::get($name);
        }

        $listDivisiPusat = app(LiquidService::class)->listDivisiPusat()->keys();

        $dataProgresses = [];
        $j = 0;

        foreach ($liquids as $liquid) {
            $dataPerAtasan = [];

            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {

                        // $divisi === "0", artinya memilih divisi LAINNYA,
                        // Ini adalah peserta tambahan diluar divisi pusat, ciri2nya:
                        // 1. snapshot_jabatan_atasan/snapshot_jabatan_bawahan (GM, EVP, dll) null
                        // 2. selain itu, kita harus mengecek apakah peserta ini orgehnya masuk ke list orget pusat atau tidak
                        // Note: orgeh ada 3 level dan kita tidak tahu seorang user ada di level mana, jadinya kita cek satu persatu ke setiap level orgeh
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else if($divisi === '1') {
                            
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    });
                if($jabatan) {
                    $atasanWithPeserta->where('snapshot_jabatan_atasan', $jabatan);
                }
                $atasanWithPeserta = $atasanWithPeserta->get()->groupBy('atasan_id');
            } else {
                $atasanWithPeserta = $this->filterUnitAtasanWithPeserta($liquid, $unit_code, $jabatan);
            }
            
            foreach ($atasanWithPeserta as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                $totalFeedback = 0;
                $totalPengukuranPertama = 0;
                $totalPengukuranKedua = 0;
                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                if (empty($pesertaFiltered)) {
                    continue;
                }

                $pesertas->load('feedback', 'pengukuranPertama', 'pengukuranKedua', 'atasan');
                $penyelarasan = Penyelarasan::where('liquid_id', $liquid->id)->where('atasan_id', $i)->exists();

                // get data name and id divisi
                if($liquid->isPusat()) {
                    $divisi_1 = object_get($pesertas->first(), 'snapshot_orgeh_1');
                    $divisi_2 = object_get($pesertas->first(), 'snapshot_orgeh_2');
                    $divisi_3 = object_get($pesertas->first(), 'snapshot_orgeh_3');
                    $unit = $this->getByIDDivisiPusat($divisi_1, $divisi_2, $divisi_3);
                    $dataPerAtasan[$i]['unit_code'] = !empty($unit) ? $unit[0]->objid : 0;
                    $dataPerAtasan[$i]['unit_name'] = !empty($unit) ? $unit[0]->stext : "LAINNYA";
                }else{
                    $dataPerAtasan[$i]['unit_code'] = object_get($pesertas->first(), 'snapshot_unit_code');
                    $dataPerAtasan[$i]['unit_name'] =  object_get($pesertas->first(), 'snapshot_unit_name');
                }

                $dataPerAtasan[$i]['penyelarasan'] = $penyelarasan;
                $dataPerAtasan[$i]['kelompok_jabatan'] = $liquid->peserta_snapshot[$i]['kelompok_jabatan'];
                $dataPerAtasan[$i]['nip'] = $liquid->peserta_snapshot[$i]['nip'];
                $dataPerAtasan[$i]['name'] = $liquid->peserta_snapshot[$i]['nama'];
                $dataPerAtasan[$i]['peserta_count'] = count($pesertaFiltered);

                $arrDiv = array_diff_key(
                    array_flip($pesertas->pluck('bawahan_id')->toArray()),
                    $pesertaFiltered
                );

                // count peserta or bawahan
                foreach ($pesertas as $peserta) {
                    if (!array_has($arrDiv, $peserta->bawahan_id)) {
                        $dataPerAtasan[$i]['feedback_count'] = $totalFeedback += (int) ($peserta->feedback !== null);
                        $dataPerAtasan[$i]['pengukuran_pertama_count'] = $peserta->pengukuranPertama
                            ? $totalPengukuranPertama += 1
                            : $totalPengukuranPertama += 0;
                        $dataPerAtasan[$i]['pengukuran_kedua_count'] = $peserta->pengukuranKedua
                            ? $totalPengukuranKedua += 1
                            : $totalPengukuranKedua += 0;

                        // $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['nip'] = $peserta->snapshot_nip_bawahan;
                    }
                }
            }
           
            $data = collect($dataPerAtasan)->groupBy(function ($item, $key) {
                return $item['kelompok_jabatan'];
            })->toArray();
            if (!empty($data)) {
                $dataProgresses[$j] = $data;
                $j++;
            }
        }
        // echo "<pre>";
        // print_r($dataProgresses);
        // echo "</pre>";
        // die();

        $resultProgress = [];
        if(!empty($dataProgresses)) {
            $i = 0;
            $class = "App\Enum\LiquidJabatan";
            foreach ($dataProgresses as $jabatan => $dataProgress) {
                foreach ($dataProgress as $kelompok_jab => $values) {
                    $array_temp = array();
                    $count_temp = array();
                    $key_result = $kelompok_jab."_".$i;
                    foreach($values as $value) {
                        $key_val = $key_result."_".$value['unit_name'];
                        if (!in_array($value['unit_name'], $array_temp)) {
                            $array_temp[] = $value['unit_name'];
                            

                            $setUnitName = $value['unit_name'];
                            $setJabatan = $value['kelompok_jabatan'];

                            $resultProgress[$key_val]['unit_name'] = $setUnitName;
                            $resultProgress[$key_val]['jabatan'] = trans("enum.{$class}.$setJabatan");

                            $count_temp[$key_val]['jml_atasan'] = 1;
                            $count_temp[$key_val]['jml_bawahan'] = $value['peserta_count'];
                            $count_temp[$key_val]['jml_feedback'] = $value['feedback_count'];
                            $count_temp[$key_val]['jml_penyelarasan'] = 0;
                            if($value['penyelarasan']) {
                                $count_temp[$key_val]['jml_penyelarasan'] = 1;
                            }
                            $count_temp[$key_val]['jml_pengukuran_1'] = $value['pengukuran_pertama_count'];
                            $count_temp[$key_val]['jml_pengukuran_2'] = $value['pengukuran_kedua_count'];
                        } else {
                            $count_temp[$key_val]['jml_atasan'] = $count_temp[$key_val]['jml_atasan']+1;
                            $count_temp[$key_val]['jml_bawahan'] = $count_temp[$key_val]['jml_bawahan'] + $value['peserta_count'];
                            $count_temp[$key_val]['jml_feedback'] = $count_temp[$key_val]['jml_feedback'] + $value['feedback_count'];
                            if($value['penyelarasan']) {
                                $count_temp[$key_val]['jml_penyelarasan'] = $count_temp[$key_val]['jml_penyelarasan']+1;
                            }
                            $count_temp[$key_val]['jml_pengukuran_1'] = $count_temp[$key_val]['jml_pengukuran_1'] + $value['pengukuran_pertama_count'];
                            $count_temp[$key_val]['jml_pengukuran_2'] = $count_temp[$key_val]['jml_pengukuran_2'] + $value['pengukuran_kedua_count'];
                        }

                        $resultProgress[$key_val]['jml_bawahan'] = $count_temp[$key_val]['jml_bawahan'];
                        $resultProgress[$key_val]['jml_atasan'] = $count_temp[$key_val]['jml_atasan'];
                        $resultProgress[$key_val]['has_feedback'] = $count_temp[$key_val]['jml_feedback'];
                        $resultProgress[$key_val]['has_penyelarasan'] = $count_temp[$key_val]['jml_penyelarasan'];
                        $resultProgress[$key_val]['has_pengukuran_1'] = $count_temp[$key_val]['jml_pengukuran_1'];
                        $resultProgress[$key_val]['has_pengukuran_2'] = $count_temp[$key_val]['jml_pengukuran_2'];

                        $persent_feedback = ($count_temp[$key_val]['jml_feedback']/$count_temp[$key_val]['jml_bawahan'])*100;
                        $persent_penyelarasan = ($count_temp[$key_val]['jml_penyelarasan']/$count_temp[$key_val]['jml_atasan'])*100;
                        $persent_pengukuran_1 = ($count_temp[$key_val]['jml_pengukuran_1']/$count_temp[$key_val]['jml_bawahan'])*100;
                        $persent_pengukuran_2 = ($count_temp[$key_val]['jml_pengukuran_2']/$count_temp[$key_val]['jml_bawahan'])*100;
                        $resultProgress[$key_val]['persent_feedback'] = round($persent_feedback,1);
                        $resultProgress[$key_val]['persent_penyelarasan'] = round($persent_penyelarasan,1);
                        $resultProgress[$key_val]['persent_pengukuran_1'] = round($persent_pengukuran_1,1);
                        $resultProgress[$key_val]['persent_pengukuran_2'] = round($persent_pengukuran_2,1);
                        
                    }
                    $i++;
                }
            }
        }
        
        if (config('app.isUsingCache')) {
            return Cache::remember($name, 60, function () use ($resultProgress) {
                return $resultProgress;
            });
        }

        return $resultProgress;
    }

    public function getByIDDivisiPusat($divisi_1, $divisi_2, $divisi_3)
    {
        $data = DB::table('v_divisi_pusat');
        if(!empty($divisi_1)) {
            $data->where('objid',"=", $divisi_1);
        }
        if(!empty($divisi_2)) {
            $data->orWhere('objid', $divisi_2);
        }
        if(!empty($divisi_3)) {
            $data->orWhere('objid', $divisi_3);
        }
        $getData = $data->get();

        return $getData;
    }

    public function setReportTitleLabelProgressLiquid(Request $request, $divisi=null)
    {
        $companyCode = $request->get('company_code', auth()->user()->company_code);
        $unitCode = $request->get('unit_code', auth()->user()->business_area);
        $label = '';

        switch (true) {
            case $companyCode === '' || $companyCode == 1000:
                if ($unitCode === '') {
                    $label = 'DIVISI: ALL UNIT';
                    if($divisi == "0") {
                        $label = 'DIVISI: KANTOR PUSAT (LAINNYA)';
                    } elseif ($divisi == "1") {
                        $label = 'DIVISI: KANTOR PUSAT (SEMUA DIVISI)';
                    } else {
                        $getDivisi = $this->getByIDDivisiPusat($divisi, null, null);
                        if(!empty($getDivisi)) {
                            $label = 'DIVISI: KANTOR PUSAT ('.$getDivisi[0]->stext.')';
                        }
                    }
                    
                } elseif ($unitCode == 1001) {
                    $label = 'DIVISI: KANTOR PUSAT';
                } else {
                    $unit = BusinessArea::query()
                        ->where('business_area', $unitCode)
                        ->value('description');

                    $label = 'UNIT PELAKSANA: '.strtoupper($unit);
                }

                break;
            default:
                $company = CompanyCode::query()
                    ->where('company_code', $companyCode)
                    ->value('description');

                if ($unitCode === '') {
                    $label = 'UNIT: '.strtoupper($company);
                } else {
                    $unit = BusinessArea::query()
                        ->where('business_area', $unitCode)
                        ->value('description');

                    $label = 'UNIT PELAKSANA: '.strtoupper($unit);
                }
                break;
        }

        return $label;
    }
}
