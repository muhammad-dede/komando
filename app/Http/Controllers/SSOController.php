<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;

use App\Activity;
use App\EksepsiInterface;
use App\User;
//use App\UserKomando;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PegawaiSHAP;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SSOController extends Controller
{
    public function getPLNRedirect()
    {

        $client_id = env('PLN_ID');
        $redirect_uri = env('PLN_REDIRECT');
        $url = 'https://iam.pln.co.id/svc-core/oauth2/auth?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&scope=openid email profile empinfo phone address';
        return redirect($url);

    }

    public function getPLNHandle(Request $request)
    {
        try{
            if ($request->code == null) {
                return redirect()->to('auth/login')
                    ->with('error', 'You did not share your profile data with our app.');
            }

            // get access token
            $client_id = env('PLN_ID');
            $client_secret = env('PLN_SECRET');
            $redirect_uri = env('PLN_REDIRECT');

            $url = 'https://iam.pln.co.id/svc-core/oauth2/token';

            $http = new Client([
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret)],
                'verify' => false
            ]);
            $response = $http->post($url, [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $redirect_uri,
                    'code' => $request->code,
                ],
            ]);

            // retrieve access token
            $data = (array)json_decode((string)$response->getBody(), true);
            $token = $data['access_token'];
            $id_token = $data['id_token'];

            // get user
            $http = new Client(['headers' =>
                ['Authorization' => 'Bearer ' . $token],
                'verify' => false]);
            $url_get_user = 'https://iam.pln.co.id/svc-core/oauth2/me';
            $response = $http->get($url_get_user);
        }
        catch (\Exception $e){
            return redirect()->to('auth/login')
                ->with('error', 'Gagal login via IAM PLN. Silakan dicoba kembali beberapa saat lagi. Jika error masih muncul, silakan menghubungi Administrator.');
        }

        $data = json_decode((string)$response->getBody(), true);
        $user_sso = collect($data);

        // ==================================================================================================

        // cek apakah user exist di database
        $user = User::where('username', '=', $user_sso->get('sub'))->where('status','ACTV')->first();

        // jika user tidak ditemukan create user
        if ($user == null) {
            $user = new User();
            // return redirect()->to('auth/login')
                // ->with('error', 'User '.$user_sso->get('sub').' belum terdaftar. Silakan hubungi Administrator.');
        }

        // update data kepegawaian
        $username_ad = $user_sso->get('sub');
        $domain = 'pusat';

        $user->active_directory = 1;
        $user->username = strtolower($username_ad);
        $user->username2 = strtolower($domain.'\\'.$username_ad);
        $user->password = Hash::make('FreeP@lestin3!');
        $user->email = strtolower($user_sso->get('email'));
        // $user->name = strtoupper($user_sso->get('name'));

        // cek apakah user sso memiliki account hrinfo
        try{
            $hrinfo = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo');
        }catch (\Exception $e){
            $hrinfo = null;
        }

        // dd($hrinfo);

        // if hrinfo exist
        if ($hrinfo!=null) {
            try{
                $company = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['personnelArea']['name'];
                $department = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['organisasi']['name'];
                $title = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['posisi']['name'];
                $nip = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['nip'];
                $company_code = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['companyCode']['id']; // disable sync company code from iam HR Interface
                $business_area = $user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['businessArea']['id'];
            }catch (\Exception $e){
                $company = 'PLN';
                $department = 'Bidang';
                $title = 'Jabatan';
                $nip = 'NIP';
                $company_code = '0000';
                $business_area = '0000';
            }
        }else{
                $company = 'PLN';
                $department = 'Bidang';
                $title = 'Jabatan';
                $nip = 'NIP';
                $company_code = '0000';
                $business_area = '0000';
        }

        $exclude = EksepsiInterface::select(['nip','pernr'])->get();
        $exclude_nip = [];
        foreach ($exclude as $exc) {
            $exclude_nip[]=$exc->nip;
        }
        if($company_code!='1200' && $company_code!='1300' && $company_code!='0000' && !in_array($nip, $exclude_nip)){
            // $user->name =$user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['registeredName'];
            $user->name = strtoupper($user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['registeredName']);
            $user->ad_display_name = strtoupper($user_sso->get('https://iam.pln.co.id/svc-core/account/hrinfo')['registeredName']);
            $user->ad_mail = strtolower($user_sso->get('email'));
            $user->ad_company = $company;
            $user->ad_department = $department;
            $user->ad_title = $title;
            $user->ad_employee_number = $nip;
            $user->ad_description = $nip;
            $user->domain = $domain;

            $user->company_code = $company_code;// disable sync company code from iam HR Interface
            $user->business_area = $business_area;
            $user->nip = strtoupper($nip);
            $user->holding=1;
            
            try{
                $user->save();
            }catch (\Exception $e){
                app('sentry')->captureException($e);
                return redirect()->to('auth/login')
                    ->with('error', 'Sinkronisasi data IAM PLN gagal. Silakan dicoba kembali beberapa saat lagi. Jika error masih muncul, silakan menghubungi Administrator.');
            }
             
            // update company_code & business_area
            if ($user->strukturJabatan == null  && !$user->hasRole('komisaris')) {
                return redirect('auth/update-nip')
                    ->with([
                        'user_id' => $user->id
                    ]);
            }

            // jika user baru, attach role pegawai jika holding, attach role shap jika non-holding
            if ($user->roles->count() == 0) {
                // attach role pegawai if holding
                if ($user->holding == 1)
                    $user->roles()->attach(5);

                // attach role shap if non-holding
                if ($user->holding == 0) {
                    $role = Role::where('name', 'shap')->first();
                    $user->roles()->attach($role->id);
                }
            }
            
            if(!$user->hasRole('komisaris')){
                $user->business_area = $user->pa0032->pa0001->gsber;
                $user->company_code = $user->pa0032->pa0001->bukrs;
                $user->save();
            }
            
            // update data kepegawaian user holding
            $strukjab = $user->strukturJabatan;
            if($strukjab){
                // $user->holding = 1;
                $user->orgeh = $strukjab->orgeh;
                $user->plans = $strukjab->plans;
                $user->personel_area = $strukjab->werks;
                $user->personel_subarea = str_pad($strukjab->btrtl,4,'0',STR_PAD_LEFT);
                $user->bidang = @$strukjab->strukturPosisi->stxt2;
                $user->jabatan = @$strukjab->strukturPosisi->stext;
                $user->save();
            }
        }
        // jika user company code in (1200,1300) atau masuk di execption list
        else{
            // cek pegawai apakah ada di table pegawai shap hxms
            $pegawai_shap = PegawaiSHAP::where('username', $user_sso->get('sub'))->first();

            // jika pegawai shap tidak ditemukan
            if($pegawai_shap == null){
                return redirect()->to('auth/login')
                    ->with('error', 'Data Username : '.$user_sso->get('sub').' tidak ditemukan di sistem HXMS. Silakan hubungi Administrator.');
            }

            $user->holding = 0;
            $user->company_code = $pegawai_shap->company_code;
            $user->business_area = $pegawai_shap->business_area;
            $user->orgeh = $pegawai_shap->orgeh;
            $user->plans = $pegawai_shap->plans;
            $user->personel_area = $pegawai_shap->personel_area_sap;
            $user->personel_subarea = $pegawai_shap->personel_subarea_sap;
            $user->bidang = $pegawai_shap->personel_area;
            $user->jabatan = $pegawai_shap->jabatan;
            $user->nip = $pegawai_shap->nip;
            $user->email = strtolower($pegawai_shap->email);
            $user->name = $pegawai_shap->nama;

            // cek apakah ada error saat menyimpan data
            try{
                $user->save();
            }catch (\Exception $e){
                // send exception to sentry
                app('sentry')->captureException($e);
                return redirect()->to('auth/login')
                    ->with('error', 'Sinkronisasi data IAM PLN pegawai Sub Holding/Anak Perusahaan gagal.');
            } 

            /*

            // update data pegawai shap
            // $user->name = strtoupper($user_sso->get('name'));
            $user->email = strtolower($user_sso->get('email'));

            // cek apakah ada error saat menyimpan data
            try{
                $user->save();
            }catch (\Exception $e){
                // send exception to sentry
                app('sentry')->captureException($e);
                return redirect()->to('auth/login')
                    ->with('error', 'Sinkronisasi data IAM PLN pegawai Sub Holding/Anak Perusahaan gagal.');
            } 

            // update data kepegawaian user subholding from data pegawai shap HXMS
            $pegawai_shap = PegawaiSHAP::where('nip', $user->nip)->first();
            if($pegawai_shap!=null){
                $user->holding = 0;
                $user->company_code = $pegawai_shap->company_code;
                $user->business_area = $pegawai_shap->business_area;
                $user->orgeh = $pegawai_shap->orgeh;
                $user->plans = $pegawai_shap->plans;
                $user->personel_area = $pegawai_shap->personel_area_sap;
                $user->personel_subarea = $pegawai_shap->personel_subarea_sap;
                $user->bidang = $pegawai_shap->personel_area;
                $user->jabatan = $pegawai_shap->jabatan;
                $user->nip = $pegawai_shap->nip;

                try{
                    $user->save();
                }catch (\Exception $e){
                    app('sentry')->captureException($e);
                    return redirect()->to('auth/login')
                        ->with('error', 'Update data kepegawaian Sub Holding/Anak Perusahaan gagal. Silakan dicoba kembali beberapa saat lagi. Jika error masih muncul, silakan menghubungi Administrator.');
                }
            }
            // else{
            //     return redirect()->to('auth/login')
            //         ->with('error', 'Username: '.$user->username.' dan NIP:'.$user->nip.' belum terdaftar. Silakan hubungi Administrator.');
            // }


            */

            // jika user baru, attach role shap
            if ($user->roles->count() == 0) {
                // attach role shap
                $role = Role::where('name', 'shap')->first();
                $user->roles()->attach($role->id);
            }

            // cek apakah sudah memiliki data employee di table users
            if($user->orgeh == null){
                return redirect()->to('auth/login')
                    ->with('error', 'Username: '.$user->username.' dan NIP:'.$user->nip.' belum memiliki kode organisasi. Silakan hubungi Administrator.');
            }

            if($user->company_code == null){
                return redirect()->to('auth/login')
                    ->with('error', 'Username: '.$user->username.' dan NIP:'.$user->nip.' belum memiliki kode company_code. Silakan hubungi Administrator.');
            }

            if($user->business_area == null){
                return redirect()->to('auth/login')
                    ->with('error', 'Username: '.$user->username.' dan NIP:'.$user->nip.' belum memiliki kode business_area. Silakan hubungi Administrator.');
            }

        }

        session(['login_from' => 'SSO','id_token'=>$id_token]);

        auth()->login($user, true);
        Activity::log('Signed in with IAM PLN account.', 'success');

        return redirect('/');

    }

    public function getLogout(Request $request)
    {
        if (Auth::check()) Activity::log('Signed out.', 'success');
        Auth::logout();
        Session::flush();
        return 'ok';
        //Activity::log('Logout dari SSO', 'success');
    }

    public function testAPI()
    {


// URL Get Data Ketenagalistrikan
        $url_get_data = 'http://mercusuar.pln.co.id/api/esdm';

        $url_token = 'http://mercusuar.pln.co.id/oauth/access_token';
        $client_id = '3'; // masukkan client_id
        $client_secret = 'esdm'; // masukkan client_secret
        $client_username = 'esdm.api'; // username
        $client_password = 'esdm@mercusuar'; // password

        $http = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $response = $http->post($url_token, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'username' => $client_username,
                'password' => $client_password
            ],
        ]);

        $data = (array)json_decode((string)$response->getBody(), true);
        $token = $data['access_token'];
        echo $token;

//        dd($token);

        $url_get_data = 'http://mercusuar.pln.co.id/api/esdm';

        $http = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $grup_id = 2;
        $tanggal = '21-01-2019';

        $response = $http->post($url_get_data, [
            'form_params' => [
                'access_token' => $token,
                'grup_id' => $grup_id,
                'tanggal' => $tanggal,
            ],
        ]);

        $data = (array) json_decode((string) $response->getBody(), true);

        var_dump($data);

        dd($data);


    }

    public function logoutSSO()
    {
        $login_from = session('login_from');
        $ur_logout_sso = 'https://iam.pln.co.id/svc-core/oauth2/session/end?post_logout_redirect_uri=' . env('POST_LOGOUT_REDIRECT_URI', 'http://localhost:8800/auth/logout') . '&id_token_hint=' . session('id_token');
//        dd($ur_logout_sso);

        if ($login_from == 'SSO') {
            return redirect($ur_logout_sso);
        }
    }
}
