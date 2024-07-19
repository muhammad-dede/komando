<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Date\Date;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:vpn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email Extend VPN';

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
        Date::setLocale('id');
        $date = Date::now()->addMonth();
        $periode = $date->format('F Y');
        $exp = '1 '.Date::now()->addMonths(2)->format('F Y');

        $emails = ['saputra.neva@pln.co.id','servicedesk@pln.co.id'];
        // $emails = ['asmaning.sukowati@pln.co.id','m.fahmi.rizal@pln.co.id'];
        $cc = ['marson.ompusunggu@pln.co.id','asmaning.sukowati@pln.co.id',
                'm.fahmi.rizal@pln.co.id','thaufan.widya@pln.co.id','kris.aprianto@pln.co.id','faiz.fakhri@pln.co.id','diah.paramita@pln.co.id','faradina.riantika@pln.co.id'];
        // $cc = ['asmaning.sukowati@pln.co.id','m.fahmi.rizal@pln.co.id','wirawan.hidayat@pln.co.id','freddy.richard@pln.co.id'];

        $subject = 'Permintaan akses VPN Sub Bid Aplikasi Human Capital';

        Mail::queue(
            'emails.perpanjang_vpn',
            [
                'kepada' => 'Neva dan Tim',
                'exp' => $exp
            ],
            function ($message) use ($emails, $subject, $cc) {
                $message->to($emails)
                    ->cc($cc)
                    ->subject($subject)
                    ->from('asmaning.sukowati@pln.co.id', 'Asmaning Ayu Sukowati');
            }
        );

        echo 'Mail Sent!';
    }
}
