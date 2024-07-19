<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group liquid
 */
class LiquidDokumenControllerTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    /**
     * @test
     */
    public function tbd_can_see_liquid_dokumen_edit_page()
    {
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();

        $this
            ->visit("liquid/{$liquid->getKey()}/dokumen/edit")
            ->see("Edit Liquid #{$liquid->getKey()}")
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function b18_can_update_dokumen()
    {
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $file = base_path('tests/data/dummy-temp.pdf');
        copy(base_path('tests/data/dummy.pdf'), $file);
        $uploadedFile = new \Illuminate\Http\UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->login();
        $this->call('PUT', route('liquid.dokumen.update', $liquid), [], [], ['dokumen' => [$uploadedFile]], []);
        $this->assertResponseStatus(302);
        $this->assertEquals($liquid->getMedia()->count(), 1);
    }

    /**
     * @test
     */
    public function b19_cannot_update_dokumen_with_invalid_file()
    {
        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $file = base_path('tests/data/dummy.jpg');
        $uploadedFile = new \Illuminate\Http\UploadedFile($file, 'dummy.jpg', 'image/jpg', filesize($file), null, true);
        $this->login();
        $this->call('PUT', route('liquid.dokumen.update', $liquid), [], [], ['dokumen' => [$uploadedFile]], []);

        $this->assertEquals($liquid->getMedia()->count(), 0);
    }

    /**
     * @test
     */
    public function b20_can_delete_dokumen()
    {
        $dokumen = base_path('tests/data/dummy.pdf');
        $this->login();

        $liquid = factory(\App\Models\Liquid\Liquid::class)->create();
        $media = $liquid->addMedia($dokumen)->preservingOriginal()->toMediaLibrary();

        $this->get("liquid/{$liquid->getKey()}/dokumen/{$media->getKey()}/delete")->assertResponseStatus(302);
        $this->notSeeInDatabase('media', ['id' => $media->getKey()]);
    }
}
