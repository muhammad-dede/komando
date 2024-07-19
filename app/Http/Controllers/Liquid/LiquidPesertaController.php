<?php

namespace App\Http\Controllers\Liquid;

use App\Activity;
use App\Enum\LiquidJabatan;
use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Services\LiquidService;
use App\StrukturJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LiquidPesertaController extends Controller
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
        $listPeserta = $this->groupPesertaByJabatan(app(LiquidService::class)->listPeserta($liquid));

        // Default to empty, dropdoown options will populated using Ajax
        $listAtasan = $listBawahan = [];

        $listJabatan = LiquidJabatan::toDropdownArray();

        return view('liquid.liquid-peserta.edit', compact('liquid', 'listAtasan', 'listBawahan', 'listPeserta', 'listJabatan'));
    }

    public function show(Liquid $liquid)
    {
        $listAtasan = $this->formatDropdownPeserta(app(LiquidService::class)->listAtasan($liquid, true));
        $listAtasanLengkap = $this->formatDropdownPeserta(app(LiquidService::class)->listAtasan($liquid));
        $listPeserta = $this->groupPesertaByJabatan(app(LiquidService::class)->listPeserta($liquid));
        $listBawahan = [];
        foreach ($listPeserta as $atasanId => $atasan) {
            $listBawahan[$atasanId] = $this->formatDropdownPeserta(app(LiquidService::class)->listBawahan(
                $liquid,
                $atasanId
            ));
        }

        return view(
            'liquid.liquid-peserta.show',
            compact('liquid', 'listAtasanLengkap', 'listAtasan', 'listBawahan', 'listPeserta')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Liquid $liquid
     *
     * @return void
     */
    public function store(Request $request, Liquid $liquid)
    {
        if ($request->has('atasan_id')) {
            $atasan = StrukturJabatan::findOrFail($request->get('atasan_id'));
            $peserta = $request->input('bawahan', []);
            if (Gate::denies('liquidPesertaExist', [$liquid, $request])) {
                return redirect()->back()->withError('Peserta gagal ditambahkan, data sudah ada!');
            }
            app(LiquidService::class)->tambahPeserta($liquid, $peserta, $atasan, $request->input('jabatan'));
        } else {
            $peserta = $request->input('atasan', []);
            if (Gate::denies('liquidPesertaAtasanExist', [$liquid, $request])) {
                return redirect()->back()->withError('Peserta gagal ditambahkan, atasan sudah ada!');
            }
            app(LiquidService::class)->tambahAtasanDanGenerateBawahan($liquid, $peserta, $request->input('jabatan'));
        }

        if (!empty($peserta)) {
            return redirect()->back()->withSuccess('Peserta berhasil ditambahkan');
        }

        Activity::log('[LIQUID] Tambah Peserta Bawahan', 'success');

        return redirect()
            ->back()
            ->withWarning('Silakan pilih atasan/bawahan untuk ditambahkan sebagai peserta!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Liquid $liquid
     *
     * @return void
     */
    public function update(Request $request, Liquid $liquid)
    {
        $atasanLama = StrukturJabatan::find($request->get('atasan_lama'));
        $atasanBaru = StrukturJabatan::find($request->get('atasan_baru'));

        if ($atasanLama === null) {
            return redirect()->route('liquid.peserta.edit', $liquid)->withError('Data atasan lama tidak valid');
        }

        if ($atasanBaru === null) {
            return redirect()->route('liquid.peserta.edit', $liquid)->withError('Data atasan baru tidak valid');
        }

        app(LiquidService::class)->gantiAtasan($liquid, $atasanLama, $atasanBaru);

        return redirect()->route('liquid.peserta.edit', $liquid)->withSuccess('Peserta berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liquid $liquid, $id)
    {
        $action = request('action');
        switch ($action) {
            case 'atasan':
                app(LiquidService::class)->hapusAtasan($liquid, $id);

                return redirect()->back()->withSuccess('Atasan berhasil dihapus');

                break;
            case 'bulk':
                app(LiquidService::class)->hapusPesertaBulk($liquid, request('pesertaIds', []));

                return redirect()->back()->withSuccess('Peserta berhasil dihapus');

                break;
            default:
                $peserta = LiquidPeserta::findOrFail($id);
                app(LiquidService::class)->hapusPeserta($liquid, $peserta);

                return redirect()->back()->withSuccess('Peserta berhasil dihapus');

                break;
        }
    }

    protected function formatDropdownPeserta($peserta)
    {
        $dropdown = [];
        foreach ($peserta->sortBy('sname') as $person) {
            $dropdown[$person->pernr] = sprintf('%s - %s', $person->nip, $person->sname);
        }

        return $dropdown;
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
}
