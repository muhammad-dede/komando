<?php

namespace App\Http\Controllers;

use App\Enum\LiquidStatus;
use App\Models\Liquid\Liquid;
use Carbon\Carbon;

class SurveyLiquidController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $divisiPusat = $user->getKodeDivisiPusat();

        $companyCode = request()->company_code;
        $unitCode = request()->unit_code;
        $divisi = request()->divisi;
        $status = request()->status;

        $filterByUnit = false;
        $filterByCompany = false;
        $filterDefault = true;

        if (! empty($companyCode) && ! empty($unitCode)) {
            $filterByUnit = true;
            $filterDefault = false;
        } else if (! empty($companyCode)) {
            $filterByCompany = true;
            $filterDefault = false;
        }

        $liquids = Liquid::with(['businessAreas', 'peserta', 'creator'])
            ->when($filterByUnit, function ($query) use ($unitCode) {
                return $query->forUnit($unitCode);
            })
            ->when($filterByCompany, function ($query) use ($companyCode) {
                return $query->forCompany($companyCode);
            })
            ->when($filterDefault, function ($query) use ($user) {
                return $query->forUnit($user->business_area);
            })
            ->get()->each(function ($item) {
                return $this->generateItemObject($item);
            });

        if ($status) {
            $liquids = $liquids->filter(function ($liquid) use ($status) {
                return $status === $liquid->getCurrentSchedule();
            });
        }

        return view('report.survey-liquid.index', compact(
            'liquids',
            'user',
            'companyCode',
            'unitCode',
            'periode',
            'divisi',
            'status'
        ));
    }

    public function show(Liquid $liquid)
    {
        $liquid->load([
            'surveyQuestionDetail' => function ($query) {
                $query->with([
                    'surveyQuestion',
                    'feedback',
                    'peserta',
                ])->orderBy('id', 'desc');
            },
            'media',
        ]);

        $questions = $this->convertToQuestions($liquid);

        $docs = $liquid->getMedia();

        return view('report.survey-liquid.show', compact('liquid', 'questions', 'docs'));
    }

    private function generateItemObject(Liquid $item)
    {
        $item->unit_view = '';

        foreach ($item->businessAreas as $area) {
            $item->unit_view .= $area->description_option;
        }

        $item->jadwal_view = date_id($item->feedback_start_date) . ' - ' . date_id($item->feedback_end_date);
        $item->penyelarasan_view = date_id($item->penyelarasan_start_date) . ' - ' .
            date_id($item->penyelarasan_end_date);
        $item->pengukuran_pertama_view = date_id($item->pengukuran_pertama_start_date) . ' - ' .
            date_id($item->pengukuran_pertama_end_date);
        $item->pengukuran_kedua_view = date_id($item->pengukuran_kedua_start_date) . ' - ' .
            date_id($item->pengukuran_kedua_end_date);
        $item->atasan_view = $item->peserta->groupBy('atasan_id')->map(function ($atasan) {
            return $atasan->count();
        })->count();
        $item->bawahan_view = $item->peserta->groupBy('bawahan_id')->map(function ($atasan) {
            return $atasan->count();
        })->count();

        $item->status_view = '';

        if ($item->status === LiquidStatus::PUBLISHED) {
            if ($item->getCurrentSchedule() === LiquidStatus::SELESAI) {
                $item->status_view = '<span class="badge badge-success">Selesai</span>';
            } else {
                $item->status_view = '<span class="badge badge-warning">' . $item->getCurrentSchedule() .
                    '</span>';
            }
        } else {
            $item->status_view = '<span class="badge badge-warning">' . LiquidStatus::DRAFT . '</span>';
        }

        $item->admin_view = $item->creator_nip . ' - ' . $item->creator_name;

        $item->aksi_view = '';
        $item->status = (object) [
            'isDraft' => $item->status === LiquidStatus::DRAFT,
            'isPublished' => $item->status === LiquidStatus::PUBLISHED,
        ];

        return $item;
    }

    private function getTypePeserta(array $snapshotPeserta, $type, $liquidPesertaId)
    {
        foreach ($snapshotPeserta as $data) {
            foreach ($data['peserta'] as $value) {
                if ($value['liquid_peserta_id'] === $liquidPesertaId) {
                    return $value[$type];
                }
            }
        }

        return null;
    }

    private function convertToQuestions($liquid)
    {
        $questions = [];

        $liquid->surveyQuestionDetail->groupBy('survey_question_id')
            ->each(function ($item) use (&$questions, $liquid) {
                $title = $item->first()->surveyQuestion->question;

                $item->each(function ($detail) use ($liquid) {
                    $detail->surveyor = $this->getTypePeserta($liquid->peserta_snapshot, 'nama', $detail->liquid_peserta_id);
                    $detail->nip = $this->getTypePeserta($liquid->peserta_snapshot, 'nip', $detail->liquid_peserta_id);
                    $detail->jabatan = $this->getTypePeserta($liquid->peserta_snapshot, 'jabatan', $detail->liquid_peserta_id);
                    $detail->atasan = null;

                    if ($detail->peserta) {
                        $nip = $detail->peserta->snapshot_nip_atasan;
                        $unit = $detail->peserta->snapshot_unit_code." - ".$detail->peserta->snapshot_unit_name;

                        $detail->atasan = (object) [
                            'nama' => $detail->peserta->snapshot_nama_atasan,
                            'jabatan' => $detail->peserta->snapshot_jabatan2_atasan,
                            'nip' => $detail->peserta->snapshot_nip_atasan,
                            'unit' => $unit,
                        ];

                        $detail->group_by = $nip;
                    }

                    $detail->score = (int) $detail->answer;

                    return $detail;
                });

                $questions[] = (object) [
                    'title' => $title,
                    'result' => $item->groupBy('group_by'),
                ];
            });

        return $questions;
    }
}
