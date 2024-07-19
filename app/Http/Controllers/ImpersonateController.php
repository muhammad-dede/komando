<?php

namespace App\Http\Controllers;

use App\Helpers\ValidateCV;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:impersonate');
    }

    /**
     * Impersonate the given user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function impersonate(User $user)
    {
//        dd($user);
        if ($user->id !== ($original = Auth::user()->id)) {
            session()->forget('menu_liquid');
            session()->put('original_user', $original);

            if (env('VALIDASI_CV_ENABLE', false)) {
                // destroy session validasi cv
                session()->forget('validatecv');
                // destroy session list validasi cv
                session()->forget('list_validasi_cv');
                
                $validasi = new ValidateCV();
                $validasi->validate($user);
            }

            auth()->login($user);
        }

        return redirect('/');
    }

    public function impersonateNIP($nip)
    {
        $user = User::where('nip', $nip)->where('status', 'ACTV')->first();

        if($user!=null){
            if ($user->id !== ($original = Auth::user()->id)) {
                session()->forget('menu_liquid');
                session()->put('original_user', $original);

                if (env('VALIDASI_CV_ENABLE', false)) {
                    // destroy session validasi cv
                    session()->forget('validatecv');
                    // destroy session list validasi cv
                    session()->forget('list_validasi_cv');
                    
                    $validasi = new ValidateCV();
                    $validasi->validate($user);
                }    

                auth()->login($user);
            }

            return redirect('/');
        }

        return redirect()->back()->with('error', 'NIP tidak ditemukan.');
    }

    public function impersonateUsername($username)
    {
        $user = User::where('username', $username)->where('status', 'ACTV')->first();

        if($user!=null){
            if ($user->id !== ($original = Auth::user()->id)) {
                session()->forget('menu_liquid');
                session()->put('original_user', $original);

                if (env('VALIDASI_CV_ENABLE', false)) {
                    // destroy session validasi cv
                    session()->forget('validatecv');
                    // destroy session list validasi cv
                    session()->forget('list_validasi_cv');
                    
                    $validasi = new ValidateCV();
                    $validasi->validate($user);
                }    

                auth()->login($user);
            }

            return redirect('/');
        }

        return redirect()->back()->with('error', 'Username tidak ditemukan.');
    }

    /**
     * Revert to the original user.
     *
     * @return \Illuminate\Http\Response
     */
    public function revert()
    {
        if (env('VALIDASI_CV_ENABLE', false)) {
            // destroy session validasi cv
            session()->forget('validatecv');
            // destroy session list validasi cv
            session()->forget('list_validasi_cv');
            
            $validasi = new ValidateCV();
            $user = User::find(session()->get('original_user'));
            $validasi->validate($user);
        }

        auth()->loginUsingId(session()->get('original_user'));

        session()->forget('menu_liquid');
        session()->forget('original_user');

        return redirect('/');
    }
}
