<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\ConfigLabel;

class ConfigLabelHelper
{
    public static function getLabel($key)
    {
        try{
            $data = ConfigLabel::select('translasi');
            if(empty($key)){
                throw new Exception();
            }
            $data->where('keys', $key)->where('status', '1');
            $result = $data->first();
            
            $label = "Config not found";
            if(!empty($result->translasi)){
                $label = $result->translasi;
            }
            
            return $label;
        }catch (\Exception $ex) {
            $result = $ex;
            return "Config not found ex";
        }
    }

    public static function getLabelSort($key)
    {
        try{
            $data = ConfigLabel::select('sort_translasi');
            if(empty($key)){
                throw new Exception();
            }
            $data->where('keys', $key)->where('status', '1');
            $result = $data->first();
            
            $label = "Config not found";
            if(!empty($result->sort_translasi)){
                $label = $result->sort_translasi;
            }
            
            return $label;
        }catch (\Exception $ex) {
            $result = $ex;
            return "Config not found ex";
        }
    }
}