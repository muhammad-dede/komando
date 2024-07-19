<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Notification;
use App\Exceptions\IncompleteLiquidAttributes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Liquid\LiquidGathering\Update;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use App\Enum\LiquidJabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FormatDateIndonesia;
use Maatwebsite\Excel\Facades\Excel;

class LiquidGatheringController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param Liquid $liquid
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Liquid $liquid)
    {
        return view('liquid.liquid-gathering.edit', compact('liquid'));
    }

    public function show(Liquid $liquid)
    {
        return view('liquid.liquid-gathering.show', compact('liquid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param Liquid $liquid
     *
     * @return void
     */
    public function update(Update $request, Liquid $liquid)
    {
        try {
            $listPeserta = $this->groupPesertaByJabatan(app(LiquidService::class)->listPesertaLessThan_3($liquid));
            $emailLogin = Auth::user()->email;
            $company = Auth::user()->ad_company;
            $kepadaLogin = Auth::user()->name." (".$company.")";
            $liquid->update($request->sanitize());

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquid['feedback_start_date']));
            $dateFeedbackEnd = date("Y-m-d", strtotime($liquid['feedback_end_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquid['penyelarasan_start_date']));
            $datePenyelarasanEnd = date("Y-m-d", strtotime($liquid['penyelarasan_end_date']));
            $datePPertama = date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date']));
            $datePPertamaEnd = date("Y-m-d", strtotime($liquid['pengukuran_pertama_end_date']));
            $datePKedua = date("Y-m-d", strtotime($liquid['pengukuran_kedua_start_date']));
            $datePKeduaEnd = date("Y-m-d", strtotime($liquid['pengukuran_kedua_end_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, false);
            $setDateIndoEnd = $setDate->tanggal_indo($dateFeedbackEnd, true, false);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['date_feedback_indo_end'] = $setDateIndoEnd;
            $dataLiquid['feedback'] = false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, false);
            $setDateIndoEnd = $setDate->tanggal_indo($datePenyelarasanEnd, true, false);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['date_penyelarasan_indo_end'] = $setDateIndoEnd;
            $dataLiquid['penyelarasan'] = false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, false);
            $setDateIndoEnd = $setDate->tanggal_indo($datePPertamaEnd, true, false);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['date_pengukuran_pertama_indo_end'] = $setDateIndoEnd;
            $dataLiquid['pengukuran_pertama'] = false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, false);
            $setDateIndoEnd = $setDate->tanggal_indo($datePKeduaEnd, true, false);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['date_pengukuran_kedua_indo_end'] = $setDateIndoEnd;
            $dataLiquid['pengukuran_kedua'] = false;

            app(LiquidService::class)->publish($liquid);

            $units = $liquid->businessAreas()
                ->with('companyCode')
                ->get()
                ->map(function ($area) {
                    return $area->description;
                })
                ->toArray();
            $units = implode(', ', $units);

            if (! $liquid->isPublished()) {
                Activity::log("[LIQUID] Create Jadwal Liquid $units.", 'success');
            }

            if(!empty($listPeserta)){
                $to = Auth::user()->name;
                $user_id_to = Auth::user()->id;
                $subject = 'Informasi Pembuatan Liquid';
                $message = "Ada Atasan yang memiliki bawahan kurang dari 3!!!";
                $url = "liquid/".$liquid['id'].'/peserta/edit';
                $notification = new Notification();
                $saved = $notification->sendBySystem($user_id_to, $to, $subject, $message, $url);
                $last_id = DB::getPDO()->lastInsertId();
                $linkExcel = route('liquid.gathering.ExcelPesertaLessThan3', $liquid);

                Mail::queue('emails.liquid_less_than_3', ['kepada' => $kepadaLogin, 'linkExcel' => $linkExcel, 'jadwal' => $dataLiquid, 'notif_id' => $last_id], function($message) use ($emailLogin) {
                    $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                    $message->to($emailLogin);
                    $message->subject('Informasi Pembuatan Liquid');
                });
            }

            Activity::log("[LIQUID] Edit Jadwal Liquid $units.", 'success');

            return redirect()->route('dashboard-admin.liquid-jadwal.index')->withSuccess('Liquid berhasil dipublish');
        } catch (IncompleteLiquidAttributes $exception) {
            switch ($exception->getCode()) {
                case IncompleteLiquidAttributes::TANGGAL_BELUM_DIISI:
                    return redirect()->route('liquid.edit', $liquid)->withWarning('Silakan lengkapi tanggal Liquid');
                    break;
                case IncompleteLiquidAttributes::UNIT_KERJA_BELUM_DIISI:
                    return redirect()->route('liquid.unit-kerja.edit', $liquid)->withWarning('Silakan lengkapi unit kerja Liquid');
                    break;
                case IncompleteLiquidAttributes::PESERTA_BELUM_DIISI:
                    return redirect()->route('liquid.peserta.edit', $liquid)->withWarning('Silakan lengkapi peserta Liquid');
                    break;
                case IncompleteLiquidAttributes::DOKUMEN_BELUM_DIISI:
                    return redirect()->route('liquid.dokumen.edit', $liquid)->withWarning('Silakan lengkapi dokumen Liquid');
                    break;
                case IncompleteLiquidAttributes::UNIT_SUDAH_PUNYA_LIQUID:
                    return redirect()->route('liquid.unit-kerja.edit', $liquid)->withWarning('Unit '.$exception->getMessage().' sudah mempunyai liquid pada tahun ini');
                    break;
                default:
            }
        }
    }

    protected function groupPesertaByJabatan($peserta)
    {
        return collect($peserta)
            ->map(function ($item, $key) {
                $item['pernr'] = $key;
                $item['peserta'] = collect($item['peserta'])
                    ->map(function ($item, $key) {
                        $item['pernr'] = $key;

                        return $item;
                    })
                    ->sortBy('name')
                    ->groupBy('kelompok_jabatan')
                    ->sortBy(function ($group, $key) {
                        return LiquidJabatan::getOrder($key);
                    });

                return $item;
            })
            ->sortBy('name')
            ->groupBy('kelompok_jabatan')
            ->sortBy(function ($group, $key) {
                return LiquidJabatan::getOrder($key);
            });
        ;
    }

    public function ExcelPesertaLessThan3(Liquid $liquid){
        $listPeserta = $this->groupPesertaByJabatan(app(LiquidService::class)->listPesertaLessThan_3($liquid));
        Excel::create(date('YmdHis').'_atasan_yang_memiliki_bawahan_dibawah_3',
            function ($excel) use ($listPeserta) {
                $excel->sheet('Bawahan Kurand dari 3', function ($sheet) use ($listPeserta) {
                    $sheet->loadView('report/liquid/export_excel_perserta_less_than_3', [
                        'listPeserta'=>$listPeserta
                    ]);
                    $sheet->setAutoSize(true);
                });

                $lastrow= $excel->getActiveSheet()->getHighestRow();
                $excel->getActiveSheet()->getStyle('E3:E'.$lastrow)->getAlignment()->setWrapText(true);
            })->download('xlsx');
    }
}
