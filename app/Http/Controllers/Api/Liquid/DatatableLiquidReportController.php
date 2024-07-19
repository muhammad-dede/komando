<?php

namespace App\Http\Controllers\Api\Liquid;

use App\Http\Controllers\Controller;
use App\Services\Datatable;
use App\Services\LiquidService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DatatableLiquidReportController extends Controller
{
    protected $limit;
    protected $start;
    protected $order;
    protected $dir;
    protected $searchVal;

    public function __construct()
    {
        $this->start = request()->input('start');
        $this->limit = request()->input('length');
        $this->order = request()->input('order.0.column');
        $this->dir = request()->input('order.0.dir');
        $this->searchVal = request()->input('search.value');
    }

    public function fetchRekapFeedbackLainnya(Request $request)
    {
        $columns = [
            'no',
            'unit',
            'atasan',
            'nip',
            'jabatan',
            'kelebihan_lainnya',
            'kekurangan_lainnya',
            'saran',
            'harapan'
        ];
        empty(request()->get('periode')) ? $periode = \Carbon\Carbon::now()->year  :  $periode = request()->get('periode');
        $liquids = \App\Models\Liquid\Liquid::ActiveForUnitWhitoutCurrentYear(request()->get('unit_code', auth()->user()->getKodeUnit()))
            ->whereYear('feedback_start_date', "=", (int)$periode)->get();
        $liquidWithData = $liquids->map(function ($liquid) {
            return app(\App\Services\LiquidReportService::class)->rekapFeedbackLainnya($liquid);
        });

        Cache::forget('liquid_rekap_lainnya_report');
        Cache::forever('liquid_rekap_lainnya_report', $liquidWithData);

        $data = collect();
        $index = 0;
        $totalData = 0;
        foreach ($liquidWithData as $pesertas) {
            $totalData += count($pesertas);
            foreach ($pesertas as $peserta) {
                $nestedData['no'] = $index += 1;
                $nestedData['unit'] = $peserta['unit_name'];
                $nestedData['atasan'] = $peserta['nama'];
                $nestedData['nip'] = $peserta['nip'];
                $nestedData['jabatan'] = $peserta['jabatan'];
                if (! empty($peserta['kelebihan_lainnya'])) {
                    $nestedData['kelebihan_lainnya'] = '<ul>';
                    if (is_string($peserta['kelebihan_lainnya'])) {
                        $nestedData['kelebihan_lainnya'] .= '<li>'.$peserta['kelebihan_lainnya'].'</li>';
                    } elseif (is_array($peserta['kelebihan_lainnya'])) {
                        foreach ($peserta['kelebihan_lainnya'] as $value) {
                            $nestedData['kelebihan_lainnya'] .= '<li>'.$value.'</li>';
                        }
                    }
                    $nestedData['kelebihan_lainnya'] .= '</ul>';
                } else {
                    $nestedData['kelebihan_lainnya'] = '-';
                }
                if (! empty($peserta['kekurangan_lainnya'])) {
                    $nestedData['kekurangan_lainnya'] = '<ul>';
                    if (is_string($peserta['kekurangan_lainnya'])) {
                        $nestedData['kekurangan_lainnya'] .= '<li>'.$peserta['kekurangan_lainnya'].'</li>';
                    } elseif (is_array($peserta['kekurangan_lainnya'])) {
                        foreach ($peserta['kekurangan_lainnya'] as $value) {
                            $nestedData['kekurangan_lainnya'] .= '<li>'.$value.'</li>';
                        }
                    }
                    $nestedData['kekurangan_lainnya'] .= '</ul>';
                } else {
                    $nestedData['kekurangan_lainnya'] = '-';
                }
                if (! empty($peserta['saran'])) {
                    $nestedData['saran'] = '<ul>';
                    foreach ($peserta['saran'] as $value) {
                        $nestedData['saran'] .= '<li>'.$value.'</li>';
                    }
                    $nestedData['saran'] .= '</ul>';
                } else {
                    $nestedData['saran'] = '-';
                }
                if (! empty($peserta['harapan'])) {
                    $nestedData['harapan'] = '<ul>';
                    foreach ($peserta['harapan'] as $value) {
                        $nestedData['harapan'] .= '<li>'.$value.'</li>';
                    }
                    $nestedData['harapan'] .= '</ul>';
                } else {
                    $nestedData['harapan'] = '-';
                }

                $data->push($nestedData);
            }
        }

        if (! is_null($this->order)) {
            $sortBy = $columns[$this->order];

            if ($this->dir === 'asc') {
                $data = $data->sortBy($sortBy);
            } elseif ($this->dir === 'desc') {
                $data = $data->sortByDesc($sortBy);
            }
        }

        if (! empty($this->searchVal)) {
            $data = $data->filter(function ($item, $key) {
                $itemKeys = array_keys($item);
                foreach ($itemKeys as $index) {
                    $bool = strpos($item[$index], $this->searchVal) !== false
                        ? true : false;
                    if ($bool) {
                        return $bool;
                    }
                }
            });
        }

        $recordsFiltered = $data->count();
        $data = $data->slice(intval($this->start), intval($this->limit));

        $jsonData = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->values()->toArray()
        ];

        return response()->json($jsonData);
    }

    public function fetchRekapLiquid(Request $request)
    {
        $data = collect();
        $isPusat = is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit()));

        $request->has('periode') ? $periode = $request->periode : $periode = \Carbon\Carbon::now()->year;
        $liquids = \App\Models\Liquid\Liquid::ActiveForUnitWhitoutCurrentYear($request->get('unit_code', auth()->user()->getKodeUnit()))
            ->whereYear('feedback_start_date', "=", (int)$periode)->get();
        $liquidWithData = $liquids->map(function ($liquid) {
            return app(LiquidReportService::class)
                ->displayLiquidData($liquid);
        })
            ->toArray();

        Cache::forget('liquid_history_report');
        Cache::forever('liquid_history_report', $liquidWithData);

        foreach ($liquidWithData as $liquidData) {
            foreach ($liquidData as $dataAtasan) {
                $data->push($dataAtasan);
            }
        }

        $tableRowView = 'datatable-row-views.liquid.rekap_liquid_row';

        if ($isPusat) {
            $tableRowView = 'datatable-row-views.liquid.rekap_liquid_pusat_row';
        }

        $datatable = Datatable::fromArray($data)
            ->rowView($tableRowView)
            ->columns(
                [
                    ['data' => 'nama'],
                    ['data' => 'nip'],
                    ['data' => 'jumlah_bawahan'],
                    ['data' => 'jenjang_jabatan'],
                    ['data' => 'sebutan_jabatan'],
                    ['data' => 'unit'],
                    ['data' => 'jumlah_feedback'],
                    ['data' => 'hasil_liquid'],
                    ['data' => 'tanggal_pelaksanaan_pengukuran_pertama'],
                    ['data' => 'tanggal_pelaksanaan_pengukuran_kedua'],
                    ['data' => 'aksi'],
                ]
            );

        if ($isPusat) {
            $datatable->appendColumn(['data' => 'divisi']);
        }

        $datatable->searchFromArray(function ($arrayData, $keyword) {
            $keyword = strtolower($keyword);

            return $arrayData->filter(function ($item) use ($keyword) {
                $itemKeys = array_keys($item);
                unset($itemKeys['divisi']);
                unset($itemKeys['resolusi']);
                unset($itemKeys['liquid_id']);

                foreach ($itemKeys as $index) {
                    if (is_string($item[$index])) {
                        $bool = strpos(strtolower($item[$index]), $keyword) !== false
                            ? true : false;

                        if ($bool) {
                            return $bool;
                        }
                    } elseif (is_array($item[$index])) {
                        $keyKeys = array_keys($item[$index]);
                        unset($keyKeys['peserta']);

                        foreach ($keyKeys as $keyKey) {
                            if (is_string($item[$index][$keyKey])) {
                                $bool = strpos(strtolower($item[$index][$keyKey]), $keyword) !== false
                                    ? true : false;

                                if ($bool) {
                                    return $bool;
                                }
                            }
                        }
                    }
                }
            });
        });

        $datatable->customRowStyle(function ($arrayData) {
            $hasilLiquid = '<table class="table">
                <thead>
                    <th class="vertical-middle" >KELEBIHAN (3 TERBANYAK YANG DINILAI)</th>
                    <th class="vertical-middle" >RESOLUSI</th>
                    <th class="vertical-middle">RATA-RATA HASIL PENGUKURAN PERTAMA</th>
                    <th class="ertical-middle">RATA-RATA HASIL PENGUKURAN KEDUA</th>
                </thead>';

            return $arrayData->map(function ($data) use ($hasilLiquid) {
                if (! empty($data['kelebihan'])) {
                    for ($i = 0; $i < count($data['kelebihan']); $i++) {
                        $hasilLiquid .= '<tr>';
                        $hasilLiquid .= '<td>'.(array_key_exists($i, $data['kelebihan']) ? $data['kelebihan'][$i] : '').'</td>';
                        $hasilLiquid .= '<td>'.(count($data['resolusi']) !== 0 ? $data['resolusi'][$i] : '').'</td>';
                        $hasilLiquid .= '<td>'.(array_key_exists('rata_rata', $data['pengukuran_pertama']) ? app_format_skor_penilaian($data['pengukuran_pertama']['rata_rata'][$i]) : '').'</td>';
                        $hasilLiquid .= '<td>'.(array_key_exists('rata_rata', $data['pengukuran_kedua']) ? app_format_skor_penilaian($data['pengukuran_kedua']['rata_rata'][$i]) : '').'</td>';
                        $hasilLiquid .= '</tr>';
                    }
                    $hasilLiquid .= '</table>';
                }
                return collect($data)
                    ->merge(['hasil_liquid' => ! empty($data['kelebihan']) ? $hasilLiquid : ''])
                    ->toArray();
            });
        });

        return $datatable
            ->sortFromArray()
            ->paginateArray()
            ->fromArrayToJson();
    }

    public function fetchHistoryInformation()
    {
        $user = auth()->user();

        $canViewDetail = $user->can('liquid_info_detil_pelaksannan');
        $unitCode = request('unit_code', $user->business_area);
        $divisi = request('divisi', $user->getKodeDivisiPusat());
        $jabatan = request('kelompok_jabatan');
        $startedDate = request('startedDate');
        $endDate = request('endDate');
        $year = request('year');

        $params = new \stdClass();
        $params->unitCode = $unitCode;
        $params->divisi = $divisi;
        $params->year = $year;
        $params->date = new \stdClass(); 
        $params->date->start = $startedDate;
        $params->date->end = $endDate; 

        $datas = Cache::get(
            'liquid_information_history_'.$user->id,
            (new LiquidService)->getHistoryInformation_forpeserta($unitCode, $divisi, $params)
        );

        $datas = collect(array_first($datas));

        $rowView = 'datatable-row-views.liquid.liquid_history_information_row';
        if ($canViewDetail) {
            $rowView = 'datatable-row-views.liquid.liquid_history_information_row_with_detail_col';
        }

        $datatable = Datatable::fromArray(collect($datas->get($jabatan)))
            ->rowView($rowView)
            ->columns(
                [
                    ['data' => 'unit'],
                    ['data' => 'foto'],
                    ['data' => 'nama_atasan'],
                    ['data' => 'nip'],
                    ['data' => 'jabatan'],
                    ['data' => 'jumlah_bawahan'],
                    ['data' => 'feedback_bawahan'],
                    ['data' => 'penyelarasan'],
                    ['data' => 'pengukuran_pertama'],
                    ['data' => 'act_log'],
                    ['data' => 'pengukuran_kedua'],
                    ['data' => 'activate_pengukuran_kedua'],
                    ['data' => 'jadwal_current'],
                    ['data' => 'valid_dibawah_3']
                ]
            );

        if ($canViewDetail) {
            $datatable->appendColumn(['data' => 'detail_view']);
        }

        $datatable->searchFromArray(function ($arrayData, $keyword) use ($jabatan) {
            $keyword = strtolower($keyword);

            return collect($arrayData)
                ->filter(function ($atasan) use ($keyword) {
                    $keys = array_keys($atasan);
                    unset($keys['id']);
                    foreach ($keys as $key) {
                        if (is_string($atasan[$key])) {
                            $bol = strpos(strtolower($atasan[$key]), $keyword) !== false
                                ? true : false;
                            if ($bol) {
                                return $bol;
                            }
                        } elseif (is_array($atasan[$key])) {
                            $keyKeys = array_keys($atasan[$key]);
                            foreach ($keyKeys as $keyKey) {
                                if (is_string($atasan[$key][$keyKey])) {
                                    $bol = strpos(strtolower($atasan[$key][$keyKey]), $keyword) !== false
                                        ? true : false;
                                    if ($bol) {
                                        return $bol;
                                    }
                                } elseif ($atasan[$key][$keyKey]) {
                                    $keyKeyKeys = array_keys($atasan[$key][$keyKey]);
                                    foreach ($keyKeyKeys as $keyKeyKey) {
                                        if (is_string($atasan[$key][$keyKey][$keyKeyKey])) {
                                            $bol = strpos(
                                                strtolower($atasan[$key][$keyKey][$keyKeyKey]),
                                                $keyword
                                            ) !== false
                                                ? true : false;
                                            if ($bol) {
                                                return $bol;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
        });

        return $datatable
            ->sortFromArray()
            ->paginateArray()
            ->fromArrayToJson();
    }

    public function fetchHistoryInformationLessThan3()
    {
        $user = auth()->user();

        $unitCode = request('unit_code', $user->business_area);
        $divisi = request('divisi', $user->getKodeDivisiPusat());
        $jabatan = request('kelompok_jabatan');
        $startedDate = request('startedDate');
        $endDate = request('endDate');
        $year = request('year');

        $params = new \stdClass();
        $params->unitCode = $unitCode;
        $params->divisi = $divisi;
        $params->year = $year;
        $params->date = new \stdClass(); 
        $params->date->start = $startedDate;
        $params->date->end = $endDate; 

        $datas = Cache::get(
            'liquid_information_history_less_than_3_'.$user->id,
            (new LiquidService)->getHistoryInformationLessThan_3($unitCode, $divisi, $params)
        );

        $datas = collect(array_first($datas));
        $result = $this->mappingPesertaInformasiLessThan3($datas);

        $rowView = 'datatable-row-views.liquid.liquid_history_information_less_than_3';

        $datatable = Datatable::fromArray(collect($result))
            ->rowView($rowView)
            ->columns(
                [
                    ['data' => 'unit'],
                    ['data' => 'foto'],
                    ['data' => 'nama_atasan'],
                    ['data' => 'nip'],
                    ['data' => 'jabatan'],
                    ['data' => 'jumlah_bawahan'],
                ]
            );

        // if ($canViewDetail) {
        //     $datatable->appendColumn(['data' => 'detail_view']);
        // }

        $datatable->searchFromArray(function ($arrayData, $keyword) use ($jabatan) {
            $keyword = strtolower($keyword);

            return collect($arrayData)
                ->filter(function ($atasan) use ($keyword) {
                    $keys = array_keys($atasan);
                    unset($keys['id']);
                    foreach ($keys as $key) {
                        if (is_string($atasan[$key])) {
                            $bol = strpos(strtolower($atasan[$key]), $keyword) !== false
                                ? true : false;
                            if ($bol) {
                                return $bol;
                            }
                        } elseif (is_array($atasan[$key])) {
                            $keyKeys = array_keys($atasan[$key]);
                            foreach ($keyKeys as $keyKey) {
                                if (is_string($atasan[$key][$keyKey])) {
                                    $bol = strpos(strtolower($atasan[$key][$keyKey]), $keyword) !== false
                                        ? true : false;
                                    if ($bol) {
                                        return $bol;
                                    }
                                } elseif ($atasan[$key][$keyKey]) {
                                    $keyKeyKeys = array_keys($atasan[$key][$keyKey]);
                                    foreach ($keyKeyKeys as $keyKeyKey) {
                                        if (is_string($atasan[$key][$keyKey][$keyKeyKey])) {
                                            $bol = strpos(
                                                strtolower($atasan[$key][$keyKey][$keyKeyKey]),
                                                $keyword
                                            ) !== false
                                                ? true : false;
                                            if ($bol) {
                                                return $bol;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
        });

        return $datatable
            ->sortFromArray()
            ->paginateArray()
            ->fromArrayToJson();
    }

    protected function mappingPesertaInformasiLessThan3($data) {
        $result = array();
        $i = 0;
        foreach ($data as $jabatan) {
            foreach ($jabatan as $val) {
                $result[$i] = $val;
                $i++;
            }
        }
        return $result;
    }

    public function fetchRekapPartisipan()
    {
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', '');
        $periode = \request('periode', Carbon::now()->year);

        $query = DB::table('v_liquid_rekap_partisipan')
            ->where(function ($q) use ($companyCode, $unitCode, $periode) {
                if ($companyCode === '' && $unitCode === '') {
                    $q->whereNotNull('liquid_id');
                }
                if ($companyCode) {
                    $q->where('company_code', $companyCode);
                }
                if ($unitCode) {
                    $q->where('business_area', $unitCode);
                }
            })->whereYear('periode', "=", intval($periode));

        $datatable = Datatable::make($query)
            ->rowView('datatable-row-views.liquid.liquid_rekap_partisipan_row')
            ->columns([
                ['data' => 'rn', 'searchable' => false],
                ['data' => 'company', 'searchable' => true],
                ['data' => 'business', 'searchable' => true],
                ['data' => 'levell', 'searchable' => true],
                ['data' => 'jml_atasan', 'searchable' => true],
                ['data' => 'jml_bawahan', 'searchable' => true],
                ['data' => 'jml_feedback', 'searchable' => true],
                ['data' => 'persen_feedback', 'searchable' => true],
                ['data' => 'jml_penyelarasan', 'searchable' => true],
                ['data' => 'persen_penyelarasan', 'searchable' => true],
                ['data' => 'jml_pengukuran_pertama', 'searchable' => true],
                ['data' => 'persen_pengukuran_pertama', 'searchable' => true],
                ['data' => 'activity_log', 'searchable' => true],
                ['data' => 'jml_pengukuran_kedua', 'searchable' => true],
                ['data' => 'persen_pengukuran_kedua', 'searchable' => true],
            ]);

        $datatable->search(function ($query, $keyword) {
            $keyword = strtolower($keyword);

            $query->where(function ($q) use ($keyword) {
                $q->orWhere(DB::raw('lower(company)'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('lower(business)'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('lower(levell)'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_atasan'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_bawahan'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_feedback'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('persen_feedback'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_penyelarasan'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('persen_penyelarasan'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_pengukuran_pertama'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('persen_pengukuran_pertama'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('activity_log'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('jml_pengukuran_kedua'), 'like', "%$keyword%")
                    ->orWhere(DB::raw('persen_pengukuran_kedua'), 'like', "%$keyword%");
            });
        });

        $response = $datatable
            ->toJson();

        return $response;
    }
}
