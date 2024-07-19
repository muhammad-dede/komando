<?php

namespace App\Http\Controllers;

use App\GalleryCoc;
use App\KomitmenPegawai;
use App\Session;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Streaming;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspire = Inspiring::quote();
        //if( Cache::has('dashboard') ) {
        //    return Cache::get('dashboard');
        //}
        //$dashboard = view('dashboard', compact('inspire'));
        //Cache::put( 'dashboard', $dashboard, 1 );
//        $user_online = Session::all()->count();
//        dd($session);
//        $arr_inspire = explode(' - ',$inspire);
//        $inspire = "\"".$arr_inspire[0]."\" - ".$arr_inspire[1];
//        return view('dashboard', compact('inspire', 'user_online'));

        if( Cache::has('gallery_unit') ) {
            $gallery_unit =  Cache::get('gallery_unit');
        }
        else {
            $gallery_unit = GalleryCoc::with('coc.pemateri.strukturPosisi', 'coc.organisasi')
                            ->where('status','ACTV')
                            ->whereMonth('created_at', '=', date('m'))
                            ->whereYear('created_at', '=', date('Y'))
                            ->orderByRaw('DBMS_RANDOM.VALUE')
                            ->take(10)
                            ->get();
//            dd($gallery_unit);
            Cache::put('gallery_unit', $gallery_unit, 30);
        }

        // dd(Cache::has('list_berita_pln'));
        if( Cache::has('list_berita_pln') ) {
            $collection = Cache::get('list_berita_pln');
            $berita_col = $collection;
        }
        else{
            $feed_host = env('FEED_HOST','www.pln.co.id');
            $feed_base_url = 'http://'.$feed_host.'/feed';
            $per_page = 10;
            $page = 1;
            $feed_url = $feed_base_url.'?page='.$page.'&per_page='.$per_page;
            // dd($feed_url);
            try {
                $feed = file_get_contents($feed_url);
                $data = (array)json_decode($feed);
                $collection = collect($data['items']);
                $berita_col = $collection;
                Cache::put( 'list_berita_pln', $collection, 60 );
            }catch (\Exception $e){
                $berita_col = null;
            }

            //Cache::put( 'berita_pln', $berita, 1 );
        }

        // dd($berita_col);

        // PLN MEDIA
//        $arr_video = ['lebaran.mp4', 'mudik.mp4', 'asian-games.mp4', 'lisdes-kal.mp4', 'ramadhan.mp4'];
//        $arr_video = ['trc-pln.mp4', 'lebaran.mp4', 'lisdes-kal.mp4', 'asian-games.mp4','layang2.mp4'];
//        $arr_video = ['papua-terang.mp4','bersama-pln.mp4','trc-pln.mp4', 'lebaran.mp4', 'lisdes-kal.mp4', 'asian-games.mp4', 'layang2.mp4'];
        $arr_video = ['tsunami-1.mp4','tsunami-2.mp4','tsunami-3.mp4', 'tsunami-4.mp4', 'tsunami-5.mp4', 'tsunami-6.mp4', 'tsunami-7.mp4'];
        $arr_judul = ['Cepat Tanggap PLN UID Lampung Bantu Korban Tsunami Selat Sunda dan Pulihkan Kelistrikan Lampung',
                        'Pembangkit listrik dekat Gunung Anak Krakatau masih beroperasi secara normal',
                        'PLN Peduli Bantu Korban Bencana Tsunami Selat Sunda',
                        'Pascabencana, PLN Tembus Pulau Sebesi Lampung',
                        'Aksi Relawan PLN Pulihkan Kelistrikan Pascabencana Tsunami Selat Sunda',
                        'Recovery Kelistrikan Daerah Terdampak Tsunami Selat Sunda Capai 95 Persen',
                        'Sinergi BUMN Sambung Listrik Gratis untuk Keluarga Tidak Mampu di Jawa Barat dan Banten'];
        $index = rand(0,6);
        $video = $arr_video[$index];
        $judul_video = $arr_judul[$index];

        // get data streaming
        $streaming = Streaming::where('status','ACTV')->orderBy('id', 'desc')->first();
        if($streaming != null){
            $is_streaming = true;
        }
        else{
            $is_streaming = false;
        }

        return view('dashboard', compact('inspire', 'gallery_unit', 'berita_col', 'video', 'judul_video', 'is_streaming', 'streaming'));
//        return $dashboard;
    }

    private function storeHome()
    {
        $this->putInCache('home', $this->render->getHome(), 'home');
    }

    private function putInCache($key, $content, $tag)
    {
        Cache::tags($tag)->put($key, $content, 43200);
    }

    public function sistem($id)
    {
//        $sistem_besar   = SistemBesar::findOrfail($id);
//
//        return view('dashboard_sistem', compact('sistem_besar'));
    }

    public function help(){
        $filename = 'app/user_manual.pdf';
        $path = storage_path($filename);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }

    public function dashboardBudaya(){
        return view('dashboard_budaya');
    }
}
