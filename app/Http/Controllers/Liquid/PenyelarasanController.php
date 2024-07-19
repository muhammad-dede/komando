<?php

namespace App\Http\Controllers\Liquid;

use App\Enum\ConfigLabelEnum;
use App\Helpers\ConfigLabelHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Liquid\Penyelarasan\Store;
use App\Http\Requests\Liquid\Penyelarasan\Update;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\Penyelarasan;
use App\Services\PenyelarasanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;

class PenyelarasanController extends Controller
{
    protected $service;

    public function __construct(PenyelarasanService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('accessPenyelarasan');

        $user = auth()->user()->load('strukturJabatan');
        $liquid = Liquid::with([
            'penyelarasan' => function ($query) use ($user) {
                $query->where('atasan_id', $user->strukturJabatan->pernr);
            },
            'peserta' => function ($query) use ($user) {
                $query->where('atasan_id', $user->strukturJabatan->pernr);
            },
        ])->findOrFail((int) request()->liquid_id);

        $resolusis = [];
        $aksiNyatas = [];
        $keterangans = [];
        $penyelarasans = $liquid->penyelarasan;

        foreach ($liquid->penyelarasan as $index => $penyelarasan) {
            $resolusis[$index] = $this->service->getResolusiAsArray($penyelarasan->resolusi);
            $aksiNyatas[$index] = $penyelarasan->aksi_nyata;
            $keterangans[$index] = $penyelarasan->keterangan_aksi_nyata;
        }

        $isValid = $liquid->peserta->count() >= 3;

        return view('liquid.penyelarasan.index', compact(
            'penyelarasans', 'liquid', 'resolusis', 'isValid', 'aksiNyatas', 'keterangans'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('accessPenyelarasan');

        $liquid = Liquid::findOrFail((int) request()->get('liquid_id'));

        $this->authorize('penyelarasanInProgress', $liquid);

        $feedbackData = $this->service->feedbackData($liquid, auth()->user());
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);

        return view('liquid.penyelarasan.create', compact('feedbackData', 'kelebihan', 'kekurangan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $this->authorize('accessPenyelarasan');

        $this->authorize(
            'penyelarasanInProgress',
            Liquid::findOrFail($request->liquid_id)
        );

        DB::beginTransaction();

        try {
            $this->service->store($request, auth()->user());

            DB::commit();

            return redirect()
                ->route('penyelarasan.index', ['liquid_id' => $request->liquid_id])
                ->with('success', 'Berhasil Menambahkan Data Penyelarasan');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('accessPenyelarasan');

        $penyelarasan = Penyelarasan::findOrFail($id);
        $feedbackData = $this->service->edit($penyelarasan->liquid, $penyelarasan, auth()->user());

        $this->authorize('penyelarasanInProgress', $penyelarasan->liquid);

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);

        return view(
            'liquid.penyelarasan.edit',
            compact(
                'feedbackData',
                'penyelarasan',
                'kelebihan',
                'kekurangan'
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
        $this->authorize('accessPenyelarasan');

        $penyelarasan = Penyelarasan::findOrFail($id);

        $this->service->update($request, $penyelarasan);

        $this->authorize('penyelarasanInProgress', $penyelarasan->liquid);

        return redirect()
            ->route('penyelarasan.index', ['liquid_id' => $penyelarasan->liquid_id])
            ->with('success', 'Berhasil Mengubah Data Penyelarasan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('accessPenyelarasan');

        $penyelarasan = Penyelarasan::findOrFail($id);

        $this->service->delete($penyelarasan);

        $this->authorize('penyelarasanInProgress', $penyelarasan->liquid);

        return redirect()
            ->route('penyelarasan.index')
            ->with('success', 'Berhasil Menghapus Data Penyelarasan');
    }
}
