<?php

namespace App\Http\Controllers;

use App\DoDont;
use App\Jawaban;
use App\JawabanPegawai;
use App\PedomanPerilaku;
use App\PerilakuPegawai;
use App\Pertanyaan;
use App\PLNTerbaik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;

class PedomanPerilakuController extends Controller
{
    public function _index(Request $request)
    {
        $jml_pedoman = env('JML_PEDOMAN',18);
//        dd($request->session()->get('token'));
        //generate token
        if (!$request->session()->has('token')) {
            $token = bcrypt(str_random(16));
            $request->session()->put('token', $token);
        }
        else{
            $token = $request->session()->get('token');
        }

//        dd($token);
//        $list_pedoman = PedomanPerilaku::orderBy('id')->get();
        $perilaku_pegawai = PerilakuPegawai::where('user_id',Auth::user()->id)->where('tahun','=',date('Y'))->count();

        if($perilaku_pegawai == 0){ // komitmen baru
//            $id = PedomanPerilaku::orderBy('nomor_urut', 'asc')->first()->id;
            $id = 15;
//        }elseif($perilaku_pegawai == PedomanPerilaku::all('id')->count()){ // tandatangan komitmen
        }elseif($perilaku_pegawai == $jml_pedoman){ // tandatangan komitmen
            return redirect('commitment/komitmen-pegawai/'.$request->tahun);
        }
        else{
            $id = PerilakuPegawai::getNextKomitmenUser(); // komitmen terakhir
        }

        if($id!=null) {
            if( Cache::has('pedoman_'.$id) ) {
                $pedoman = Cache::get('pedoman_'.$id);
            }
            else {
                $pedoman = PedomanPerilaku::findOrFail($id);
                Cache::forever( 'pedoman_'.$id, $pedoman);
            }
//            $progress = ($pedoman->urutan / (PedomanPerilaku::all('id')->count()+1))*100;
            $progress = ($pedoman->urutan / ($jml_pedoman+1))*100;
//            dd($progress);
            if($pedoman->tipe == 1)
                return view('commitment.pedoman_perilaku', compact('pedoman', 'progress', 'token'));
            else
                return view('commitment.pedoman_perilaku2', compact('pedoman', 'progress', 'token'));
        }
        else{
            return redirect('/')->with('info','Anda sudah membaca Pedoman Perilaku.');
        }
    }

    public function index($tahun, Request $request)
    {
        if($tahun>date('Y') || $tahun<2017)
            return redirect('/')->with('warning','Tahun tidak sesuai');

        // jika role direksi / komisaris langsung redirect ke tandatangan komitmen
        if(Auth::user()->hasRole(['direksi','komisaris']))
            return redirect('commitment/direksi-komisaris/'.$tahun);

        $jml_pedoman = env('JML_PEDOMAN',14);
//        dd($request->session()->get('token'));
        //generate token
        if (!$request->session()->has('token')) {
            $token = bcrypt(str_random(16));
            $request->session()->put('token', $token);
        }
        else{
            $token = $request->session()->get('token');
        }

//        dd($jml_pedoman);
//        $list_pedoman = PedomanPerilaku::orderBy('id')->get();
        $perilaku_pegawai = PerilakuPegawai::where('user_id',Auth::user()->id)->where('tahun',$tahun)->get()->count();
//        dd($perilaku_pegawai);
        if($perilaku_pegawai == 0){ // komitmen baru
//            $id = PedomanPerilaku::orderBy('nomor_urut', 'asc')->first()->id;
            $id = 1;
//            dd($id);
//        }elseif($perilaku_pegawai == PedomanPerilaku::all('id')->count()){ // tandatangan komitmen
        }elseif($perilaku_pegawai == $jml_pedoman){ // tandatangan komitmen
            return redirect('commitment/komitmen-pegawai/'.$tahun);
        }
        else{
            $id = PerilakuPegawai::getNextKomitmenUser($tahun); // komitmen terakhir
        }

//        dd($id);

        if($id!=null) {
            if( Cache::has('pedoman_'.$id) ) {
                $pedoman = Cache::get('pedoman_'.$id);
            }
            else {
//                $pedoman = PedomanPerilaku::findOrFail($id);
                $pedoman = PLNTerbaik::findOrFail($id);
                Cache::forever( 'pedoman_'.$id, $pedoman);
            }
//            $progress = ($pedoman->urutan / (PedomanPerilaku::all('id')->count()+1))*100;
            $progress = ($pedoman->urutan / ($jml_pedoman+1))*100;
//            dd($pedoman->tipe);
            /*if($pedoman->tipe == 1)
                return view('commitment.pedoman_perilaku', compact('pedoman', 'progress', 'token', 'tahun'));
            else {
                return view('commitment.pedoman_perilaku2', compact('pedoman', 'progress', 'token', 'tahun'));
            }*/

            return view('commitment.pedoman_perilaku_pln_terbaik', compact('pedoman', 'progress', 'token', 'tahun'));
        }
        else{
            return redirect('/')->with('info','Anda sudah membaca Pedoman Perilaku.');
        }
    }

    public function submitPedoman(Request $request)
    {
        //cek pedoman perilaku
        $cek_perilaku = PerilakuPegawai::where('user_id', Auth::user()->id)->where('pedoman_perilaku_id',$request->pedoman_perilaku_id)->first();
//        dd($cek_perilaku);
        if($cek_perilaku==null) {
            $perilaku = new PerilakuPegawai();
            $perilaku->user_id = Auth::user()->id;
            $perilaku->pedoman_perilaku_id = $request->pedoman_perilaku_id;
            $perilaku->do = $request->checkbox_do;
            $perilaku->dont = $request->checkbox_dont;
            $perilaku->save();
        }

//        return view('commitment.pedoman_perilaku', compact('pedoman'));
        return redirect('commitment/pedoman-perilaku/tahun/'.$request->tahun);
    }

    public function quizPedoman(Request $request)
    {
        $jml_pedoman = env('JML_PEDOMAN',14);
//        $perilaku                       = new PerilakuPegawai();
//        $perilaku->user_id              = Auth::user()->id;
//        $perilaku->pedoman_perilaku_id  = $request->pedoman_perilaku_id;
//        $perilaku->do                   = $request->checkbox_do;
//        $perilaku->dont                 = $request->checkbox_dont;
//        $perilaku->save();

        //generate token
        if (!$request->session()->has('token')) {
            $token = bcrypt(str_random(16));
            $request->session()->put('token', $token);
        }
        else{
            $token = $request->token;
        }

//        dd($token.' - '.$request->session()->get('token'));
//        //wrong token
//        if($request->session()->get('token') != $request->token){
//            die('error token');
//        }
        if( Cache::has('pedoman_'.$request->pedoman_perilaku_id) ) {
            $pedoman = Cache::get('pedoman_'.$request->pedoman_perilaku_id);
        }
        else{
            $pedoman  = PedomanPerilaku::findOrFail($request->pedoman_perilaku_id);
            Cache::forever( 'pedoman_'.$request->pedoman_perilaku_id, $pedoman);
        }
//        $pedoman  = PedomanPerilaku::findOrFail($request->pedoman_perilaku_id);
        $progress = ($pedoman->urutan / ($jml_pedoman+1))*100;
        $pertanyaan = $pedoman->pertanyaan()->where('status','ACTV')->get()->random();
//        $jawaban = $pertanyaan->jawaban->shuffle();

//        dd($jawaban);
	if( Cache::has('list_berita_pln') ) {
            $collection = Cache::get('list_berita_pln');
            $berita = $collection[rand(0, 9)];
        }
	else{
        //$jml = Materi::count();
        //Cache::put( 'jml_materi', $jml, 1 );

        $feed_base_url = 'http://www.pln.co.id/feed';
        $per_page = 10;
        $page = 1;
        $feed_url = $feed_base_url.'?page='.$page.'&per_page='.$per_page;
        try {
            $feed = file_get_contents($feed_url);
            $data = (array)json_decode($feed);
            $collection = collect($data['items']);
            $berita = $collection[rand(0, 9)];
		    Cache::put( 'list_berita_pln', $collection, 10 );
        }catch (\Exception $e){
            $berita = null;
        }

	//Cache::put( 'berita_pln', $berita, 1 );
	}
//        dd($berita);
//        dd($berita->image->full);
//        dd($collection);

        $tahun = $request->tahun;

        return view('commitment.pertanyaan', compact('pertanyaan', 'progress', 'berita', 'token', 'tahun'));
//        return redirect('commitment/pedoman-perilaku');
    }

    public function daftarPedomanPerilaku(){
//        $pedoman_list = PedomanPerilaku::orderBy('urutan')->get();
        $pedoman_list = PLNTerbaik::orderBy('urutan')->get();
        return view('master.pedoman_perilaku_list', compact('pedoman_list'));
    }

    public function show($id){
//        $pedoman = PedomanPerilaku::findOrFail($id);
        $pedoman = PLNTerbaik::findOrFail($id);
        return view('master.pedoman_perilaku_display', compact('pedoman'));
    }

    public function answerPedoman(Request $request){
//        dd($request->token.' = '.$request->session()->get('token'));

        $tahun = $request->tahun;

        $jml_pedoman = env('JML_PEDOMAN',18);
        if($request->token != $request->session()->get('token'))
            return redirect('commitment/pedoman-perilaku/tahun/'.$tahun)->with('warning', 'Token salah.');

        $jawaban    = Jawaban::findOrFail($request->jawaban);

        if( Cache::has('pertanyaan_'.$request->pertanyaan_id) ) {
            $pertanyaan = Cache::get('pertanyaan_'.$request->pertanyaan_id);
        }
        else{
            $pertanyaan = Pertanyaan::findOrFail($request->pertanyaan_id);
            Cache::forever( 'pertanyaan_'.$request->pertanyaan_id, $pertanyaan);
        }

        $jawaban_pegawai                        = new JawabanPegawai();
        $jawaban_pegawai->user_id               = Auth::user()->id;
        $jawaban_pegawai->orgeh                 = Auth::user()->strukturJabatan->orgeh;
        $jawaban_pegawai->plans                 = Auth::user()->strukturJabatan->plans;
        $jawaban_pegawai->pedoman_perilaku_id   = $pertanyaan->pedoman_perilaku_id;
        $jawaban_pegawai->pertanyaan_id         = $pertanyaan->id;
        $jawaban_pegawai->jawaban_id            = $jawaban->id;
        $jawaban_pegawai->benar                 = $jawaban->benar;
        $jawaban_pegawai->tahun                 = $tahun;
        $jawaban_pegawai->save();

//        Jika jawaban pegawai benar
        if($jawaban->id == $pertanyaan->getJawabanBenar()->id){
            //cek pedoman perilaku
            $cek_perilaku = PerilakuPegawai::where('user_id', Auth::user()->id)->where('pedoman_perilaku_id',$pertanyaan->pedoman_perilaku_id)->where('tahun',$tahun)->first();
//            dd($cek_perilaku);
            if($cek_perilaku==null) {
                $perilaku = new PerilakuPegawai();
                $perilaku->user_id = Auth::user()->id;
                $perilaku->pedoman_perilaku_id = $pertanyaan->pedoman_perilaku_id;
                $perilaku->do = '1';
                $perilaku->dont = '1';
                $perilaku->tahun = $tahun;
                $perilaku->save();

                if ($perilaku->pedomanPerilaku->urutan == $jml_pedoman) {
                    return redirect('commitment/komitmen-pegawai/'.$tahun);
                }
//                else {
//                    return redirect('commitment/pedoman-perilaku');
//                }
            }
            return redirect('commitment/pedoman-perilaku/tahun/'.$tahun);

        }
        else{
            $token = bcrypt(str_random(16));
            $request->session()->put('token', $token);
            return redirect('commitment/pedoman-perilaku/tahun/'.$tahun)->with('warning', 'Jawaban Anda salah. Silakan coba lagi.');
        }

    }

    public function komitmenPegawai(){
        return view('commitment.komitmen_pegawai');
    }

    public function buku(){
//        $pedoman_list = PedomanPerilaku::orderBy('urutan')->get();
        $pedoman_list = PLNTerbaik::orderBy('urutan')->get();
        return view('commitment.buku_pedoman_perilaku', compact('pedoman_list'));
    }

    public function showBuku($id){
//        $pedoman = PedomanPerilaku::findOrFail($id);
        $pedoman = PLNTerbaik::findOrFail($id);
        $prev = $pedoman->urutan-1;
        if($prev==0) $prev='';
        else{
//            $pedoman_prev = PedomanPerilaku::where('urutan', $prev)->first();
            $pedoman_prev = PLNTerbaik::where('urutan', $prev)->first();
            $prev = $pedoman_prev->id;
        }
        $next = $pedoman->urutan+1;
        if($next==15) $next='';
        else{
//            $pedoman_next = PedomanPerilaku::where('urutan', $next)->first();
            $pedoman_next = PLNTerbaik::where('urutan', $next)->first();
            $next = $pedoman_next->id;
        }
        return view('commitment.pedoman_perilaku_display', compact('pedoman', 'prev', 'next'));
    }
}
