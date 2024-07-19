<?php

namespace App;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryCoc extends Model
{
    protected $table = 'gallery_coc';

    public function setCocIdAttribute($attrValue)
    {
        $this->attributes['coc_id'] = (string)$attrValue;
    }

    public function coc()
    {
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }

    public static function renameFoto()
    {
        $list_foto = GalleryCoc::where('id', '<', 14430)
            ->where('status', 'ACTV')
            ->orderBy('id', 'desc')->get();

//        $foto = GalleryCoc::find(14511);

        foreach ($list_foto as $foto) {
            $new_filename = $foto->coc_id . '_' . $foto->filename;
            $exists = Storage::disk('local')->exists($foto->folder . '/' . $foto->filename);
            //dd($exists);
            if ($exists) {
                Storage::move($foto->folder . '/' . $foto->filename, $foto->folder . '/' . $new_filename);
                $foto->filename = $new_filename;
                $foto->save();
                echo $foto->id . ', ';
            }
        }

//        dd($list_foto->count());
    }

    public static function renameFotoUser(){
        $list_foto = User::whereNotNull('foto')
            ->orderBy('id', 'asc')->get();

        $foto = User::find(16581);

//        foreach ($list_foto as $foto) {
            $new_filename = $foto->nip . '_' . $foto->foto;
            $exists = Storage::disk('local')->exists($foto->business_area . '/foto/' . $foto->foto);
            //dd($exists);
            if ($exists) {
                Storage::move($foto->business_area . '/foto/' . $foto->foto, $foto->business_area . '/foto/' . $new_filename);
                $foto->foto = $new_filename;
                $foto->save();
                echo $foto->id . ', ';
            }
//        }
    }
}
