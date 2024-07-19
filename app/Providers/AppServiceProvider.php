<?php

namespace App\Providers;

use App\Enum\KelebihanKekuranganStatus;
use App\Models\Liquid\KelebihanKekurangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Carbon::setLocale('id');
        
        if(config('app.env') === 'production' || config('app.env') === 'training') {
            $url->forceSchema('https');
        }

		Validator::extend(
			'activate_masdat_kelebihan_kekurangan',
			function($attribute, $value, $parameters, $validator) {
				$aktifExist = KelebihanKekurangan::where('status', KelebihanKekuranganStatus::AKTIF)
					->count();
				if (isset($parameters[1])) {
					$data = KelebihanKekurangan::findOrFail($parameters[1]);
					if ($value === KelebihanKekuranganStatus::TIDAK_AKTIF) {
						return true;
					} else if ($data->status === KelebihanKekuranganStatus::AKTIF && $aktifExist <= 1) {
						return true;
					} else {
						return $aktifExist === 0;
					}
				} else {
					if ($value === KelebihanKekuranganStatus::TIDAK_AKTIF) {
						return true;
					} else {
						return $aktifExist ? false : true;
					} 
				}
			}
		);

		Validator::extend(
			'date_greater_or_sameass',
			function ($attribute, $value, $parameters, $validator) {
				$data 			= $validator->getData();
				$compareField	= strtotime(
					Carbon::parse($data[$parameters[0]])
				);
				$current		= strtotime(
					Carbon::parse($value)
				);
				return $current >= $compareField;
			}
		);

		Validator::replacer('greater_or_sameass', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
