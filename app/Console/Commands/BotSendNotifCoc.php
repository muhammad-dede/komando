<?php

namespace App\Console\Commands;

use App\API;
use App\Bot;
use Illuminate\Console\Command;

class BotSendNotifCoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:send-notif {kode_organisasi} {chat_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bot send notification report check-in CoC';

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
        $kode_organisasi = $this->argument('kode_organisasi');
        $chat_id = $this->argument('chat_id');

        // send notification Report CoC
        $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-notif-report-coc';
        $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id]); 

        $response = $response->getData();

        Bot::log($chat_id, 'OUT', 'Report CoC', $response->message, 'OK', $kode_organisasi);
        
        $this->info($response->message);

        return 0;
    }
}
