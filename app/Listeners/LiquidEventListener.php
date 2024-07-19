<?php

namespace App\Listeners;

use App\Enum\JenisEmail;
use App\Events\LiquidPublished;
use App\MailLog;
use App\Models\Liquid\BusinessArea;
use App\Notification;
use App\Services\LiquidService;
use App\User;
use Illuminate\Support\Facades\Auth;

class LiquidEventListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            LiquidPublished::class,
            __CLASS__.'@onPublished'
        );
    }

    public function onPublished(LiquidPublished $event)
    {
        $liquid = $event->liquid;
        $peserta = app(LiquidService::class)->listPeserta($liquid);

        foreach ($peserta as $atasan) {
            $userAtasan = User::query()->where('nip', $atasan['nip'])->first();
            if ($userAtasan) {
                $this->notifyUser($liquid, $userAtasan);
            }

            foreach ($atasan['peserta'] as $bawahanId => $bawahan) {
                $userBawahan = User::query()->where('nip', $bawahan['nip'])->first();
                if ($userBawahan) {
                    $this->notifyUser($liquid, $userBawahan);
                }
            }
        }
    }

    protected function notifyUser($liquid, $userToNotify)
    {
        $notif = new Notification();
        $notif->from = 'SYSTEM';
        $notif->user_id_from = Auth::user()->id;
        $notif->to = $userToNotify->username2;
        $notif->user_id_to = $userToNotify->getKey();
        $notif->subject = 'Jadwal Pelaksanaan Liquid';
        $notif->color = 'info';
        $notif->icon = 'fa fa-info';
        $notif->message = sprintf(
            'Berikut jadwal pelaksanaan Liquid tahun %s : 

            Feedback %s s/d %s. 
            Penyelarasan %s s/d %s. 
            Pengukuran 1 %s s/d %s. 
            Pengukuran 2 %s s/d %s.',
            $liquid->feedback_start_date->format('Y'),
            $liquid->feedback_start_date->format('d-m-Y'),
            $liquid->feedback_end_date->format('d-m-Y'),
            $liquid->penyelarasan_start_date->format('d-m-Y'),
            $liquid->penyelarasan_end_date->format('d-m-Y'),
            $liquid->pengukuran_pertama_start_date->format('d-m-Y'),
            $liquid->pengukuran_pertama_end_date->format('d-m-Y'),
            $liquid->pengukuran_kedua_start_date->format('d-m-Y'),
            $liquid->pengukuran_kedua_end_date->format('d-m-Y')
        );

        $notif->url = "feedback/{$liquid->getKey()}";
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
    }
}
