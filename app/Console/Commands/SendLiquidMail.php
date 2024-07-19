<?php

namespace App\Console\Commands;

use App\Enum\JenisEmail;
use App\MailLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLiquidMail extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'liquid:send-mail';
    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Kirim email notifikasi terkait liquid';

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
        $mail_pending = MailLog::where('status', 'PENDING')->get(['to'])->pluck('to')->unique()->all();
        $mail_log = MailLog::where('status', 'CRTD')
            ->where('jenis', JenisEmail::LIQUID)
            ->orderBy('id', 'asc')
            ->take(100)
            ->get();

        if (!env('ENABLE_EMAIL')) {
            $this->warn('ENABLE_EMAIL = false, do nothing');

            return false;
        }

        $pending = $sent = $error = [];

        foreach ($mail_log as $mail) {
            if (in_array($mail->to, $mail_pending)) {
                $mail->status = 'PENDING';
                $mail->save();
                $this->info($mail->id.':'.$mail->to.' PENDING');
                $pending[$mail->id] = $mail->to;
                continue;
            }
            $this->info($mail->id.':'.$mail->to);

            if ($mail->to != '') {
                try {
                    Mail::queue(
                        $mail->file_view,
                        [
                            'kepada' => $mail->to_name,
                            'pesan' => $mail->message,
                            'notif_id' => $mail->notification_id,
                        ],
                        function ($message) use ($mail) {
                            $message->to($mail->to)
                                ->subject($mail->subject);
                        }
                    );

                    $mail->status = 'SENT';
                    $mail->error_message = '';
                    $mail->save();

                    $sent[$mail->id] = $mail->to;
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                    $mail->status = 'ERROR';
                    $mail->error_message = $e->getMessage();
                    $mail->save();

                    $error[$mail->id] = $mail->to;
                }
            }
        }
    }
}
