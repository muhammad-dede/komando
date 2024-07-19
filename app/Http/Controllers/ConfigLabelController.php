<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConfigLabel;

class ConfigLabelController extends Controller
{
    function __construct()
    {
        $this->model = ConfigLabel::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.config_label_list');
    }

    public function getList(Request $request) 
    {
        $search = $request->search['value'];
        $columns = $request->columns;
        $order_column = !empty($request->order[0]['column']) ? $columns[$request->order[0]['column']]['data']: "created_at";
        $order_dir = $request->order[0]['dir'];
        $start = $request->start;
        $length = $request->length;

        $data = DB::table('config_label')
            ->select("*");
        $dataFilter = DB::table('config_label');

        if(!empty($search)){
            $data->where('keys', 'like', '%'.$search.'%');
            $data->orWhere('transalasi', 'like', '%'.$search.'%');

            $dataFilter->where('keys', 'like', '%'.$search.'%');
            $dataFilter->orWhere('transalasi', 'like', '%'.$search.'%');
        }

        $data->orderBy($order_column, $order_dir)->skip($start)->take($length);
        $result = $data->get();
        $totalData = ConfigLabel::count();
        $totalFiltered = $dataFilter->count();

        foreach($result as $key => $val){
            $val->no = $key+$start+1;
            $new_status = "Tidak Aktif";
            if($val->status == '1'){
                $new_status = "Aktif";
            }
            $val->status = $new_status;
        }

        $response = array(
            "draw"            => isset($request->draw) ? intval($request->draw) : 0,
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $result
        );

        return json_encode($response);
    }

    public function create()
    {
        $data['action'] = url('master-data/config-label/store');
        $data['edit'] = "";
        $data['title'] = "Tambah";
        return view('master.config_label_form', $data);
    }

    public function store(Request $request)
    {
        if(empty($request->keys)){
            return redirect()->back()->with('error', 'Key tidak boleh kosong.');
        }
        if(empty($request->translasi)){
            return redirect()->back()->with('error', 'Translasi tidak boleh kosong.');
        }
        if(empty($request->sort_translasi)){
            return redirect()->back()->with('error', 'Sort Translasi tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            // jika status aktif
            if($request->status == "1"){
                $upStatus = ConfigLabel::where('keys', $request->keys)->where('status','1')->update(['status' => '0']);
            }

            $data = new ConfigLabel;
            $data->keys = $request->keys;
            $data->translasi = $request->translasi;
            $data->sort_translasi = $request->sort_translasi;
            $data->status = $request->status;
            $data->created_by = Auth::user()->id;
            $result = $data->save();
            
            DB::commit();
            return redirect('master-data/config-label')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    public function edit($id)
    {
        $edit = ConfigLabel::findOrFail($id);
        $data['title'] = "Edit";
        $data['edit'] = $edit;
        $data['action'] = url('master-data/config-label/edit').'/'.$edit->id;

        return view('master.config_label_form', $data);
    }

    public function update(Request $request, $id) 
    {
        if(empty($request->keys)){
            return redirect()->back()->with('error', 'Key tidak boleh kosong.');
        }
        if(empty($request->translasi)){
            return redirect()->back()->with('error', 'Translasi tidak boleh kosong.');
        }
        if(empty($request->sort_translasi)){
            return redirect()->back()->with('error', 'Sort Translasi tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            // jika status aktif
            if($request->status == "1"){
                $upStatus = ConfigLabel::where('keys', $request->keys)->where('status','1')->update(['status' => '0']);
            }

            $data = ConfigLabel::findOrFail($id);
            $data->keys = $request->keys;
            $data->translasi = $request->translasi;
            $data->sort_translasi = $request->sort_translasi;
            $data->status = $request->status;
            $data->modified_by = Auth::user()->id;
            $result = $data->save();

            DB::commit();
            return redirect('master-data/config-label')->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal diubah');
        }
    }
}