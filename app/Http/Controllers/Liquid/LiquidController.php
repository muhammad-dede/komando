<?php

namespace App\Http\Controllers\Liquid;

use App\Enum\ReminderAksiResolusi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Liquid\Liquid\Store;
use App\Http\Requests\Liquid\Liquid\Update;
use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;

class LiquidController extends Controller
{
    public function index()
    {
        $jadwalLiquid = app(LiquidService::class)->jadwal(auth()->user());
        $btnActive = 'kalendar-liquid';

        return view('liquid.dashboard-admin.kalendar-liquid', compact('btnActive', 'jadwalLiquid'));
    }

    public function create()
    {
        $reminderOptions = ReminderAksiResolusi::toDropdownArray();
        $kelebihanKekuranganId = KelebihanKekurangan::getActiveId();

        return view('liquid.liquid.create', compact('reminderOptions', 'kelebihanKekuranganId'));
    }

    public function store(Store $request)
    {
        $liquid = Liquid::createByFormInput($request->all());

        return redirect()->route('liquid.unit-kerja.edit', $liquid);
    }

    public function edit(Liquid $liquid)
    {
        $reminderOptions = ReminderAksiResolusi::toDropdownArray();

        return view('liquid.liquid.edit', compact('liquid', 'reminderOptions'));
    }

    public function show(Liquid $liquid)
    {
        $reminderOptions = ReminderAksiResolusi::toDropdownArray();

        return view('liquid.liquid.show', compact('liquid', 'reminderOptions'));
    }

    public function update(Update $request, Liquid $liquid)
    {
        $liquid->update($request->sanitize());

        return redirect()->route('liquid.unit-kerja.edit', $liquid);
    }

    public function destroy($id)
    {
        Liquid::findOrFail($id)
            ->delete();

        return redirect()->back()->with('success', 'Liquid berhasil dihapus');
    }
}
