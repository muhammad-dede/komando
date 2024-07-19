<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithPages;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidPesertaControllerTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use InteractsWithPages;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    public function testEditOk()
    {
        $this->emptyLiquidTables()->login();
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->visit("liquid/{$liquid->getKey()}/peserta/edit")
            ->see("Edit Peserta Liquid #{$liquid->getKey()}")
            ->assertResponseOk();
    }

    public function testBisaTambahAtasan()
    {
        $this->emptyLiquidTables()->login();
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
     
        $this->callCustom('POST', "liquid/{$liquid->getKey()}/peserta", [
            'atasan' => [
                '90157009'
            ]
        ])->followRedirects()
        ->see('Peserta berhasil ditambahkan');
    }

    public function testBisaUbahAtasan()
    {
        $this->emptyLiquidTables()->login();
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $this->callCustom('POST', "liquid/{$liquid->getKey()}/peserta", [
            'atasan_id' => '90157009',
            'bawahan' => ['90157009']
        ]);
        $link = route('liquid.peserta.update', [$liquid->getKey()]);

        $this->callCustom('PUT', $link, [
            '_token' => csrf_token(),
            "atasan_lama" => "90157009",
            "atasan_baru" => "77047403"
            ])
            ->followRedirects()
            ->see('Peserta berhasil diperbarui');
    }

    public function testBisaTambahBawahan()
    {
        $this->emptyLiquidTables()->login();
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $this->callCustom('POST', "liquid/{$liquid->getKey()}/peserta", [
            'atasan_id' => '90157009',
            'bawahan' => ['90157009']
        ])->followRedirects()
        ->see('Peserta berhasil ditambahkan');
    }

    public function testBisaHapusBawahan()
    {
        $this->emptyLiquidTables()->login();
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $this->callCustom('POST', "liquid/{$liquid->getKey()}/peserta", [
            'atasan_id' => '90157009',
            'bawahan' => ['90157009']
        ]);
        $link = route('liquid.peserta.destroy', [$liquid->getKey(), '90157009']);

        $this->callCustom('DELETE', $link, ['_token' => csrf_token(), 'action' => 'atasan'])
            ->followRedirects()
            ->see('Atasan berhasil dihapus');
    }
}
