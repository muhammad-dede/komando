<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidScopeTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    public function testScopePublished()
    {
        $this->emptyLiquidTables();
        factory(\App\Models\Liquid\Liquid::class)->create(['status' => \App\Enum\LiquidStatus::DRAFT]);
        factory(\App\Models\Liquid\Liquid::class)->create(['status' => \App\Enum\LiquidStatus::PUBLISHED]);

        $jumlahLiquidPublished = \App\Models\Liquid\Liquid::query()->published()->count();

        $this->assertEquals(1, $jumlahLiquidPublished);
    }

    public function testScopeCurrentYear()
    {
        $this->emptyLiquidTables();

        // Buat 2 buah liquid, satu tahun ini satu tahun depan
        factory(\App\Models\Liquid\Liquid::class)->create(['feedback_start_date' => \Carbon\Carbon::now()->format('Y-m-d')]);
        factory(\App\Models\Liquid\Liquid::class)->create(['feedback_start_date' => \Carbon\Carbon::now()->addYear()->format('Y-m-d')]);

        $jumlahLiquidTahunIni = \App\Models\Liquid\Liquid::query()->currentYear()->count();
        $this->assertEquals(1, $jumlahLiquidTahunIni);
    }

    public function testScopeForUnit()
    {
        $service = app(\App\Services\LiquidService::class);
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $businessAreas = [2001, 5120];
        $service->syncBusinessAreaDanPeserta($liquid, $businessAreas);

        // 2001 adalah kode business area SBU
        $jumlahLiquidSBU = \App\Models\Liquid\Liquid::query()->forUnit(2001)->count();
        $this->assertEquals(1, $jumlahLiquidSBU);
    }

    public function testScopeActiveFor()
    {
        $service = app(\App\Services\LiquidService::class);

        $liquid1 = factory(\App\Models\Liquid\Liquid::class)->create(['feedback_start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'status' => \App\Enum\LiquidStatus::PUBLISHED]);
        $liquid2 = factory(\App\Models\Liquid\Liquid::class)->create(['feedback_start_date' => \Carbon\Carbon::now()->format('Y-m-d'), 'status' => \App\Enum\LiquidStatus::DRAFT]);
        $liquid3 = factory(\App\Models\Liquid\Liquid::class)->create(['feedback_start_date' => \Carbon\Carbon::now()->addYear()->format('Y-m-d'), 'status' => \App\Enum\LiquidStatus::PUBLISHED]);

        $businessAreas = [2001];
        $service->syncBusinessAreaDanPeserta($liquid1, $businessAreas);
        $service->syncBusinessAreaDanPeserta($liquid2, $businessAreas);
        $service->syncBusinessAreaDanPeserta($liquid3, $businessAreas);

        $activeLiquid = \App\Models\Liquid\Liquid::query()->activeForUnit(2001)->count();
        $this->assertEquals(1, $activeLiquid);
    }
}
