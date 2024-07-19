<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Services\Datatable;
use App\Tema;
use App\TemaCoc;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class TemaController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function index()
    {
        $queryCoc = TemaCoc::query();
        $queryMaster = Tema::query();

        $datatableCoc = Datatable::make($queryCoc)
            ->rowView('master.tema_coc_row')
            ->search(function ($builder, $keyword) {
                $keyword = strtolower($keyword);
                $builder->whereHas('tema', function($q) use ($keyword) {
                    $q->where(DB::raw('lower(tema)'), 'like', "%$keyword%");
                })->orWhereHas(('updatedBy'), function($q) use ($keyword) {
                    $q->where(DB::raw('lower(name)'), 'like', "%$keyword%");
                });
            })
            ->columns([
                ['data' => 'tema', 'sortable' => true],
                ['data' => 'start_date', 'sortable' => false],
                ['data' => 'end_date', 'sortable' => false],
                ['data' => 'updated_by', 'sortable' => false],
                ['data' => 'updated_at', 'sortable' => false],
            ]);

        $datatableMaster = Datatable::make($queryMaster)
            ->rowView('master.tema_list_row')
            ->columns([
                ['data' => 'tema', 'sortable' => true, 'searchable' => true],
                ['data' => 'count', 'sortable' => false],
            ]);

        if (\request()->wantsJson()) {
            if (\request('tab', 'coc') === 'coc') {
                return $datatableCoc->toJson();
            }

            return $datatableMaster->toJson();
        }

        return view('master.tema_list', compact('datatableCoc', 'datatableMaster'));
    }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return view('master.tema_create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
//    Tema::create($request->all());
    $tema = new Tema();
    $tema->tema = $request->tema;
    $tema->save();

    Activity::log('Add tema : '.$tema->tema.'.', 'success');

    return redirect('master-data/tema')->with('success','Tema berhasil disimpan.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $tema = Tema::findOrFail($id);
    return view('master.tema_edit', compact('tema'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $tema = Tema::findOrFail($id);
    $tema->update($request->all());

    return redirect('master-data/tema')->with('success','Tema berhasil diubah.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {

  }

}

?>
