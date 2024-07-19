<?php

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
	Route::resource('pegawai', 'PegawaiController', ['only' => 'index']);
    Route::get('pegawaiAtasan', 'PegawaiController@selfService')->name('pegawaiAtasan.selfService');

	Route::group(['prefix' => 'liquid-report', 'middleware' => ['auth']], function () {
		Route::get('rekap-feedback-lainnya', 'Liquid\\DatatableLiquidReportController@fetchRekapFeedbackLainnya')
			->name('data_table.rekap.feedback.lainnya');
        Route::get('rekap-liquid', 'Liquid\\DatatableLiquidReportController@fetchRekapLiquid')
            ->name('data_table.rekap.liquid');
        Route::get('liquid-history', 'Liquid\\DatatableLiquidReportController@fetchHistoryInformation')
            ->name('data_table.liquid.history');
            Route::get('liquid-history-less-than-3', 'Liquid\\DatatableLiquidReportController@fetchHistoryInformationLessThan3')
            ->name('data_table.liquid.history.lessThan.3');
        Route::get('rekap-partisipan', 'Liquid\\DatatableLiquidReportController@fetchRekapPartisipan')
            ->name('data_table.rekap.partisipan');
	});

    Route::group(['prefix' => 'report', 'middleware' => ['auth']], function () {
        Route::get('history-coc', 'ReportController@fetchHistoryCoc')
            ->name('data_table.report.history.coc');
        Route::get('status-coc', 'ReportController@fetchReportCoc')
            ->name('data_table.report.status.coc');
        Route::get('status-coc-cc', 'ReportController@fetchStatusCocCompanyCode')
            ->name('data_table.report.status.coc.cc');
        Route::get('persentase-coc', 'ReportController@fetchPersentaseCoc')
            ->name('data_table.report.persentase.coc');
        Route::get('briefing-coc', 'ReportController@fetchBriefingCoc')
            ->name('data_table.report.briefing.coc');

        Route::get('monitoring-checkin-coc', 'ReportController@fetchMonitoringCheckinCoc')
        ->name('data_table.report.monitoring.coc');

        Route::get('persentase-baca-materi', 'ReportController@fetchPersentaseBacaMateri')
        ->name('data_table.report.monitoring.materi');

        Route::get('monitoring-baca-materi-pegawai', 'ReportController@fetchMonitoringBacaMateriPegawai')
        ->name('data_table.report.baca.materi.pegawai');

        Route::get('persentase-baca-materi-pegawai', 'ReportController@fetchPersentaseBacaMateriPegawai')
        ->name('report.baca.materi.pegawai.persentase');
    });

    Route::get('spell-checker', 'SpellCheckerController@index');
});
