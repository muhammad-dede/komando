<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\Media;

class LiquidDokumenController extends Controller
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
        $dokumen = $liquid->getMedia();

        return view('liquid.liquid-dokumen.edit', compact('liquid', 'dokumen'));
    }

    public function show(Liquid $liquid)
    {
        $dokumen = $liquid->getMedia();

        return view('liquid.liquid-dokumen.show', compact('liquid', 'dokumen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Liquid $liquid
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liquid $liquid)
    {
        $n = 0;
        foreach ($request->file('dokumen') as $file) {
            if ($file instanceof UploadedFile && ($file->getMimeType() === 'application/pdf')) {
                if ($liquid->addMedia($file)->toMediaLibrary()) {
                    $n++;
                }
            }
        }

        return redirect()->back()->withSuccess("$n dokumen berhasil diupload");
    }

    public function destroy(Liquid $liquid, Media $media)
    {
        $media->delete();

        return redirect()->back()->withSuccess('Dokumen berhasil dihapus');
    }
}
