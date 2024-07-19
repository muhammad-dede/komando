<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\SurveyQuestion;

class SurveyQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.survey_question_list');
    }

    public function getList(Request $request)
    {
        $search = $request->search['value'];
        $columns = $request->columns;
        $order_column = !empty($request->order[0]['column']) ? $columns[$request->order[0]['column']]['data']: "created_at";
        $order_dir = $request->order[0]['dir'];
        $start = $request->start;
        $length = $request->length;

        $data = SurveyQuestion::select("*");
        $dataFilter = DB::table('survey_question');

        if(!empty($search)){
            $data->where('question', 'like', '%'.$search.'%');

            $dataFilter->where('question', 'like', '%'.$search.'%');
        }

        $data->orderBy($order_column, $order_dir)->skip($start)->take($length);
        $result = $data->get();
        $totalData = SurveyQuestion::count();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['action'] = url('master-data/survey-question/store');
        $data['edit'] = "";
        $data['title'] = "Tambah";
        return view('master.survey_question_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request->question)){
            return redirect()->back()->with('error', 'Transalasi tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            // jika status aktif
            if($request->status == "1"){
                $upStatus = SurveyQuestion::where('status','1')->update(['status' => '0']);
            }

            $data = new SurveyQuestion;
            $data->question = $request->question;
            $data->status = $request->status;
            $data->created_by = Auth::user()->id;
            $result = $data->save();
            
            DB::commit();
            return redirect('master-data/survey-question')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = SurveyQuestion::findOrFail($id);
        $data['title'] = "Edit";
        $data['edit'] = $edit;
        $data['action'] = url('master-data/survey-question/edit').'/'.$edit->id;

        return view('master.survey_question_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(empty($request->question)){
            return redirect()->back()->with('error', 'Transalasi tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            // jika status aktif
            if($request->status == "1"){
                $upStatus = SurveyQuestion::where('status','1')->update(['status' => '0']);
            }

            $data = SurveyQuestion::findOrFail($id);
            $data->question = $request->question;
            $data->status = $request->status;
            $data->modified_by = Auth::user()->id;
            $result = $data->save();
            
            DB::commit();
            return redirect('master-data/survey-question')->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
