<?php

namespace App\Console\Commands;

use App\API;
use App\Bot;
use Illuminate\Console\Command;

class BotSendNotifDivisi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:send-divisi {divisi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bot send notification report check-in CoC Divisi';

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

        $arr_divisi = ['DIVSTI'];

        $divisi = $this->argument('divisi');

        if(in_array($divisi, $arr_divisi)){

            if($divisi=='DIVSTI'){
                $kode_organisasi = '10073855'; // DIVSTI
                $chat_id = env('CHAT_ID_DIVSTI','1567317315'); // LOLOL
                // $chat_id = '-1001244409234'; // Grup DIVSTI
            }

            // send notification Report CoC
            $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-notif-report-coc';
            $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id]);

            $response = $response->getData();

            Bot::log($chat_id, 'OUT', 'Report CoC', $response->message, 'OK', $kode_organisasi);
            
            $this->info($response->message);

        }
        else{
            $this->error('Divisi '.$divisi.' belum terdaftar!');
        }

        return 0;
    }
}
