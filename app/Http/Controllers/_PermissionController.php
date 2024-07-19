<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Module;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Permission::all()->sortBy('id');

        return view('pengguna.permission', compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr_module = Module::all()->sortBy('id')->lists('display_name','id');
//        dd($arr_module);
        return view('pengguna.permission_create', compact('arr_module'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        //
        $permission = new Permission();
        $permission->id             = $permission->getLastID();
        $permission->module_id      = $request->module_id;
        $permission->name           = $request->name;
        $permission->display_name   = $request->display_name;
        $permission->description    = $request->description;
        $permission->save();

        return redirect('/user/permission/create')->with('success', 'Permission berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        $arr_module = Module::all()->sortBy('id')->lists('display_name','id');

        return view('pengguna.permission_display', compact('permission', 'arr_module'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $arr_module = Module::all()->sortBy('id')->lists('display_name','id');

        return view('pengguna.permission_edit', compact('permission', 'arr_module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, $id)
    {
        //
        $permission = Permission::findOrFail($id);
        $permission->module_id      = $request->module_id;
        $permission->name           = $request->name;
        $permission->display_name   = $request->display_name;
        $permission->description    = $request->description;
        $permission->save();

        return redirect('/user/permission/'.$id)->with('success', 'Permission berhasil disimpan.');
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
