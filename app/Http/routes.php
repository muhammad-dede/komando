<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route::get('/login', function () {
//    return view('auth.login');
//});

use App\Activity;
use App\StrukturOrganisasi;
use App\StrukturPosisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Jenssegers\Agent\Agent;

Route::get('test-tps/{limit}', 'TpsController@test');
Route::get('test-lb', function () {
    echo '<html>';
    echo '<head>';
    if(@Request::get('auto-refresh')==1)
        echo '<meta http-equiv="refresh" content="1" />';
    echo '</head>';
    echo '<body>';
    echo 'Server : '.env('SERVER','-').'<br>';
    echo 'User : '.@Request::user()->id.'/'.@Request::user()->username.'/'.@Request::user()->nip.'<br>';

    $agent = new Agent();
    echo 'Device : '.$agent->device().'<br>';
    echo 'Platform : '.$agent->platform().'<br>';
    echo 'Browser : '.$agent->browser().'<br>';

    echo 'IP Address : '.\Request::ip().'<br>';
    echo '</body><html>';

    // return env('APP_NAME', 'BUDAYA-00');
});

Route::get('error-page', function(){
	return view('errors/load');
});
Route::get('auth/login', 'Auth\AuthController@getLogin')->name('auth_login');
Route::post('auth/login', 'Auth\AuthController@validateLDAP');
Route::get('auth/logout', 'Auth\AuthController@logout')->name('auth_logout');

Route::get('logout-portalhc', function(){
    if (Auth::check()) Activity::log('Signed out.', 'success');
    Auth::logout();
    Session::flush();
    $url = 'https://portalhc.pln.co.id/';
    return Redirect::to($url);
});

Route::get('lapor/ldap/{domain}/{username}/{token}', 'Auth\AuthController@lapor');

Route::get('auth/update-nip', 'UserController@editNIP');
Route::post('auth/update-nip', 'UserController@updateNIP');

Route::get('oauth/redirect/pln', 'SSOController@getPLNRedirect');
Route::get('oauth/handle/pln', 'SSOController@getPLNHandle');
Route::get('oauth/logout', 'SSOController@logoutSSO')->name('logout_sso');

Route::get('evp/help', 'EVPController@help');

Route::get('foto-pegawai/{nip}', 'UserController@getFotoPegawai');
Route::get('foto-thumb-pegawai/{id}', 'UserController@getFotoThumbnailPegawai');


Route::group(['middleware' => ['auth']], function () {

    Route::get('dashboard-budaya', 'DashboardController@dashboardBudaya');

    Route::get('help', 'DashboardController@help');
    Route::get('notif', 'NotificationController@show');

    //  # Revert route...
    Route::get('impersonation/revert', 'ImpersonateController@revert')->name('impersonate.revert');

    Route::group(['middleware' => ['role:root']], function () {

        // reopen room coc with parameter array id
        Route::get('coc/reopen/{id}', 'CocController@reopen');

        Route::get('/phpinfo', function () {
            phpinfo();
        });

        Route::get('/debug-sentry', function () {
            throw new Exception('My first Sentry error!');
        });

        Route::get('update-foto', 'UserController@getFotoFromTalent');

        Route::get('migrate/admin-unit', 'UserController@migrateAdminUnit');
        Route::get('update/company-code', 'UserController@updateCompanyCode');
        Route::get('update/nip', 'UserController@updateNIPMass');
        Route::get('update/nip-from-ad', 'UserController@updateNIPFromAD');
        Route::get('update/persg', 'UserController@updatePersg');
        Route::get('update/email-purna', 'UserController@deleteEmailPurna');
        Route::get('resolve/wrong-nip', 'UserController@resolveWrongNIP');

        Route::get('cek/user', 'UserController@cekUser');
        Route::get('cek/user-role', 'UserController@cekUserRole');
        Route::get('cek/duplicate-email', 'UserController@cekEmailDuplicate');
        Route::get('cek/user-nip', 'UserController@cekWrongNIP');

        Route::get('update/business-area', 'BusinessAreaController@updateBusinessArea');

        // interface
        Route::get('update/data-sap', 'InterfaceController@updateDataFromSAP');
        Route::get('interface/ess', 'InterfaceController@updateDataESS');
        Route::get('interface/sap', 'InterfaceController@updateDataSAP');
        Route::get('migrate/dev-trn', 'InterfaceController@migrateFromDevToTrn');
        Route::get('interface/check-status', 'InterfaceController@checkStatus');


        Route::get('update/log/username2', 'UserController@updateActivityLog');
        Route::get('update/read-materi', 'UserController@updateReadMateri');
        Route::get('update/tema', 'UserController@updateTema');

        Route::get('update/hrp1008', 'InterfaceController@updateHrp1008');
        Route::get('update/hrp1513', 'InterfaceController@updateHrp1513');
        Route::get('update/pa0001', 'InterfaceController@updatePa0001');
        Route::get('update/pa0032', 'InterfaceController@updatePa0032');
        Route::get('update/strukjab', 'InterfaceController@updateStrukjab');
        Route::get('update/strukpos', 'InterfaceController@updateStrukpos');

        Route::get('resolve/duplicate-commit', 'CommitmentController@deleteDuplicate');
        Route::get('jumlah-komitmen/{tahun}', 'CommitmentController@getJmlKomitmenTahun');

        Route::get('migrate/realisasi', 'CocController@initialOrgehRealisasi');
        Route::get('migrate/jenjang-strukorg', 'InterfaceController@updateJenjangStrukOrg');

        Route::get('convert/foto', 'UserController@convertImage');
        Route::get('convert/foto-coc', 'GalleryController@massConvertGallery');
        Route::get('convert/ttd', 'UserController@convertImageTTD');

        Route::get('update/description/{domain}', 'UserController@updateDescription');
        Route::get('clean/user', 'UserController@cleanDataUser');

        Route::get('update/file-foto', 'UserController@updateFileFoto');

        Route::get('send/mail-admin', 'MailController@sendMailAdmin');
        Route::get('send/mail-coc', 'MailController@sendMailCoc');
        Route::get('send/mail-interface', 'MailController@sendMailInterfaceLog');

        Route::get('get/children/{orgeh}', 'StrukturOrganisasiController@getChildren');

        Route::get('get/user-ad/{domain}/{username}', 'UserController@getUserAD');

        Route::get('evp/update-status', 'VolunteerController@updateStatusVolunteer');

        //  # Impersonate route...
        Route::get('impersonation/{user}', 'ImpersonateController@impersonate')->name('impersonate.impersonate');
        Route::get('impersonation/nip/{nip}', 'ImpersonateController@impersonateNip');
        Route::get('impersonation/username/{username}', 'ImpersonateController@impersonateUsername');

        // reset unfinished commitment
        Route::get('reset/commitment', 'InterfaceController@resetCommitment');

        Route::get('coc/auto-complete','CocController@autoCompleteRoomCoc');
        Route::get('coc/auto-complete/year','CocController@autoCompleteRoomTahun');

        Route::get('coc/update-jenis','CocController@massUpdateJenisCoC');
        Route::get('coc/update-leader','CocController@massUpdatePlansLeaderCoc');
        Route::get('coc/insert-pelanggaran','CocController@massInsertPelanggaranCoc');

        Route::get('materi/delete-read-duplicate/{materi_id}','ReadMateriController@deleteDuplicate');

        Route::get('delete/organisasi-lama', 'InterfaceController@deleteOraganisasiLama');
        Route::get('mail/organisasi-baru', 'InterfaceController@notifOrganisasiBaru');
        Route::get('send/mail-organisasi-baru', 'MailController@sendMailOrganisasiBaru');

    });

    Route::get('/', 'DashboardController@index');
    Route::post('/corona/store', 'UserController@storeSuhu');

    Route::get('/notification', 'NotificationController@index');
    Route::get('/notification/clear-all', 'NotificationController@clearAllNotification');
    Route::get('/notification/{id}', 'NotificationController@readThenRedirect');

    /*
    |--------------------------------------------------------------------------
    | Start Komitmen Routes
    |--------------------------------------------------------------------------
    */
//    Route::get('commitment', 'CommitmentController@index');
//    Route::post('commitment', 'CommitmentController@searchResult');
//    Route::get('commitment/export/{business_area}', 'CommitmentController@exportCommitment');

    Route::group(['middleware' => ['role:pegawai']], function () {

        Route::get('commitment', 'CommitmentController@commitmentPegawai');

        Route::get('commitment/komitmen-pegawai/{tahun}', 'CommitmentController@komitmenPegawai');
        Route::post('commitment/komitmen-pegawai', 'CommitmentController@storeKomitmenPegawai');

        Route::get('commitment/direksi-komisaris/{tahun}', 'CommitmentController@komitmenDireksi');
        Route::post('commitment/direksi-komisaris', 'CommitmentController@storeKomitmenDireksi');

    //    Route::get('commitment/pedoman-perilaku', 'PedomanPerilakuController@index');
        Route::get('commitment/pedoman-perilaku/tahun/{tahun}', 'PedomanPerilakuController@index');
        Route::post('commitment/pedoman-perilaku', 'PedomanPerilakuController@submitPedoman');
        Route::post('commitment/pedoman-perilaku/quiz', 'PedomanPerilakuController@quizPedoman');
        Route::post('commitment/pedoman-perilaku/quiz/answer', 'PedomanPerilakuController@answerPedoman');

        Route::get('commitment/buku', 'PedomanPerilakuController@buku');
        Route::get('commitment/buku/{id}', 'PedomanPerilakuController@showBuku');
    //    Route::get('commitment/pertanyaan', 'CommitmentController@pertanyaan');

    });

    /*
    |--------------------------------------------------------------------------
    | End Komitmen Routes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Start COC Routes
    |--------------------------------------------------------------------------
    */
//    Route::get('/pembebanan-trafo/sistem','PembebananTrafoController@sistem');
//    Route::get('/pembebanan-trafo/diatas-80','PembebananTrafoController@diatas80');
    Route::get('coc', 'CocController@index');
    Route::get('dod', 'SlicingController@dod');
    Route::get('edit-dod', 'SlicingController@editdod');
    Route::get('detail-dod', 'SlicingController@detaildod');
    Route::get('penilaian-list', 'SlicingController@penilaian');
    Route::get('penilaian-edit', 'SlicingController@penilaianEdit');
    Route::get('penilaian-create', 'SlicingController@penilaianCreate');
    Route::get('penilaian-atasan', 'SlicingController@penilaianAtasan');
    Route::get('pengukuran-kedua-create', 'SlicingController@pengukuranKeduaCreate');
    Route::get('pengukuran-kedua-edit', 'SlicingController@pengukuranKeduaEdit');
    Route::get('pengukuran-kedua', 'SlicingController@pengukuranKedua');
    Route::get('input-activity-log', 'SlicingController@inputActivityLog');
    Route::get('activity-log', 'SlicingController@activityLog');
    Route::get('dashboard-admin-satu', 'SlicingController@dashboardAdminSatu');
    Route::get('dashboard-admin-dua', 'SlicingController@dashboardAdminDua');
    Route::get('dashboard-atasan-status-liquid', 'SlicingController@dashboardAtasanStatusLiquid');
    Route::get('dashboard-atasan-kalendar-liquid', 'SlicingController@dashboardAtasanKalendarLiquid');
    Route::get('feedback-list-atasan', 'SlicingController@feedbackListAtasan');
    Route::get('pengukuran-pertama-list-atasan', 'SlicingController@pengukuranPertamaListAtasan');
    Route::get('pengukuran-kedua-list-atasan', 'SlicingController@pengukuranKeduaListAtasan');

    Route::post('coc/apply-thematic', ['middleware' => ['permission:input_tema_nasional'], 'uses' => 'TemaCocController@store']);
//    Route::post('coc/apply-thematic','TemaCocController@store');

    Route::get('coc/create/kantor-induk', ['middleware' => ['permission:input_coc_ki'], 'uses' => 'CocController@create']);
    Route::get('coc/create/local', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@createLocal']);
    Route::get('coc/create/local/{tema_id}', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@createLocalFromTema']);

    Route::get('coc/complete/local', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@create']);
//    Route::get('coc/create/local','CocController@create');
    Route::get('coc/create/materi/gm', ['middleware' => ['permission:input_materi_gm'], 'uses' => 'CocController@createMateri']);
//    Route::get('coc/create/materi/gm','CocController@createMateri');
    Route::get('coc/create/materi/nasional', ['middleware' => ['permission:input_materi_pusat'], 'uses' => 'CocController@createMateri']);
//    Route::get('coc/create/materi/nasional','CocController@createMateri');
//    Route::get('coc/create/unit','CocController@create');
//    Route::get('coc/create/nasional','CocController@create');

    Route::post('coc/create/kantor-induk', ['middleware' => ['permission:input_coc_ki'], 'uses' => 'CocController@store']);
    Route::post('coc/create/local', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@storeLocal']);
//    Route::post('coc/create/local','CocController@store');
//    Route::post('coc/create/unit','CocController@store');
    Route::post('coc/create/materi/gm', ['middleware' => ['permission:input_materi_gm'], 'uses' => 'CocController@storeMateri']);
//    Route::post('coc/create/unit','CocController@storeMateri');
//    Route::post('coc/create/nasional','CocController@store');
    Route::post('coc/create/materi/nasional', ['middleware' => ['permission:input_materi_pusat'], 'uses' => 'CocController@storeMateri']);
//    Route::post('coc/create/nasional','CocController@storeMateri');

    Route::group(['middleware' => ['permission:input_coc_local']], function () {
        Route::get('coc/initial/{materi_id}', 'CocController@initialMateri');
        Route::post('coc/create/{materi_id}', 'CocController@createFromMateri');
        Route::get('coc/create/{materi_id}', 'CocController@createFromMateri');
        Route::post('coc/create/{materi_id}/store', 'CocController@storeFromMateri');

        Route::get('ajax/get-perilaku/{id}/{orgeh}/{jenis}', 'CocController@ajaxGetPerilaku');
        Route::get('ajax/get-pelanggaran/{orgeh}', 'CocController@ajaxGetPelanggaran');
        Route::get('ajax/get-history/{orgeh}', 'CocController@ajaxGetHistoryPedoman');
        Route::get('ajax/get-history-pelanggaran/{orgeh}', 'CocController@ajaxGetHistoryPelanggaran');
        Route::get('ajax/get-jml-pegawai/{orgeh}', 'CocController@ajaxGetJmlPegawai');
        Route::get('ajax/clear-history/{orgeh}', 'CocController@ajaxClearHistoryPedoman');
        Route::get('ajax/clear-history-pelanggaran/{orgeh}', 'CocController@ajaxClearHistoryPelanggaran');
//        Route::get('ajax/get-dont/{id}','CocController@ajaxGetDont');

        Route::get('coc/event/{id}/export', 'CocController@exportPeserta');
    });

    Route::post('coc/visi-misi/{coc_id}', 'CocController@visiMisi');
    Route::post('coc/prinsip/{coc_id}', 'CocController@prinsip');
    Route::post('coc/nilai/{coc_id}', 'CocController@nilai');
    Route::post('coc/tata-nilai/{coc_id}', 'CocController@tataNilai');
    Route::post('coc/fokus-perilaku/{coc_id}', 'CocController@fokusPerilaku');
    Route::post('coc/pedoman-perilaku/{coc_id}', 'CocController@pedomanPerilaku');
    Route::post('coc/isu-nasional/{coc_id}', 'CocController@isuNasional');
    Route::post('coc/pelanggaran/{coc_id}', 'CocController@pelanggaran');

//    Route::post('coc/create/nasional','CocController@storeNasional');
//    Route::post('coc/create/unit','CocController@storeUnit');
//    Route::post('coc/create/local','CocController@storeLocal');
//    Route::post('coc/create/{scope}','CocController@store');

    Route::get('coc/ajax-pemateri', 'UserController@dataAjaxPemateri');
    Route::get('coc/ajax-leader', 'UserController@dataAjaxLeader');
    Route::get('coc/check-in/today', 'CocController@checkInToday');
    Route::get('coc/check-in/{id}', 'CocController@checkIn');
    Route::post('coc/check-in/{id}', 'CocController@storeCheckIn');
    Route::get('coc/event/{id}', 'CocController@show');
    //Route::post('coc/complete/{id}', 'CocController@complete');
    Route::get('ajax/get-coc/{id}', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@ajaxGetCoc']);
    Route::post('coc/complete/{id}', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@complete']);
    Route::post('coc/complete', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@completeAdmin']);
    Route::get('coc/cancel/{id}', ['middleware' => ['permission:input_coc_local'], 'uses' => 'CocController@cancelCoc']);
    Route::post('coc/upload-foto/{id}', ['middleware' => ['permission:upload_foto'], 'uses' => 'CocController@uploadFoto']);
//    Route::post('coc/upload-foto/{id}', 'CocController@uploadFoto');

    Route::post('coc/forum/{id}', 'CocController@storeComment');

    Route::get('coc/tema/{id}', 'TemaCocController@show');
    Route::get('coc/tema/export/{id}', 'TemaCocController@exportTemaCoc');

    Route::post('coc/read-materi/{event_id}', 'ReadMateriController@store');

    Route::get('coc/list', 'CocController@listCoc');
    Route::post('coc/list', 'CocController@searchListCoc');

    Route::group(['middleware' => ['permission:input_coc_local']], function () {
        Route::get('coc/list/admin', 'CocController@listCocAdmin');
        Route::post('coc/list/admin', 'CocController@searchListCocAdmin');

        Route::get('/coc/delete-foto/{foto_id}', 'CocController@deleteFoto');

    });


    /*
    |--------------------------------------------------------------------------
    | End COC Routes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Start Report Routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => ['permission:report']], function () {

        Route::get('report/monitoring-checkin-coc', 'ReportController@monitoringCheckInCoc');
        Route::get('report/persentase-baca-materi', 'ReportController@persentaseBacaMateri');
        Route::get('report/persentase-baca-materi/export', 'ReportController@exportPersentaseBacaMateri');

        
        Route::get('report/monitoring-baca-materi-pegawai', 'ReportController@monitoringBacaMateriPegawai');
        Route::get('report/monitoring-baca-materi-pegawai/export', 'ReportController@exportPesertaBacaMateri');

        Route::get('report/history-coc', 'ReportController@historyCoc');
        Route::get('report/history-coc/export', 'ReportController@exportHistoryCoc');

        Route::get('report/tema-coc', 'ReportController@temaCoc');
        Route::get('report/tema-coc/export', 'ReportController@exportTemaCoc');

        Route::get('report/briefing-coc', 'ReportController@briefingCoc');
        Route::post('report/briefing-coc', 'ReportController@searchResultBriefing');
        Route::get('report/briefing-coc/export/{company_code}/{start_date}/{end_date}', 'ReportController@exportBriefingCoc');

        Route::get('report/rekap-coc', 'ReportController@rekapCoc');
        Route::post('report/rekap-coc', 'ReportController@searchResultRekap');
        Route::get('report/rekap-coc/export/{company_code}/{tgl_awal}/{tgl_akhir}', 'ReportController@exportRekapCoc');

        Route::get('report/rekap-coc/{company_code}/{jenjang_id}', 'ReportController@detilRekapCoC');
        Route::post('report/rekap-coc/{company_code}/{jenjang_id}', 'ReportController@searchDetilRekapCoC');
        Route::get('report/rekap-coc/export-detil/{company_code}/{jenjang_id}/{tgl_awal}/{tgl_akhir}', 'ReportController@exportDetilRekapCoC');

        Route::get('report/rekap-coc-org', 'ReportController@rekapCocOrg');

        Route::get('report/persentase-coc', 'ReportController@persentaseCoc');
        Route::post('report/persentase-coc', 'ReportController@searchPersentaseCoc');
        Route::get('report/persentase-coc/export/{company_code}/{tgl_awal}/{tgl_akhir}/{jenis_coc_id}', 'ReportController@exportPersentaseCoc');

        Route::get('report/jumlah-coc', 'ReportController@jumlahCoc');
        Route::post('report/jumlah-coc', 'ReportController@jumlahCoc');
        Route::get('report/jumlah-coc/export/{company_code}/{tgl_awal}/{tgl_akhir}', 'ReportController@exportJumlahCoc');

        Route::get('report/status-coc', 'ReportController@statusCoc');
        Route::post('report/status-coc', 'ReportController@searchStatusCoc');

        Route::get('report/status-coc-cc', 'ReportController@statusCocCompanyCode');
        Route::post('report/status-coc-cc', 'ReportController@searchStatusCocCompanyCode');

        Route::get('report/status-coc/export/{business_area}/{tgl_awal}/{tgl_akhir}/{status}', 'ReportController@exportStatusCoc');
        Route::get('report/status-coc-cc/export/{company_code}/{tgl_awal}/{tgl_akhir}/{status}', 'ReportController@exportStatusCocCompanyCode');

//        Route::get('report/commitment', 'ReportController@persentaseCoc');
        Route::get('report/commitment', 'CommitmentController@index');
        Route::get('report/commitment-direksi', 'CommitmentController@commitmentDireksi');
        Route::get('report/commitment-dekom', 'CommitmentController@commitmentDekom');
        Route::post('report/commitment', 'CommitmentController@searchResult');
        Route::get('report/commitment/export/{business_area}', 'CommitmentController@exportCommitment');
        Route::get('report/commitment/export/{business_area}/{tahun}', 'CommitmentController@exportCommitmentTahun');
        Route::get('report/commitment-direksi/export/{tahun}', 'CommitmentController@exportCommitmentDireksi');
        Route::get('report/commitment-dekom/export/{tahun}', 'CommitmentController@exportCommitmentDekom');

        Route::get('report/commitment/export-all/{tahun}', 'CommitmentController@exportCommitmentTahunAll');

        Route::group(['middleware' => ['role:root|admin_pusat|admin_ki']], function () {
            Route::get('report/commitment-induk', 'CommitmentController@indexInduk');
            Route::post('report/commitment-induk', 'CommitmentController@searchResultInduk');
//        Route::get('report/commitment-induk/export/{company_code}', 'CommitmentController@exportCommitmentInduk');
            Route::get('report/commitment-induk/export/{company_code}/{tahun}', 'CommitmentController@exportCommitmentTahunInduk');

            Route::get('report/rekap-commitment', 'CommitmentController@rekapInduk');
            Route::get('report/rekap-commitment/export', 'CommitmentController@exportRekapInduk');
//            Route::post('report/rekap-commitment', 'CommitmentController@searchResultRekap');
        });

        Route::get('report/problem', 'ProblemController@index');
        Route::post('report/problem', 'ProblemController@searchResult');
        Route::get('report/problem/create', 'ProblemController@create');
        Route::post('report/problem/create', 'ProblemController@store');
        Route::get('report/problem/{id}', 'ProblemController@edit');
        Route::post('report/problem/{id}', 'ProblemController@update');
        Route::get('report/problem/foto/{id}', 'ProblemController@getFoto');
        Route::get('report/problem/close/{id}', 'ProblemController@close');

        Route::get('report/survey-liquid', 'SurveyLiquidController@index')->name('report.survey-liquid');
        Route::get('report/survey-liquid/{liquid}', 'SurveyLiquidController@show')->name('report.survey-liquid.show');
    });
    /*
    |--------------------------------------------------------------------------
    | End Report Routes
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Start Master Routes
    |--------------------------------------------------------------------------
    */
//    Route::group(['middleware' => ['role:root']], function () {
    Route::group(['middleware' => ['permission:md_perilaku']], function () {
        Route::get('master-data/pedoman-perilaku', 'PedomanPerilakuController@daftarPedomanPerilaku');
        Route::get('master-data/pedoman-perilaku/{id}/display', 'PedomanPerilakuController@show');
    });

    Route::group(['middleware' => ['permission:md_target_coc']], function () {
        Route::get('master-data/target-checkin-coc', 'TargetCocController@targetCheckinCoc');
        Route::get('master-data/target-checkin-coc/create', 'TargetCocController@createTargetCheckinCoc');
        Route::post('master-data/target-checkin-coc/create', 'TargetCocController@submitTargetCheckinCoc');
        Route::get('master-data/target-checkin-coc/{id}/edit', 'TargetCocController@editTargetCheckinCoc');
        Route::post('master-data/target-checkin-coc/{id}/edit', 'TargetCocController@updateTargetCheckinCoc');
        Route::get('master-data/target-checkin-coc/{id}/delete', 'TargetCocController@deleteTargetCheckinCoc');

        Route::get('master-data/target-coc', 'TargetCocController@index');
        Route::post('master-data/target-coc', 'TargetCocController@searchResult');
        Route::post('master-data/target-coc/{tahun}', 'TargetCocController@search');

        Route::get('master-data/target-coc/{tahun}/{jenjang_id}/create', 'TargetCocController@create');
        Route::post('master-data/target-coc/{tahun}/{jenjang_id}/create', 'TargetCocController@store');

        Route::get('master-data/target-coc/{tahun}/{jenjang_id}/edit', 'TargetCocController@edit');
        Route::post('master-data/target-coc/{tahun}/{jenjang_id}/edit', 'TargetCocController@update');
    });

    Route::group(['middleware' => ['permission:md_materi_coc']], function () {
        Route::post('master-data/materi/{id}', 'MateriController@update');
        Route::resource('master-data/materi', 'MateriController');
        Route::get('master-data/materi/export/{tahun}/{jenis_materi_id}', 'MateriController@exportMateri');
    });

    Route::group(['middleware' => ['permission:md_tema_coc']], function () {
        Route::resource('master-data/tema', 'TemaController');
        Route::resource('master-data/tema-coc', 'TemaCocController');
    });
//        Route::get('master-data/tema-coc', 'TemaCocController@index');
//        Route::get('master-data/tema-coc/create', 'TemaCocController@create');
//        Route::post('master-data/tema-coc/create', 'TemaCocController@submit');

//        Route::get('/master-data/unit', 'WilayahController@index');

//        Route::get('/master-data/unit', 'WilayahController@index');
//    Route::get('/master/unit/create', 'WilayahController@create');
//    Route::post('/master/unit/create', 'WilayahController@store');
//    Route::get('/master/unit/{id}', 'WilayahController@show');
//    Route::post('/master/unit/{id}', 'WilayahController@update');

//        Route::get('/master-data/sistem', 'SistemController@index');
//    Route::get('/master/sistem/create', 'SistemController@create');
//    Route::post('/master/sistem/create', 'SistemController@store');
//    Route::get('/master/sistem/{id}', 'SistemController@show');
//    Route::post('/master/sistem/{id}', 'SistemController@update');

    Route::group(['middleware' => ['permission:md_perilaku']], function () {
        Route::get('master-data/pertanyaan', 'PertanyaanController@index');
        Route::get('master-data/pertanyaan/pilihan-ganda', 'PertanyaanController@createPilihanGanda');
        Route::post('master-data/pertanyaan/pilihan-ganda', 'PertanyaanController@storePilihanGanda');
        Route::get('master-data/pertanyaan/pilihan-ganda/{id}', 'PertanyaanController@createPilihanGandaFromDisplay');
        Route::get('master-data/pertanyaan/pilihan-ganda/{id}/edit', 'PertanyaanController@editPilihanGanda');
        Route::post('master-data/pertanyaan/pilihan-ganda/{id}/edit', 'PertanyaanController@updatePilihanGanda');
        Route::post('master-data/pertanyaan/delete', 'PertanyaanController@delete');

        Route::get('master-data/pertanyaan/benar-salah/{pedoman_id}', 'PertanyaanController@createBenarSalahFromDisplay');
        Route::post('master-data/pertanyaan/benar-salah', 'PertanyaanController@storeBenarSalah');
        Route::get('master-data/pertanyaan/benar-salah/{id}/edit', 'PertanyaanController@editBenarSalah');
        Route::post('master-data/pertanyaan/benar-salah/{id}/edit', 'PertanyaanController@updateBenarSalah');
    });

    Route::group(['middleware' => ['permission:md_posisi']], function () {
        Route::get('master-data/posisi', 'StrukturPosisiController@index');
        Route::get('master-data/posisi/server-processing', 'StrukturPosisiController@serverProcessing');
    });

    Route::group(['middleware' => ['permission:md_organisasi']], function () {
        Route::get('master-data/organisasi', 'StrukturOrganisasiController@index');
        Route::get('master-data/organisasi/server-processing', 'StrukturOrganisasiController@serverProcessing');
    });

    Route::group(['middleware' => ['permission:md_jabatan']], function () {
        Route::get('master-data/jabatan', 'StrukturJabatanController@index');
        Route::get('master-data/jabatan/server-processing', 'StrukturJabatanController@serverProcessing');
    });

    Route::group(['middleware' => ['role:root']], function () {
        Route::get('master-data/nip', 'StrukturJabatanController@updateNIP');
        Route::get('master-data/email', 'StrukturJabatanController@updateEmail');
        Route::get('master-data/pegawai-kantor', 'StrukturJabatanController@updateKantor');
        Route::get('master-data/business-area', 'StrukturOrganisasiController@updateBusinessArea');
    });

    Route::group(['middleware' => ['permission:md_config']], function () {
        Route::get('master-data/config-label', 'ConfigLabelController@index');
        Route::post('master-data/config-label/list', 'ConfigLabelController@getList');
        Route::get('master-data/config-label/create', 'ConfigLabelController@create');
        Route::post('master-data/config-label/store', 'ConfigLabelController@store');
        Route::get('master-data/config-label/edit/{id}', 'ConfigLabelController@edit');
        Route::patch('master-data/config-label/edit/{id}', 'ConfigLabelController@update');
    });

    Route::group(['middleware' => ['permission:md_survey_question']], function () {
        Route::get('master-data/survey-question', 'SurveyQuestionController@index');
        Route::post('master-data/survey-question/list', 'SurveyQuestionController@getList');
        Route::get('master-data/survey-question/create', 'SurveyQuestionController@create');
        Route::post('master-data/survey-question/store', 'SurveyQuestionController@store');
        Route::get('master-data/survey-question/edit/{id}', 'SurveyQuestionController@edit');
        Route::patch('master-data/survey-question/edit/{id}', 'SurveyQuestionController@update');
    });
    /*
    |--------------------------------------------------------------------------
    | End Master Routes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Start User Routes
    |--------------------------------------------------------------------------
    */

    Route::get('profile', function () {
        return view('user_profile');
    });

    Route::get('ajax/tree/strukorg', 'StrukturOrganisasiController@getTree');
    Route::get('ajax/tree/strukorg-lazy', 'StrukturOrganisasiController@getTreeLazy');


    Route::get('coc/foto/{id}', 'GalleryController@getFoto');
    Route::get('coc/foto-dashboard/{id}', 'GalleryController@getFotoDashboard');
    Route::get('user/foto', 'UserController@getFotoUser');
    Route::get('user/foto/{id}', 'UserController@getFoto');
    Route::get('user/foto-thumb', 'UserController@getFotoThumb');
    Route::get('user/foto-thumb/{id}', 'UserController@getFotoThumbnail');
    Route::get('user/foto-icon/{filename}', 'UserController@getFotoIcon');
    Route::get('user/foto-thumb-user', 'UserController@getFotoUserThumbnail');
    Route::get('user/foto-pegawai/{nip}', 'UserController@getFotoFromNIP');
    Route::get('user/foto-pegawai-thumb/{nip}', 'UserController@getFotoFromNIPThumbnail');
    Route::get('user/ttd', 'UserController@getTtdUser');
    Route::get('user/ttd/{id}', 'UserController@getTtd');
//    Route::get('coc/atch/{id}', 'AttachmentController@getAttachment');
    Route::get('coc/atch/{id}', 'AttachmentMateriController@getAttachment');

    Route::post('user/{id}/update-foto', 'UserController@updateFoto');
    Route::post('user/{id}/update-ttd', 'UserController@updateTtd');


    Route::group(['middleware' => ['role:root']], function () {
//        Route::get('user-management/user', 'UserController@index');
//        Route::post('user-management/user', 'UserController@index');
        Route::get('user-management/user/create', 'UserController@create');
        Route::post('user-management/user/create', 'UserController@store');
//        Route::get('user-management/user/{id}', 'UserController@show');
        Route::post('user-management/user/{id}', 'UserController@update');
        Route::post('user-management/user/{id}/update-role', 'UserController@updateRole');

        Route::get('user-management/role', 'RoleController@index');
        Route::get('user-management/role/create', 'RoleController@create');
        Route::post('user-management/role/create', 'RoleController@store');
        Route::get('user-management/role/{id}', 'RoleController@edit');
        Route::post('user-management/role/{id}', 'RoleController@update');

        Route::resource('user-management/permission', 'PermissionController');

//        Route::get('import-email','UserController@importEmail');
    });

    Route::group(['middleware' => ['permission:user_list']], function () {
        Route::get('user-management/user', 'UserController@index');
        Route::post('user-management/user', 'UserController@index');
        Route::get('user-management/user/{id}', 'UserController@show');
        Route::get('user-management/nip/{nip}', 'UserController@showFromNIP');
        Route::get('user-management/username/{username}', 'UserController@showfromUsername');
        Route::get('user-management/user/{id}/edit', ['middleware' => ['permission:edit_user'], 'uses' => 'UserController@editStruktur']);
        Route::post('user-management/user/{id}/edit', ['middleware' => ['permission:edit_user'], 'uses' => 'UserController@updateStruktur']);
//        Route::get('user-management/user/{id}/edit', 'UserController@editStruktur');
//        Route::post('user-management/user/{id}/edit', 'UserController@updateStruktur');
//        Route::get('ajax/get-text-orgeh/{orgeh}', 'StrukturOrganisasiController@ajaxGetTextOrgeh');
    });
    /*
    |--------------------------------------------------------------------------
    | End User Routes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Start Employee Volunteer Program
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => ['permission:evp_list']], function () {
        Route::get('evp/program', 'EVPController@index');
        Route::post('evp/program', 'EVPController@index');
        Route::get('ajax/get-evp/{lokasi}', 'EVPController@ajaxEVPList');
    });

    Route::group(['middleware' => ['permission:evp_create']], function () {
        Route::get('evp/create/{id}', 'EVPController@create');
        Route::post('evp/create/{id}', 'EVPController@store');

    });

    Route::group(['middleware' => ['permission:evp_edit']], function () {
        Route::get('evp/edit/{id}', 'EVPController@edit');
        Route::post('evp/edit/{id}', 'EVPController@update');

    });

    Route::get('evp/detail/{id}', 'EVPController@show');
    Route::get('evp/dashboard', 'VolunteerController@index');

    Route::get('evp/download/{id}', 'EVPController@downloadDokumen');

    Route::get('evp/register/{id}', 'VolunteerController@create');
    Route::post('evp/register/{id}', 'VolunteerController@store');

    Route::get('evp/volunteer/{id}', 'VolunteerController@show');
    Route::get('evp/volunteer/{id}/edit', 'VolunteerController@edit');
    Route::post('evp/volunteer/{id}/edit', 'VolunteerController@update');

    Route::get('evp/approval-atasan', 'VolunteerController@approvalIndex');
    Route::get('evp/approval', 'EVPController@indexApproval');
    Route::get('evp/approval/volunteer/{id}', 'VolunteerController@approvalEVPIndex');

    Route::group(['middleware' => ['role:admin_evp']], function () {
        Route::get('evp/approval/send-to-gm/{id}', 'VolunteerController@sendNotifToGM');
    });

    Route::group(['middleware' => ['gm']], function () {
        Route::get('evp/approval/approve-all/{id}', 'VolunteerController@approveAllGM');
    });

    Route::get('evp/approval/{id}/{approver}', 'VolunteerController@approveEVP');
//    Route::get('evp/reject', 'VolunteerController@rejectEVP');
    Route::post('evp/reject', 'VolunteerController@rejectEVP');

//    Route::group(['middleware' => ['role:admin_evp|admin_pusat|root']], function () {
//    Route::group(['middleware' => ['permission:evp_log']], function () {
    Route::get('evp/log', 'ActivityLogController@index');
    Route::get('evp/log/volunteer/{id}', 'ActivityLogController@volunteerIndex');
//    });

    Route::get('evp/log/list/{id}', 'ActivityLogController@logIndex');

    Route::get('evp/log/approve/{id}', 'ActivityLogController@approveLog');
    Route::get('evp/log/approve-all/{id}', 'ActivityLogController@approveAllLog');

    Route::get('evp/log/create/{id}', 'ActivityLogController@create');
    Route::post('evp/log/create/{id}', 'ActivityLogController@store');

    Route::get('evp/log/edit/{id}', 'ActivityLogController@edit');
    Route::post('evp/log/edit/{id}', 'ActivityLogController@update');

    Route::post('evp/testimoni/create', 'VolunteerController@storeTestimoni');

    /*
    |--------------------------------------------------------------------------
    | End Employee Volunteer Program
    |--------------------------------------------------------------------------
    */


//    Route::get('/home', function () {
//        return view('dashboard');
//    });

    Route::get('/starter', function () {
        return view('starter');
//        return view('index');
    });

    // Route Pegawai
    Route::get('self-assessment/pegawai','SelfAssessmentController@indexPegawai');
    Route::get('self-assessment/get-detail-kkj/{jabatan_id}','SelfAssessmentController@getDetailKKJ');
    Route::get('self-assessment/get-detail-kompetensi/{kode_kompetensi}','SelfAssessmentController@getDetailKompetensi');
    Route::get('self-assessment/edit-kompetensi','SelfAssessmentController@editKompetensi');
    Route::post('self-assessment/update-kompetensi','SelfAssessmentController@updateKompetensi');
    Route::post('self-assessment/update-jabatan','SelfAssessmentController@updateJabatan');
    Route::get('self-assessment/ajax-verfikator', 'UserController@dataAjaxVerifikator');
    Route::post('self-assessment/update-verifikator','SelfAssessmentController@updateVerifikator');
    Route::post('self-assessment/update-hardskill','SelfAssessmentController@updateHardskill');
    Route::get('self-assessment/send-assessment/{peserta_id}','SelfAssessmentController@sendPengukuran');
    Route::post('self-assessment/feedback','SelfAssessmentController@submitFeedback');

    Route::get('self-assessment/ajax-level-profisiensi', 'SelfAssessmentController@dataAjaxKamusLevel');


    // Route Verifikator
    Route::group(['middleware' => ['verifikator_assessment']], function () {
        Route::get('self-assessment/verifikator','SelfAssessmentController@indexVerifikator');
        Route::get('self-assessment/get-detail-kkj-verifikator/{peserta_id}','SelfAssessmentController@getDetailKKJVerifikator');
        Route::get('self-assessment/edit-kompetensi-verifikator','SelfAssessmentController@editKompetensiVerifikator');
        Route::post('self-assessment/update-kompetensi-verifikator','SelfAssessmentController@updateKompetensiVerifikator');
        Route::get('self-assessment/approve/{peserta_id}','SelfAssessmentController@approvePengukuran');
        Route::get('self-assessment/unapprove/{peserta_id}','SelfAssessmentController@unapprovePengukuran');
        Route::get('self-assessment/detail-peserta/{peserta_id}','SelfAssessmentController@detailPeserta');
        Route::get('self-assessment/prioritas-pengembangan/{peserta_id}','SelfAssessmentController@prioritasPengembangan');
        Route::get('self-assessment/set-prioritas-pengembangan/{assessment_id}','SelfAssessmentController@setPrioritasPengembangan');

    });

    // Route Admin Assessment
    Route::group(['middleware' => ['permission:report_assessment']], function () {
        Route::get('self-assessment/daftar-peserta','SelfAssessmentController@daftarPeserta');
        // Route::get('self-assessment/detail-peserta/{peserta_id}','SelfAssessmentController@detailPeserta');

        // Route::get('self-assessment/get-detail-kkj-verifikator/{peserta_id}','SelfAssessmentController@getDetailKKJVerifikator');
        // Route::get('self-assessment/edit-kompetensi-verifikator','SelfAssessmentController@editKompetensiVerifikator');
        // Route::post('self-assessment/update-kompetensi-verifikator','SelfAssessmentController@updateKompetensiVerifikator');

        Route::post('self-assessment/add-peserta','SelfAssessmentController@submitPeserta');
        Route::get('self-assessment/remove-peserta/{peserta_id}','SelfAssessmentController@removePeserta');
        Route::get('self-assessment/export-rekap','SelfAssessmentController@exportRekap');
    });
    

});

Route::resource('sample', 'SampleController');
Route::get('api/check-absence','CocController@checkAbsence');
Route::post('api/check-absence','CocController@checkAbsence');
Route::post('api/check-absence-1-level','CocController@checkAbsence1Level');
Route::get('api/get-organisasi','CocController@getStrukturOrganisasi');
Route::post('api/get-organisasi','StrukturOrganisasiController@getStrukturOrganisasi');
Route::post('api/get-data-organisasi-pegawai','StrukturOrganisasiController@getDataPegawai');
Route::post('api/get-data-organisasi-pegawai-1-level','StrukturOrganisasiController@getDataPegawai1Level');
Route::post('api/get-coc-nasional','CocController@getCocNasional');
Route::post('api/get-children-organisasi','StrukturOrganisasiController@getStrukturOrganisasi1Level');
Route::get('telebot/webhook/bot/1845084070:AAFpp46au_1ttBU4JVI48IPTv9EfLf0fWZg','BotController@handler');
Route::post('telebot/webhook/bot/1845084070:AAFpp46au_1ttBU4JVI48IPTv9EfLf0fWZg','BotController@handler');
Route::post('api/update-replied-bot-log','BotController@updateRepliedBotLog');