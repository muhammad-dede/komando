<?php

namespace App\Http\Controllers;

use App\BusinessArea;
use App\CompanyCode;
use App\MailLog;
use App\ProblemStatus;
use App\Notification;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Swift_SwiftException;
use Swift_TransportException;
use App\Models\Liquid\Liquid;
use App\Enum\LiquidJabatan;
use App\Services\LiquidService;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\FormatDateIndonesia;
use App\Services\MailLogService;
use Illuminate\Database\QueryException;

class MailController extends Controller
{
    public static function sendMail()
    {
        $mail_log = MailLog::where('status', '!=', 'SENT')->where('jenis', '1')->orderBy('id', 'asc')->get();
        // dd(json_decode($mail->parameter));
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $problem = json_decode($mail->parameter);
                // $pelapor = User::find($problem->user_id_pelapor);
                // dd($parameter);
                $company_code = CompanyCode::where('company_code', $problem->company_code)->first();
                $business_area = BusinessArea::where('business_area', $problem->business_area)->first();
                $status = ProblemStatus::find($problem->status);
                // $notif = $mail->notification;
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'company_code' => $company_code,
                                'business_area' => $business_area,
                                'status' => $status,
                                'problem' => $problem,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                            $message->to($mail->to)
                                ->subject($mail->subject);
                        });

                        $mail->status = 'SENT';
                        $mail->error_message = '';
                        $mail->save();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                }
            }
        }

    }

    public static function sendMailInterfaceLog()
    {
        $mail_log = MailLog::where('status', '!=', 'SENT')->where('jenis', '666')->orderBy('id', 'asc')->get();
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $error_msg = json_decode($mail->parameter);
                // return $mail->parameter;
                // dd($error_msg->msg_error);
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'tanggal' => $error_msg->tanggal,
                                'error_msg' => $error_msg->msg_error,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                            $message->to($mail->to)
                                ->subject($mail->subject);
                        });

                        $mail->status = 'SENT';
                        $mail->error_message = '';
                        $mail->save();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                }
            }
        }

        return 'FINISHED';

    }

    public static function sendMailOrganisasiBaru()
    {
        $mail_log = MailLog::where('status', '!=', 'SENT')->where('jenis', '555')->orderBy('id', 'asc')->get();
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $parameter = json_decode($mail->parameter);
                // return $mail->parameter;
                // dd($error_msg->msg_error);
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'tanggal' => $parameter->tanggal,
                                'info_msg' => $parameter->info_msg,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                            $message->to($mail->to)
                                ->subject($mail->subject);
                        });

                        $mail->status = 'SENT';
                        $mail->error_message = '';
                        $mail->save();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                }
            }
        }

        return 'FINISHED';

    }

    public static function sendMailAdmin()
    {
        $mail_pending = MailLog::where('status', 'PENDING')->get(['to'])->pluck('to')->unique()->all();
        $mail_log = MailLog::where('status', 'CRTD')->where('jenis', '2')->orderBy('id', 'asc')->take(100)->get();
        // dd(json_decode($mail->parameter));
        foreach($mail_log as $mail){
            if(in_array($mail->to,$mail_pending)){
                $mail->status = 'PENDING';
                // $mail->error_message = $e->getMessage();
                $mail->save();
                echo $mail->id.':'.$mail->to.' PENDING <br>';
                continue;
            }
            echo $mail->id.':'.$mail->to.'<br>';
            // $mail = MailLog::where('to', 'm.fahmi.rizal@pln.co.id')->first();

            if($mail->to!='') {
                $materi = json_decode($mail->parameter);
                // $pelapor = User::find($problem->user_id_pelapor);
                // dd($parameter);
                // $company_code = CompanyCode::where('company_code', $problem->company_code)->first();
                // $business_area = BusinessArea::where('business_area', $problem->business_area)->first();
                // $status = ProblemStatus::find($problem->status);
                // $notif = $mail->notification;
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'materi' => $materi,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                                $message->to($mail->to)
                                    ->subject($mail->subject);
                            });

                            $mail->status = 'SENT';
                            $mail->error_message = '';
                            $mail->save();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                }
            }
            // dd($mail);
        }

        // dd($mail);
        // return 'FINISH';
    }

    public static function sendMailCoc()
    {
        $mail_pending = MailLog::where('status', 'PENDING')->get(['to'])->pluck('to')->unique()->all();
        $mail_log = MailLog::where('status', 'CRTD')->where('jenis', '3')->orderBy('id', 'asc')->take(100)->get();
        // $mail_log = MailLog::where('to', 'm.fahmi.rizal@pln.co.id')->where('jenis', '3')->orderBy('id', 'desc')->take(1)->get();
        //dd($mail_log);
        foreach($mail_log as $mail){
            if(in_array($mail->to,$mail_pending)){
                $mail->status = 'PENDING';
                // $mail->error_message = $e->getMessage();
                $mail->save();
                echo $mail->id.':'.$mail->to.' PENDING <br>';
                continue;
            }
            echo $mail->id.':'.$mail->to.'<br>';

            // $mail = MailLog::where('to', 'm.fahmi.rizal@pln.co.id')->where('jenis', '3')->first();
            // $mail = MailLog::where('to', 'm.fahmi.rizal@pln.co.id')->where('jenis', '3')->orderBy('id', 'desc')->take(1)->first();
            // $mail = MailLog::find(10650);
            if($mail->to!='') {
                $coc = json_decode($mail->parameter, true);
                // $pelapor = User::find($problem->user_id_pelapor);
                // dd($parameter);
                // $company_code = CompanyCode::where('company_code', $problem->company_code)->first();
                // $business_area = BusinessArea::where('business_area', $problem->business_area)->first();
                // $status = ProblemStatus::find($problem->status);
                // $notif = $mail->notification;
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'coc' => $coc,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                                $message->to($mail->to)
                                    ->subject($mail->subject);
                            });

                        $mail->status = 'SENT';
                        $mail->error_message = '';
                        $mail->save();
                    }
                    catch(\Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_SwiftException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_IoException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_DependencyException $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_Plugins_Pop_Pop3Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_RfcComplianceException $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }

                    // $mail->status = 'SENT';
                    // $mail->error_message = '';
                    // $mail->save();
                }
            }
            // dd($mail);
        }

        // dd($mail);
        //return 'FINISH';
    }

    public static function sendMailEVP()
    {
        $mail_pending = MailLog::where('status', 'PENDING')->get(['to'])->pluck('to')->unique()->all();
        $mail_log = MailLog::where('status', 'CRTD')->where('jenis', '4')->orderBy('id', 'asc')->take(100)->get();
        //dd($mail_log);
        foreach($mail_log as $mail){
            if(in_array($mail->to,$mail_pending)){
                $mail->status = 'PENDING';
                $mail->save();
                echo $mail->id.':'.$mail->to.' PENDING <br>';
                continue;
            }
            echo $mail->id.':'.$mail->to.'<br>';

            if($mail->to!='') {
                $evp = json_decode($mail->parameter, true);
                if (env('ENABLE_EMAIL', true)) {
                    try {
                        Mail::queue($mail->file_view,
                            ['kepada' => $mail->to_name,
                                'evp' => $evp,
                                'notif_id' => $mail->notification_id
                            ],
                            function ($message) use ($mail) {
                                $message->to($mail->to)
                                    ->subject($mail->subject);
                            });

                        $mail->status = 'SENT';
                        $mail->error_message = '';
                        $mail->save();
                    }
                    catch(\Swift_TransportException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_SwiftException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_IoException $e){
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_DependencyException $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_Plugins_Pop_Pop3Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch(\Swift_RfcComplianceException $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }
                    catch (\Exception $e) {
                        $mail->status = 'ERROR';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }

                }
            }
            // dd($mail);
        }

       // dd($mail);
    }

    // ================= broadcast email prosess liquid =================
    public function MailLogLiquid() {
        ini_set('max_execution_time', 300); // limit 5 minutes execute function
        $getTime = date("Y-m-d");
        $service = new MailLogService;
        $timeNow = new \DateTime($getTime);
        $timeNow->modify('-1 day');
        $timeNow = $timeNow->format('Y-m-d');

        // create mail log untuk informasi terpilihnya peserta liquid
        $liquid = Liquid::whereDate('created_at', '=', $timeNow)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");

            $jenis6 = $service->mailInformasiLiquid($liquid, $allPeserta);
            if(!$jenis6) {
                echo "error jenis6:".$jenis6."\n<br>";
            }
            
            echo "berhasil mail log liquid\n<br>";
            die();
        }


        $timeNow_ = new \DateTime($getTime);
        $timeNow_->modify('+1 day');
        $timeNow_ = $timeNow_->format('Y-m-d');
        // create broadcast email feedback
        $liquid = Liquid::whereDate('feedback_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");

            $jenis7 = $service->mailFeedback($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail feedback:".$jenis7."\n<br>";
            }
            
            echo "berhasil create mail log feedback\n<br>";
            die();
        }else{
            echo "data feedback tidak ditemukan\n<br>";
        }

        // create broadcast email penyelarasan
        $liquid = Liquid::whereDate('penyelarasan_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPenyelarasan($liquid, $pesertaAtasan);
            if(!$jenis7) {
                echo "error mail penyelarasan:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log penyelarasan\n<br>";
            die();
        }else{
            echo "data penyelarasan tidak ditemukan\n<br>";
        }

        // create broadcast mail pengukuran pertama
        $liquid = Liquid::whereDate('pengukuran_pertama_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPengukuranPertama($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail pengukuran pertama:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log pengukuran pertama\n<br>";
            die();
        }else{
            echo "data pengukuran pertama tidak ditemukan\n<br>";
        }

        // create broadcast mail pengukuran kedua 
        $liquid = Liquid::whereDate('pengukuran_kedua_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        dd($liquid);
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPengukuranKedua($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail pengukuran kedua:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log pengukuran kedua\n<br>";
            die();
        }else{
            echo "data pengukuran kedua tidak ditemukan\n<br>";
        }

        // create mail log activity log
        $liquid = Liquid::whereDate('pengukuran_pertama_start_date', '=', $getTime)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $jenis7 = $service->mailLogActivity($liquid, $pesertaAtasan);
            if(!$jenis7) {
                echo "error mail activity log:".$jenis7."\n<br>";
            }
            echo "berhasil mail activity log\n<br>";
            die();
        }
    }

    public function SendMailInfomationLiquid() {
        ini_set('max_execution_time', '60'); // limit 1 minutes execute function
        $mail_log = MailLog::where('status', '=', 'CRTD_SCHEDULE_LIQUID')->where('jenis', '6')->orderBy('id', 'asc')->skip(0)->take(5)->get();
        // dd($mail_log);
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $parameter = json_decode($mail->parameter);
                if (env('ENABLE_EMAIL', true)) {
                    DB::beginTransaction();
                    try {
                        Mail::queue($mail->file_view, ['kepada' => $mail->to_name, 'jadwal' => $parameter->jadwal, 'link' => $parameter->link], function ($message) use ($mail) {
                            $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                            $message->to($mail->to);
                            $message->subject($mail->subject);
                        });

                        $mail->status = 'SENT_SCHEDULE_LIQUID';
                        $mail->error_message = '';
                        $mail->save();

                        DB::commit();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (QueryException $th) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $th->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }
                }
            }
        }
        die();
    }

    public function SendMailProsesLiquid() {
        ini_set('max_execution_time', '60'); // limit 1 minutes execute function
        $timeNow = date("Y-m-d");
        $mail_log = MailLog::where('date_send', '=', $timeNow)->where('status', '=', 'CRTD_SCHEDULE_LIQUID')->where('jenis', '7')->orderBy('id', 'asc')->skip(0)->take(5)->get();
        // dd($mail_log);
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $parameter = json_decode($mail->parameter);
                if (env('ENABLE_EMAIL', true)) {
                    DB::beginTransaction();
                    try {
                        Mail::queue($mail->file_view, ['kepada' => $mail->to_name, 'jadwal' => $parameter->jadwal, 'link' => $parameter->link], function ($message) use ($mail) {
                            $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                            $message->to($mail->to);
                            $message->subject($mail->subject);
                        });

                        $mail->status = 'SENT_SCHEDULE_LIQUID';
                        $mail->error_message = '';
                        $mail->save();

                        $user = User::where('email', '=', $mail->to)->first();
                        if(!empty($user)) {
                            $to = $mail->to_name;
                            $user_id_to = $user['id'];
                            $subject = $mail->subject;
                            $message = $mail->message;
                            $url = $parameter->link_notif;
                            $notification = new Notification();
                            $saved = $notification->sendBySystem($user_id_to, $to, $subject, $message, $url);
                        }
                        
                        DB::commit();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (QueryException $th) {
                        DB::rollback();
                        $errorMessage = $th->getMessage();
                        return $errorMessage;
                    }
                }
            }
        }
        die();
    }
    // ================= end broadcast email prosess liquid =================

    // testing method
    public function MailLogLiquid_2($getTime) {
        ini_set('max_execution_time', 300); // limit 5 minutes execute function
        // $getTime = date("Y-m-d");
        $service = new MailLogService;
        $timeNow = new \DateTime($getTime);
        $timeNow->modify('-1 day');
        $timeNow = $timeNow->format('Y-m-d');

        $liquid = Liquid::whereDate('created_at', '=', $timeNow)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");

            $jenis6 = $service->mailInformasiLiquid($liquid, $allPeserta);
            if(!$jenis6) {
                echo "error jenis6:".$jenis6."\n<br>";
            }
            
            echo "berhasil mail log liquid\n<br>";
            die();
        }else{
            echo "data liquid tidak ditemukan\n<br>";
        }

        // create broadcast email feedback
        $timeNow_ = new \DateTime($getTime);
        $timeNow_->modify('+1 day');
        $timeNow_ = $timeNow_->format('Y-m-d');
        $liquid = Liquid::whereDate('feedback_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");

            $jenis7 = $service->mailFeedback($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail feedback:".$jenis7."\n<br>";
            }
            
            echo "berhasil create mail log feedback\n<br>";
            die();
        }else{
            echo "data feedback tidak ditemukan\n<br>";
        }

        // create broadcast email penyelarasan
        $liquid = Liquid::whereDate('penyelarasan_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPenyelarasan($liquid, $pesertaAtasan);
            if(!$jenis7) {
                echo "error mail penyelarasan:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log penyelarasan\n<br>";
            die();
        }else{
            echo "data penyelarasan tidak ditemukan\n<br>";
        }

        // create broadcast mail pengukuran pertama
        $liquid = Liquid::whereDate('pengukuran_pertama_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPengukuranPertama($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail pengukuran pertama:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log pengukuran pertama\n<br>";
            die();
        }else{
            echo "data pengukuran pertama tidak ditemukan\n<br>";
        }

        // create broadcast mail pengukuran kedua 
        $liquid = Liquid::whereDate('pengukuran_kedua_start_date', '=', $timeNow_)->where('status', '=', 'PUBLISHED')->first();
        dd($liquid);
        if(!empty($liquid)) {
            $allPeserta = $this->unique_multidim_array($liquid, "nip");
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $pesertaBawahan = $this->uniqid_multi_peserta_bawahan($liquid, "nip", "bawahan");
            
            $jenis7 = $service->mailPengukuranKedua($liquid, $pesertaBawahan);
            if(!$jenis7) {
                echo "error mail pengukuran kedua:".$jenis7."\n<br>";
            }

            echo "berhasil create mail log pengukuran kedua\n<br>";
            die();
        }else{
            echo "data pengukuran kedua tidak ditemukan\n<br>";
        }

        // create mail log activity log
        $liquid = Liquid::whereDate('pengukuran_pertama_start_date', '=', $getTime)->where('status', '=', 'PUBLISHED')->first();
        if(!empty($liquid)) {
            $pesertaAtasan = $this->uniqid_multi_peserta_atasan($liquid, "nip", "atasan");
            $jenis7 = $service->mailLogActivity($liquid, $pesertaAtasan);
            if(!$jenis7) {
                echo "error mail activity log:".$jenis7."\n<br>";
            }
            echo "berhasil mail activity log\n<br>";
            die();
        }else{
            echo "data activity log tidak ditemukan\n<br>";
            die();
        }
    }

    public function SendMailInfomationLiquid_2($valid_dump) {
        ini_set('max_execution_time', '60'); // limit 1 minutes execute function
        $mail_log = MailLog::where('status', '=', 'CRTD_SCHEDULE_LIQUID')->where('jenis', '6')->orderBy('id', 'asc')->skip(0)->take(1)->get();
        if($valid_dump == "true") {
            dd($mail_log);
        }
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $parameter = json_decode($mail->parameter);
                if (env('ENABLE_EMAIL', true)) {
                    DB::beginTransaction();
                    try {
                        Mail::queue($mail->file_view, ['kepada' => $mail->to_name, 'jadwal' => $parameter->jadwal, 'link' => $parameter->link], function ($message) use ($mail) {
                            $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                            $message->to($mail->to);
                            $message->subject($mail->subject);
                        });

                        $mail->status = 'SENT_SCHEDULE_LIQUID';
                        $mail->error_message = '';
                        $mail->save();

                        DB::commit();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }catch (QueryException $th) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $th->getMessage();
                        $mail->save();
                        DB::commit();
                        continue;
                    }
                }
            }
        }
        die();
    }

    public function SendMailProsesLiquid_2($timeNow, $valid_dump) {
        ini_set('max_execution_time', '60'); // limit 1 minutes execute function
        // $timeNow = date("Y-m-d");
        $mail_log = MailLog::where('date_send', '=', $timeNow)->where('status', '=', 'CRTD_SCHEDULE_LIQUID')->where('jenis', '7')->orderBy('id', 'asc')->skip(0)->take(1)->get();
        if($valid_dump == "true"){
            dd($mail_log);
        }
        foreach($mail_log as $mail){
            echo $mail->id.':'.$mail->to.'<br>';
            if($mail->to!='') {
                $parameter = json_decode($mail->parameter);
                if (env('ENABLE_EMAIL', true)) {
                    DB::beginTransaction();
                    try {
                        Mail::queue($mail->file_view, ['kepada' => $mail->to_name, 'jadwal' => $parameter->jadwal, 'link' => $parameter->link], function ($message) use ($mail) {
                            $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                            $message->to($mail->to);
                            $message->subject($mail->subject);
                        });

                        $mail->status = 'SENT_SCHEDULE_LIQUID';
                        $mail->error_message = '';
                        $mail->save();

                        $user = User::where('email', '=', $mail->to)->first();
                        if(!empty($user)) {
                            $to = $mail->to_name;
                            $user_id_to = $user['id'];
                            $subject = $mail->subject;
                            $message = $mail->message;
                            $url = $parameter->link_notif;
                            $notification = new Notification();
                            $saved = $notification->sendBySystem($user_id_to, $to, $subject, $message, $url);
                        }
                        
                        DB::commit();
                    }
                    catch(Swift_TransportException $e){
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (\Exception $e) {
                        $mail->status = 'ERROR_SCHEDULE_LIQUID';
                        $mail->error_message = $e->getMessage();
                        $mail->save();
                        continue;
                    }catch (QueryException $th) {
                        DB::rollback();
                        $errorMessage = $th->getMessage();
                        return $errorMessage;
                    }
                }
            }
        }
        die();
    }
    // end testing method

    // === method not release ===
    public function sendLiquidTimeline() {
        $timeNow = date("Y-m-d");

        // check jadwal pelaksanaan feedback
        $liquidFeed = Liquid::where('feedback_start_date', '=', $timeNow)->first();
        if(!empty($liquidFeed)) {
            // delete data duplicate
            $listPeserta = $this->unique_multidim_array($liquidFeed, 'nip');
            $id_liquid = $liquidFeed['id'];

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquidFeed['feedback_start_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquidFeed['penyelarasan_start_date']));
            $datePPertama = date("Y-m-d", strtotime($liquidFeed['pengukuran_pertama_start_date']));
            $datePKedua = date("Y-m-d", strtotime($liquidFeed['pengukuran_kedua_start_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $kepada = $user['name'];
                    $emailLogin = $user['email'];
                    $subject = "Pelaksanaan Feedback";
                    $link = "Untuk pelaksanaan feedback sudah dapat dilakukan. Silahkan <a target=\"_blank\" href=\"".route('feedback.index', ['liquid_id' => $id_liquid])."\">klik disini</a> untuk mengisi feedback.";
                    Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                        $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                        $message->to($emailLogin);
                        $message->subject($subject);
                    });
                }
            }
            // $user = User::where('id', '=', '1')->first();
            // print_r("Email sudah terkirim ke ".$user['email']);
            return;
        }

        // check jadwal pelaksanaan penyelarasan
        $liquidPenyelarasan = Liquid::where('penyelarasan_start_date', '=', $timeNow)->first();
        if(!empty($liquidPenyelarasan)) {
            // delete data duplicate
            $listPeserta = $this->unique_multidim_array($liquidPenyelarasan, 'nip');
            $id_liquid = $liquidPenyelarasan['id'];

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquidPenyelarasan['feedback_start_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquidPenyelarasan['penyelarasan_start_date']));
            $datePPertama = date("Y-m-d", strtotime($liquidPenyelarasan['pengukuran_pertama_start_date']));
            $datePKedua = date("Y-m-d", strtotime($liquidPenyelarasan['pengukuran_kedua_start_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $kepada = $user['name'];
                    $emailLogin = $user['email'];
                    $subject = "Pelaksanaan Penyelarasan";
                    $link = "Untuk pelaksanaan penyelarasan sudah dapat dilakukan. Silahkan <a target=\"_blank\" href=\"".route('penyelarasan.index', ['liquid_id' => $id_liquid])."\">klik disini</a> untuk mengisi penyelarasan.";
                    Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                        $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                        $message->to($emailLogin);
                        $message->subject($subject);
                    });
                }
            }
            // $user = User::where('id', '=', '1')->first();
            // var_dump($link);
            return;
        }

        // check jadwal pengukuran pertama
        $liquidPengukuranPertama = Liquid::where('pengukuran_pertama_start_date', '=', $timeNow)->first();
        if(!empty($liquidPengukuranPertama)) {
            // delete data duplicate
            $listPeserta = $this->unique_multidim_array($liquidPengukuranPertama, 'nip');
            $id_liquid = $liquidPengukuranPertama['id'];

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquidPengukuranPertama['feedback_start_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquidPengukuranPertama['penyelarasan_start_date']));
            $datePPertama = date("Y-m-d", strtotime($liquidPengukuranPertama['pengukuran_pertama_start_date']));
            $datePKedua = date("Y-m-d", strtotime($liquidPengukuranPertama['pengukuran_kedua_start_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $kepada = $user['name'];
                    $emailLogin = $user['email'];
                    $subject = "Pelaksanaan Pengukuran Pertama";
                    $link = "Untuk pelaksanaan pengukuran pertama sudah dapat dilakukan. Silahkan <a target=\"_blank\" href=\"".route('penilaian.index', ['liquid_id' => $id_liquid])."\">klik disini</a> untuk mengisi pengukuran pertama.";
                    Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                        $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                        $message->to($emailLogin);
                        $message->subject($subject);
                    });
                }
            }
            // $user = User::where('id', '=', '1')->first();
            // var_dump($link);
            return;
        }

        // check jadwal pengukuran kedua
        $liquidPengukuranKedua = Liquid::where('pengukuran_kedua_start_date', '=', $timeNow)->first();
        if(!empty($liquidPengukuranKedua)) {
            // delete data duplicate
            $listPeserta = $this->unique_multidim_array($liquidPengukuranKedua, 'nip');
            $id_liquid = $liquidPengukuranKedua['id'];

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquidPengukuranKedua['feedback_start_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquidPengukuranKedua['penyelarasan_start_date']));
            $datePPertama = date("Y-m-d", strtotime($liquidPengukuranKedua['pengukuran_pertama_start_date']));
            $datePKedua = date("Y-m-d", strtotime($liquidPengukuranKedua['pengukuran_kedua_start_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $kepada = $user['name'];
                    $emailLogin = $user['email'];
                    $subject = "Pelaksanaan Pengukuran Kedua";
                    $link = "Untuk pelaksanaan pengukuran kedua sudah dapat dilakukan. Silahkan <a target=\"_blank\" href=\"".route('pengukuran-kedua.index')."\">klik disini</a> untuk mengisi pengukuran kedua.";
                    Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                        $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                        $message->to($emailLogin);
                        $message->subject($subject);
                    });
                }
            }
            // $user = User::where('id', '=', '1')->first();
            // var_dump($link);
            return;
        }

        // notif liquid selesai
        // get previous date
        $previousDate = date('Y-m-d', strtotime('-1 day', strtotime($timeNow)));
        $liquidFinish = Liquid::where('pengukuran_kedua_end_date', '=', $previousDate)->first();
        if(!empty($liquidFinish)) {
            // delete data duplicate
            $listPeserta = $this->unique_multidim_array($liquidFinish, 'nip');
            $id_liquid = $liquidFinish['id'];

            // set format date indonesia
            $dateFeedback = date("Y-m-d", strtotime($liquidFinish['feedback_start_date']));
            $datePenyelarasan = date("Y-m-d", strtotime($liquidFinish['penyelarasan_start_date']));
            $datePPertama = date("Y-m-d", strtotime($liquidFinish['pengukuran_pertama_start_date']));
            $datePKedua = date("Y-m-d", strtotime($liquidFinish['pengukuran_kedua_start_date']));
            $dataLiquid = array();

            $setDate = new FormatDateIndonesia;
            $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
            $dataLiquid['date_feedback_indo'] = $setDateIndo;
            $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
            $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
            $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
            $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

            $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
            $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
            $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $kepada = $user['name'];
                    $emailLogin = $user['email'];
                    $subject = "Liquid Selesai";
                    $link = "Untuk pelaksanaan LIQUID periode ".date("d-m-Y", strtotime($dateFeedback))."-".date("d-m-Y", strtotime($liquidFinish['pengukuran_kedua_end_date']))." telah selesai dilaksanakan. Silahkan <a target=\"_blank\" href=\"".route('pengukuran-kedua.index')."\">klik disini</a> untuk melihat hasil liquid ".date('Y');
                    Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                        $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                        $message->to($emailLogin);
                        $message->subject($subject);
                    });
                }
            }
            // $user = User::where('id', '=', '1')->first();
            // var_dump($link);
            return;
        }

        // check one week : pengukuran pertama between pengukuran kedua
        $liquidPerWeek = Liquid::where('pengukuran_pertama_end_date', '<', date('Y-m-d', strtotime($timeNow)))
        ->where('pengukuran_kedua_start_date', '>', date('Y-m-d', strtotime($timeNow)))->first();
        if(!empty($liquidPerWeek)) {
            $start    = new \DateTime($liquidPerWeek['pengukuran_pertama_end_date']);
            $end      = new \DateTime($liquidPerWeek['pengukuran_kedua_start_date']);
            $interval = \DateInterval::createFromDateString('1 day');
            $period   = new \DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                if ($dt->format("N") == 1) {
                    if($dt->format("Y-m-d") == $timeNow) {
                        // echo $dt->format("l Y-m-d") . "<br>\n";
                        // delete data duplicate
                        $listPeserta = $this->unique_multidim_array($liquidPerWeek, 'nip');
                        $id_liquid = $liquidPerWeek['id'];

                        // set format date indonesia
                        $dateFeedback = date("Y-m-d", strtotime($liquidPerWeek['feedback_start_date']));
                        $datePenyelarasan = date("Y-m-d", strtotime($liquidPerWeek['penyelarasan_start_date']));
                        $datePPertama = date("Y-m-d", strtotime($liquidPerWeek['pengukuran_pertama_start_date']));
                        $datePKedua = date("Y-m-d", strtotime($liquidPerWeek['pengukuran_kedua_start_date']));
                        $dataLiquid = array();

                        $setDate = new FormatDateIndonesia;
                        $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, true);
                        $dataLiquid['date_feedback_indo'] = $setDateIndo;
                        $dataLiquid['feedback'] = ($dateFeedback < $timeNow) ? true : false;

                        $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, true);
                        $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
                        $dataLiquid['penyelarasan'] = ($datePenyelarasan < $timeNow) ? true : false;

                        $setDateIndo = $setDate->tanggal_indo($datePPertama, true, true);
                        $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
                        $dataLiquid['pengukuran_pertama'] = ($datePPertama < $timeNow) ? true : false;

                        $setDateIndo = $setDate->tanggal_indo($datePKedua, true, true);
                        $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
                        $dataLiquid['pengukuran_kedua'] = ($datePKedua < $timeNow) ? true : false;

                        // get email from peserta liquid
                        // $i = 0;
                        foreach ($listPeserta as $pernr => $peserta) {
                            $user = User::where('nip', '=', $peserta['nip'])->first();
                            if(!empty($user['email'])) {
                                $kepada = $user['name'];
                                $emailLogin = $user['email'];
                                $subject = "Pengisian Activity Log";
                                $link = "Untuk pengisian activity log sudah dapat dilakukan. Silahkan <a target=\"_blank\" href=\"".route('activity-log.index')."\">klik disini</a> untuk mengisi activity log.";
                                Mail::queue('emails.liquid_timeline', ['kepada' => $kepada, 'jadwal' => $dataLiquid, 'link' => $link], function($message) use ($emailLogin, $subject) {
                                    $message->from(env('MAIL_FROM', 'komando@pln.co.id'), 'KOMANDO');
                                    $message->to($emailLogin);
                                    $message->subject($subject);
                                });
                            }
                        }
                        // $user = User::where('id', '=', '1')->first();
                        break;
                    }   
                }
            }
            print_r('sukses');
            return;
        }
        
        print_r("lanjut");
    }

    public function testingOneMounthDate($timeNow) {
        $start    = new \DateTime('2021-08-16');
        $end      = new \DateTime('2021-11-16');
        // $interval = \DateInterval::createFromDateString('first day of next month');
        // $end->modify('+1 day');
        // P2W = dua minggu kemudian
        // P1M = satu bulan kemudian
        $interval = new \DateInterval('P2W');

        $period   = new \DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            // if ($dt->format("N") == 2) {
                echo $dt->format("Y-m-d")."\n<br>";
            // }
        }
    }
    // === end method not release ===

    protected function unique_multidim_array(Liquid $liquid, $key) {
        $temp_array = array();
        // $i = 0;
        $key_array = array();

        foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
            foreach ($atasan['peserta'] as $pernrBawahan => $bawahan) {
                // search bawahan yang sama
                if (!in_array($bawahan[$key], $key_array)) {
                    $key_array[$pernrBawahan] = $bawahan[$key];
                    $temp_array[$pernrBawahan] = $bawahan;
                }
            }

            // search atasan yang sama
            if (!in_array($atasan[$key], $key_array)) {
                $setData = array();
                $setData['nama'] = $atasan['nama'];
                $setData['nip'] = $atasan['nip'];
                $setData['jabatan'] = $atasan['jabatan'];
                $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                $setData['business_area'] = $atasan['business_area'];
                $key_array[$pernr] = $atasan[$key];
                $temp_array[$pernr] = $setData;
            }
            // $i++;
        }
        return $temp_array;
    }

    protected function uniqid_multi_peserta_atasan(Liquid $liquid, $key) {
        $temp_array = array();
        $key_array = array();

        foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
            // search atasan yang sama
            if (!in_array($atasan[$key], $key_array)) {
                $setData = array();
                $setData['nama'] = $atasan['nama'];
                $setData['nip'] = $atasan['nip'];
                $setData['jabatan'] = $atasan['jabatan'];
                $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                $setData['business_area'] = $atasan['business_area'];
                $key_array[$pernr] = $atasan[$key];
                $temp_array[$pernr] = $setData;
            }
        }
        return $temp_array;
    }

    protected function uniqid_multi_peserta_bawahan(Liquid $liquid, $key) {
        $temp_array = array();
        $key_array = array();

        foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
            foreach ($atasan['peserta'] as $pernrBawahan => $bawahan) {
                // search bawahan yang sama
                if (!in_array($bawahan[$key], $key_array)) {
                    $key_array[$pernrBawahan] = $bawahan[$key];
                    $temp_array[$pernrBawahan] = $bawahan;
                }
            }
        }
        return $temp_array;
    }
}
