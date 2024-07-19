<?php

namespace App\Console\Commands;

use App\Enum\JenisEmail;
use App\MailLog;
use App\Models\Liquid\BusinessArea;
use App\Models\Liquid\Liquid;
use App\Notification;
use App\Services\LiquidService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class NotifyPesertaLiquid extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'liquid:notify';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Notify peserta liquid terkait tanggal penting';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $this->warn('Notify Jadwal Feedback');
        $this->notifyJadwalFeedback();

        $this->warn('Notify Jadwal Penyelarasan');
        $this->notifyJadwalPenyelarasan();

        $this->warn('Notify Jadwal Pengukuran Pertama');
        $this->notifyJadwalPengukuranPertama();

        $this->warn('Notify Jadwal Pengukuran Kedua');
        $this->notifyJadwalPengukuranKedua();
    }

    protected function notifyJadwalFeedback()
    {
        $liquids = Liquid::where(function (Builder $sub) {
            $today = Carbon::today();
            $sub->where('feedback_start_date', $today)
                ->orWhere('feedback_end_date', $today);
        })->get();

        $this->info(sprintf('Memproses %d liquid', $liquids->count()));

        $userExists = [];
        $userNotExists = [];

        foreach ($liquids as $liquid) {
            $this->info(sprintf('Liquid #%d', $liquid->getKey()));

            $peserta = app(LiquidService::class)->listPeserta($liquid);
            $creator = $liquid->creator;
            $totalBawahan = collect($peserta)->sum(function ($peserta) use ($creator) {
                return count($peserta['peserta']);
            });

            $bar = $this->output->createProgressBar($totalBawahan + count($creator));

            foreach ($peserta as $atasan) {
                $businessArea = BusinessArea::where('business_area', $atasan['business_area'])
                    ->first();
                $this->notifyAdminCreator($creator, $liquid, 'Feedback', $businessArea, $bar);

                foreach ($atasan['peserta'] as $bawahanId => $bawahan) {
                    $userToNotify = User::query()->where('nip', $bawahan['nip'])->first();
                    if ($userToNotify) {
                        $userExists[] = $bawahanId;
                        $notif = new Notification();
                        $notif->from = 'SYSTEM';
                        $notif->user_id_from = 1;
                        $notif->to = $userToNotify->username2;
                        $notif->user_id_to = $userToNotify->getKey();
                        $notif->subject = 'Jadwal Feedback';
                        $notif->color = 'info';
                        $notif->icon = 'fa fa-info';
                        $notif->message = sprintf(
                            '"Pelaksanaan Feedback". Atasan: %s. NIP: %s. JABATAN: %s. KANTOR: %s - %s. Tanggal %s s/d %s.',
                            $atasan['nama'],
                            $atasan['nip'],
                            $atasan['jabatan'],
                            $atasan['business_area'],
                            $businessArea->description,
                            $liquid->feedback_start_date->format('d-m-Y'),
                            $liquid->feedback_end_date->format('d-m-Y')
                        );
                        $notif->url = route('feedback.create').'?liquid_peserta_id='.$bawahan['liquid_peserta_id'].'&liquid_id='.$liquid->id;
                        $saved = $notif->save();

                        if ($saved && $userToNotify->email) {
                            $mail = new MailLog();
                            $mail->to = $userToNotify->email;
                            $mail->to_name = $userToNotify->name;
                            $mail->subject = $notif->subject;

                            $mail->file_view = 'emails.liquid_published';
                            $mail->message = $notif->message;

                            $mail->status = 'CRTD';
                            $mail->parameter = json_encode(['pesan' => $mail->message]);
                            $mail->notification_id = $notif->id;
                            $mail->jenis = JenisEmail::LIQUID;
                            $mail->save();
                        }
                    } else {
                        $userNotExists[] = $bawahanId;
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

    protected function notifyJadwalPenyelarasan()
    {
        $liquids = Liquid::where(function (Builder $sub) {
            $today = Carbon::today();
            $sub->where('penyelarasan_start_date', $today)
                ->orWhere('penyelarasan_end_date', $today);
        })->get();

        $this->info(sprintf('Memproses %d liquid', $liquids->count()));

        $userExists = [];
        $userNotExists = [];

        foreach ($liquids as $liquid) {
            $this->info(sprintf('Liquid #%d', $liquid->getKey()));

            $peserta = app(LiquidService::class)->listPeserta($liquid);
            $creator = $liquid->creator;
            $totalBawahan = collect($peserta)->sum(function ($peserta) {
                return count($peserta['peserta']);
            });
            $bar = $this->output->createProgressBar($totalBawahan + count($creator));

            foreach ($peserta as $atasanId => $atasan) {
                $businessArea = BusinessArea::where('business_area', $atasan['business_area'])
                    ->first();
                $this->notifyAdminCreator($creator, $liquid, 'Penyelarasan', $businessArea, $bar);

                $userToNotify = User::query()->where('nip', $atasan['nip'])->first();
                if ($userToNotify) {
                    $userExists[] = $atasanId;
                    $notif = new Notification();
                    $notif->from = 'SYSTEM';
                    $notif->user_id_from = 1;
                    $notif->to = $userToNotify->username2;
                    $notif->user_id_to = $userToNotify->getKey();
                    $notif->subject = 'Jadwal Penyelarasan';
                    $notif->color = 'info';
                    $notif->icon = 'fa fa-info';
                    $notif->message = sprintf(
                        '"Pelaksanaan Penyelarasan". Silakan melakukan penyelarasan dengan tim mengenai Resolusi yang akan Anda lakukan. Tanggal %s s/d %s.',
                        $liquid->penyelarasan_start_date->format('d-m-Y'),
                        $liquid->penyelarasan_end_date->format('d-m-Y')
                    );
                    $notif->url = "penyelarasan?liquid_id={$liquid->getKey()}";
                    $saved = $notif->save();

                    if ($saved && $userToNotify->email) {
                        $mail = new MailLog();
                        $mail->to = $userToNotify->email;
                        $mail->to_name = $userToNotify->name;
                        $mail->subject = $notif->subject;

                        $mail->file_view = 'emails.liquid_published';
                        $mail->message = $notif->message;

                        $mail->status = 'CRTD';
                        $mail->parameter = json_encode(['pesan' => $mail->message]);
                        $mail->notification_id = $notif->id;
                        $mail->jenis = JenisEmail::LIQUID;
                        $mail->save();
                    }
                } else {
                    $userNotExists[] = $atasanId;
                }

                $bar->advance();
            }
            $bar->finish();

            $this->line('');
            $this->info(sprintf('%d peserta dinotifikasi', count($userExists)));
            $this->info(sprintf('%d peserta tidak punya akun', count($userNotExists)));
        }
    }

    protected function notifyJadwalPengukuranPertama()
    {
        $liquids = Liquid::where(function (Builder $sub) {
            $today = Carbon::today();
            $sub->where('pengukuran_pertama_start_date', $today)
                ->orWhere('pengukuran_pertama_end_date', $today);
        })->get();

        $this->info(sprintf('Memproses %d liquid', $liquids->count()));

        $userExists = [];
        $userNotExists = [];

        foreach ($liquids as $liquid) {
            $this->info(sprintf('Liquid #%d', $liquid->getKey()));

            $peserta = app(LiquidService::class)->listPeserta($liquid);
            $creator = $liquid->creator;
            $totalBawahan = collect($peserta)->sum(function ($peserta) {
                return count($peserta['peserta']);
            });

            $bar = $this->output->createProgressBar($totalBawahan + count($creator));

            foreach ($peserta as $atasan) {
                $businessArea = BusinessArea::where('business_area', $atasan['business_area'])
                    ->first();
                $this->notifyAdminCreator($creator, $liquid, 'Pengukuran Pertama', $businessArea, $bar);

                foreach ($atasan['peserta'] as $bawahanId => $bawahan) {
                    $userToNotify = User::query()->where('nip', $bawahan['nip'])->first();
                    if ($userToNotify) {
                        $userExists[] = $bawahanId;
                        $notif = new Notification();
                        $notif->from = 'SYSTEM';
                        $notif->user_id_from = 1;
                        $notif->to = $userToNotify->username2;
                        $notif->user_id_to = $userToNotify->getKey();
                        $notif->subject = 'Jadwal Pengukuran Pertama';
                        $notif->color = 'info';
                        $notif->icon = 'fa fa-info';
                        $notif->message = sprintf(
                            '"Pelaksanaan Pengukuran Pertama". Atasan: %s. NIP: %s. JABATAN: %s. KANTOR: %s - %s. Tanggal %s s/d %s.',
                            $atasan['nama'],
                            $atasan['nip'],
                            $atasan['jabatan'],
                            $atasan['business_area'],
                            $businessArea->description,
                            $liquid->pengukuran_pertama_start_date->format('d-m-Y'),
                            $liquid->pengukuran_pertama_end_date->format('d-m-Y')
                        );
                        $notif->url = route('penilaian.create').'?liquid_peserta_id='.$bawahan['liquid_peserta_id'];
                        $saved = $notif->save();

                        if ($saved && $userToNotify->email) {
                            $mail = new MailLog();
                            $mail->to = $userToNotify->email;
                            $mail->to_name = $userToNotify->name;
                            $mail->subject = $notif->subject;

                            $mail->file_view = 'emails.liquid_published';
                            $mail->message = $notif->message;

                            $mail->status = 'CRTD';
                            $mail->parameter = json_encode(['pesan' => $mail->message]);
                            $mail->notification_id = $notif->id;
                            $mail->jenis = JenisEmail::LIQUID;
                            $mail->save();
                        }
                    } else {
                        $userNotExists[] = $bawahanId;
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

    protected function notifyJadwalPengukuranKedua()
    {
        $liquids = Liquid::where(function (Builder $sub) {
            $today = Carbon::today();
            $sub->where('pengukuran_kedua_start_date', $today)
                ->orWhere('pengukuran_kedua_end_date', $today);
        })->get();

        $this->info(sprintf('Memproses %d liquid', $liquids->count()));

        $userExists = [];
        $userNotExists = [];

        foreach ($liquids as $liquid) {
            $this->info(sprintf('Liquid #%d', $liquid->getKey()));

            $peserta = app(LiquidService::class)->listPeserta($liquid);
            $creator = $liquid->creator;
            $totalBawahan = collect($peserta)->sum(function ($peserta) {
                return count($peserta['peserta']);
            });

            $bar = $this->output->createProgressBar($totalBawahan + count($creator));

            foreach ($peserta as $atasan) {
                $businessArea = BusinessArea::where('business_area', $atasan['business_area'])
                    ->first();
                $this->notifyAdminCreator($creator, $liquid, 'Pengukuran Kedua', $businessArea, $bar);

                foreach ($atasan['peserta'] as $bawahanId => $bawahan) {
                    $userToNotify = User::query()->where('nip', $bawahan['nip'])->first();
                    if ($userToNotify) {
                        $userExists[] = $bawahanId;
                        $notif = new Notification();
                        $notif->from = 'SYSTEM';
                        $notif->user_id_from = 1;
                        $notif->to = $userToNotify->username2;
                        $notif->user_id_to = $userToNotify->getKey();
                        $notif->subject = 'Jadwal Pengukuran Kedua';
                        $notif->color = 'info';
                        $notif->icon = 'fa fa-info';
                        $notif->message = sprintf(
                            '"Pelaksanaan Pengkuran Kedua". Atasan: %s. NIP: %s. JABATAN: %s. KANTOR: %s - %s. Tanggal %s s/d %s.',
                            $atasan['nama'],
                            $atasan['nip'],
                            $atasan['jabatan'],
                            $atasan['business_area'],
                            $businessArea->description,
                            $liquid->pengukuran_pertama_start_date->format('d-m-Y'),
                            $liquid->pengukuran_pertama_end_date->format('d-m-Y')
                        );
                        $notif->url = route('pengukuran-kedua.create').'?liquid_peserta_id='.$bawahan['liquid_peserta_id'];
                        $saved = $notif->save();

                        if ($saved && $userToNotify->email) {
                            $mail = new MailLog();
                            $mail->to = $userToNotify->email;
                            $mail->to_name = $userToNotify->name;
                            $mail->subject = $notif->subject;

                            $mail->file_view = 'emails.liquid_published';
                            $mail->message = $notif->message;

                            $mail->status = 'CRTD';
                            $mail->parameter = json_encode(['pesan' => $mail->message]);
                            $mail->notification_id = $notif->id;
                            $mail->jenis = JenisEmail::LIQUID;
                            $mail->save();
                        }
                    } else {
                        $userNotExists[] = $bawahanId;
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

    protected function saveEmail(User $user, $subject, $message, Notification $notif)
    {
        $mail = new MailLog();
        $mail->to = $user->email;
        $mail->to_name = $user->name;
        $mail->subject = $subject;
        $mail->file_view = null;
        $mail->message = $message;
        $mail->status = 'CRTD';
        $mail->parameter = json_encode([]);
        $mail->notification_id = $notif->getKey();
        $mail->jenis = 999; //TODO cari tahu jenis email LIQUID berapa

        return $mail->save();
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
