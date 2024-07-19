<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use Illuminate\Http\Request;

class LiquidUnitKerjaController extends Controller
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
        $businessAreas = app(LiquidService::class)->listUnitKerja_2(auth()->user());
        $selected = $liquid->businessAreas->pluck('business_area')->toArray();

        return view('liquid.liquid-unit-kerja.edit', compact('liquid', 'businessAreas', 'selected'));
    }

    public function show(Liquid $liquid)
    {
        $businessAreas = app(LiquidService::class)->listUnitKerja(auth()->user());
        $selected = $liquid->businessAreas->pluck('business_area')->toArray();

        return view('liquid.liquid-unit-kerja.show', compact('liquid', 'businessAreas', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Liquid $liquid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Liquid $liquid, Request $request)
    {
        if ($liquid->isPublished()) {
            return redirect()->back()->withError('Aksi tidak dapat dilakukan karena Liquid status = '.$liquid->status);
        }

        app(LiquidService::class)->syncBusinessAreaDanPeserta($liquid, $request->get('business_area', []));

        return redirect()->route('liquid.peserta.edit', $liquid);
    }
}
