<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    //
    protected $table = 'notification';
    protected $fillable = ['from', 'to', 'user_id_from', 'user_id_to', 'subject', 'message', 'url', 'color', 'icon'];

    public function getLastID(){
        $lastid = $this->all()->sortByDesc('id')->first();
        if($lastid==null) $id = 1;
        else $id = $lastid->id + 1;

        return $id;
    }

    public function tos(){
        return $this->belongsTo('App\User', 'user_id_to', 'id');
    }

    public function froms(){
        return $this->belongsTo('App\User', 'user_id_from', 'id');
    }

    public function setUserIdToAttribute($attrValue){
        $this->attributes['user_id_to'] = (string) $attrValue;
    }

    public function setUserIdFromAttribute($attrValue){
        $this->attributes['user_id_from'] = (string) $attrValue;
    }

    public static function send($user_to, $subject, $message, $url){
        $notif = new Notification();
        $notif->from = Auth::user()->username2;
        $notif->to = $user_to->username2;
        $notif->user_id_from = Auth::user()->id;
        $notif->user_id_to = $user_to->id;
        $notif->subject = $subject;
//        $notif->subject = 'Persetujuan EVP ' . $volunteer->nama;
//            $notif->color = 'pink';
//            $notif->icon = 'fa fa-heart-o';

        $notif->message = $message;
//        $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
        $notif->url = $url;
//        $notif->url = 'evp/volunteer/'.$volunteer->id;

        $notif->save();
    }

    public static function sendBySystem($user_to_id, $user_to_name, $subject, $message, $url) {
        $notification = new Notification();
        $notification->from = 'SYSTEM';
        $notification->user_id_from = 1;
        $notification->to = $user_to_name;
        $notification->user_id_to = $user_to_id;
        $notification->subject = $subject;
        $notification->color = "info";
        $notification->icon = "fa fa-info";

        $notification->message = $message;

        $notification->url = $url;
        $saved = $notification->save();
    }
}
