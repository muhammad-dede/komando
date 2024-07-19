<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bot_log';

    public static function log($chat_id, $in_out, $jenis_notif, $message='-', $status='OK', $kode_organisasi='', 
                                $update_id='', $message_id='', $username='', $first_name='', $last_name='', 
                                $type='', $text='', $bot_command='', $is_bot=0, $json_update='')
    {
        $bot = new Bot();
        $bot->chat_id = $chat_id;
        $bot->in_out = $in_out;
        $bot->jenis_notif = $jenis_notif;
        $bot->message = $message;
        $bot->kode_organisasi = $kode_organisasi;
        $bot->status = $status;
        
        $bot->update_id = $update_id;
        $bot->message_id = $message_id;
        $bot->username = $username;
        $bot->first_name = $first_name;
        $bot->last_name = $last_name;
        $bot->type = $type;
        $bot->text = $text;
        $bot->bot_command = $bot_command;
        $bot->is_bot = $is_bot;
        $bot->json_update = $json_update;

        $bot->server = env('SERVER','Server-00');
        
        $bot->save();
        
    }
}
