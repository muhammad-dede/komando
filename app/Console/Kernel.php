<?php

namespace App\Console;

use App\Autocomplete;
use App\Console\Commands\AutoCompleteCoC;
use App\Console\Commands\BotSendNotifCoc;
use App\Console\Commands\BotSendNotifDivisi;
use App\Console\Commands\CheckInCoC;
use App\Console\Commands\CheckSchedulerWorking;
use App\Console\Commands\Commitment;
use App\Console\Commands\DeleteCoC;
use App\Console\Commands\DeleteLiquidRecords;
use App\Console\Commands\FixEmptyPesertaSnapshot;
use App\Console\Commands\FixPenilaianDuplikat;
use App\Console\Commands\GenerateTest;
use App\Console\Commands\HapusPesertaDuplikat;
use App\Console\Commands\InterfaceESS;
use App\Console\Commands\InterfaceSAP;
use App\Console\Commands\NotifyAtasanLiquid;
use App\Console\Commands\NotifyPesertaLiquid;
use App\Console\Commands\RefreshDatabaseView;
use App\Console\Commands\ReopenCoC;
use App\Console\Commands\SendEmail;
use App\Console\Commands\SendLiquidMail;
use App\Console\Commands\UpdateJabatanPesertaSelfAssessment;
use App\Console\Commands\UpdateUnitPesertaSelfAssessment;
use App\Console\Commands\UpdateVerifikatorPesertaSelfAssessment;
use App\Console\Commands\CleansingDataSelfAssessment;
use App\Console\Commands\CreateOrUpdateUserFromIAM;
use App\Console\Commands\CreateUserSHAPFromAD;
use App\Console\Commands\ExportReportCommitment;
use App\Console\Commands\GetAllDataSHAP;
use App\Console\Commands\IntegrasiHXMS;
use App\Console\Commands\ResetRoleSHAP;
use App\Console\Commands\UpdateDapegUserHolding;
use App\Console\Commands\UpdateDataADSHAP;
use App\Console\Commands\UpdateUserSHAP;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateTest::class,
        RefreshDatabaseView::class,
        NotifyPesertaLiquid::class,
        CheckSchedulerWorking::class,
        SendLiquidMail::class,
        DeleteLiquidRecords::class,
        HapusPesertaDuplikat::class,
        NotifyAtasanLiquid::class,
        FixPenilaianDuplikat::class,
        FixEmptyPesertaSnapshot::class,
        InterfaceSAP::class,
        InterfaceESS::class,
        SendEmail::class,
        Commitment::class,
        DeleteCoC::class,
        BotSendNotifCoc::class,
        BotSendNotifDivisi::class,
        ReopenCoC::class,
        UpdateJabatanPesertaSelfAssessment::class,
        UpdateUnitPesertaSelfAssessment::class,
        UpdateVerifikatorPesertaSelfAssessment::class,
        CleansingDataSelfAssessment::class,
        ExportReportCommitment::class,
        CheckInCoC::class,
        IntegrasiHXMS::class,
        UpdateUserSHAP::class,
        UpdateDapegUserHolding::class,
        AutoCompleteCoC::class,
        GetAllDataSHAP::class,
        UpdateDataADSHAP::class,
        CreateUserSHAPFromAD::class,
        ResetRoleSHAP::class,
        CreateOrUpdateUserFromIAM::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:check-scheduler')->everyMinute();

        //$schedule->call('\App\Http\Controllers\MailController@sendMail')->everyMinute();
        //$schedule->call('\App\Http\Controllers\MailController@sendMailAdmin')->everyMinute();
        //$schedule->call('\App\Http\Controllers\MailController@sendMailCoc')->everyMinute();
        //$schedule->call('\App\Http\Controllers\InterfaceController@updateDataSAP')->everyMinute();
        //$schedule->call('\App\Http\Controllers\InterfaceController@updateDataESS')->everyMinute();
//        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataESSLive')->everyMinute();

        /*$schedule->call('\App\Http\Controllers\UserController@convertImage')
        ->dailyAt('20:30')
        ->timezone('Asia/Jakarta');*/

        /*$schedule->call('\App\Http\Controllers\GalleryController@massConvertGallery')
            ->dailyAt('22:47')
            ->timezone('Asia/Jakarta');*/

        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataESS')
            ->dailyAt('03:20')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataSAP')
            ->dailyAt('02:30')
            ->timezone('Asia/Jakarta');
/*
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataESS')
            ->monthlyOn(25, '03:00')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataSAP')
            ->monthlyOn(25, '02:30')
            ->timezone('Asia/Jakarta');
        $schedule->command('hxms:update-user-holding')
            ->monthlyOn(25, '03:30')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@checkStatus')
            ->monthlyOn(25, '05:00')
            ->timezone('Asia/Jakarta');

        
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataESS')
            ->dailyAt('03:00')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataSAP')
            ->dailyAt('02:30')
            ->timezone('Asia/Jakarta');
        $schedule->command('hxms:update-user-holding')
            ->dailyAt('03:30')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@checkStatus')
            ->dailyAt('05:00')
            ->timezone('Asia/Jakarta');

        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataESS')
            ->dailyAt('18:00')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@updateDataSAP')
            ->dailyAt('19:00')
            ->timezone('Asia/Jakarta');
        $schedule->command('hxms:update-user-holding')
            ->dailyAt('20:00')
            ->timezone('Asia/Jakarta');
        $schedule->call('\App\Http\Controllers\InterfaceController@checkStatus')
            ->dailyAt('21:00')
            ->timezone('Asia/Jakarta');

            */

        $schedule->call('\App\Http\Controllers\MailController@sendMailCoc')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@sendMailAdmin')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@sendMail')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@sendMailEVP')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@sendMailInterfaceLog')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@sendMailOrganisasiBaru')->everyMinute();
        $schedule->call('\App\Http\Controllers\VolunteerController@updateStatusVolunteer')
            ->dailyAt('01:00')
            ->timezone('Asia/Jakarta');

        // $schedule->call('\App\Http\Controllers\CocController@autoCompleteRoomCoc')
        //     ->dailyAt('00:01')
        //     ->timezone('Asia/Jakarta');

        $schedule->command('coc:autocomplete')
            ->dailyAt('00:01')
            ->timezone('Asia/Jakarta');

        $schedule->command('liquid:notify')
            ->dailyAt('01:30')
            ->timezone('Asia/Jakarta');

        $schedule->command('liquid:notify-atasan')
            ->dailyAt('00:01')
            ->timezone('Asia/Jakarta');

        $schedule->command('liquid:send-mail')->everyMinute();

        // refresh materialized view
        $schedule->command('app:refresh-db-view')->dailyAt('00:30');

        // mail extend vpn
        // $schedule->command('email:vpn')->monthlyOn(28, '09:00');

        // bot notif report coc
        $schedule->command('bot:send-divisi DIVSTI')
                ->dailyAt('08:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('bot:send-divisi DIVSTI')
                ->dailyAt('10:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('bot:send-divisi DIVSTI')
                ->dailyAt('12:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('bot:send-divisi DIVSTI')
                ->dailyAt('14:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('bot:send-divisi DIVSTI')
                ->dailyAt('16:00')
                ->timezone('Asia/Jakarta');


        // $schedule->call('\App\Http\Controllers\ReadMateriController@deleteDuplicate')->everyMinute();

        // $schedule->call('\App\Http\Controllers\CocController@autoCompleteRoom')->everyFiveMinutes()->withoutOverlapping();
        // $schedule->call('\App\Http\Controllers\CocController@autoCompleteRoomTahun')->everyMinute();
        /*  $schedule->call('\App\Http\Controllers\CocController@autoCompleteRoom')
            ->dailyAt('00:00')
            ->timezone('Asia/Jakarta');*/
        /*$schedule->call('\App\Http\Controllers\InterfaceController@resetCommitment')
            ->dailyAt('23:20')
            ->timezone('Asia/Jakarta');*/

        //$schedule->call('\App\Http\Controllers\CocController@massUpdateJenisCoC')->everyMinute();
        //$schedule->call('\App\Http\Controllers\CocController@massUpdatePlansLeaderCoc')->everyMinute();
        // $schedule->call('\App\Http\Controllers\CocController@autoCompleteRoomCoc')->everyMinute();
        
        $schedule->call('\App\Http\Controllers\MailController@MailLogLiquid')->daily();
        $schedule->call('\App\Http\Controllers\MailController@SendMailInfomationLiquid')->everyMinute();
        $schedule->call('\App\Http\Controllers\MailController@SendMailProsesLiquid')->everyMinute();
    }
}
