<?php

namespace App\Utils;

use App\Enum\RolesEnum;
use App\User;

class UnitKerjaUtil
{
    public $unitKerja;

    public function shiftingBusinessArea(User $user)
    {
        $user->load('unitKerjas');

        $result = $user->unitKerjas->pluck('business_area');

        return $this->result($user, $user->business_area, $result, 'business_area');
    }

    public function shiftingCompanyCode(User $user, $isWithHardcodeDefaultValue = true)
    {
        $user->load('unitKerjas');

        if (
            ($user->hasRole('root') || $user->hasRole('admin_pusat'))
            && $isWithHardcodeDefaultValue
        ) {
            $companyCode = '8200';
        } else {
            $companyCode = $user->company_code;
        }

        $result = $user->unitKerjas->pluck('company_code');

        $array = $this->result($user, $companyCode, $result, 'company_code');

        return array_unique($array);
    }

    private function result($user, $defaultValue, $collect, $type)
    {
        if (! $user->hasRole(RolesEnum::ADMIN_HTD)) {
            $defaultValue = $user->{$type};
        }

        return $user->unitKerjas->isEmpty()
            ? [$defaultValue]
            : $collect->toArray();
    }
}
