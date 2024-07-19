<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\BusinessArea;
use App\Http\Controllers\Controller;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\LiquidReportData;
use App\Services\Datatable;
use App\Services\LiquidPesertaService;
use App\Services\LiquidReportService;
use App\Services\LiquidService;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LiquidReportController extends Controller
{
    /** @var \Illuminate\Database\Eloquent\Collection */
    private static $masterKelebihanKekuranganDetail;

    public function index()
    {
        $user = auth()->user();
        $reqPeriode = request('periode', date('Y'));
        $reqCC = request('company_code', $user->company_code);
        $reqUnit = request('unit_code', $user->business_area);
        $param = [
            'periode' => $reqPeriode,
            'company_code' => $reqCC,
            'unit_code' => $reqUnit,
        ];
        $nav = "rekap-liquid";
        $liquids = Liquid::query()
            ->published()
            ->forYear($reqPeriode)
            ->forCompany($reqCC)
            ->forUnit($reqUnit)
            ->pluck('id');

        $query = LiquidReportData::query()->whereIn('liquid_id', $liquids);

        if (is_unit_pusat(request('unit_code', $user->getKodeUnit()))) {
            $arrayConf = [
                ['data' => "nama", 'searchable' => true, 'sortable' => true],
                ['data' => "nip", 'searchable' => true, 'sortable' => true],
                ['data' => "jumlah_bawahan", 'searchable' => true, 'sortable' => true],
                ['data' => "jenjang_jabatan", 'searchable' => true, 'sortable' => true],
                ['data' => "sebutan_jabatan", 'searchable' => true, 'sortable' => true],
                ['data' => "unit", 'searchable' => true, 'sortable' => true],
                ['data' => "divisi", 'searchable' => false, 'sortable' => false],
                ['data' => "jumlah_activity_log", 'searchable' => false, 'sortable' => true],
                ['data' => "pengukuran_pertama", 'searchable' => true, 'sortable' => false],
                ['data' => "pengukuran_kedua", 'searchable' => true, 'sortable' => false],
                ['data' => "aksi", 'searchable' => false, 'sortable' => false],
            ];
        } else {
            $arrayConf = [
                ['data' => "nama", 'searchable' => true, 'sortable' => true],
                ['data' => "nip", 'searchable' => true, 'sortable' => true],
                ['data' => "jumlah_bawahan", 'searchable' => true, 'sortable' => true],
                ['data' => "jenjang_jabatan", 'searchable' => true, 'sortable' => true],
                ['data' => "sebutan_jabatan", 'searchable' => true, 'sortable' => true],
                ['data' => "unit", 'searchable' => true, 'sortable' => true],
                ['data' => "jumlah_activity_log", 'searchable' => false, 'sortable' => true],
                ['data' => "pengukuran_pertama", 'searchable' => true, 'sortable' => false],
                ['data' => "pengukuran_kedua", 'searchable' => true, 'sortable' => false],
                ['data' => "aksi", 'searchable' => false, 'sortable' => false],
            ];
        }

        $datatable = Datatable::make($query)
            ->columns($arrayConf)
            ->rowView('liquid.dashboard-admin.liquid-download-report-row');

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('liquid.dashboard-admin.liquid-download-report', compact('nav', 'datatable', 'param'));
    }

    public function show($liquidId, $atasanId)
    {
        $liquid = Liquid::findOrFail($liquidId);
        $peserta = LiquidPeserta::where('atasan_id', $atasanId)
            ->where('liquid_id', $liquid->id)
            ->firstOrFail();

        $detail = app(LiquidPesertaService::class)->getDetailForAtasan($peserta);

        return view('liquid.dashboard-admin.liquid-download-report-detail', compact('detail'));
    }

    public function download()
    {
        $liquids = Liquid::query()
            ->published()
            ->forYear(request('periode', date('Y')))
            ->forCompany(request('company_code', auth()->user()->company_code))
            ->forUnit(request('unit_code', auth()->user()->business_area))
            ->pluck('id');

        if (count($liquids) === 0) {
            return abort(404);
        }

        // logic using cache
        // if (config('app.isUsingCache')) {
        //     if (Cache::has('report-liquid-data')) {
        //         $report = Cache::get('report-liquid-data');
        //     } else {
        //         $report = Cache::remember('report-liquid-data', 60, function () use ($liquids) {
        //             return LiquidReportData::query()->whereIn('liquid_id', $liquids)->get();
        //         });
        //     }
        // } else {
        //     $report = LiquidReportData::query()->whereIn('liquid_id', $liquids)->get();
        // }
        $report = LiquidReportData::query()->whereIn('liquid_id', $liquids)->get();

        $th = [
            'NO',
            'NAMA',
            'NIP',
            'JUMLAH BAWAHAN',
            'JENJANG JABATAN',
            'SEBUTAN JABATAN',
            'UNIT',
            'JUMLAH ACTIVITY LOG',
            'KELEBIHAN (3 TERBANYAK YANG DINILAI)',
            'RESOLUSI',
            'RATA-RATA HASIL (P1)',
            'TANGGAL PELAKSANAAN (P1)',
            'RATA-RATA HASIL (P2)',
            'TANGGAL PELAKSANAAN (P2)'
        ];

        $border = (new BorderBuilder)
            ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
            ->build();

        $style = (new StyleBuilder())
            ->setBorder($border)
            ->setFontSize(12)
            ->setShouldWrapText()
            ->build();

        $label = app(LiquidReportService::class)->setReportTitleLabel(request());
        $writer = WriterFactory::create(Type::XLSX);
        $reportTitle = 'REKAPITULASI HASIL LEADERSHIP QUALITY FEEDBACK (INTERNAL PEGAWAI)';

        $writer->openToBrowser(date('YmdHis').'_report_liquid_'.$label.'.xlsx');

        app(LiquidReportService::class)->setReportTitle($writer, $label, $reportTitle);

        $writer->addRowWithStyle($th, $style);

        $row = 1;
        $rows = [];

        foreach ($report as $data) {
            $kelebihan = $this->parseKelebihanTerbanyak($data->kelebihan_raw);
            $resolusi = $this->parseResolusi($data->resolusi_raw);
            $pengukuranPertama = $pengukuranKedua = '-';

            if ($resolusi !== '-') {
                $pengukuranPertama = $this->parsePengukuran($data->pengukuran_pertama_raw, $resolusi->keys());
                $pengukuranKedua = $this->parsePengukuran($data->pengukuran_kedua_raw, $resolusi->keys());
            }

            $rows[] = [
                $row++,
                $data->nama,
                $data->nip,
                $data->jumlah_bawahan,
                $data->present_jenjang_jabatan,
                $data->sebutan_jabatan,
                $data->unit,
                $data->jumlah_activity_log,
                $kelebihan,
                is_array($resolusi) ? $resolusi->implode("\n") : $resolusi,
                $pengukuranPertama,
                $data->pengukuran_pertama,
                $pengukuranKedua,
                $data->pengukuran_kedua
            ];
        }

        $writer->addRows($rows);
        $writer->close();
    }

    public function downloadLiquidHistory()
    {
        $unitCode = request('unit_code', auth()->user()->business_area);
        $unitName = BusinessArea::where('business_area', $unitCode)
            ->first();

        Excel::create(
            date('YmdHis').'_liquid_peserta_history'.$unitName->description,
            function ($excel) use ($unitName) {
                $excel->sheet(str_limit('unit_'.$unitName->business_area, 13), function ($sheet) use ($unitName) {
                    $sheet->getStyle('F5')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('G5')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('H')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('I')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('L')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('M')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('O')->getAlignment()->setWrapText(true);
                    $sheet->getStyle('P')->getAlignment()->setWrapText(true);

                    $sheet->loadView('report/liquid/liquid_peserta_history_xls', [
                        'unitName' => $unitName
                    ]);
                });
            }
        )->download('xlsx');
    }

    public function downloadRekapPartisipan()
    {
        ini_set('max_execution_time', 500);

        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', '');
        $periode = \request('periode', Carbon::now()->year);

        $data = DB::table('v_liquid_rekap_partisipan')
            ->where(function ($q) use ($companyCode, $unitCode) {
                if ($companyCode === '' && $unitCode === '') {
                    $q->whereNotNull('liquid_id');
                }
                if ($companyCode) {
                    $q->where('company_code', $companyCode);
                }
                if ($unitCode) {
                    $q->where('business_area', $unitCode);
                }
            })
            ->when(request('search.value'), function ($query) {
                $keyword = strtolower(request('search.value'));

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
            })
            ->whereYear('periode', "=", intval($periode))
            ->get();

        $label = app(LiquidReportService::class)
            ->setReportTitleLabel(request());

        Excel::create(
            date('YmdHis').'_liquid_rekap_partisipan_'.$label,
            function ($excel) use ($label, $data) {
                $excel->sheet(str_replace(':', '', str_limit($label, 13)), function ($sheet) use ($label, $data) {
                    $sheet->loadView('report/liquid/liquid_rekap_partisipan_xls', [
                        'title' => $label,
                        'datas' => $data,
                    ]);
                });
            }
        )->download('xlsx');
    }

    private function parseRawAggregation($raw)
    {
        $data = json_decode(str_replace('#', '', str_replace(']#[', ',', $raw)), true);
        if (is_array($data)) {
            return $data;
        }

        return [];
    }

    private function parseKelebihanTerbanyak($raw, $limit = 3)
    {
        $data = $this->parseRawAggregation($raw);
        $kelebihanIds = collect(array_count_values($data))->sort()->reverse()->take($limit)->keys();

        if ($kelebihanIds->count() === 0) {
            return '-';
        }

        $format = '%s. %s';
        $no = 1;

        return static::$masterKelebihanKekuranganDetail
            ->whereIn('id', $kelebihanIds->toArray())
            ->pluck('deskripsi_kelebihan')
            ->map(function ($item) use (&$no, $format) {
                return sprintf($format, $no++, $item);
            })
            ->implode("\n");
    }

    private function parseResolusi($raw)
    {
        $resolusiIds = $this->parseRawAggregation($raw);

        if (count($resolusiIds) === 0) {
            return '-';
        }

        return static::$masterKelebihanKekuranganDetail
            ->whereIn('id', $resolusiIds)
            ->pluck('deskripsi_kelebihan', 'id');
    }

    private function parsePengukuran($raw, $resolusi)
    {
        return collect($this->parseRawAggregation($raw))->transform(function ($item) {
            $temp = explode(':', $item);

            return [
                'resolusi' => $temp[0],
                'skor' => $temp[1],
            ];
        })
            ->groupBy('resolusi')
            ->transform(function (Collection $group) {
                return app_format_skor_penilaian($group->average('skor'));
            })
            ->sortBy(function ($item, $id) use ($resolusi) {
                $item;

                return $resolusi->search($id);
            })
            ->values()
            ->transform(function ($item, $key) {
                return sprintf("%s. %s", ($key + 1), $item);
            })->implode("\n");
    }
}
