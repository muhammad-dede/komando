<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigLabel extends Model
{
    protected $table = 'config_label';
    protected $fillable = [
        'keys', 'translasi', 'sort_translasi', 'status', 'created_by', 'created_at', 'modified_by', 'updated_at'
    ];
}