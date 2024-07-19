<?php

namespace App\Utils;

use App\CompanyCode;
use App\Enum\RolesEnum;
use App\User;

class CompanyCodeUtil
{
    public $companyCode;

    public function getAll()
    {
        $this->companyCode = CompanyCode::all();

        return $this->sortId();
    }

    public function toSelectOptions($isWithCompanyCode = true)
    {
        return $this->companyCode->pluck($isWithCompanyCode ? 'description_option' : 'description', 'company_code')->toArray();
    }

    private function sortId()
    {
        $this->companyCode->sortBy('id');

        return $this;
    }

    public function generateOptions(User $user, array $selected, $isWithPlaceholder = true, $isWithCompanyCode = true)
    {
        $arrayInit = [];

        if ($isWithPlaceholder) {
            $arrayInit = ['Semua Company Code'];
        }

        if ($user->hasRole(RolesEnum::ADMIN_HTD) && ! empty($selected)) {
            $result = $arrayInit + CompanyCode::whereIn('company_code', $selected)->get()
                ->pluck($isWithCompanyCode ? 'description_option' : 'description', 'company_code')->toArray();
        } elseif ($user->hasRole('root') || $user->hasRole('admin_pusat') || $user->hasRole('admin_ki')) {
            $result = $arrayInit + $this->getAll()->toSelectOptions($isWithCompanyCode);
        } else {
            $result = $arrayInit + CompanyCode::whereIn('company_code', [$user->company_code])->get()
                ->pluck($isWithCompanyCode ? 'description_option' : 'description', 'company_code')->toArray();
        }

        return $result;
    }

    public function getWhere($column, $value)
    {
        $this->companyCode = CompanyCode::where($column, $value);

        return $this;
    }
}
