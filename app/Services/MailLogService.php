<?php

namespace App\Services;

use App\MailLog;
use App\User;
use App\Notification;

use App\Models\Liquid\Liquid;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatDateIndonesia;

class MailLogService {

    // jenis 6: send email jadwal liquid ke semua peserta liquid
    public function mailInformasiLiquid(Liquid $liquid, $listPeserta=array()) {
        DB::beginTransaction();
        try{
            $dataMailLog = array();
            // get email from peserta liquid
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $link = "Bapak/Ibu terpilih sebagai peserta liquid dengan agenda kegiatan sebagai berikut:";
                    $parameter = $this->generateTimeLine($liquid, $link, "", "");

                    $tempArray = array();
                    $tempArray['to'] = $user['email'];
                    $tempArray['to_name'] = $user['name'];
                    $tempArray['subject'] = "[KOMANDO] Informasi Jadwal Liquid";
                    $tempArray['file_view'] = "emails.liquid_timeline";
                    $tempArray['message'] = "message_generated_by_template";
                    $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                    $tempArray['parameter'] = $parameter;
                    $tempArray['notification_id'] = null;
                    $tempArray['jenis'] = 6;
                    $tempArray['date_send'] = date("Y-m-d", strtotime($liquid['created_at']));
                    $tempArray['created_at'] = date("Y-m-d H:i:s");
                    $tempArray['updated_at'] = date("Y-m-d H:i:s");
                    array_push($dataMailLog, $tempArray);
                }
            }

            MailLog::insert($dataMailLog);

            DB::commit();

            return true;
        }catch (QueryException $th) {
            DB::rollback();
            $errorMessage = $th->getMessage();
            return $errorMessage;
        }
    }

    // jenis 7: send email jadwal liquid sesuai dengan date_send di table mail_log
    public function mailFeedback(Liquid $liquid, $listPeserta=array()) {
        DB::beginTransaction();
        try{
            $dataMailLog = array();
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $link = "Hari ini merupakan waktu pelaksanaan pengisian feedback, peserta feedback silahkan <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian feedback. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                    $linkNotif = url("/feedback")."?liquid_id=".$liquid['id'];
                    $parameter = $this->generateTimeLine($liquid, $link, "feedback", $linkNotif);

                    $tempArray = array();
                    $tempArray['to'] = $user['email'];
                    $tempArray['to_name'] = $user['name'];
                    $tempArray['subject'] = "[KOMANDO] Informasi Pelaksaan Feedback Liquid";
                    $tempArray['file_view'] = "emails.liquid_timeline";
                    $tempArray['message'] = "Hari ini merupakan waktu pelaksanaan pengisian feedback, peserta feedback silahkan melakukan pengisian feedback.";
                    $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                    $tempArray['parameter'] = $parameter;
                    $tempArray['notification_id'] = null;
                    $tempArray['jenis'] = 7;
                    $tempArray['date_send'] = date("Y-m-d", strtotime($liquid['feedback_start_date']));
                    $tempArray['created_at'] = date("Y-m-d H:i:s");
                    $tempArray['updated_at'] = date("Y-m-d H:i:s");
                    array_push($dataMailLog, $tempArray);
                }
            }

            MailLog::insert($dataMailLog);

            DB::commit();

            return true;
        }catch (QueryException $th) {
            DB::rollback();
            $errorMessage = $th->getMessage();
            return $errorMessage;
        }
    }

    public function mailPenyelarasan(Liquid $liquid, $listPeserta=array()) {
        DB::beginTransaction();
        try{
            $dataMailLog = array();
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $link = "Hari ini merupakan waktu pelaksanaan penyelarasan, silahkan <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian penyelarasan. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                    $linkNotif = url('/penyelarasan')."?liquid_id=".$liquid['id'];
                    $parameter = $this->generateTimeLine($liquid, $link, "penyelarasan", $linkNotif);

                    $tempArray = array();
                    $tempArray['to'] = $user['email'];
                    $tempArray['to_name'] = $user['name'];
                    $tempArray['subject'] = "[KOMANDO] Informasi Pelaksaan Penyelarasan Liquid";
                    $tempArray['file_view'] = "emails.liquid_timeline";
                    $tempArray['message'] = "Hari ini merupakan waktu pelaksanaan penyelarasan, silahkan melakukan pengisian penyelarasan.";
                    $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                    $tempArray['parameter'] = $parameter;
                    $tempArray['notification_id'] = null;
                    $tempArray['jenis'] = 7;
                    $tempArray['date_send'] = date("Y-m-d", strtotime($liquid['penyelarasan_start_date']));
                    $tempArray['created_at'] = date("Y-m-d H:i:s");
                    $tempArray['updated_at'] = date("Y-m-d H:i:s");
                    array_push($dataMailLog, $tempArray);
                }
            }

            MailLog::insert($dataMailLog);

            DB::commit();

            return true;
        }catch (QueryException $th) {
            DB::rollback();
            $errorMessage = $th->getMessage();
            return $errorMessage;
        }
    }

    public function mailPengukuranPertama(Liquid $liquid, $listPeserta=array()) {
        DB::beginTransaction();
        try{
            $dataMailLog = array();
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $link = "Hari ini merupakan waktu pelaksanaan pengukuran pertama, silahkan <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian pengukuran pertama. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                    $linkNotif = url('/penilaian')."?liquid_id=".$liquid['id'];
                    $parameter = $this->generateTimeLine($liquid, $link, "pengukuran_pertama", $linkNotif);

                    $tempArray = array();
                    $tempArray['to'] = $user['email'];
                    $tempArray['to_name'] = $user['name'];
                    $tempArray['subject'] = "[KOMANDO] Informasi Pelaksaan Pengukuran Pertama Liquid";
                    $tempArray['file_view'] = "emails.liquid_timeline";
                    $tempArray['message'] = "Hari ini merupakan waktu pelaksanaan pengukuran pertama, silahkan untuk melakukan pengisian pengukuran pertama.";
                    $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                    $tempArray['parameter'] = $parameter;
                    $tempArray['notification_id'] = null;
                    $tempArray['jenis'] = 7;
                    $tempArray['date_send'] = date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date']));
                    $tempArray['created_at'] = date("Y-m-d H:i:s");
                    $tempArray['updated_at'] = date("Y-m-d H:i:s");
                    array_push($dataMailLog, $tempArray);
                }
            }

            MailLog::insert($dataMailLog);

            DB::commit();

            return true;
        }catch (QueryException $th) {
            DB::rollback();
            $errorMessage = $th->getMessage();
            return $errorMessage;
        }
    }

    public function mailPengukuranKedua(Liquid $liquid, $listPeserta=array()) {
        DB::beginTransaction();
        try{
            $dataMailLog = array();
            foreach ($listPeserta as $pernr => $peserta) {
                $user = User::where('nip', '=', $peserta['nip'])->first();
                if(!empty($user['email'])) {
                    $link = "Hari ini merupakan waktu pelaksanaan pengukuran kedua, silahkann <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian pengukuran kedua. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                    $linkNotif = url('/pengukuran-kedua');
                    $parameter = $this->generateTimeLine($liquid, $link, "pengukuran_kedua", $linkNotif);

                    $tempArray = array();
                    $tempArray['to'] = $user['email'];
                    $tempArray['to_name'] = $user['name'];
                    $tempArray['subject'] = "[KOMANDO] Informasi Pelaksaan Pengukuran Kedua Liquid";
                    $tempArray['file_view'] = "emails.liquid_timeline";
                    $tempArray['message'] = "Hari ini merupakan waktu pelaksanaan pengukuran kedua, silahkan melakukan pengisian pengukuran kedua.";
                    $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                    $tempArray['parameter'] = $parameter;
                    $tempArray['notification_id'] = null;
                    $tempArray['jenis'] = 7;
                    $tempArray['date_send'] = date("Y-m-d", strtotime($liquid['pengukuran_kedua_start_date']));
                    $tempArray['created_at'] = date("Y-m-d H:i:s");
                    $tempArray['updated_at'] = date("Y-m-d H:i:s");
                    array_push($dataMailLog, $tempArray);
                }
            }

            MailLog::insert($dataMailLog);

            DB::commit();

            return true;
        }catch (QueryException $th) {
            DB::rollback();
            $errorMessage = $th->getMessage();
            return $errorMessage;
        }
    }

    public function mailLogActivity(Liquid $liquid, $listPeserta=array()) {
        $reminder = $liquid['reminder_aksi_resolusi'];
        switch($reminder) {
            case "MINGGUAN":
                DB::beginTransaction();
                try{
                    $start    = new \DateTime(date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date'])));
                    $start->modify('+2 day');
                    $end      = new \DateTime(date("Y-m-d", strtotime($liquid['pengukuran_kedua_end_date'])));
                    $interval = new \DateInterval('P1W');
    
                    $period   = new \DatePeriod($start, $interval, $end);
                    $dataMailLog = array();
                    foreach ($period as $dt) {
                        foreach ($listPeserta as $pernr => $peserta) {
                            $user = User::where('nip', '=', $peserta['nip'])->first();
                            if(!empty($user['email'])) {
                                $link = "Kegitan pengukuran pertama telah dilaksanakan. Silahkan bapak/ibu <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian Log Activity. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                                $linkNotif = url('/activity-log');
                                $parameter = $this->generateTimeLineActivityLog($liquid, $link, $dt->format("Y-m-d"), $linkNotif);
    
                                $tempArray = array();
                                $tempArray['to'] = $user['email'];
                                $tempArray['to_name'] = $user['name'];
                                $tempArray['subject'] = "[KOMANDO] Informasi Pengisan Log Activity Kegiatan Liquid";
                                $tempArray['file_view'] = "emails.liquid_timeline";
                                $tempArray['message'] = "Kegitan pengukuran pertama telah dilaksanakan. Silahkan bapak/ibu untuk melakukan pengisian Log Activity.";
                                $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                                $tempArray['parameter'] = $parameter;
                                $tempArray['notification_id'] = null;
                                $tempArray['jenis'] = 7;
                                $tempArray['date_send'] = $dt->format("Y-m-d");
                                $tempArray['created_at'] = date("Y-m-d H:i:s");
                                $tempArray['updated_at'] = date("Y-m-d H:i:s");
                                array_push($dataMailLog, $tempArray);
                            }
                        }
                    }
                    MailLog::insert($dataMailLog);

                    DB::commit();

                    return true;
                }catch (QueryException $th) {
                    DB::rollback();
                    $errorMessage = $th->getMessage();
                    return $errorMessage;
                }
            break;
            default:
                DB::beginTransaction();
                try{
                    $start    = new \DateTime(date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date'])));
                    $start->modify('+2 day');
                    $end      = new \DateTime(date("Y-m-d", strtotime($liquid['pengukuran_kedua_end_date'])));
                    $interval = new \DateInterval('P1M');

                    $period   = new \DatePeriod($start, $interval, $end);
                    $dataMailLog = array();
                    foreach ($period as $dt) {
                        foreach ($listPeserta as $pernr => $peserta) {
                            $user = User::where('nip', '=', $peserta['nip'])->first();
                            if(!empty($user['email'])) {
                                $link = "Kegitan pengukuran pertama telah dilaksanakan. Silahkan bapak/ibu <a href=\"".url('/notification')."\">klik disini</a> untuk melakukan pengisian Log Activity. jadwal kegiatan Liquid dapat anda cermati sebagai berikut:";
                                $linkNotif = url('/activity-log');
                                $parameter = $this->generateTimeLineActivityLog($liquid, $link, $dt->format("Y-m-d"),  $linkNotif);

                                $tempArray = array();
                                $tempArray['to'] = $user['email'];
                                $tempArray['to_name'] = $user['name'];
                                $tempArray['subject'] = "[KOMANDO] Informasi Pengisan Log Activity Kegiatan Liquid";
                                $tempArray['file_view'] = "emails.liquid_timeline";
                                $tempArray['message'] = "Kegitan pengukuran pertama telah dilaksanakan. Silahkan bapak/ibu untuk melakukan pengisian Log Activity.";
                                $tempArray['status'] = "CRTD_SCHEDULE_LIQUID";
                                $tempArray['parameter'] = $parameter;
                                $tempArray['notification_id'] = null;
                                $tempArray['jenis'] = 7;
                                $tempArray['date_send'] = $dt->format("Y-m-d");
                                $tempArray['created_at'] = date("Y-m-d H:i:s");
                                $tempArray['updated_at'] = date("Y-m-d H:i:s");
                                array_push($dataMailLog, $tempArray);
                            }
                        }
                    }

                    MailLog::insert($dataMailLog);

                    DB::commit();

                    return true;
                }catch (QueryException $th) {
                    DB::rollback();
                    $errorMessage = $th->getMessage();
                    return $errorMessage;
                }
            break;
        }
    }
    // end jenis 7.

    /* ================================ */
    protected function generateTimeLine(Liquid $liquid, $link, $activeColor, $link_notif) {
        // set format date indonesia
        $dateNow = date("Y-m-d");
        $dateFeedback = date("Y-m-d", strtotime($liquid['feedback_start_date']));
        $dateFeedbackEnd = date("Y-m-d", strtotime($liquid['feedback_end_date']));
        $datePenyelarasan = date("Y-m-d", strtotime($liquid['penyelarasan_start_date']));
        $datePenyelarasanEnd = date("Y-m-d", strtotime($liquid['penyelarasan_end_date']));
        $datePPertama = date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date']));
        $datePPertamaEnd = date("Y-m-d", strtotime($liquid['pengukuran_pertama_end_date']));
        $datePKedua = date("Y-m-d", strtotime($liquid['pengukuran_kedua_start_date']));
        $datePKeduaEnd = date("Y-m-d", strtotime($liquid['pengukuran_kedua_end_date']));
        $dataLiquid = array();

        $setDate = new FormatDateIndonesia;
        $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($dateFeedbackEnd, true, false);
        $dataLiquid['date_feedback_indo'] = $setDateIndo;
        $dataLiquid['date_feedback_indo_end'] = $setDateIndoEnd;
        // $dataLiquid['feedback'] = ($dateFeedback <= $dateNow) ? false : true;

        $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePenyelarasanEnd, true, false);
        $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
        $dataLiquid['date_penyelarasan_indo_end'] = $setDateIndoEnd;
        // $dataLiquid['penyelarasan'] = ($datePenyelarasan <= $dateNow) ? false : true;

        $setDateIndo = $setDate->tanggal_indo($datePPertama, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePPertamaEnd, true, false);
        $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
        $dataLiquid['date_pengukuran_pertama_indo_end'] = $setDateIndoEnd;
        // $dataLiquid['pengukuran_pertama'] = ($datePPertama <= $dateNow) ? false : true;

        $setDateIndo = $setDate->tanggal_indo($datePKedua, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePKeduaEnd, true, false); 
        $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
        $dataLiquid['date_pengukuran_kedua_indo_end'] = $setDateIndoEnd;
        // $dataLiquid['pengukuran_kedua'] = ($datePKeduaEnd <= $dateNow) ? false : true;

        switch($activeColor){
            case "feedback":
                $dataLiquid['feedback'] = false;
                $dataLiquid['penyelarasan'] = true;
                $dataLiquid['pengukuran_pertama'] = true;
                $dataLiquid['pengukuran_kedua'] = true;
                break;
            case "penyelarasan":
                $dataLiquid['feedback'] = false;
                $dataLiquid['penyelarasan'] = false;
                $dataLiquid['pengukuran_pertama'] = true;
                $dataLiquid['pengukuran_kedua'] = true;
                break;
            case "pengukuran_pertama":
                $dataLiquid['feedback'] = false;
                $dataLiquid['penyelarasan'] = false;
                $dataLiquid['pengukuran_pertama'] = false;
                $dataLiquid['pengukuran_kedua'] = true;
                break;
            case "pengukuran_kedua":
                $dataLiquid['feedback'] = false;
                $dataLiquid['penyelarasan'] = false;
                $dataLiquid['pengukuran_pertama'] = false;
                $dataLiquid['pengukuran_kedua'] = false;
                break;
            default:
                $dataLiquid['feedback'] = false;
                $dataLiquid['penyelarasan'] = false;
                $dataLiquid['pengukuran_pertama'] = false;
                $dataLiquid['pengukuran_kedua'] = false;
                break;
        }

        $parameter = array();
        $parameter['jadwal'] = $dataLiquid;
        $parameter['link'] = $link;
        $parameter['link_notif'] = $link_notif;

        return json_encode($parameter);
    }

    protected function generateTimeLineActivityLog(Liquid $liquid, $link, $dateActive, $link_notif) {
        // set format date indonesia
        $dateFeedback = date("Y-m-d", strtotime($liquid['feedback_start_date']));
        $dateFeedbackEnd = date("Y-m-d", strtotime($liquid['feedback_end_date']));
        $datePenyelarasan = date("Y-m-d", strtotime($liquid['penyelarasan_start_date']));
        $datePenyelarasanEnd = date("Y-m-d", strtotime($liquid['penyelarasan_end_date']));
        $datePPertama = date("Y-m-d", strtotime($liquid['pengukuran_pertama_start_date']));
        $datePPertamaEnd = date("Y-m-d", strtotime($liquid['pengukuran_pertama_end_date']));
        $datePKedua = date("Y-m-d", strtotime($liquid['pengukuran_kedua_start_date']));
        $datePKeduaEnd = date("Y-m-d", strtotime($liquid['pengukuran_kedua_end_date']));
        $dataLiquid = array();

        $setDate = new FormatDateIndonesia;
        $setDateIndo = $setDate->tanggal_indo($dateFeedback, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($dateFeedbackEnd, true, false);
        $dataLiquid['date_feedback_indo'] = $setDateIndo;
        $dataLiquid['date_feedback_indo_end'] = $setDateIndoEnd;
        $dataLiquid['feedback'] = false;

        $setDateIndo = $setDate->tanggal_indo($datePenyelarasan, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePenyelarasanEnd, true, false);
        $dataLiquid['date_penyelarasan_indo'] = $setDateIndo;
        $dataLiquid['date_penyelarasan_indo_end'] = $setDateIndoEnd;
        $dataLiquid['penyelarasan'] = false;

        $setDateIndo = $setDate->tanggal_indo($datePPertama, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePPertamaEnd, true, false);
        $dataLiquid['date_pengukuran_pertama_indo'] = $setDateIndo;
        $dataLiquid['date_pengukuran_pertama_indo_end'] = $setDateIndoEnd;
        $dataLiquid['pengukuran_pertama'] = true;
        if(($datePPertama <= $dateActive) && ($datePPertamaEnd >= $dateActive )) {
            $dataLiquid['pengukuran_pertama'] = false;
        }

        $setDateIndo = $setDate->tanggal_indo($datePKedua, true, false);
        $setDateIndoEnd = $setDate->tanggal_indo($datePKeduaEnd, true, false); 
        $dataLiquid['date_pengukuran_kedua_indo'] = $setDateIndo;
        $dataLiquid['date_pengukuran_kedua_indo_end'] = $setDateIndoEnd;
        $dataLiquid['pengukuran_kedua'] = true;
        if(($datePKedua <= $dateActive) && ($datePKeduaEnd >= $dateActive )) {
            $dataLiquid['pengukuran_kedua'] = false;
        }

        $parameter = array();
        $parameter['jadwal'] = $dataLiquid;
        $parameter['link'] = $link;
        $parameter['link_notif'] = $link_notif;

        return json_encode($parameter);
    }
}