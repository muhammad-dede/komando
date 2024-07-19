<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterfaceLog extends Model
{
    protected $table = 'interface_log';

    public static function log($file, $message, $status)
    {
        $log = new InterfaceLog();
        $log->file = $file;
        $log->message = $message;
        $log->status = $status;
        $log->save();
    }
}
