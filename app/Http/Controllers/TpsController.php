<?php

namespace App\Http\Controllers;

use App\Session;
use App\Tps;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TpsController extends Controller
{
    public function test($limit){

        echo 'Server : '.env('APP_NAME').'<br>';
        echo 'User Online : '.Session::all()->count().'<br>';
        echo 'User Login : '.Session::whereNotNull('user_id')->get()->count().'<br><br>';

        $date = Carbon::now();

        if($limit=='')
            $limit = 200;

//        INSERT
        echo '1. TEST INSERT<br><br>';
        for($x=1;$x<=$limit;$x++) {
            $tps = new Tps();
//            $tps->id = $x.'';
            $tps->name = 'Data ' . $x;
            $tps->save();

            echo 'Input ke-'.$x.': '.$tps->name.' | Time: '.Carbon::now()->format('H:i:s').'<br>';
        }

//        SELECT
        echo '<br><br>';
        echo '2. TEST SELECT<br><br>';
        for($x=1;$x<=$limit;$x++) {
            $tps = Tps::first();
            echo 'Select ke-'.$x.': '.$tps->name.' | Time: '.Carbon::now()->format('H:i:s').'<br>';
        }

//        UPDATE
        echo '<br><br>';
        echo '3. TEST UPDATE<br><br>';
        for($x=1;$x<=$limit;$x++) {
            $tps = Tps::first();
            $tps->name = 'Data '. $x;
            $tps->save();
            echo 'Update ke-'.$x.': '.$tps->name.' | Time: '.Carbon::now()->format('H:i:s').'<br>';
        }

//        SELECT
        echo '<br><br>';
        echo '4. TEST SELECT<br><br>';
        for($x=1;$x<=$limit;$x++) {
            $tps = Tps::first();
            echo 'Select ke-'.$x.': '.$tps->name.' | Time: '.Carbon::now()->format('H:i:s').'<br>';
        }

//        DELETE
        echo '<br><br>';
        echo '5. TEST DELETE<br><br>';
        for($x=1;$x<=$limit;$x++) {
            $tps = Tps::first();
            echo 'Delete ke-'.$x.': '.$tps->name.' | Time: '.Carbon::now()->format('H:i:s').'<br>';
            $tps->delete();
        }

        return view('reload');

    }


}
