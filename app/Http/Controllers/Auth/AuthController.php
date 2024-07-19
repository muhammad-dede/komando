<?php

namespace App\Http\Controllers\Auth;

use Adldap\Adldap;
use Adldap\Auth\BindException;
use App\Activity;
use App\PA0032;
use App\StrukturJabatan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/auth/login';
    protected $username = 'username2';
//    protected $loginPath = '/auth/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function validateLDAP(Request $request)
    {
//        dd($request);
//        $credentials = $this->getCredentials($request);
        /*Begin Validate LDAP*/

        $user = null;
        //kebutuhan active directory
        $username_ad = $request->get('username');
        $password_ad = $request->get('password');
        //disimpan temporary  jika yg login eksternal
        $username = $request->get('username');
        $password = $request->get('password');
        $detail = explode("\\", $username_ad);
//        if (sizeof($detail) > 1) {
        $domain = strtolower(substr($username_ad, 0, strrpos($username_ad, "\\")));
        $username_ad = substr($username_ad, strrpos($username_ad, "\\") + 1, strlen($username_ad) - strrpos($username_ad, "\\"));
//        } else {
//            $domain = "esdm";
//        }
        $credentials = ['username2' => strtolower($username), 'password' => $password_ad];

        // jika domain pln
//        if($domain != "esdm") {

        if (env('ACTIVE_DIRECTORY', 'true')) {

            if ($domain == 'pusat') {
//                    $user_admin = 'komitmen.pusat';
//                    $pass_admin = 'P@ssw0rd#3';
                $user_admin = 'simpus';
                $pass_admin = 'P@ssw0rd123#';
            } elseif ($domain == 'sutg') {
//                    $user_admin = 'komitmen.sutg';
//                    $pass_admin = 'P@ssw0rd#2';
                $user_admin = 'sutg.simpus';
                $pass_admin = 'P@ssw0rdadmin2018';
            } elseif ($domain == 'bali') {
//                    $user_admin = 'komitmen.bali';
//                    $pass_admin = 'P@ssw0rd';
                $user_admin = 'simpusbali';
                $pass_admin = 'simpusbali123';
            } elseif ($domain == 'nad' || $domain == 'aceh') {
//                    $user_admin = 'komitmen.nad';
//                    $pass_admin = 'P@ssw0rd';
                $user_admin = 'aceh.simpus';
                $pass_admin = 'P@ssw0rd#123';
                $domain = 'nad';
            } elseif ($domain == 'jateng') {
                $user_admin = 'komitmen.jateng';
                $pass_admin = 'P@ssw0rd#3';
            } elseif ($domain == 'jabar') {
                $user_admin = 'komitmen.jabar';
                $pass_admin = 'P@ssw0rd#5';
            } elseif ($domain == 'jatim') {
                $user_admin = 'komitmen.jatim';
                $pass_admin = 'P@ssw0rd#4';
            } elseif ($domain == 'jaya') {
                $user_admin = 'komitmen.jaya';
                $pass_admin = 'P@ssw0rd#2';
            } elseif ($domain == 'kitsbs') {
                $user_admin = 'komitmen.kitsbs';
                $pass_admin = 'P@ssw0rd#4';
            } elseif ($domain == 'lampung') {
                $user_admin = 'komitmen.lampung';
                $pass_admin = 'P@ssw0rd#4';
            } elseif ($domain == 'ntb') {
                $user_admin = 'komitmen.ntb';
                $pass_admin = 'P@ssw0rd#5';
            } elseif ($domain == 'riau') {
                $user_admin = 'komitmen.riau';
                $pass_admin = 'P@ssw0rd#4';
            } elseif ($domain == 's2jb') {
                $user_admin = 'komitmen.s2jb';
                $pass_admin = 'P@ssw0rd#5';
            } else {
                $user_admin = 'komitmen.pusat';
                $pass_admin = 'P@ssw0rd#2';
            }

            // Construct new Adldap instance.
            $ad = new Adldap();

            // Create a configuration array.
            $config = [
                // Your account suffix, for example: jdoe@corp.acme.org
                'account_suffix' => '@' . $domain . '.corp.pln.co.id',

                // The domain controllers option is an array of your LDAP hosts. You can
                // use the either the host name or the IP address of your host.
                'domain_controllers' => [$domain . '.corp.pln.co.id'],

                // The base distinguished name of your domain.
                'base_dn' => 'DC=' . $domain . ',DC=corp,DC=pln,DC=co,DC=id',

                // The account to use for querying / modifying LDAP records. This
                // does not need to be an actual admin account.
                'admin_username' => $user_admin,
                'admin_password' => $pass_admin,

                // Optional Configuration Options
                'port' => 389,
                'follow_referrals' => false,
                'use_ssl' => false,
                'use_tls' => false,
            ];

            try { 
                // Add a connection provider to Adldap.
                $ad->addProvider($config);
    
                // If a successful connection is made to your server, the provider will be returned.
                $provider = $ad->connect();

                // authenticate

                if (!$provider->auth()->attempt($username_ad, $password_ad)) {
                    return redirect('/auth/login')->with('status', 'The username or password is incorrect.');
                }

                // Finding a record.
//                    $user_ad = $provider->search()->find($username_ad);
                //$user_ad = $provider->search()->users()->find('='.$username_ad);
                //$user_test = $provider->search()->users()->find('=hikmat');
                $user_ad = $provider->search()->users()->where('samaccountname', '=', $username_ad)->first();
//                dd($user_ad);

            } catch (BindException $e) {

                // There was an issue binding / connecting to the server.
                return redirect('/auth/login')->with('status', 'Can\'t connect to Active Directory Server. Try again later or try to sign in with IAM PLN.');

            }

            $user = new User();

//              Cek Tabel User, jika tidak ada -> register, jika ada -> update data.
//            if (User::where('username', '=', strtolower($username_ad))->where('domain', '=', $domain)->count() > 0) {
            if (User::where('username2', '=', strtolower($domain.'\\'.$username_ad))->count() > 0) {
                // User lama
//                $user = User::where('username', '=', strtolower($username_ad))->where('domain', '=', $domain)->first();
                $user = User::where('username2', '=', strtolower($domain.'\\'.$username_ad))->first();

                // User inactive
                if ($user->status == 'INAC') {
                    return redirect('/auth/login')->with('status', 'User account is no longer active.');
                }
            } else {
                // User baru
                $user->status = 'ACTV';
            }

            $user->username = strtolower($username_ad);
            $user->username2 = strtolower($domain.'\\'.$username_ad);
            $user->name = $user_ad->getAttribute('displayname')[0];
            $user->email = strtolower($user_ad->getAttribute('mail')[0]);
            $user->password = Hash::make($password_ad);
            $user->active_directory = 1;
            $user->ad_display_name = $user_ad->getAttribute('displayname')[0];
            $user->ad_mail = $user_ad->getAttribute('mail')[0];
            $user->ad_company = $user_ad->getAttribute('company')[0];
            $user->ad_department = $user_ad->getAttribute('department')[0];
            $user->ad_title = $user_ad->getAttribute('title')[0];
            $user->ad_employee_number = $user_ad->getAttribute('employeenumber')[0];
            $user->ad_description = $user_ad->getAttribute('description')[0];
            $user->ad_dn = $user_ad->getAttribute('dn');
//                $user->ad_employee_number   = '';
            $user->domain = $domain;
            $user->save();

//            dd($user->strukturJabatan);

            // update company_code & business_area
            if ($user->strukturJabatan == null  && !$user->hasRole('komisaris')) {
//                    return redirect('auth/login')->with('error','Terjadi kesalahan sinkronisasi data. Email SAP berbeda dengan Email Active Directory. Silakan lapor ke Administrator <a href="'.url('lapor/ldap/'.$domain.'/'.$username_ad.'/'.csrf_token()).'">sekarang</a>.');

//                if ($user->ad_employee_number == '') {
//                        return redirect('auth/login')->with('error', 'Terjadi kesalahan sinkronisasi data. Email SAP berbeda dengan Email Active Directory. Silakan lapor ke Administrator.');
                    return redirect('auth/update-nip')
                        ->with([
//                            'info' => 'Silakan masukkan NIP Anda.',
                            'user_id' => $user->id
                        ]);
//                }
//                $strukjab = StrukturJabatan::where('nip', $user->ad_employee_number)->first();
//                $strukjab->email = $user->email;
//                $strukjab->save();
//                    return redirect('auth/update-email')
//                        ->with(['warning' => 'Terjadi kesalahan sinkronisasi data. Email SAP berbeda dengan Email Active Directory. Silakan update email anda.',
//                            'user_id' => $user->id
//                        ]);
            }
            //dd($user->strukturJabatan->strukturOrganisasi);
//                $user->strukturJabatan->strukturOrganisasi->getKantor()->hrp1008->businessArea;
//            $jabatan = $user->strukturJabatan;
//            if ($jabatan != null) {
//
//                //update nip
//                $user->nip = $jabatan->nip;
//                $user->save();
//
//                //update business_area
//                $user->business_area = $user->pa0032->pa0001->gsber;
//                $user->company_code = $user->pa0032->pa0001->bukrs;
//                $user->save();
//
////                    $organisasi = $jabatan->strukturOrganisasi;
////                    if($organisasi!=null){
////                        $unit = $organisasi->getKantor();
////                        if($unit!=null){
//////                            $business_area = $unit->hrp1008->businessArea;
//////                            $user->business_area    = $business_area->business_area;
//////                            $user->company_code     = $business_area->companyCode->company_code;
////
////                            $user->business_area    = $user->pa0032->pa0001->gsber;
////                            $user->company_code     = $user->pa0032->pa0001->bukrs;
////                            $user->save();
////
//////                            dd($user);
////                        }
////                    }
//            }

            // jika user baru, attach role guest
            if ($user->roles->count() == 0) {
//                //update business_area
//                $user->business_area = $user->pa0032->pa0001->gsber;
//                $user->company_code = $user->pa0032->pa0001->bukrs;
//                $user->save();
                // attach role pegawai
                $user->roles()->attach(5);
            }
        }
        else{
            $user = User::where('username2', strtolower($username))->first();
        }
//        }

        $this->validate($request, [
//            $this->loginUsername() => 'required', 'password' => 'required',
            'username' => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if ($user === null) {
            return redirect('/auth/login')->with('status', 'User account or password is incorrect');
        }

        if(!$user->hasRole('komisaris')){
            $jml_pernr_peg = PA0032::where('nip', $user->pa0032->nip)->count();

    //        dd($jml_pernr_peg);

            if($jml_pernr_peg > 1 || $jml_pernr_peg == 0 ) {
                return redirect('/auth/login')->withErrors( 'Duplikasi NIP atau NIP tidak ditemukan. Silakan hubungi Administrator');
            }
        }

        // login attempt
        if (Auth::attempt($credentials, $request->has('remember'))) {
            //                //update business_area
            //dd($user->pa0032->pa0001->cname);
            //$user->name = $user->pa0032->pa0001->cname;
//            dd($user->pa0032->pa001==null);

            if(!$user->hasRole('komisaris')){
                $user->business_area = $user->pa0032->pa0001->gsber;
                $user->company_code = $user->pa0032->pa0001->bukrs;
                $user->save();
            }

            Activity::log('Signed in.', 'success');
            return $this->handleUserWasAuthenticated($request, $throttles);
        } // jika salah
        else {
            // cari data user
            if ($user == null) {
                $user = User::where('username', '=', $username_ad);
            } else {
                $user = $user->where('username', '=', $username_ad);
            }
            // jika user ketemu
            if (sizeof($user->get()) > 0) {
                //user exist but wrong password
                $user = $user->first();
                // jika user memiliki password
                if ($user->password != "") {
                    // jika password salah
                    if (!Hash::check($password, $user->password)) {
                        return redirect('/auth/login')->with('status', 'User account or password is incorrect');
                    }
                } // jika user belum memiliki password
                else {
                    return redirect('/auth/login')->with('status', 'Your account has not been approved. Please contact admin.');
                }
            } // user tidak ditemukan / belum register
            else {
                return redirect('/auth/login')->with('status', 'Your account has not been registered. Please contact admin');
            }
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

//        return redirect($this->loginPath())
        return redirect('auth/login')
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    public function logout()
    {
//        LogController::storeActivity('Logout','AUTHENTICATION' , 'User logout dari aplikasi.');
        if (Auth::check()) Activity::log('Signed out.', 'success');
        Auth::logout();
        Session::flush();
        return redirect('/auth/login');
    }

    public function lapor($domain, $username, $token)
    {
        if ($token != csrf_token()) {
            return redirect('auth/login')->with('error', 'Wrong token.');
        }

    }
}
