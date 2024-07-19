<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\LiquidService;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use Illuminate\Support\Facades\DB;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\User;
use App\Notification;
use Carbon\Carbon;

class AddAtasanController extends Controller
{
    public function index() 
    {
        $jadwalLiquid_v2 = app(LiquidService::class)->jadwalBawahanTahunAktif(auth()->user());
        $btnActive = 'add-atasan';
        $idLiquid = (empty($jadwalLiquid_v2)) ? "" : $jadwalLiquid_v2['id'];

        $listAtasan = $listBawahan = [];

        $label = new ConfigLabelHelper;
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view('liquid.dashboard-bawahan.add-atasan', compact('jadwalLiquid_v2', 'btnActive', 'idLiquid', 'listAtasan', 'usulan_atasan'));
    }

    public function tableAddAtasan(Request $request)
    {
        $search = $request->search['value'];
        $columns = $request->columns;
        $order_column = !empty($request->order[0]['column']) ? $columns[$request->order[0]['column']]['data']: "snapshot_nama_atasan";
        $order_dir = $request->order[0]['dir'];
        $start = $request->start;
        $length = $request->length;

        $data = LiquidPeserta::query()    
        ->where('bawahan_id', auth()->user()->strukturJabatan->pernr);
        $dataFilter = LiquidPeserta::query()    
        ->where('bawahan_id', auth()->user()->strukturJabatan->pernr);

        // add filter when you have already
        if(!empty($request->idLiquid)){
            $data->where('liquid_id', $request->idLiquid);
            $dataFilter->where('liquid_id', $request->idLiquid);
        }

        $data->orderBy($order_column, $order_dir)->skip($start)->take($length);
        $result = $data->get();
        $totalData = LiquidPeserta::query()->where('bawahan_id', auth()->user()->strukturJabatan->pernr)->where('liquid_id', $request->idLiquid)->count();
        $totalFiltered = $dataFilter->count();

        $response = array(
            "draw"            => isset($request->draw) ? intval($request->draw) : 0,
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $result
        );

        return json_encode($response);
    }

    public function saveAtasan(Request $request)
    {
        $idLiquidPeserta = $request->idLiquidPeserta;
        if(empty($idLiquidPeserta)) {
            $jsons = array();
            $jsons['status'] = false;
            $jsons['msg'] = "Atasan tidak boleh kosong";
            return json_encode($jsons);
        }

        $pernrUserLogin = $request->pernr;
        if(empty($pernrUserLogin)) {
            $jsons = array();
            $jsons['status'] = false;
            $jsons['msg'] = "Jabatan tidak ditemukan";
            return json_encode($jsons);
        }
        $idLiquid = $request->idLiquid;
        if(empty($idLiquid)) {
            $jsons = array();
            $jsons['status'] = false;
            $jsons['msg'] = "Jabatan tidak ditemukan";
            return json_encode($jsons);
        }

        DB::beginTransaction();
        try {
            $dataAtasan = LiquidPeserta::findOrFail($idLiquidPeserta);
            $liquid = Liquid::findOrFail($idLiquid);
            $user = User::where('id', '=', $liquid->created_by)->first();
            $snapshotAtasan = DB::table('v_snapshot_pegawai')->where('pernr', $dataAtasan->atasan_id)->first();
            $snapshotBawahan = DB::table('v_snapshot_pegawai')->where('pernr', $pernrUserLogin)->first();

            $dataPeserta = [
                'liquid_id' => $idLiquid,
                'atasan_id' => $snapshotAtasan->pernr,
                'bawahan_id' => $pernrUserLogin,
                'SNAPSHOT_JABATAN_ATASAN' => $dataAtasan->snapshot_jabatan_atasan,
                'SNAPSHOT_NAMA_ATASAN' => data_get($snapshotAtasan, 'nama'),
                'SNAPSHOT_NIP_ATASAN' => data_get($snapshotAtasan, 'nip'),
                'SNAPSHOT_JABATAN2_ATASAN' => data_get($snapshotAtasan, 'jabatan'),
                'SNAPSHOT_JABATAN_BAWAHAN' => null,
                'SNAPSHOT_NAMA_BAWAHAN' => data_get($snapshotBawahan, 'nama'),
                'SNAPSHOT_NIP_BAWAHAN' => data_get($snapshotBawahan, 'nip'),
                'SNAPSHOT_JABATAN2_BAWAHAN' => data_get($snapshotBawahan, 'jabatan'),
                'SNAPSHOT_UNIT_CODE' => data_get($snapshotAtasan, 'unit_code'),
                'SNAPSHOT_UNIT_NAME' => data_get($snapshotAtasan, 'unit_name'),
                'SNAPSHOT_PLANS' => data_get($snapshotAtasan, 'plans'),
                'SNAPSHOT_ORGEH_1' => data_get($snapshotAtasan, 'orgeh1'),
                'SNAPSHOT_ORGEH_2' => data_get($snapshotAtasan, 'orgeh2'),
                'SNAPSHOT_ORGEH_3' => data_get($snapshotAtasan, 'orgeh3'),
                'flag_self_service' => 1
            ];
            $filterExists = [
                'liquid_id' => $idLiquid,
                'atasan_id' => $snapshotAtasan->pernr,
                'bawahan_id' => $pernrUserLogin,
            ];

            if (!LiquidPeserta::where($filterExists)->exists()) {
                $peserta = new LiquidPeserta();
                $peserta->fill($dataPeserta);
                $peserta->save();

                $liquid->generatePesertaSnapshot();

                // add notification
                $to = $user->name;
                $user_id_to = $user->id;
                $subject = 'Informasi Usulan Atasan';
                $message = data_get($snapshotBawahan, 'nama')." mendaftarkan usulan atasan!!!";
                $url = "dashboard-admin/liquid-jadwal?unit_code=".data_get($snapshotAtasan, 'unit_code').'&divisi=&year='.Carbon::now()->format('Y');
                $notification = new Notification();
                $saved = $notification->sendBySystem($user_id_to, $to, $subject, $message, $url);

                DB::commit();
                $jsons = array();
                $jsons['status'] = true;
                $jsons['msg'] = "success";
                return json_encode($jsons);
            }else{
                $jsons = array();
                $jsons['status'] = false;
                $jsons['msg'] = "Data Atasan is Already";
                return json_encode($jsons);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $jsons = array();
            $jsons['status'] = false;
            $jsons['msg'] = $ex;
            return json_encode($jsons);
        }
    }
}
