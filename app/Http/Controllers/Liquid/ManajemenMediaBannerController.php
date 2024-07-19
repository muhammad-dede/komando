<?php

namespace App\Http\Controllers\Liquid;

use App\Enum\MediaKitJenis;
use App\Enum\MediaKitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MediaKit\Store;
use App\Http\Requests\MediaKit\Update;
use App\Models\MediaKit;
use Illuminate\Http\UploadedFile;

class ManajemenMediaBannerController extends Controller
{
    public function index()
    {
        $items = MediaKit::query()
            ->orderBy('created_at','desc')->get();

        return view(
            'liquid.manajemen-banner-background.index',
            compact('items')
        );
    }

    public function create()
    {
        $item = new MediaKit();
        $jenis = MediaKitJenis::toDropdownArray();
        $status = MediaKitStatus::toDropdownArray();

        return view(
            'liquid.manajemen-banner-background.create',
            compact('item', 'jenis', 'status')
        );
    }

    public function store(Store $request)
    {
        $media = MediaKit::create($request->only('judul', 'jenis', 'status'));
        foreach ($request->file('media') as $file) {
            if ($file instanceof UploadedFile) {
                $media->addMedia($file)->toMediaLibrary();
            }
        }

        return redirect()->route('manajemen-media-banner.index')->with('success', 'Media berhasil disimpan');
    }

    public function edit($id)
    {
        $item = MediaKit::findOrFail($id);
        $jenis = MediaKitJenis::toDropdownArray();
        $status = MediaKitStatus::toDropdownArray();

        return view(
            'liquid.manajemen-banner-background.edit',
            compact('item', 'jenis', 'status')
        );
    }

    public function update(Update $request, $id)
    {
        $item = MediaKit::findOrFail($id);
        $item->update($request->only('judul', 'jenis', 'status'));

        foreach ($request->file('media', []) as $file) {
            if ($file instanceof UploadedFile) {
                $item->addMedia($file)->toMediaLibrary();
            }
        }

        return redirect()->back()->with('success', 'Media berhasil diperbarui');
    }

    public function show($id)
    {
        $item = MediaKit::findOrFail($id);
        return view(
            'liquid.manajemen-banner-background.show',
            compact('item')
        );
    }

    public function destroy($id)
    {
        MediaKit::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Masteer Media berhasil dihapus');
    }
}
