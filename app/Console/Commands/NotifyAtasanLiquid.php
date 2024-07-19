<?php

namespace App\Console\Commands;

use App\Enum\JenisEmail;
use App\Enum\ReminderAksiResolusi;
use App\MailLog;
use App\Models\Liquid\BusinessArea;
use App\Models\Liquid\Liquid;
use App\Notification;
use App\Services\LiquidService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class NotifyAtasanLiquid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquid:notify-atasan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify peserta liquid (atasan) terkait reminder aksi nyata';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Notify Pelaksanaan Activity (Aksi Nyata) Atasan');
        $this->notifyPelaksanaanActivityAtasan();
    }

    public function notifyPelaksanaanActivityAtasan()
    {
        $liquids = Liquid::query()
            ->currentYear()
            ->where(function (Builder $sub) {
                $today = Carbon::today();
                $sub->where('pengukuran_pertama_end_date', '<=', $today)
                    ->where('pengukuran_kedua_start_date', '>=', $today);
            })->get()
            ->filter(function ($liquid) {
                $dates = $this->getTanggalNotifikasiAksiNyata($liquid);

                if (! empty($dates)) {
                    foreach ($dates as $date) {
                        return strtotime(Carbon::today()) === strtotime($date);
                    }
                }
            });

        $this->info(sprintf('Memproses %d liquid', $liquids->count()));

        $userExists = [];
        $userNotExists = [];

        foreach ($liquids as $liquid) {
            $this->info(sprintf('Liquid #%d', $liquid->getKey()));

            $atasans = app(LiquidService::class)
                ->listPeserta($liquid);
            $creator = $liquid->creator;

            $bar = $this->output->createProgressBar(count($atasans) + count($creator));

            foreach ($atasans as $atasan) {
                $businessArea = BusinessArea::where('business_area', $atasan['business_area']);
                $this->notifyAdminCreator($creator, $liquid, 'Pelaksanaan Aksi Nyata', $businessArea, $bar);

                $atasanToNotify = User::query()
                    ->where('nip', $atasan['nip'])
                    ->first();

                if (! empty($atasanToNotify)) {
                    $userExists[] = $atasan['nip'];

                    $notification = new Notification();
                    $notification->from = 'SYSTEM';
                    $notification->user_id_from = 1;
                    $notification->to = $atasanToNotify->username2;
                    $notification->user_id_to = $atasanToNotify->getKey();
                    $notification->subject = 'Jadwal Pelaksanaan Aksi Nyata';
                    $notification->color = 'info';
                    $notification->icon = 'fa fa-info';

                    $notification->message = "\"Pelaksanaan Aksi Nyata Resolusi\"."
                        ." Silahkan melakukan Aksi Nyata Resolusi dengan tim Sesuai Resolusi yang Sudah ditetapkan."
                        ." Setiap atasan minimal melakukan 4 kegiatan Aksi Nyata Resolusi dalam sati bulan. Terimakasih.";

                    $notification->url = "activity-log/create";
                    $saved = $notification->save();

                    if ($saved && $atasanToNotify->email) {
                        $mail = new MailLog();
                        $mail->to = $atasanToNotify->email;
                        $mail->to_name = $atasanToNotify->name;
                        $mail->subject = $notification->subject;

                        $mail->file_view = 'emails.liquid_published';
                        $mail->message = $notification->message;

                        $mail->status = 'CRTD';
                        $mail->parameter = json_encode(['pesan' => $mail->message]);
                        $mail->notification_id = $notification->id;
                        $mail->jenis = JenisEmail::LIQUID;
                        $mail->save();
                    } else {
                        $userNotExists[] = $atasan['nip'];
                    }

                    $bar->advance();
                }
            }

            $bar->finish();
            $this->line('');
            $this->info(sprintf('%d peserta dinotifikasi', count($userExists)));
            $this->info(sprintf('%d peserta tidak punya akun', count($userNotExists)));
        }
    }

    public function getTanggalNotifikasiAksiNyata(Liquid $liquid)
    {
        $dates = [];
        $currentDate = Carbon::today();

        $i = 0;
        while (strtotime($currentDate) <= strtotime($liquid->pengukuran_kedua_start_date)) {
            $dates[$i] = $currentDate;

            $currentDate = $currentDate->addDays(
                ReminderAksiResolusi::toNumber($liquid->reminder_aksi_resolusi)
            );
            $i += 1;
        }

        return $dates;
    }

    public function notifyAdminCreator(User $creator, Liquid $liquid, $activity, $businessArea, $bar)
    {
        $notif = new Notification();
        $notif->from = 'SYSTEM';
        $notif->user_id_from = 1;
        $notif->to = $creator->username2;
        $notif->user_id_to = $creator->getKey();
        $notif->subject = 'Jadwal '.$activity;
        $notif->color = 'info';
        $notif->icon = 'fa fa-info';
        $notif->message = sprintf(
            '"Pelaksanaan "'.$activity.' '.$businessArea->description.'. Tanggal %s s/d %s.',
            $liquid->feedback_start_date->format('d-m-Y'),
            $liquid->feedback_end_date->format('d-m-Y')
        );
        $notif->url = ' dashboard-admin/liquid-jadwal?unit_code='.$businessArea->business_area;
        $saved = $notif->save();

        if ($saved && $creator->email) {
            $mail = new MailLog();
            $mail->to = $creator->email;
            $mail->to_name = $creator->name;
            $mail->subject = $notif->subject;

            $mail->file_view = 'emails.liquid_published';
            $mail->message = $notif->message;

            $mail->status = 'CRTD';
            $mail->parameter = json_encode(['pesan' => $mail->message]);
            $mail->notification_id = $notif->id;
            $mail->jenis = JenisEmail::LIQUID;
            $mail->save();
        }

        $bar->advance();
    }
}
