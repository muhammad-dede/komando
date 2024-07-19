<?php

namespace App\Http\Controllers;

use App\Module;
use App\Permission;
use App\Role;
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
        $permissions = Permission::all()->sortBy('id');
        return view('user.permission_list', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $arr_module = Module::all()->sortBy('id')->lists('display_name','id');
        $modules = Module::all()->sortBy('id');

//        return view('pengguna.permission_create', compact('arr_module'));
        return view('user.permission_create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $role                   = new Role();
        $role->id               = $role->getLastID();
        $role->name             = $request->name;
        $role->display_name     = $request->display_name;
        $role->description      = $request->description;

        $role->save();

        //save permission
        $modules = Module::all()->sortBy('id');
        foreach($modules as $module){
            $permission = $request->get($module->name);
            for($x=0;$x<count($permission);$x++){
//            dd($permission);
                $role->perms()->attach($permission[$x]);
            }
        }

//        dd($role->perms());

        return redirect('/user/role')->with('success', 'Role berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modules = Module::all()->sortBy('id');

        $role = Permission::findOrFail($id);

        return view('user.role_display', compact('modules', 'role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modules = Module::all()->sortBy('id');

        $role = Permission::findOrFail($id);

        return view('user.role_edit', compact('modules', 'role'));
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
        //
//        $role                   = new Role();
        $role                   = Role::findOrFail($id);
//        $role->id               = $role->getLastID();
        $role->name             = $request->name;
        $role->display_name     = $request->display_name;
        $role->description      = $request->description;

        $role->save();
        $role->perms()->sync([]);

        //save permission
        $modules = Module::all()->sortBy('id');
        foreach($modules as $module){
            $permission = $request->get($module->name);
            for($x=0;$x<count($permission);$x++){
                $role->perms()->attach($permission[$x]);
            }
        }

        return redirect('/user/role/'.$role->id)->with('success', 'Role berhasil disimpan');
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
