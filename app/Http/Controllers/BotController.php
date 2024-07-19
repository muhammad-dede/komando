<?php

namespace App\Http\Controllers;

use App\API;
use App\Bot;
use Illuminate\Http\Request;

use App\Http\Requests;

class BotController extends Controller
{
    public function handler(Request $request)
    {
        $respon = collect(json_decode($request->getContent(), true));
        
        $update_id = $respon->get('update_id');

        // if chat from message
        $message = $respon->get('message');
        $callback_query = $respon->get('callback_query');
        $my_chat_member = $respon->get('my_chat_member');

        if($message!=null){
            
            // $message = $respon->get('message');
            $message_id = $message['message_id'];
            if(array_key_exists('text',$message)){
                $text = $message['text'];
            }
            else{
                $text = '-';
            }
            $chat = $message['chat'];
            
            $chat_id = $chat['id'];
            
            if(array_key_exists('username',$chat)) $username = $chat['username'];
            else $username = ''; 

            if(array_key_exists('first_name',$chat)) $first_name = $chat['first_name'];
            else $first_name = '';

            if(array_key_exists('last_name',$chat)) $last_name = $chat['last_name'];
            else $last_name = '';
            
            $type = $chat['type'];
            
            $from = $message['from'];
            $is_bot = $from['is_bot'];
            if($is_bot=='false') $is_bot = 0;
            elseif($is_bot=='true') $is_bot = 1;
            else $is_bot = 0;

            if(array_key_exists('entities',$message)){    
                $entities = $message['entities'][0];
                $entities_type = $entities['type'];
                if($entities_type=='bot_command'){
                    $bot_command = $text;
                    $jenis_notif = 'bot_command';
                }
                else {
                    $bot_command = '';
                    $jenis_notif = 'chat';
                }
            }
            else {
                $bot_command = '';
                $jenis_notif = 'chat';
            }

        }

        // if chat from callback query
        elseif($callback_query!=null){

            $callback_query = $respon->get('callback_query');
            $data = $callback_query['data'];
            $from = $callback_query['from'];
            $message = $callback_query['message'];
            $chat = $message['chat'];

            $chat_id = $from['id'];
            $jenis_notif = 'bot_command';
            $text = $data;
            $message_id = $message['message_id'];

            if(array_key_exists('username',$from)) $username = $from['username'];
            else $username = '';

            if(array_key_exists('first_name',$from)) $first_name = $from['first_name'];
            else $first_name = '';

            if(array_key_exists('last_name',$from)) $last_name = $from['last_name'];
            else $last_name = '';

            // $first_name = $from['first_name'];
            // $last_name = $from['last_name'];

            $type = $chat['type'];
            $bot_command = $data;
            $is_bot = $from['is_bot'];
            
        }
        elseif($my_chat_member!=null){
            
            $my_chat_member = $respon->get('my_chat_member');
            $data = '-';
            $from = $my_chat_member['from'];
            // $message = $my_chat_member['message'];
            $chat = $my_chat_member['chat'];

            $chat_id = $from['id'];
            $jenis_notif = 'chat';
            $text = $data;
            $message_id = '-';

            if(array_key_exists('username',$from)) $username = $from['username'];
            else $username = '';

            if(array_key_exists('first_name',$from)) $first_name = $from['first_name'];
            else $first_name = '';

            if(array_key_exists('last_name',$from)) $last_name = $from['last_name'];
            else $last_name = '';

            // $first_name = $from['first_name'];
            // $last_name = $from['last_name'];
            $type = $chat['type'];
            $bot_command = '';
            $is_bot = $from['is_bot'];
        }
                 
        // log($chat_id, $in_out, $jenis_notif, $message, $status='OK', $kode_organisasi='', 
        //    $update_id='', $message_id='', $username='', $first_name='', $last_name='', 
        //    $type='', $text='', $bot_command='', $is_bot='', $json_update='')

        Bot::log($chat_id, 'IN', $jenis_notif, $text, 'OK', '', $update_id, $message_id, $username, $first_name, $last_name, $type, $text, $bot_command, $is_bot, $respon);

        // start
        if($text == '/start'){
             // reply welcome
             $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-welcome-message';
             API::responsePost($url_api, ['update_id'=>$update_id,'chat_id'=>$chat_id]); 
        }
        elseif($text == '/reportcocdivsti'){
            // if($divisi=='DIVSTI'){
                $kode_organisasi = '10073855'; // DIVSTI
                // $chat_id = env('CHAT_ID_DIVSTI','1567317315'); // LOLOL
                // $chat_id = '-1001244409234'; // Grup DIVSTI
            // }

            // send notification Report CoC
            $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/reply-report-coc';
            // $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id,'update_id'=>$update_id]);
            $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id]);

            $response = $response->getData();

            Bot::log($chat_id, 'OUT', 'Report CoC', $response->message, 'OK', $kode_organisasi);
        }
        else{
            // reply welcome
            $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-welcome-message';
            API::responsePost($url_api, ['update_id'=>$update_id,'chat_id'=>$chat_id]);
        }
        
    }

    public function updateRepliedBotLog(Request $request)
    {
        $update_id = $request->get('update_id');
        $bot_log = Bot::where('update_id',$update_id)->first();
        $bot_log->status = 'Replied';
        $bot_log->save();
    }
}
