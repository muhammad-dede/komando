<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidPublishedTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    protected $service;

    protected function setUp()
    {
        parent::setUp();
        $this->emptyLiquidTables();
        $this->service = app(\App\Services\LiquidService::class);
    }

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function it_can_send_notifications()
    {
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $this->service->syncBusinessAreaDanPeserta($liquid, ['7201']);
        $liquid->addMedia(base_path('tests/data/dummy.pdf'))->preservingOriginal()->toMediaLibrary();
        $this->service->publish($liquid);

        $this->assertTrue(true);
    }
}
