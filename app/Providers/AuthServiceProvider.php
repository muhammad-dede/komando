<?php

namespace App\Providers;

use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Policies\Liquid\FeedbackPolicy;
use App\Policies\Liquid\PenyelarasanPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		LiquidPeserta::class	=> FeedbackPolicy::class
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        Gate::define('accessPenyelarasan', 'App\Policies\Liquid\PenyelarasanPolicy@accessPenyelarasan');
        Gate::define('inputPenyelarasan', 'App\Policies\Liquid\PenyelarasanPolicy@inputPenyelarasan');
        Gate::define('penyelarasanInProgress', 'App\Policies\Liquid\PenyelarasanPolicy@dateInProgress');
        Gate::define('inputActLogBook', 'App\Policies\Liquid\ActivityLogBookPolicy@input');
        Gate::define('canAccessActLogBook', 'App\Policies\Liquid\ActivityLogBookPolicy@canAccess');
        Gate::define('atasanShouldHavePenyelarasan', 'App\Policies\Liquid\PengukuranPertamaPolicy@hasPenyelarasan');
        Gate::define('liquidPesertaExist', 'App\Policies\Liquid\LiquidPesertaPolicy@pesertaExist');
        Gate::define('liquidPesertaAtasanExist', 'App\Policies\Liquid\LiquidPesertaPolicy@atasanExist');
    }
}
