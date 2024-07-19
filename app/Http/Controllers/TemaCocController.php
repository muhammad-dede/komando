<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Event;
use App\Http\Requests\TemaRequest;
use App\Tema;
use App\TemaCoc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TemaCocController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(TemaRequest $request)
    {
        $tema = Tema::findOrFail($request->tema_id);
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date . ' 23:00:00');

        // cek apakah sudah ada tema pada tanggal yang dipilih
        $date = clone $start_date;
        if ($start_date->format('d-m-Y') != $end_date->format('d-m-Y')) {
            while ($date->format('d-m-Y') != $end_date->format('d-m-Y')) {
                $tema_find = TemaCoc::whereDate('start_date', '<=', $date->format('Y-m-d'))->whereDate('end_date', '>=', $date->format('Y-m-d'))->first();
                if ($tema_find != null)
                    return redirect('coc')->with('warning', 'Sudah ada tema pada tanggal : ' . $date->format('d F Y'));
//              echo $date->format('Y-m-d').' -> '.@$tema->tema_id."<br>";
                $date->addDay();
            }
            $tema_find = TemaCoc::whereDate('start_date', '<=', $date->format('Y-m-d'))->whereDate('end_date', '>=', $date->format('Y-m-d'))->first();
            if ($tema_find != null) {
                return redirect('coc')->with('warning', 'Sudah ada tema pada tanggal : ' . $date->format('d F Y'));
            }
        } else {
            $tema_find = TemaCoc::whereDate('start_date', '=', $start_date->format('Y-m-d'))->whereDate('end_date', '=', $end_date->format('Y-m-d'))->first();
            if ($tema_find != null) {
                return redirect('coc')->with('warning', 'Sudah ada tema pada tanggal : ' . $date->format('d F Y'));
            }
        }
//    dd($tema);

        $event = new Event();
        $event->title = $tema->tema;
        $event->all_day = '1';
        $event->start = $start_date;
        $event->end = $end_date;
        $event->url = '';
//    $event->color   = '#1bb99a';
        $event->class_name = 'bg-success';
        $event->save();

        $tema_coc = new TemaCoc();
        $tema_coc->tema_id = $request->tema_id;
        $tema_coc->event_id = $event->id;
        $tema_coc->start_date = $start_date;
        $tema_coc->end_date = $end_date;

//        $tema_coc->created_by = Auth::user()->username;
//        $tema_coc->updated_by = Auth::user()->username;

        $tema_coc->user_id_create = Auth::user()->id;
        $tema_coc->user_id_update = Auth::user()->id;

        $tema_coc->save();

        Activity::log('Add tema : '.$tema->tema.' ('.$tema_coc->start_date->format('d/m/Y').'-'.$tema_coc->end_date->format('d/m/Y').')', 'success');

//      dd($tema_coc);
        return redirect('coc')->with('success', 'Tema berhasil ditetapkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $tema_coc = TemaCoc::findOrFail($id);

//    dd($tema_coc->tema->coc);

        return view('coc.tema_coc_display', compact('tema_coc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $tema_coc = TemaCoc::find($id);
        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }
//        dd($tema_coc);
        return view('master.tema_coc_edit', compact('tema_coc', 'tema_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

    public function exportTemaCoc($id)
    {
//    $attendants = Auth::user()->attendant()->orderBy('id', 'desc')->get();

        $tema_coc = TemaCoc::findOrFail($id);

        $list_tema_coc = $tema_coc->tema->coc()->orderBy('id', 'desc')->get();

        Excel::create(date('YmdHis') . '_tema_' . str_replace(' ', '_', strtolower($tema_coc->tema->tema)), function ($excel) use ($tema_coc) {

            $excel->sheet('Tema CoC', function ($sheet) use ($tema_coc) {
                $sheet->loadView('coc/template_tema_coc')->with('tema_coc', $tema_coc);
            });

        })->download('xlsx');
//    })->download('xls');
    }

}

?>