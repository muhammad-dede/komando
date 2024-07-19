<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Liquid\LiquidPeserta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Liquid\Liquid;

class WidgetController extends Controller
{
    public function selfService(Request $request){
        $search = $request->search['value'];
        $columns = $request->columns;
        $order_column = !empty($request->order[0]['column']) ? $columns[$request->order[0]['column']]['data']: "id";
        $order_dir = $request->order[0]['dir'];
        $start = !empty($request->start) ? $request->start : 0;
        $length = $request->length;
        $divisi = $request->divisi;
        $unit_code = $request->unit_code;
        $year = $request->year;

        $data = Liquid::query()
            ->select('*')
            ->leftJoin('liquid_peserta', 'liquids.id','=','liquid_peserta.liquid_id')
            ->with('logBook')
            ->activeForUnit($unit_code)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'");
            })
            ->where('flag_self_service','=', 1);

        $dataFilter = Liquid::query()
        ->leftJoin('liquid_peserta', 'liquids.id','=','liquid_peserta.liquid_id')
        ->with('logBook')
        ->activeForUnit($unit_code)
        ->when($year, function ($query) use ($year) {
            return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'");
        })
        ->where('flag_self_service','=', 1);

        if(!empty($search)){
            $data->whereHas('peserta', function($query) use($search) {
                $query->where('SNAPSHOT_NAMA_ATASAN', 'like', '%'.$search.'%');
                $query->orWhere('SNAPSHOT_NAMA_BAWAHAN', 'like', '%'.$search.'%');
            });

            $dataFilter->whereHas('peserta', function($query) use($search) {
                $query->where('SNAPSHOT_NAMA_ATASAN', 'like', '%'.$search.'%');
                $query->orWhere('SNAPSHOT_NAMA_BAWAHAN', 'like', '%'.$search.'%');
            });
        }
        if(!empty($divisi)) {
            $data->whereHas('peserta', function($query) use($unit_code) {
                $query->where('snapshot_orgeh_1', '=', $unit_code);
                $query->orWhere('snapshot_orgeh_2', '=', $unit_code);
                $query->orWhere('snapshot_orgeh_3', '=', $unit_code);
            });
            

            $dataFilter->whereHas('peserta', function($query) use($unit_code) {
                $query->where('snapshot_orgeh_1', '=', $unit_code);
                $query->orWhere('snapshot_orgeh_2', '=', $unit_code);
                $query->orWhere('snapshot_orgeh_3', '=', $unit_code);
            });
        }

        // kode eloquent atau query builder atau semacamnya
        $result = $data->skip($start)->take($length)->get();
        $totalData = Liquid::query()
            ->with('logBook')
            ->activeForUnit($unit_code)
            ->whereHas('peserta', function($query) {
                $query->where('flag_self_service','=', 1);
            })->count();
        $totalFiltered = $dataFilter->count();

        $response = array(
            "draw"            => isset($request->draw) ? intval($request->draw) : 0,
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $result
        );

        return json_encode($response);
    }
}
