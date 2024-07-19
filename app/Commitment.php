<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    //
    public static function deleteDuplicate(){
//        $user = User::find(2594);

        $user_list = User::where('status', 'ACTV')->orderBy('id', 'asc')->get();

        foreach($user_list as $user) {
            $perilaku = $user->perilakuPegawaiTahun(2017)->sortBy('pedoman_perilaku_id', 1);
            if ($perilaku->count() > 18) {
                echo '>>> '.$perilaku.'<br>';
                for ($x = 1; $x <= 18; $x++) {
                    echo 'pedoman ' . $x . ' <br>';
                    $duplicate = $user->perilakuPegawaiTahun(2017)->where('pedoman_perilaku_id', $x . '')->sortBy('id', 1);
                    if ($duplicate->count() > 1) {
                        $y = 1;
                        foreach ($duplicate as $dup) {
                            echo $y . ' - ' . $dup->pedoman_perilaku_id . '<br>';
                            if ($y > 1) {
//                            dd($dup);
                                $dup->delete();
                                echo 'DEL<br>';
                            }
                            $y++;
                        }
                    }
                }

            }
        }
//        dd($perilaku);

    }
}
