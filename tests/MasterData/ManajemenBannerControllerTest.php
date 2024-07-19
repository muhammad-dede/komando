<?php

use App\Models\MediaKit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

/**
 * @group manajemenbanner
 */
class ManajemenBannerControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    
    protected function setUp()
    {
        parent::setUp();

        $this->skipAuthorization();
        $this->emptyLiquidTables();
    }

    public function login_as_fahmi()
    {
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
    }

    // manajemen-media-banner
    /**
     * route
     * manajemen-media-banner
     * get
     * H1
     *
     * @test
     * */
    public function H1_can_access_manajemen_media_banner_as_fahmi()
    {
        $this->login_as_fahmi();
        $this->get('manajemen-media-banner')
        ->followRedirects()
        ->see('Master Data Media & Banner');
    }

    /**
     * route
     * manajemen-media-banner
     * get
     * H1
     *
     * @test
     * */
    public function H4_can_create_access_manajemen_media_banner_as_fahmi()
    {
        $this->H1_can_access_manajemen_media_banner_as_fahmi();
        $this->get('manajemen-media-banner/create')
            ->followRedirects()
            ->see('Tambah Media & Banner');
    }

    /**
     * route
     * manajemen-media-banner
     * post
     * H4
     *
     * @test
     * */
    public function H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input()
    {
        $link = route('manajemen-media-banner.store');
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_can_create_access_manajemen_media_banner_as_fahmi();
        $this->callCustom('POST', $link, [
            'judul' => str_random(20),
            'jenis' => str_random(20),
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->followRedirects()
        ->see('Media berhasil disimpan');
    }

    /**
     * route
     * manajemen-media-banner
     * post
     * H4
     *
     * @test
     * */
    public function H4_post_create_access_manajemen_media_banner_as_fahmi_wit_null_input()
    {
        $link = route('manajemen-media-banner.store');
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_can_create_access_manajemen_media_banner_as_fahmi();
        $this->callCustom('POST', $link, [
            'judul' => '',
            'jenis' => '',
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->assertSessionHasErrors();
    }

    /**
     * route
     * manajemen-media-banner
     * post
     * H4
     *
     * @test
     * */
    public function H4_post_create_access_manajemen_media_banner_as_fahmi_wit_266_input()
    {
        $this->markTestSkipped(
            "di db max 255, belom ada validasi max 255"
        );
        $link = route('manajemen-media-banner.store');
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_can_create_access_manajemen_media_banner_as_fahmi();
        $this->callCustom('POST', $link, [
            'judul' => str_random(266),
            'jenis' => str_random(266),
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->assertSessionHasErrors();
    }

    /**
     * route
     * manajemen-media-banner
     * get
     * H5
     *
     * @test
     * */
    public function H5_get_show_access_manajemen_media_banner_as_fahmi()
    {
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.show', $items[0]->id);
        $this->get($link)
            ->followRedirects()
            ->see('Informasi');
    }

    /**
     * route
     * manajemen-media-banner
     * get
     * H7
     *
     * @test
     * */
    public function H7_get_edit_access_manajemen_media_banner_as_fahmi()
    {
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.edit', $items[0]->id);
        $this->get($link)
            ->followRedirects()
            ->see('Edit Media & Banner');
    }

    /**
     * route
     * manajemen-media-banner
     * PUT
     * H7
     *
     * @test
     * */
    public function H7_put_edit_access_manajemen_media_banner_as_fahmi_positive_case()
    {
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.update', $items[0]->id);
        $this->callCustom('PUT', $link, [
            'judul' => str_random(20),
            'jenis' => str_random(20),
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->followRedirects()
        ->see('Media berhasil diperbarui');
    }

    /**
     * route
     * manajemen-media-banner
     * PUT
     * H7
     *
     * @test
     * */
    public function H7_put_edit_access_manajemen_media_banner_as_fahmi_null_case()
    {
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.update', $items[0]->id);
        $this->callCustom('PUT', $link, [
            'judul' => '',
            'jenis' => '',
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->assertSessionHasErrors();
    }

    /**
     * route
     * manajemen-media-banner
     * PUT
     * H7
     *
     * @test
     * */
    public function H7_put_edit_access_manajemen_media_banner_as_fahmi_266_case()
    {
        $this->markTestSkipped(
            "di db max 255, belom ada validasi max 255"
        );
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.update', $items[0]->id);
        $this->callCustom('PUT', $link, [
            'judul' => str_random(266),
            'jenis' => str_random(266),
            'status' => 'ACTIVE',
        ], ['media' => $file])
        ->assertSessionHasErrors();
    }

    /**
     * route
     * manajemen-media-banner
     * get
     * H8
     *
     * @test
     * */
    public function H8_delete_access_manajemen_media_banner_as_fahmi()
    {
        $this->H4_post_create_access_manajemen_media_banner_as_fahmi_with_positive_input();
        $items = MediaKit::query()->orderBy('jenis')->orderBy('status')->get();
        $link = route('manajemen-media-banner.destroy', $items[0]->id);
        $this->callCustom('DELETE', $link, ['_token' => csrf_token()])
            ->followRedirects()
            ->see('Media berhasil dihapus');
    }
}
