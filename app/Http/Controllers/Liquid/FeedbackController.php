<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Enum\FeedbackStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Liquid\Feedback\Store;
use App\Http\Requests\Liquid\Feedback\Update;
use App\Models\Liquid\Feedback;
use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\Models\MediaKit;
use App\User;
use App\SurveyQuestion;
use App\SurveyQuestionDetail;
use App\Utils\WordCountUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pastikan parameter liquid_id yang dikirim valid (liquid aktif), otherwise kita tampilkan halaman 404
        $liquid = Liquid::currentYear()->published()->findOrFail(request('liquid_id'));
        $dataAtasan = LiquidPeserta::with('atasan.bawahans')
            ->where('bawahan_id', auth()->user()->strukturJabatan->pernr)
            ->where('liquid_id', $liquid->id)
            ->get()->each(function ($item) {
                $isValid = $item->atasan->bawahans->count() >= 3;
                $liquidPesertaId = $item->id;
                $liquidId = $item->liquid_id;

                $item->href = $isValid
                    ? route('feedback.create') . "?liquid_peserta_id=$liquidPesertaId&liquid_id=$liquidId"
                    : '';
                $item->class = $isValid
                    ? 'btn btn-primary'
                    : 'btn btn-secondary disabled';
                $item->message = ! $isValid
                    ? '<span class="label label-danger">Atasan anda  memiliki bawahan kurang dari 3, silahkan ' .
                        'hubungi admin sistem</span>'
                    : null;

                return $item;
            });

        return view('liquid.feedback.index', compact('dataAtasan', 'liquid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pesertaId = request()->get('liquid_peserta_id');
        $liquidPeserta = LiquidPeserta::findOrFail($pesertaId);

        $liquidPeserta->load('liquid', 'atasan.user');

        $liquid = $liquidPeserta->liquid;
        $feedback = new Feedback();
        $dataKK = KelebihanKekurangan::with('details')->findOrFail($liquid->kelebihan_kekurangan_id);
        $dataAtasan = $liquidPeserta->atasan->user;

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        $surveyQuestion = SurveyQuestion::select('id','question')->where('status', '1')->first();

        $selectedKelebihan = $selectedKekurangan = [];
        $wordCount = config('liquid.word_count');

        $mostOfi = 'assets/images/most-ofi.jpeg';
        $mostStrength = 'assets/images/most-strength.jpeg';
        $posters = (object) [
            'isExist' => (object) [
                'ofi' => file_exists(public_path() . '/' . $mostOfi),
                'strength' => file_exists(public_path() . '/' . $mostStrength),
            ],
            'ofi' => url($mostOfi),
            'strength' => url($mostStrength),
        ];

        if(empty($dataAtasan)) {
            return redirect()->back()->with('error', 'Data Atasan Tidak Ditemukan. Hubungi Admin untuk mengecek data atasan di manajemen user.');
        }

        return view(
            'liquid.feedback.create',
            compact(
                'feedback',
                'dataKK',
                'dataAtasan',
                'selectedKelebihan',
                'selectedKekurangan',
                'kelebihan',
                'kekurangan',
                'saran',
                'wordCount',
                'posters',
                'surveyQuestion'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $validated = WordCountUtil::validate($request->boxes_alasan_kelebihan, $request->boxes_alasan_kekurangan);

        if (! $validated) {
            $wordCount = config('liquid.word_count');

            return redirect()->back()->with('error', "Keterangan/Alasan masih ada yang kurang dari $wordCount kata!");
        }

        DB::beginTransaction();
        try {
            $liqPeserta = LiquidPeserta::find(
                (int) $request->liquid_peserta_id
            );
            $feedback = new Feedback(
                [
                    'kelebihan' => $request->boxes_kelebihan,
                    'kekurangan' => $request->boxes_kekurangan,
                    'harapan' => $request->harapan,
                    'saran' => $request->saran,
                    'new_kelebihan' => $request->new_kelebihan,
                    'new_kekurangan' => $request->new_kekurangan,
                    'status' => FeedbackStatus::PUBLISHED,
                    'alasan_kelebihan' => $request->boxes_alasan_kelebihan,
                    'alasan_kekurangan' => $request->boxes_alasan_kekurangan,
                ]
            );

            $liqPeserta->feedback()->save($feedback);
            $last_id = DB::getPDO()->lastInsertId();
            $upSurveyDetail = SurveyQuestionDetail::findOrFail($request->survey_question_detail_id);
            $upSurveyDetail->feedback_id = $last_id;
            $upSurveyDetail->save();

            Activity::log(set_liquid_bawahan_log_label('Input Feedback Atasan', $liqPeserta), 'success');

            DB::commit();
            return redirect()->route('feedback.index', ['liquid_id' => $liqPeserta->liquid_id])
                ->with('success', 'Berhasil Mengisi Feedback');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->load([
            'liquidPeserta' => function ($query) {
                $query->with(['liquid', 'atasan.user']);
            },
        ]);

        $dataKK = KelebihanKekurangan::withTrashed()->findOrFail(
            $feedback
                ->liquidPeserta
                ->liquid
                ->kelebihan_kekurangan_id
        );

        $selectedKelebihan = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', $feedback->kelebihan)
            ->get()
            ->pluck('deskripsi_kelebihan', 'id')
            ->toArray();
        $selectedKekurangan = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', $feedback->kekurangan)
            ->get()
            ->pluck('deskripsi_kekurangan', 'id')
            ->toArray();
        if (!empty($feedback->new_kelebihan)) {
            $selectedKelebihan['__OTHER__'] = is_string($feedback->new_kelebihan)
                ? $feedback->new_kelebihan
                : array_first($feedback->new_kelebihan);
        }
        if (!empty($feedback->new_kekurangan)) {
            $selectedKekurangan['__OTHER__'] = is_string($feedback->new_kekurangan)
                ? $feedback->new_kekurangan
                : array_first($feedback->new_kekurangan);
        }

        $dataAtasan = $feedback
            ->liquidPeserta
            ->atasan
            ->user;

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);
        $wordCount = config('liquid.word_count');

        $mostOfi = 'assets/images/most-ofi.jpeg';
        $mostStrength = 'assets/images/most-strength.jpeg';
        $posters = (object) [
            'isExist' => (object) [
                'ofi' => file_exists(public_path() . '/' . $mostOfi),
                'strength' => file_exists(public_path() . '/' . $mostStrength),
            ],
            'ofi' => url($mostOfi),
            'strength' => url($mostStrength),
        ];

        return view(
            'liquid.feedback.edit',
            compact(
                'feedback',
                'dataKK',
                'selectedKelebihan',
                'selectedKekurangan',
                'dataAtasan',
                'kelebihan',
                'kekurangan',
                'wordCount',
                'posters',
                'saran'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Update $request, $id)
    {
        $validated = WordCountUtil::validate($request->boxes_alasan_kelebihan, $request->boxes_alasan_kekurangan);

        if (! $validated) {
            $wordCount = config('liquid.word_count');

            return redirect()->back()->with('error', "Keterangan/Alasan masih ada yang kurang dari $wordCount kata!");
        }

        $feedback = Feedback::findOrFail($id);
        $this->authorize('updateFeedback', $feedback->liquidPeserta);

        $feedback->kelebihan = $request->boxes_kelebihan;
        $feedback->kekurangan = $request->boxes_kekurangan;
        $feedback->harapan = $request->harapan;
        $feedback->saran = $request->saran;
        $feedback->new_kelebihan = $request->new_kelebihan;
        $feedback->new_kekurangan = $request->new_kekurangan;
        $feedback->alasan_kelebihan = $request->boxes_alasan_kelebihan;
        $feedback->alasan_kekurangan = $request->boxes_alasan_kekurangan;
        $feedback->save();

        Activity::log(set_liquid_bawahan_log_label('Edit Feedback Atasan', $feedback->liquidPeserta), 'success');

        return redirect()->route('feedback.index', ['liquid_id' => $feedback->liquidPeserta->liquid_id])
            ->with('success', 'Berhasil Mengubah Feedback');
    }

    public function save_survey(Request $request)
    {
        DB::beginTransaction();
        try {
            $checkData = SurveyQuestionDetail::where('liquids_id','=',$request->liquids_id)->where('liquid_peserta_id','=',$request->liquid_peserta_id)->first();
            if(!empty($checkData)) {
                $checkData->answer = $request->answer;
                $checkData->reason = $request->reason;
                $checkData->modified_by = auth()->user()->id;
                $checkData->save();
                $last_id = $checkData['id'];
            }else{
                $data = new SurveyQuestionDetail;
                $data->survey_question_id = $request->survey_question_id;
                $data->liquids_id = $request->liquids_id;
                $data->liquid_peserta_id = $request->liquid_peserta_id;
                $data->answer = $request->answer;
                $data->reason = $request->reason;
                $data->created_by = auth()->user()->id;
                $data->save();
                $last_id = $data->id;
            }

            DB::commit();
            $jsons = array();
            $jsons['status'] = true;
            $jsons['msg'] = "success";
            $jsons['survey_question_detail_id'] = $last_id;
            return json_encode($jsons);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $jsons = array();
            $jsons['status'] = false;
            $jsons['msg'] = $ex;
            $jsons['survey_question_detail_id'] = "";
            return json_encode($jsons);
        }
    }
}
