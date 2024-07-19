<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Coc;
use App\Event;
use App\ReadMateri;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReadMateriController extends Controller
{
    public function store(Request $request, $event_id)
    {
        $coc = Coc::find($event_id);

        if(Auth::user()->hasReadMateriCoc(@$coc->materi->id, $coc->id) == false){

            $read = new ReadMateri();
            $read->username = Auth::user()->username;
            $read->user_id = Auth::user()->id;
            if(Auth::user()->strukturJabatan != null)
                $read->pernr = @Auth::user()->strukturJabatan->pernr;
            $read->nip = Auth::user()->nip;
            $read->materi_id = $request->materi_id;
            $read->rate_star = $request->rate;
            $read->tanggal_jam = Carbon::now();
            $read->coc_id = $coc->id;
            $read->admin_id = $coc->admin_id;
            $read->save();
            
            // calculate rate materi
            $materi = $read->materi;
            $materi->calcArrJmlRate();

            Activity::log('Baca Materi : '.$coc->judul.'('.$coc->tanggal_jam->format('d/m/Y').')', 'success');
            return redirect('coc/event/'.$event_id)->with('success', 'Terimakasih Anda telah membaca materi.');
        }

        return redirect('coc/event/'.$event_id)->with('warning', 'Anda sudah membaca materi.');
    }

    public function deleteDuplicate(){
        $materi_id = 2680;
        $read_materi = ReadMateri::where('materi_id', $materi_id)->orderBy('id', 'asc')->get(['id','user_id','coc_id','materi_id']);
        foreach($read_materi as $read){
            // cek user read > 1
            $jml_user_read = ReadMateri::where('materi_id', $materi_id)
                                        ->where('coc_id', $read->coc_id)
                                        ->where('user_id', $read->user_id)
                                        ->count();
            // update data
            if($jml_user_read > 1){
                $list_update = ReadMateri::where('materi_id', $materi_id)
                                        ->where('coc_id', $read->coc_id)
                                        ->where('user_id', $read->user_id)
                                        ->take($jml_user_read-1)->get();
                // dd($list_update);                        
                foreach($list_update as $update){
                    $update->materi_id = 0;
                    $update->save();
                    echo $update->id.'/'.$update->username.' - ';
                }
            }
        }
        // dd($read_materi);
        return 'FINISHED';
    }

}
