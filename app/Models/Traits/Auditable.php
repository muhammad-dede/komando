<?php

namespace App\Models\Traits;

trait Auditable
{
    public static function bootAuditable()
    {
        // skip auditable jika tidak ada user yg authenticated
        if (! auth()->check()) {
            return;
        }

        static::creating(function ($table) {
            if (is_null($table->created_by)) {
                $table->created_by = auth()->user()->id;
            }
        });

        static::updating(function ($table) {
            if (is_null($table->modified_by)) {
                $table->modified_by = auth()->user()->id;
            }
        });

        static::deleting(function ($table) {
            if (is_null($table->deleted_by)) {
                $table->deleted_by = auth()->user()->id;
            }
        });
    }
}
