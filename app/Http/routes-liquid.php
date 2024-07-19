<?php

Route::group(['middleware' => ['auth']], function () {

    Route::group([
		'middleware'	=> ['permission:liquid_access_dashboard'],
		'prefix'		=> 'dashboard-admin'
	], function () {
        Route::get('liquid-jadwal', 'Liquid\\DashboardAdmin\\LiquidController@index')->name('dashboard-admin.liquid-jadwal.index');
        Route::get('liq-status', 'Liquid\\DashboardAdmin\\StatusLiquidController@index')->name('dashboard-admin.liquid-status.index');
        Route::get('liquid-all', 'Liquid\\DashboardAdmin\\LiquidAllController@index')->name('dashboard-admin.liquid-all.index');
        Route::get('download-report-liquid', 'Liquid\\DashboardAdmin\\LiquidReportController@index')->name('dashboard-admin.liquid.index');
        Route::get('download-report-liquid/{liquid_id}/{pernr}', 'Liquid\\DashboardAdmin\\LiquidReportController@show')->name('dashboard-admin.liquid.show');
        Route::get('download-report-liquid/download', 'Liquid\\DashboardAdmin\\LiquidReportController@download')->name('dashboard-admin.liquid.download');
        Route::get('download-peserta-liquid', 'Liquid\\DashboardAdmin\\LiquidReportController@downloadLiquidHistory')->name('dashboard-admin.liquid-peserta.download');
        Route::get('download-rekap-partisipan', 'Liquid\\DashboardAdmin\\LiquidReportController@downloadRekapPartisipan')->name('dashboard-admin.liquid-rekap-partisipan.download');
        Route::get('rekap-feedback', 'Liquid\\DashboardAdmin\\RekapFeedbackController@index')->name('dashboard-admin.rekap-feedback.index');
        Route::get('rekap-feedback/download', 'Liquid\\DashboardAdmin\\RekapFeedbackController@download')->name('dashboard-admin.rekap-feedback.download');
        Route::get('rekap-kelebihan', 'Liquid\\DashboardAdmin\\RekapKelebihanController@index')->name('dashboard-admin.rekap-kelebihan.index');
        Route::get('rekap-all-kelebihan', 'Liquid\\DashboardAdmin\\RekapKelebihanController@all')->name('dashboard-admin.rekap-kelebihan.all');
        Route::get('rekap-kelebihan/download', 'Liquid\\DashboardAdmin\\RekapKelebihanController@download')->name('dashboard-admin.rekap-kelebihan.download');
        Route::get('rekap-kekurangan', 'Liquid\\DashboardAdmin\\RekapKekuranganController@index')->name('dashboard-admin.rekap-kekurangan.index');
        Route::get('rekap-all-kekurangan', 'Liquid\\DashboardAdmin\\RekapKekuranganController@all')->name('dashboard-admin.rekap-kekurangan.all');
        Route::get('rekap-kekurangan/download', 'Liquid\\DashboardAdmin\\RekapKekuranganController@download')->name('dashboard-admin.rekap-kekurangan.download');
        Route::get('rekap-partisipan', 'Liquid\\DashboardAdmin\\RekapPartisipanController@index')->name('dashboard-admin.rekap-partisipan.index');
        Route::get('rekap-progress-liquid', 'Liquid\\DashboardAdmin\\RekapProgressLiquidController@index')->name('dashboard-admin.rekap-progress-liquid.index');
        Route::get('rekap-progress-liquid/download', 'Liquid\\DashboardAdmin\\RekapProgressLiquidController@download')->name('dashboard-admin.rekap-progress-liquid.download');
		Route::get('history-penilaian/show/{liquid_id}/{pernr}', ['middleware' => ['permission:liquid_info_detil_pelaksannan'], 'uses' => 'Liquid\\DashboardAdmin\\HistoryPenilaianController@show'])->name('dashboard-admin.history-penilaian.show');
		Route::get('liquid-jadwal/notification/{liquidPesertaId}', ['middleware' => ['permission:liquid_send_notification_bawahan'], function ($liquidPesertaId) {
			return app(\App\Services\LiquidPesertaService::class)
				->sendBawahanNotification($liquidPesertaId);
		}])->name('dashboard-admin.liquid-peserta.notify');
        Route::post('self-service', 'Liquid\\DashboardAdmin\\WidgetController@selfService')->name('dashboard-admin.self-service.selfService');
    });

    Route::get('dashboard-atasan/liquid-status', 'Liquid\\DashboardAtasan\\StatusLiquidController@index')->name('dashboard-atasan.liquid-status.index');
    Route::get('dashboard-atasan/liquid-jadwal', 'Liquid\\DashboardAtasan\\LiquidController@index')->name('dashboard-atasan.liquid-jadwal.index');
    Route::get('dashboard-atasan/feedback', 'Liquid\\DashboardAtasan\\FeedbackController@index')->name('dashboard-atasan.feedback.index');
    Route::get('dashboard-atasan/saran-harapan', 'Liquid\\DashboardAtasan\\SaranHarapanController@index')->name('dashboard-atasan.saran-harapan.index');
    Route::get('dashboard-atasan/history-penilaian', 'Liquid\\DashboardAtasan\\HistoryPenilaianController@index')->name('dashboard-atasan.history-penilaian.index');
    Route::get('dashboard-atasan/history-penilaian/show/{liquid_id}', 'Liquid\\DashboardAtasan\\HistoryPenilaianController@show')->name('dashboard-atasan.history-penilaian.show');

    Route::get('dashboard-bawahan/liquid-status', 'Liquid\\DashboardBawahan\\StatusLiquidController@index')->name('dashboard-bawahan.liquid-status.index');
    Route::get('dashboard-bawahan/liquid-jadwal', 'Liquid\\DashboardBawahan\\LiquidController@index')->name('dashboard-bawahan.liquid-jadwal.index');
    Route::get('dashboard-bawahan/penilaian-atasan', 'Liquid\\DashboardBawahan\\PenilaianAtasanController@index')->name('dashboard-bawahan.penilaian-atasan.index');
    Route::get('dashboard-bawahan/resolusi-atasan', 'Liquid\\DashboardBawahan\\ResolusiAtasanController@index')->name('dashboard-bawahan.resolusi-atasan.index');
    Route::get('dashboard-bawahan/history-penilaian/show/{liquid_peserta_id}', 'Liquid\\DashboardBawahan\\HistoryPenilaianController@show')->name('dashboard-bawahan.history-penilaian.show');
    Route::get('dashboard-bawahan/add-atasan', 'Liquid\\DashboardBawahan\\AddAtasanController@index')->name('dashboard-bawahan.add-atasan.index');
    Route::post('dashboard-bawahan/table-add-atasan', 'Liquid\\DashboardBawahan\\AddAtasanController@tableAddAtasan')->name('dashboard-bawahan.add-atasan.tableAddAtasan');
    Route::post('dashboard-bawahan/save-add-atasan', 'Liquid\\DashboardBawahan\\AddAtasanController@saveAtasan')->name('dashboard-bawahan.add-atasan.saveAtasan');

    Route::group(['middleware' => ['permission:md_media']], function () {
        Route::get('manajemen-media-banner', 'Liquid\\ManajemenMediaBannerController@index')->name('manajemen-media-banner.index');
        Route::get('manajemen-media-banner/create', 'Liquid\\ManajemenMediaBannerController@create')->name('manajemen-media-banner.create');
        Route::get('manajemen-media-banner/{id}', 'Liquid\\ManajemenMediaBannerController@show')->name('manajemen-media-banner.show');
        Route::post('manajemen-media-banner', 'Liquid\\ManajemenMediaBannerController@store')->name('manajemen-media-banner.store');
        Route::get('manajemen-media-banner/{id}/edit', 'Liquid\\ManajemenMediaBannerController@edit')->name('manajemen-media-banner.edit');
        Route::put('manajemen-media-banner/{id}', 'Liquid\\ManajemenMediaBannerController@update')->name('manajemen-media-banner.update');
        Route::delete('manajemen-media-banner/{id}', 'Liquid\\ManajemenMediaBannerController@destroy')->name('manajemen-media-banner.destroy');
    });

    Route::get('liquid/dashboard', 'Liquid\\DashboardController@index')->name('liquid.dashboard');

	Route::group(['middleware' => ['permission:liquid_create_liquid']], function () {
		Route::get('liquid/create', 'Liquid\\LiquidController@create')->name('liquid.create');
		Route::post('liquid', 'Liquid\\LiquidController@store')->name('liquid.store');
		Route::get('liquid/{liquid}/edit', 'Liquid\\LiquidController@edit')->name('liquid.edit');
		Route::get('liquid/{liquid}/show', 'Liquid\\LiquidController@show')->name('liquid.show');
		Route::put('liquid/{liquid}', 'Liquid\\LiquidController@update')->name('liquid.update');
		Route::delete('liquid/{liquid}/destroy', 'Liquid\\LiquidController@destroy')->name('liquid.destroy');

		Route::get('liquid/{liquid}/unit-kerja/edit', 'Liquid\\LiquidUnitKerjaController@edit')->name('liquid.unit-kerja.edit');
		Route::get('liquid/{liquid}/unit-kerja/show', 'Liquid\\LiquidUnitKerjaController@show')->name('liquid.unit-kerja.show');
		Route::put('liquid/{liquid}/unit-kerja', 'Liquid\\LiquidUnitKerjaController@update')->name('liquid.unit-kerja.update');

		Route::get('liquid/{liquid}/peserta/edit', 'Liquid\\LiquidPesertaController@edit')->name('liquid.peserta.edit');
		Route::get('liquid/{liquid}/peserta/show', 'Liquid\\LiquidPesertaController@show')->name('liquid.peserta.show');

		Route::get('liquid/{liquid}/dokumen/edit', 'Liquid\\LiquidDokumenController@edit')->name('liquid.dokumen.edit');
		Route::get('liquid/{liquid}/dokumen/show', 'Liquid\\LiquidDokumenController@show')->name('liquid.dokumen.show');
		Route::put('liquid/{liquid}/dokumen', 'Liquid\\LiquidDokumenController@update')->name('liquid.dokumen.update');
		Route::get('liquid/{liquid}/dokumen/{media}/delete', 'Liquid\\LiquidDokumenController@destroy')->name('liquid.dokumen.destroy');

		Route::get('liquid/{liquid}/gathering/edit', 'Liquid\\LiquidGatheringController@edit')->name('liquid.gathering.edit');
		Route::get('liquid/{liquid}/gathering/show', 'Liquid\\LiquidGatheringController@show')->name('liquid.gathering.show');
		Route::put('liquid/{liquid}/gathering', 'Liquid\\LiquidGatheringController@update')->name('liquid.gathering.update');
        
        Route::get('expor-excel/{liquid}/peserta-less-than-3', 'Liquid\\LiquidGatheringController@ExcelPesertaLessThan3')->name('liquid.gathering.ExcelPesertaLessThan3');
	});

	Route::group(['middleware' => ['permission:liquid_create_liquid|liquid_edit_peserta_bawahan']], function () {
        Route::post('liquid/{liquid}/peserta', 'Liquid\\LiquidPesertaController@store')->name('liquid.peserta.store');
        Route::put('liquid/{liquid}/peserta', 'Liquid\\LiquidPesertaController@update')->name('liquid.peserta.update');
        Route::delete('liquid/{liquid}/peserta/{id}', 'Liquid\\LiquidPesertaController@destroy')->name('liquid.peserta.destroy');
	});

    Route::get('media/{media}', 'Liquid\\MediaController@destroy')->name('media.destroy');

	Route::group([
		'prefix' 		=> 'master-data',
		'middleware'	=> ['permission:md_kelebihan_kekurangan']
	], function () {
		Route::resource('kelebihan-kekurangan', 'Liquid\\KelebihanKekuranganController');
	});
    Route::group([
        'prefix' 		=> 'master-data',
        'middleware'	=> ['permission:md_faq_manual_book']
    ], function () {
        Route::get('faq-manual-book', 'Liquid\\FaqManualBookController@edit')->name('faq-manual-book.index');
        Route::post('faq-manual-book', 'Liquid\\FaqManualBookController@update')->name('faq-manual-book.update');
    });


    Route::post('feedback/save-survey', 'Liquid\\FeedbackController@save_survey');
    Route::resource('feedback','Liquid\\FeedbackController', ['except' => ['show', 'destroy']]);

	Route::resource('penyelarasan', 'Liquid\\PenyelarasanController');

	Route::resource('penilaian', 'Liquid\\PengukuranPertamaController');

	Route::resource('pengukuran-kedua', 'Liquid\\PengukuranKeduaController');
	Route::put('pengukuran-kedua/toggle/{liquid}/{pernr}', 'Liquid\\PengukuranKeduaController@toggle')->name('pengukuran-kedua.toggle');

	Route::resource('activity-log', 'Liquid\\ActivityLogBookController');

	Route::get('videos', function () {
		return view('liquid.media.video_all');
	})->name('videos.all');

    /* link testing broadcast email */
    Route::get('timeline/input-mail-log/{created_at}', 'MailController@MailLogLiquid_2')->name('dashboard-admin.mail-testing.MailLogLiquid_2');
    Route::get('timeline/info-peserta/{valid_dump}', 'MailController@SendMailInfomationLiquid_2')->name('dashboard-admin.mail-testing.SendMailInfomationLiquid_2');
    Route::get('timeline/info-pelaksanaan/{time_now}/{valid_dump}', 'MailController@SendMailProsesLiquid_2')->name('dashboard-admin.mail-testing.SendMailProsesLiquid_2');
    /*  END link testing broadcast email */
});
