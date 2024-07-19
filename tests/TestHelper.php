<?php
use PHPUnit_Framework_Assert as PHPUnit;

trait TestHelper
{
    protected $user;

    protected function skipAuthorization()
    {
        // Skip Laravel built-in authorization checks
        \Gate::before(function () {
            return true;
        });

        // skip Entrust permission checking
        $middleware = Mockery::mock(\Zizaco\Entrust\Middleware\EntrustPermission::class, (app(\Illuminate\Contracts\Auth\Guard::class)));
        $middleware->shouldReceive('handle')
            ->andReturnUsing(function ($request, Closure $next) {
                return $next($request);
            });

        app()->instance(\Zizaco\Entrust\Middleware\EntrustPermission::class, $middleware);
    }

    protected function login($user = null)
    {
        if ($user) {
            $this->user = $user;
        } else {
            $this->user = \App\User::find(1);
            if (! $this->user) {
                $this->user = factory(\App\User::class)->create(['id' => 1]);
            }
        }

        return $this->actingAs($this->user);
    }

    protected function emptyLiquidTables()
    {
        \Illuminate\Support\Facades\DB::table('activity_log_book')->delete();
        \Illuminate\Support\Facades\DB::table('feedbacks')->delete();
        \Illuminate\Support\Facades\DB::table('pengukuran_pertama')->delete();
        \Illuminate\Support\Facades\DB::table('pengukuran_kedua')->delete();
        \Illuminate\Support\Facades\DB::table('penyelarasan')->delete();
        \Illuminate\Support\Facades\DB::table('liquid_peserta')->delete();
        \Illuminate\Support\Facades\DB::table('liquid_business_area')->delete();
        \Illuminate\Support\Facades\DB::table('kelebihan_kekurangan_detail')->delete();
        \Illuminate\Support\Facades\DB::table('kelebihan_kekurangan')->delete();
        \Illuminate\Support\Facades\DB::table('liquids')->delete();
        \Illuminate\Support\Facades\DB::table('media')->delete();

        return $this;
    }

    public function callCustom($type, $uri, array $data = [], array $file = [], array $headers = [])
    {
        $this->call($type, $uri, $data, [], $file, []);
        return $this;
    }

    public function assertT($var)
    {
        PHPUnit::assertTrue($var, "Expected true, got false.");

        return $this;
    }

    public function assertF($var)
    {
        PHPUnit::assertFalse($var, "Expected false, got true.");

        return $this;
    }
}
