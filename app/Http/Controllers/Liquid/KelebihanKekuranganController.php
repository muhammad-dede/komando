<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelebihanKekuranganRequest;
use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use Illuminate\Support\Facades\DB;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\Enum\PilarUtamaEnum;

class KelebihanKekuranganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = KelebihanKekurangan::all();
        $data = $data->load('details')->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->title,
                    'childs' => $item->details->count(),
                    'status' => $item->status,
                ];
            })
            ->toArray();

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);

        return view('liquid.kelebihan-kekurangan.index', compact('data','kelebihan','kekurangan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $categories = PilarUtamaEnum::toDropdownArray();

        return view('liquid.kelebihan-kekurangan.create', compact('kelebihan', 'kekurangan', 'categories'));
    }

    public function feedback()
    {
        return view('slicing.feedback');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KelebihanKekuranganRequest $request)
    {
        DB::transaction(function () use ($request) {
            $kk = new KelebihanKekurangan();
            $kk->title = $request->judul_kk;
            $kk->deskripsi = $request->deskripsi_kk;
            $kk->status = $request->status;
            $kk->save();

            for ($i = 1; $i <= (int) $request->index_kelebihan; $i++) {
                $details = new KelebihanKekuranganDetail(
                    [
                        'kelebihan' => $request->get("judul_kelebihan_$i"),
                        'kekurangan' => $request->get("judul_kekurangan_$i"),
                        'deskripsi_kelebihan' => $request->get("deskripsi_kelebihan_$i"),
                        'deskripsi_kekurangan' => $request->get("deskripsi_kekurangan_$i"),
                        'category' => $request->get("category_$i"),
                    ]
                );
                $kk->details()->save($details);
            }
        });

        return redirect()->route('master-data.kelebihan-kekurangan.index')
            ->with('success', 'Berhasil Menambahkan Data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = KelebihanKekurangan::findOrFail($id);
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);

        return view('liquid.kelebihan-kekurangan.show', compact('data', 'kelebihan', 'kekurangan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = KelebihanKekurangan::with('details')->findOrFail($id);
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $categories = PilarUtamaEnum::toDropdownArray();

        return view('liquid.kelebihan-kekurangan.edit', compact('data', 'kelebihan', 'kekurangan', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KelebihanKekuranganRequest $request, $id)
    {
        $updatedId = [];
        DB::transaction(function () use ($request, $id, $updatedId) {
            $kk = KelebihanKekurangan::findOrFail($id);
            $kk->title = $request->judul_kk;
            $kk->deskripsi = $request->deskripsi_kk;
            $kk->status = $request->status;
            $kk->save();
            $kkDetailsId = $kk->details()->pluck('id')->toArray();

            for ($i = 1; $i <= (int) $request->index_kelebihan; $i++) {
                if ($request->has("id_child_$i")) {
                    $details = KelebihanKekuranganDetail::find((int) $request->{"id_child_$i"});
                    $details->update(
                        [
                            'deskripsi_kelebihan' => $request->get("deskripsi_kelebihan_$i"),
                            'deskripsi_kekurangan' => $request->get("deskripsi_kekurangan_$i"),
                            'category' => $request->get("category_$i"),
                        ]
                    );
                    $updatedId[] = $request->{"id_child_$i"};
                } else {
                    $details = new KelebihanKekuranganDetail(
                        [
                            'kelebihan' => $request->get("judul_kelebihan_$i"),
                            'kekurangan' => $request->get("judul_kekurangan_$i"),
                            'deskripsi_kelebihan' => $request->get("deskripsi_kelebihan_$i"),
                            'deskripsi_kekurangan' => $request->get("deskripsi_kekurangan_$i"),
                            'category' => $request->get("category_$i"),
                        ]
                    );
                    $kk->details()->save($details);
                }
            }

            $kkDetailsId = array_map(function ($id) {
                return intval($id);
            }, $kkDetailsId);

            $updatedId = array_map(function ($id) {
                return intval($id);
            }, $updatedId);

            $deletedId = array_diff($kkDetailsId, $updatedId);

            if (count($deletedId) < 1000) {
                KelebihanKekuranganDetail::query()
                    ->whereIn('id', $deletedId)
                    ->delete();
            } else {
                foreach ($deletedId as $id) {
                    KelebihanKekuranganDetail::find($id)
                        ->delete();
                }
            }
        });

        return redirect()->route('master-data.kelebihan-kekurangan.index')
            ->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $liquidExists = Liquid::where('kelebihan_kekurangan_id', $id)->exists();
        if ($liquidExists) {
            return redirect()->back()
                ->with('warning', 'Master Kelebihan dan Kekurangan tidak bisa dihapus karena ada Liquid yang memakainya.');
        }

        $data = KelebihanKekurangan::findOrFail($id);

        DB::transaction(function () use ($data) {
            $data->details()->delete();
            $data->delete();
        });

        return redirect()->route('master-data.kelebihan-kekurangan.index')
            ->with('success', 'Data Berhasil Dihapus');
    }
}
