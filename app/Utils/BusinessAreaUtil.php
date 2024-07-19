<?php

namespace App\Utils;

use App\BusinessArea;
use App\Enum\RolesEnum;
use App\User;

class BusinessAreaUtil
{
    public $businessArea;

    public function getAll()
    {
        $this->businessArea = BusinessArea::all();

        return $this->sortId();
    }

    public function getWhere($column, $value)
    {
        $this->businessArea = BusinessArea::where($column, $value);

        return $this->sortId();
    }

    public function toSelectOptions()
    {
        return $this->businessArea->pluck('description_option', 'business_area')->toArray();
    }

    private function sortId()
    {
        $this->businessArea->sortBy('id');

        return $this;
    }

    public function generateOptions(User $user, $selected = [], $label = null)
    {
        $label = is_null($label)
            ? 'Business Area'
            : $label;

        $arrayInit = ['Select ' . $label];

        if ($user->hasRole(RolesEnum::ADMIN_HTD)) {
            $businessAreaOpts = $arrayInit + BusinessArea::whereIn('business_area', $selected)->get()
                ->pluck('description_option', 'business_area')->toArray();
        } elseif ($user->hasRole('root') || $user->hasRole('admin_pusat')) {
            $businessAreaOpts = $arrayInit + $this->getAll()->toSelectOptions();
        } else {
            $businessAreaOpts = $arrayInit + BusinessArea::where('business_area', $user->business_area)->get()
                ->pluck('description_option', 'business_area')->toArray();
        }

        return $businessAreaOpts;
    }
}
