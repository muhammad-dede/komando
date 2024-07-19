<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\LiquidPesertaAtasan;
use App\Services\Datatable;
use App\Services\LiquidReportService;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;

class RekapFeedbackController extends Controller
{
    public function index()
    {
        $nav = "feedback";
        $arr = LiquidPesertaAtasan::query()
            ->forYear(request('periode', date('Y')))
            ->forCompany(request('company_code', auth()->user()->company_code))
            ->forUnit(request('unit_code', auth()->user()->business_area))
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->feedback = $item->getRekapFeedback();

                return $item;
            })->toArray();

        $datatable = Datatable::fromArray(collect($arr))
            ->rowView('liquid.dashboard-admin.rekap-feedback-row')
            ->columns([
                ['data' => 'unit_code', 'searchable' => true, 'sortable' => true],
                ['data' => 'nama', 'searchable' => true, 'sortable' => true],
                ['data' => 'nip', 'searchable' => true, 'sortable' => true],
                ['data' => 'jabatan', 'searchable' => true, 'sortable' => true],
                ['data' => 'kelebihan_lainnya', 'searchable' => false, 'sortable' => false, 'className' => 'height_td'],
                ['data' => 'kekurangan_lainnya', 'searchable' => false, 'sortable' => false, 'className' => 'height_td'],
                ['data' => 'harapan', 'searchable' => false, 'sortable' => false, 'className' => 'height_td'],
                ['data' => 'saran', 'searchable' => false, 'sortable' => false, 'className' => 'height_td'],
            ]);

        if (request()->wantsJson()) {
            $datatable->searchFromArray(function ($arrayData, $keyword) {
                $keyword = strtolower($keyword);

                return collect($arrayData)
                    ->filter(function ($data) use ($keyword) {
                        $keys = array_keys($data);

                        foreach ($keys as $key) {
                            if (is_string($data[$key])) {
                                $bol = strpos(strtolower($data[$key]), $keyword) !== false
                                    ? true : false;
                                if ($bol) {
                                    return $bol;
                                }
                            }

                            foreach ($data['feedback'] as $feedbacks) {
                                foreach ($feedbacks as $feedback) {
                                    $bol = strpos(strtolower($feedback), $keyword) !== false
                                        ? true : false;
                                    if ($bol) {
                                        return $bol;
                                    }
                                }
                            }
                        }
                    });
            });

            return $datatable->fromArrayToJson();
        }

        return view('liquid.dashboard-admin.rekap-feedback', compact('nav', 'datatable'));
    }

    public function download()
    {
        $unitCode = request()->get('unit_code');
        $label = app(LiquidReportService::class)
            ->setReportTitleLabel(request());

        $writer = WriterFactory::create(Type::XLSX);
        $th = [
            'NO',
            'UNIT',
            'ATASAN',
            'NIP',
            'JABATAN',
            'KELEBIHAN LAINNYA',
            'KEKURANGAN LAINNYA',
            'HARAPAN',
            'SARAN',
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

        $writer->openToBrowser(date('YmdHis').'_report_liquid_'.$label.'.xlsx');

        app(LiquidReportService::class)
            ->setReportTitle($writer, $label, 'LIQUID REKAP FEEDBACK LAINNYA, HARAPAN, DAN SARAN');
        $writer->addRowWithStyle($th, $style);
        $index = 0;
        $atasan = LiquidPesertaAtasan::query()
            ->forYear(request('periode', date('Y')))
            ->forCompany(request('company_code', auth()->user()->company_code))
            ->forUnit(request('unit_code', auth()->user()->business_area))
            ->get();

        foreach ($atasan as $data) {
            $feedback = $data->getRekapFeedback();
            $writer->addRowWithStyle([
                $index += 1,
                $data->unit_code . '/' . $data->unit_name,
                $data->nama,
                $data->nip,
                $data->jabatan,
                $this->arrayToFriendlyText($feedback['kelebihan_lainnya']),
                $this->arrayToFriendlyText($feedback['kekurangan_lainnya']),
                $this->arrayToFriendlyText($feedback['harapan']),
                $this->arrayToFriendlyText($feedback['saran']),
            ], $style);
        }

        $writer->close();
    }

    private function arrayToFriendlyText($arrayOfText)
    {
        $result = [];
        foreach ($arrayOfText as $text) {
            $text = strip_tags($text);
            if (trim($text)) {
                $result[] = $text;
            }
        }

        return implode("\n", $result);
    }
}
