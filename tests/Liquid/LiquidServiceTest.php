<?php

use App\Enum\LiquidJabatan;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidServiceTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    protected $service;

    protected function setUp()
    {
        parent::setUp();
        $this->service = app(\App\Services\LiquidService::class);
    }

    /**
     * Test invalid dates
     * @return void
     * @dataProvider listPeserta
     * @test
     */
    public function testSyncBusinessAreaDanPeserta($unit, $jabatanAtasan, $pernAtasan, $expectedBawahan)
    {
        $this->emptyLiquidTables();

        // Arrange
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $this->service->syncBusinessAreaDanPeserta($liquid, $unit);

        // Act
        $peserta = $this->service->listPeserta($liquid);

        // Assert
        $this->assertArrayHasKey($pernAtasan, $peserta);
        $this->assertEquals($jabatanAtasan, $peserta[$pernAtasan]['kelompok_jabatan']);

        foreach ($expectedBawahan as $pernrBawahan => $jabatanBawahan) {
            $pesertaBawahan = $peserta[$pernAtasan]['peserta'];
            $this->assertArrayHasKey($pernrBawahan, $pesertaBawahan);
            $this->assertEquals($jabatanBawahan, $pesertaBawahan[$pernrBawahan]['kelompok_jabatan']);
        }
    }

    public function listPeserta()
    {
        // [business area] - Kategori Jabatan - PERNR Atasan - [List of  Bawahan => Jabatan]

        return [
            [
                ['1001'],
                LiquidJabatan::EVP,
                '75150000',
                [
                    '70943604' => LiquidJabatan::VP,
                    '64878007' => LiquidJabatan::MANAGER,
                    '64936600' => LiquidJabatan::STAF,
                ],
            ],
            [
                ['8601'],
                LiquidJabatan::GM,
                '68940024',
                [
                    '71950007' => LiquidJabatan::SRM,
                    '73930400' => LiquidJabatan::STAF,
                ],
            ],
            [
                ['1001'],
                LiquidJabatan::VP,
                '73160000',
                [
                    '87113619' => LiquidJabatan::MANAGER,
                    '78070000' => LiquidJabatan::MANAGER,
                ],
            ],
            [
                ['2101'],
                LiquidJabatan::SRM,
                '75025801',
                [
                    '72921609' => LiquidJabatan::MANAGER,
                    '70921607' => LiquidJabatan::MANAGER,
                    '80081600' => LiquidJabatan::STAF,
                ],
            ],
            // currently disabled untuk tahun 2020
            // [
            //     ['1001'],
            //     LiquidJabatan::MD_PUSAT,
            //     '85096600',
            //     [
            //         '86116803' => LiquidJabatan::SPV_ATAS_SUP,
            //         '96190006' => LiquidJabatan::STAF,
            //     ],
            // ],
            [
                ['2116'],
                LiquidJabatan::MD_UP,
                '77041600',
                [
                    '82071602' => LiquidJabatan::SPV_ATAS_SUP,
                    '71931610' => LiquidJabatan::STAF,
                ],
            ],
            [
                ['2116'],
                LiquidJabatan::SPV_ATAS_SUP,
                '82061611',
                [
                    '86081601' => LiquidJabatan::SPV,
                ],
            ],
        ];
    }
}
