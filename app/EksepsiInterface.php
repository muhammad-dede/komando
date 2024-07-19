<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EksepsiInterface extends Model
{
    protected $table = 'interface_exclude';

    protected $dates = ['begda','endda'];
}
