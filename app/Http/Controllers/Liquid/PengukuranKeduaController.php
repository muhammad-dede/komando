<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Enum\ConfigLabelEnum;
use App\Helpers\ConfigLabelHelper;
use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\PengukuranKedua;
use App\Models\Liquid\PengukuranPertama;
use App\Services\PengukuranKeduaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Utils\BasicUtil;

class PengukuranKeduaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //old code : $liquid = Liquid::query()->forBawahan(auth()->user())->currentYear()->published()->firstOrFail();
        //note : remove currentYear from query, change with desc order by feedback_start_date
        //date : March 09, 2022
        $liquid = Liquid::query()->forBawahan(auth()->user())->orderBy('feedback_start_date','desc')->published()->firstOrFail();
        $dataAtasan = app(PengukuranKeduaService::class)->index(auth()->user(), $liquid);

        return view('liquid.pengukuran-kedua.index', compact('liquid', 'dataAtasan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataAtasan = app(PengukuranKeduaService::class)
            ->create(request()->get('liquid_peserta_id'))[0];
        
        $dataAtasan->load('activityLogBooks');

        if ($dataAtasan->activityLogBooks->isEmpty()) {
            return redirect(url('pengukuran-kedua'))->with('error', 'Atasan Anda belum mengisi activity log. Silakan hubungi Administrator.');
        }

        $resolusi = app(PengukuranKeduaService::class)
            ->create(request()->get('liquid_peserta_id'))[1];

        $dataPenilaianPertama = PengukuranPertama::where('LIQUID_PESERTA_ID', request()->get('liquid_peserta_id'))->first();

        if (empty($dataPenilaianPertama)) {
            return redirect(url('pengukuran-kedua'))->with('error', 'Anda belum melakukan pengukuran pertama.');
        }

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_2),
            'ukuran1' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        $penilaianPertama = array_values($dataPenilaianPertama->penilaian());
        $penilaianPertama = (new BasicUtil)->convertPengukurangToRange($penilaianPertama);

        return view('liquid.pengukuran-kedua.create', compact('dataAtasan', 'resolusi', 'penilaianPertama', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $liquidPeserta = LiquidPeserta::findOrFail($request->liquid_peserta_id);
            app(PengukuranKeduaService::class)->store($request, $liquidPeserta);

            Activity::log(set_liquid_bawahan_log_label('Input Pengukuran Kedua', $liquidPeserta), 'success');

            DB::commit();
            return redirect()
                ->route('pengukuran-kedua.index')
                ->with('success', 'Berhasil memberikan penilaian kedua');
        }catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \BladeView|false|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        list($dataPenilaian, $dataAtasan, $resolusi) = app(PengukuranKeduaService::class)->show($id);
        $dataPenilaianPertama = PengukuranPertama::where('LIQUID_PESERTA_ID', $dataPenilaian['liquid_peserta_id'])->first();

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_2),
            'ukuran1' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        $penilaianPertama = array_values($dataPenilaianPertama->penilaian());
        $penilaianPertama = (new BasicUtil)->convertPengukurangToRange($penilaianPertama);

        return view(
            'liquid.pengukuran-kedua.show',
            compact(
                'dataPenilaian',
                'dataPenilaianPertama',
                'dataAtasan',
                'label',
                'penilaianPertama',
                'resolusi'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataPenilaian = app(PengukuranKeduaService::class)
            ->show($id)[0];
        $dataAtasan = app(PengukuranKeduaService::class)
            ->show($id)[1];
        $resolusi = app(PengukuranKeduaService::class)
            ->show($id)[2];
        $dataPenilaianPertama = PengukuranPertama::where('LIQUID_PESERTA_ID', $dataPenilaian['liquid_peserta_id'])->first();

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_2),
            'ukuran1' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        $penilaianPertama = array_values($dataPenilaianPertama->penilaian());
        $penilaianPertama = (new BasicUtil)->convertPengukurangToRange($penilaianPertama);

        return view(
            'liquid.pengukuran-kedua.edit',
            compact(
                'dataPenilaian',
                'dataPenilaianPertama',
                'dataAtasan',
                'label',
                'penilaianPertama',
                'resolusi'
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
    public function update(Request $request, $id)
    {
        $pengukuranKedua = PengukuranKedua::findOrFail($id);
        app(PengukuranKeduaService::class)->update($request, $pengukuranKedua);

        Activity::log(set_liquid_bawahan_log_label('Edit Pengukuran Kedua', $pengukuranKedua->liquidPeserta), 'success');

        return redirect()
            ->route('pengukuran-kedua.index')
            ->with('success', 'Data Pengukuran Kedua Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function toggle(Liquid  $liquid, $pernrAtasan)
    {
        $force = request('force_pengukuran_kedua', false);
        $liquidPeserta = LiquidPeserta::where([
            'liquid_id' => $liquid->id,
            'atasan_id' => $pernrAtasan,
        ]);
        $liquidPeserta->update(['force_pengukuran_kedua' => $force]);

        Activity::log(set_liquid_bawahan_log_label('Buka Pengukuran Kedua', $liquidPeserta->first()), 'success');

        $status = $force ? 'dibuka' : 'ditutup';
        $namaPegawai = DB::table('v_snapshot_pegawai')->where('pernr', $pernrAtasan)->value('nama');

        return redirect()->back()->with('success', sprintf('Pengukuran Kedua untuk %s berhasil %s', $namaPegawai, $status));
    }
}
