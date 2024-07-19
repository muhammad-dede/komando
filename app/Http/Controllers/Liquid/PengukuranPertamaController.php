<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Enum\ConfigLabelEnum;
use App\Helpers\ConfigLabelHelper;
use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Services\PengukuranPertamaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;

class PengukuranPertamaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO cek authorization
        $liquidId = (int)request('liquid_id');
        $liquid = Liquid::findOrFail((int)request('liquid_id'));
        $dataAtasan = app(PengukuranPertamaService::class)->index(auth()->user(), $liquid);
        return view('liquid.pengukuran-pertama.index', compact('dataAtasan', 'liquidId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $idLiquidPeserta = request()->get('liquid_peserta_id');

        if (Gate::denies('atasanShouldHavePenyelarasan', $idLiquidPeserta)) {
            return redirect()
                ->back()
                ->withError('Atasan belum melakukan penyelarasan');
        }

        $dataAtasan = app(PengukuranPertamaService::class)
            ->create($idLiquidPeserta)[0];

        $resolusi = app(PengukuranPertamaService::class)
            ->create($idLiquidPeserta)[1];

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        return view('liquid.pengukuran-pertama.create', compact('dataAtasan', 'resolusi', 'label'));
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
            $liquidPeserta = LiquidPeserta::findOrFail((int) $request->liquid_peserta_id);
            $pengukuranPertama = app(PengukuranPertamaService::class)
                ->store($request, $liquidPeserta);
    
            Activity::log(set_liquid_bawahan_log_label('Input Pengukuran Pertama', $liquidPeserta), 'success');

            DB::commit();
            return redirect()
                ->route('penilaian.index', ['liquid_id' => $pengukuranPertama->liquidPeserta->liquid_id])
                ->with('success', 'Berhasil memberikan penilaian pertama');
        }catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //TODO beware of undefined index
        //TODO harusnya cukup sekali call method show() agar tidak ada duplikasi query
        $liquidId = (int)request('liquid_id');
        $dataPenilaian = app(PengukuranPertamaService::class)
            ->show($id)[0];
        $dataAtasan = app(PengukuranPertamaService::class)
            ->show($id)[1];
        $resolusi = app(PengukuranPertamaService::class)
            ->show($id)[2];

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        return view(
            'liquid.pengukuran-pertama.show',
            compact(
                'dataPenilaian',
                'dataAtasan',
                'resolusi',
                'label',
                'liquidId'
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
        $dataPenilaian = app(PengukuranPertamaService::class)
            ->show($id)[0];
        $dataAtasan = app(PengukuranPertamaService::class)
            ->show($id)[1];
        $resolusi = app(PengukuranPertamaService::class)
            ->show($id)[2];

        Activity::log(set_liquid_bawahan_log_label('Edit Pengukuran Pertama', $dataPenilaian->liquidPeserta), 'success');

        $label = new ConfigLabelHelper;
        $label = (object) [
            'indikator' => $label->getLabel(ConfigLabelEnum::KEY_INDIKATOR),
            'ukuran' => $label->getLabel(ConfigLabelEnum::KEY_FORM_PENILAIAN_1),
        ];

        return view(
            'liquid.pengukuran-pertama.edit',
            compact(
                'dataPenilaian',
                'dataAtasan',
                'label',
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
        $pengukuranPertama = app(PengukuranPertamaService::class)->update($request, $id);

        return redirect()
            ->route('penilaian.index', ['liquid_id' => $pengukuranPertama->liquidPeserta->liquid_id])
            ->with('success', 'Data Pengukuran Pertama Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app(PengukuranPertamaService::class)
            ->destroy($id);

        return redirect()
                ->route('penilaian.index')
                ->with('success', 'Data Pengukuran Pertama Berhasil Dihapus');
    }
}
