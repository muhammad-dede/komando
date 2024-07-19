<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class MailLog extends Model
{
    protected $table = 'mail_log';

    public static function log($to, $name, $subject, $file_view, $message, $status)
    {
        $mail = new MailLog();
        $mail->to = $to;
        $mail->to_name = $name;
        $mail->subject = $subject;
        $mail->file_view = $file_view;
        $mail->message = $message;
        $mail->$status = $status;
        $mail->save();
    }

    public static function sendMail()
    {
        $mail_log = MailLog::where('status', 'CRTD')->get();
        // dd(json_decode($mail->parameter));
        foreach($mail_log as $mail){
            $problem = json_decode($mail->parameter);
            // $pelapor = User::find($problem->user_id_pelapor);
            // dd($parameter);
            $company_code = CompanyCode::where('company_code', $problem->company_code)->first();
            $business_area = BusinessArea::where('business_area', $problem->business_area)->first();
            $status = ProblemStatus::find($problem->status);
            if (env('ENABLE_EMAIL', true)) {
                Mail::send($mail->file_view, ['kepada' => $mail->to_name, 'company_code' => $company_code, 'business_area' => $business_area, 'status' => $status, 'problem' => $problem], function ($message) use ($mail) {
                    $message->to($mail->to)
                        ->subject($mail->subject);
                });
            }
            $mail->status = 'SENT';
            $mail->save();
        }

    }

    public function notification(){
        return $this->hasOne('App\Notification', 'notification_id', 'id');
    }

    public static function queueMail($user_to, $subject, $message, $file_view, $parameter, $notif, $jenis, $status='CRTD', $date_send=null ){
        $mail = new MailLog();
        $mail->to = $user_to->email;
        $mail->to_name = $user_to->name;
        $mail->subject = $subject;
        $mail->file_view = $file_view;
        $mail->message = $message;
        $mail->status = $status;
        $mail->parameter    = $parameter;
        $mail->notification_id = $notif->id;
        $mail->jenis = $jenis;
        $mail->date_send = $date_send;

        $mail->save();
    }
}
