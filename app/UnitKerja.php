<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'user_id', 'm_business_area_id', 'business_area', 'company_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businessArea()
    {
        return $this->belongsTo(BusinessArea::class, 'm_business_area_id');
    }
}
