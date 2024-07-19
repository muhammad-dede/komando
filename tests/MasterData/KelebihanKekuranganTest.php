<?php

use App\Enum\KelebihanKekuranganStatus;
use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\KelebihanKekuranganDetail;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group master-data
 */
class KelebihanKekuranganTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    protected function setUp()
    {
        parent::setUp();

        // $this->skipAuthorization();
    }

    /**
     * route
     * master-data/kelebihan-kekuranga
     * get
     * A1
     *
     * @test
     * */
    public function A1_IndexOk_login_mfahmi()
    {
        $this->emptyLiquidTables();
        $this->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();

        $this->visit(route('master-data.kelebihan-kekurangan.index'))
            ->see(
                $data->status === KelebihanKekuranganStatus::AKTIF
                    ? 'AKTIF' : 'Tidak Aktif'
            )
            ->see($data->details()->count())
            ->see($data->title)
            ->press('Tambah Master Data')
            ->seePageIs(route('master-data.kelebihan-kekurangan.create'));
    }

    /**
     * route
     * master-data/kelebihan-kekuranga
     * get
     * A1
     *
     * @test
     * */
    public function A1_IndexOk_login_syahrul()
    {
        $this->emptyLiquidTables();
        $userSyahrul = \App\User::where('username', 'syahrul82')->first();
        $this->login($userSyahrul);
        $this->visit(route('master-data.kelebihan-kekurangan.index'))
            ->see('You are not authorized.');
    }

    /**
     * route
     * master-data/kelebihan-kekurangan/create
     * get
     * A4-A10
     *
     * @test
     * */
    public function A4_A10_testCreateOk()
    {
        $this->emptyLiquidTables()
            ->login()
            ->visit(route('master-data.kelebihan-kekurangan.create'))
            ->see('Judul Kelebihan dan Kekurangan')
            ->see('Deskripsi')
            ->see('Status')
            ->see('Kelebihan')
            ->see('kekurangan')
            ->select(KelebihanKekuranganStatus::AKTIF, 'status')
            ->type('Test judul kelebihan dan kekurangan', 'judul_kk')
            ->type('Test deskripsi kelebihan dan kekurangan', 'deskripsi_kk')
            ->click('Tambah Kelebihan dan Kekurangan')
            ->click('Cancel')
            ->seePageIs(route('master-data.kelebihan-kekurangan.index'));
    }

    /**
     * route
     * master-data/kelebihan-kekurangan/create
     * get
     * A4-A10
     *
     * @test
     * */
    public function A4_A10_testStoreOk()
    {
        $rand = rand(1, 100);
        $this->emptyLiquidTables()
            ->login()
            ->visit(route('master-data.kelebihan-kekurangan.index'))
            ->press('Tambah Master Data')
            ->submitForm(
                'Save',
                [
                    'judul_kk' => 'TESTING Judul Master Data'
                        . " Kelebihan dan Kekurangan $rand",
                    'deskripsi_kk' => 'TESTING Deskripsi Master Data'
                        . " Kelebihan dan Kekurangan $rand",
                    'status' => KelebihanKekuranganStatus::AKTIF,
                    'deskripsi_kelebihan_1' => 'TESTING Deskripsi Master Data Child'
                        . " Kelebihan $rand",
                    'deskripsi_kekurangan_1' => 'TESTING Deskripsi Master Data Child'
                        . " Kekurangan $rand",
                    'index_kelebihan' => 1,
                    'index_kekurangan' => 1,
                ]
            )
            ->seePageIs(route('master-data.kelebihan-kekurangan.index'));

        $this->seeInDatabase(
            'kelebihan_kekurangan',
            [
                    'title' => 'TESTING Judul Master Data'
                        . " Kelebihan dan Kekurangan $rand",
                ]
        );
    }

    /**
     * route
     * master-data/kelebihan-kekurangan/create
     * get
     * A4-A10
     *
     * @test
     * */
    public function A4_A10_testStore_title_with_much_char()
    {
        $this->markTestSkipped(
            "* ORA-12899: value too large for column title actual: 300, maximum: 255
            harus ada validasi max nya berapa"
        );
        $rand = rand(1, 100);
        $this->emptyLiquidTables()
            ->login()
            ->visit(route('master-data.kelebihan-kekurangan.index'))
            ->press('Tambah Master Data')
            ->submitForm(
                'Save',
                [
                    'judul_kk' => str_random(300),
                    'deskripsi_kk' => 'TESTING Deskripsi Master Data'
                        . " Kelebihan dan Kekurangan $rand",
                    'status' => KelebihanKekuranganStatus::AKTIF,
                    'deskripsi_kelebihan_1' => 'TESTING Deskripsi Master Data Child'
                        . " Kelebihan $rand",
                    'deskripsi_kekurangan_1' => 'TESTING Deskripsi Master Data Child'
                        . " Kekurangan $rand",
                    'index_kelebihan' => 1,
                    'index_kekurangan' => 1,
                ]
            )
            ->assertSessionHasErrors();
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.show', $data->id)
     * get
     * A11
     *
     * @test
     * */
    public function A11_testShowOk()
    {
        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $this->visit(route('master-data.kelebihan-kekurangan.show', $data->id))
            ->see($data->title)
            ->see($details->deskripsi_kelebihan)
            ->see($details->deskripsi_kekurangan);
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.show', $data->id)
     * get
     * A11
     *
     * @test
     * */
    public function A11_show_when_id_is_string()
    {
        $this->markTestSkipped(
            "* gk ada validasi harus int, saat masukin string 500 keluar"
        );
        $this->emptyLiquidTables()
            ->login();
        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);
        $link = route('master-data.kelebihan-kekurangan.show', 'random');
        $this->get($link)
            ->assertResponseStatus(404);
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A13-A18
     *
     * @test
     * */
    public function A13_A18_testEditOk()
    {
        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $this->visit(route('master-data.kelebihan-kekurangan.edit', $data->id))
            ->seeInField('judul_kk', $data->title)
            ->seeInField('deskripsi_kk', $data->deskripsi)
            ->seeInField('deskripsi_kelebihan_1', $details->deskripsi_kelebihan)
            ->seeInField('deskripsi_kekurangan_1', $details->deskripsi_kekurangan);
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A13-A18
     *
     * @test
     * */
    public function A13_A18_testEdit_click_cancel()
    {
        $this->markTestSkipped(
            "* button cancel masih typenya submit, 
            jadi dia masuk dlu ke post, mending a href aja"
        );
        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $this->get(route('master-data.kelebihan-kekurangan.edit', $data->id))
            ->followRedirects()
            ->click('cancel');
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A13-A18
     *
     * @test
     * */
    public function A13_A18_testEditOk_id_string()
    {
        $this->markTestSkipped(
            "* gk ada validasi harus int, saat masukin string 500 keluar"
        );

        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $link = route('master-data.kelebihan-kekurangan.edit', 'random');
        $this->get($link)
            ->assertResponseStatus(404);
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A13-A18
     *
     * @test
     * */
    public function A13_A18_testUpdateOk()
    {
        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $this->visit(route('master-data.kelebihan-kekurangan.edit', $data->id))
            ->type('UPDATED ' . $data->title, 'judul_kk')
            ->type('UPDATED ' . $data->deskripsi, 'deskripsi_kk')
            ->press('Update');

        $this->seeInDatabase(
            'kelebihan_kekurangan',
            [
                'title' => 'UPDATED ' . $data->title,
            ]
        );
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A13-A18
     *
     * @test
     * */
    public function A13_A18_testUpdate_title_much_data()
    {
        $this->markTestSkipped(
            "* ORA-12899: value too large for column title actual: 300, maximum: 255
            harus ada validasi max nya berapa"
        );

        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();
        $details = factory(KelebihanKekuranganDetail::class)
            ->create(['parent_id' => $data->id]);

        $this->visit(route('master-data.kelebihan-kekurangan.edit', $data->id))
            ->type(str_random(300), 'judul_kk')
            ->type('UPDATED ' . $data->deskripsi, 'deskripsi_kk')
            ->press('Update')
            ->assertSessionHasErrors();
    }

    /**
     * route
     * route('master-data.kelebihan-kekurangan.edit', $data->id)
     * get
     * A19
     *
     * @test
     * */
    public function A19_testDestroyOk()
    {
        $this->emptyLiquidTables()
            ->login();

        $data = factory(KelebihanKekurangan::class)
            ->create();

        $this->visit(route('master-data.kelebihan-kekurangan.show', $data->id))
            ->press('delete-button');
    }
}
