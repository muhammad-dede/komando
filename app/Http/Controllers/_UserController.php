<?php

namespace App\Http\Controllers\Pengguna;

use App\AreaUser;
use App\BusinessArea;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userlist = User::all();
        return view('user.user_list', compact('userlist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $modules = Module::all()->sortBy('id');

        $user   = User::findOrFail($id);
        $roles  = Role::all()->sortBy('id');

        return view('pengguna.user_display', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user   = User::findOrFail($id);
        $roles  = Role::all()->sortBy('id');
        foreach(BusinessArea::all()->sortBy('id') as $wa){
            $bsAreaList[$wa->id] = $wa->business_area.' - '.$wa->description;
        }

        return view('pengguna.user_edit', compact('user', 'roles', 'bsAreaList'));
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
        $user       = User::findOrFail($id);

        $area_user  = AreaUser::where('user_id', $id)->first();
//        dd($area_user);
        if($area_user!=null)
            $area_user->delete();

        $new_area                   = new AreaUser();
        $new_area->id               = $new_area->getLastID();
        $new_area->user_id          = $id;
        $new_area->businessarea_id  = $request->businessarea_id;

//        dd($new_area);
        $new_area->save();

//        $user->areaUser->user_id            = $id;
//        $user->areaUser->businessarea_id    = $request->business_area;
//        $user->areaUser->save();

//        $roles  = Role::all()->sortBy('id');
//
//        foreach($roles as $role){
//            $user->roles()->attach($request->get($role));
//        }

        $user->roles()->sync([]);

        $roles  = $request->roles;
//        dd($roles);
        for($x=0;$x<count($roles);$x++){
            $user->roles()->attach($roles[$x]);
        }

        return redirect('/user/'.$user->id)->with('success', 'User berhasil disimpan');
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
