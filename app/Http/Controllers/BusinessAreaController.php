<?php

namespace App\Http\Controllers;

use App\BusinessArea;
use App\Tgsb;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BusinessAreaController extends Controller
{
    public function updateBusinessArea(){
        $tgsbs = Tgsb::whereNotNull('gsber')->get();

        foreach($tgsbs as $tgsb){
            $ba = BusinessArea::where('business_area', $tgsb->gsber)->first();
            if($ba!=null){
                $ba->description = $tgsb->description;
                $ba->save();
                echo $ba->business_area.'-'.$ba->description.'<br>';
            }
            else{
                echo 'create new: ';
                $ba2 = new BusinessArea();
                $cc = substr($tgsb->gsber, 0, -2).'00';
//                dd($cc);
                $ba2->company_code = $cc;
                $ba2->business_area = $tgsb->gsber;
                $ba2->description = $tgsb->description;
                $ba2->save();
                echo $ba2->company_code.'-'.$ba2->business_area.'-'.$ba2->description.'<br>';
            }
        }
    }
}
