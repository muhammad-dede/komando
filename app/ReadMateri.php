<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadMateri extends Model
{
    protected $table = 'read_materi';

    protected $fillable = [
        'username',
        'pernr',
        'nip',
        'materi_id',
        'tanggal_jam',
        'coc_id',
        'admin_id',
        'user_id',
        'rate_star',
    ];

    protected $dates = [
        'tanggal_jam',
    ];

    public function user()
    {
//        return $this->belongsTo('App\User', 'username', 'username');
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function strukturJabatan()
    {
        return $this->belongsTo('App\StrukturJabatan', 'pernr', 'pernr');
    }

    public function materi(){
        return $this->belongsTo('App\Materi', 'materi_id', 'id');
    }

    public function setAdminIdAttribute($attrValue){
        $this->attributes['admin_id'] = (string) $attrValue;
    }

    public function setCocIdAttribute($attrValue){
        $this->attributes['coc_id'] = (string) $attrValue;
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function setPernrAttribute($attrValue){
        $this->attributes['pernr'] = (string) $attrValue;
    }

    public static function updateReadMateri(){
//        $reader = ReadMateri::whereNull()->get();
        $users = User::all();
        foreach($users as $user){
            echo $user->username2.' : ';
            foreach($user->readMateriUsername()->whereNull('user_id')->get() as $read){
                $read->user_id = $user->id;
                $read->save();
                echo $read->materi_id.' | '.$user->id.'<br>\n';
            }
            echo '<br>\n';
        }
        return 'FINISH';

//        $reads = ReadMateri::whereNull('user_id')->get();
//        foreach($reads as $read){
//            $user = User::where('username',$read->username)->first();
//            $read->user_id = $user->id;
//            $read->save();
//            echo $read->materi_id.' | '.$user->id.'<br>\n';
//        }
//
//        return 'FINISH';
    }
}
