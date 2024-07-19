<?php

namespace App\Models\Traits;

use App\User;

trait LiquidHasCreator
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }

        return null;
    }

    public function getCreatorNipAttribute()
    {
        if ($this->creator) {
            return $this->creator->nip;
        }

        return null;
    }
}
