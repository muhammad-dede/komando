<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;

class PegawaiController extends Controller
{
    public function index()
    {
        $term = strtoupper(request('term'));

        $response = [
            'results' => [],
            'pagination' => false,
        ];

        $data = collect(
            DB::table('m_struktur_jabatan')
                ->join('pa0001', 'pa0001.pernr', '=', 'm_struktur_jabatan.pernr')
                ->whereIn('pa0001.PERSG', [1, 0])
                ->where(function ($query) use ($term) {
                    if ($term) {
                        $query->where('sname', 'like', "%$term%")
                            ->orWhere('nip', 'like', "%$term%");
                    }
                })
                ->limit(100)
                ->orderBy('sname')
                ->get()
        );
        foreach ($data as $person) {
            $response['results'][] = [
                'id' => $person->pernr,
                'text' => sprintf('%s - %s', $person->nip, $person->sname),
            ];
        }

        return response()->json($response);
    }

    public function selfService()
    {
        $search = strtoupper(request('search'));
        $idLiquid = request('idLiquid');
        $pernr = request('pernr');

        $response = [
            'results' => [],
            // 'pagination' => false,
        ];

        if(empty($idLiquid)) {
            return response()->json($response);
        }

        $liquid = Liquid::findOrFail($idLiquid);
        $pesertaSnapshot = $liquid->peserta_snapshot;

        $lanjut = true;
        $i = 0;
        $liquidPeserta = LiquidPeserta::query()    
        ->where('bawahan_id', $pernr)
        ->where('liquid_id', $idLiquid)
        ->get();
        $dataResponse = array();
        foreach ($liquidPeserta as $key => $value) {
            $atasan_id = $value->atasan_id;
            while($lanjut) {
                $searchAtasan = LiquidPeserta::query()    
                ->where('bawahan_id', $atasan_id)
                ->where('liquid_id', $idLiquid)
                ->first();
                if(!empty($searchAtasan)){
                    $dataResponse[$i] = [
                        'id' => $searchAtasan->id,
                        'nama' => $searchAtasan->snapshot_nama_atasan,
                        'nip' => $searchAtasan->snapshot_nip_atasan,
                        'jabatan' =>$searchAtasan->snapshot_jabatan2_atasan,
                    ];
                    $i++;
                    $atasan_id = $searchAtasan->atasan_id;
                }else{
                    $lanjut = false;
                }
            }
        }

        $i = 0;
        foreach ($dataResponse as $key => $atasan) {
            if($this->like_match('%'.$search.'%', $atasan['nama']) || $this->like_match('%'.$search.'%', $atasan['nip'])) {
                $response['results'][$i] = [
                    'id' => $atasan['id'],
                    'text' => sprintf('%s - %s (%s)', $atasan['nip'], $atasan['nama'], $atasan['jabatan']),
                ];
                $i++;
            }
        }
        
        return response()->json($response);
    }

    public function selfService_new_1()
    {
        $search = strtoupper(request('search'));
        $idLiquid = request('idLiquid');
        $pernr = request('pernr');

        $response = [
            'results' => [],
            // 'pagination' => false,
        ];

        if(empty($idLiquid)) {
            return response()->json($response);
        }

        $liquid = Liquid::findOrFail($idLiquid);
        $pesertaSnapshot = $liquid->peserta_snapshot;
        $i = 0;
        foreach ($pesertaSnapshot as $keyPernr => $atasan) {
            if($this->like_match('%'.$search.'%', $atasan['nama']) || $this->like_match('%'.$search.'%', $atasan['nip'])) {
                $response['results'][$i] = [
                    'id' => $keyPernr,
                    'text' => sprintf('%s - %s (%s)', $atasan['nip'], $atasan['nama'], $atasan['jabatan']),
                ];
                $i++;
            }
        }

        return response()->json($response);
    }

    function like_match($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }
}
