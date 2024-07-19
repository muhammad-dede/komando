<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Liquid\ActivityLogbook\Store;
use App\Http\Requests\Liquid\ActivityLogbook\Update;
use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ActivityLogBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('canAccessActLogBook');

        $actLogBook = ActivityLogBook::where('created_by', auth()->id())->get();

        return view('liquid.activity-log.index', compact('actLogBook'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('canAccessActLogBook');

        $liquid = Liquid::whereHas('penyelarasan', function ($q) {
                            $q->whereNotNull('resolusi');
                        })->published()
                        ->orderBy('feedback_start_date', 'desc')
                        ->forAtasan(auth()->user())
                        ->first();
        $resolusi = app(LiquidService::class)->resolusi(auth()->user()->strukturJabatan->pernr, $liquid);

        return view('liquid.activity-log.create', compact('resolusi', 'liquid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $this->authorize('canAccessActLogBook');

        $liquid = Liquid::findOrFail((int) $request->liquid_id);

        $this->authorize('inputActLogBook', $liquid);

        $actLogBook = new ActivityLogBook([
            'resolusi' => $request->resolusi,
            'nama_kegiatan' => $request->nama_kegiatan,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
            'tempat_kegiatan' => $request->tempat_kegiatan,
            'keterangan' => $request->deskripsi,
        ]);

        $logbook = $liquid->logBook()->save($actLogBook);

        foreach ($request->file('dokumen') as $file) {
            if ($file instanceof UploadedFile) {
                $logbook->addMedia($file)->toMediaLibrary();
            }
        }

        Activity::log('[LIQUID] Input Activity Log', 'success');

        return redirect()
            ->route('activity-log.index')
            ->with('success', 'Berhasil Membuat Activity Log Book');
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
        $this->authorize('canAccessActLogBook');

        $data = ActivityLogBook::findOrFail($id);
        $liquid = Liquid::query()->published()->orderBy('feedback_start_date', 'desc')->forAtasan(auth()->user())->first();
 
        $resolusi = app(LiquidService::class)->resolusi(auth()->user()->strukturJabatan->pernr, $liquid);

        return view(
            'liquid.activity-log.edit',
            compact(
                'data',
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
    public function update(Update $request, $id)
    {
        $this->authorize('canAccessActLogBook');

        $actLogBook = ActivityLogBook::findOrFail($id);
        $actLogBook->resolusi = $request->resolusi;
        $actLogBook->nama_kegiatan = $request->nama_kegiatan;
        $actLogBook->start_date = Carbon::parse($request->start_date);
        $actLogBook->end_date = Carbon::parse($request->end_date);
        $actLogBook->tempat_kegiatan = $request->tempat_kegiatan;
        $actLogBook->keterangan = $request->deskripsi;
        $actLogBook->save();

        foreach ($request->file('dokumen') as $file) {
            if ($file instanceof UploadedFile) {
                $actLogBook->addMedia($file)->toMediaLibrary();
            }
        }

        Activity::log('[LIQUID] Edit Activity Log', 'success');

        return redirect()
            ->route('activity-log.index')
            ->with('success', 'Berhasil Mengubah Data Activity Log Book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('canAccessActLogBook');

        ActivityLogBook::findOrFail($id)->delete();

        Activity::log('[LIQUID] Hapus Activity Log', 'success');

        return redirect()
            ->route('activity-log.index')
            ->with('success', 'Berhasil Menghapus Activity Log Book');
    }
}
