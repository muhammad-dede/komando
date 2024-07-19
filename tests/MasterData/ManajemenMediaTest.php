<?php

use App\Enum\KelebihanKekuranganStatus;
use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\KelebihanKekuranganDetail;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group master-data
 */
class ManajemenMediaTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithDatabase;
    use TestHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->skipAuthorization();
    }

    public function testIndexOk()
    {
    }

    public function testCreateOk()
    {
        // $this->emptyLiquidTables()
        //     ->login()
        //     ->visit(route('master-data.kelebihan-kekurangan.create'))
        //     ->see('Judul Kelebihan dan Kekurangan')
        //     ->see('Deskripsi')
        //     ->see('Status')
        //     ->see('Kelebihan')
        //     ->see('kekurangan')
        //     ->select(KelebihanKekuranganStatus::AKTIF, 'status')
        //     ->type('Test judul kelebihan dan kekurangan', 'judul_kk')
        //     ->type('Test deskripsi kelebihan dan kekurangan', 'deskripsi_kk')
        //     ->click('Tambah Kelebihan dan Kekurangan')
        //     ->click('Cancel')
        //     ->seePageIs(route('master-data.kelebihan-kekurangan.index'));
    }

    public function testStoreOk()
    {
        // $rand = rand(1, 100);
        // $this->emptyLiquidTables()
        //     ->login()
        //     ->visit(route('master-data.kelebihan-kekurangan.index'))
        //     ->press('Tambah Master Data')
        //     ->submitForm(
        //         'Save',
        //         [
        //             'judul_kk' => 'TESTING Judul Master Data'
        //                 . " Kelebihan dan Kekurangan $rand",
        //             'deskripsi_kk' => 'TESTING Deskripsi Master Data'
        //                 . " Kelebihan dan Kekurangan $rand",
        //             'status' => KelebihanKekuranganStatus::AKTIF,
        //             'deskripsi_kelebihan_1' => 'TESTING Deskripsi Master Data Child'
        //                 . " Kelebihan $rand",
        //             'deskripsi_kekurangan_1' => 'TESTING Deskripsi Master Data Child'
        //                 . " Kekurangan $rand",
        //             'index_kelebihan' => 1,
        //             'index_kekurangan' => 1,
        //         ]
        //     )
        //     ->seePageIs(route('master-data.kelebihan-kekurangan.index'));
        //
        // $this->seeInDatabase(
        //     'kelebihan_kekurangan',
        //     [
        //             'title' => 'TESTING Judul Master Data'
        //                 . " Kelebihan dan Kekurangan $rand",
        //         ]
        // );
    }

    public function testShowOk()
    {
        // $this->emptyLiquidTables()
        //     ->login();
        //
        // $data = factory(KelebihanKekurangan::class)
        //     ->create();
        // $details = factory(KelebihanKekuranganDetail::class)
        //     ->create(['parent_id' => $data->id]);
        //
        // $this->visit(route('master-data.kelebihan-kekurangan.show', $data->id))
        //     ->see($data->title)
        //     ->see($details->deskripsi_kelebihan)
        //     ->see($details->deskripsi_kekurangan);
    }

    public function testEditOk()
    {
        // $this->emptyLiquidTables()
        //     ->login();
        //
        // $data = factory(KelebihanKekurangan::class)
        //     ->create();
        // $details = factory(KelebihanKekuranganDetail::class)
        //     ->create(['parent_id' => $data->id]);
        //
        // $this->visit(route('master-data.kelebihan-kekurangan.edit', $data->id))
        //     ->seeInField('judul_kk', $data->title)
        //     ->seeInField('deskripsi_kk', $data->deskripsi)
        //     ->seeInField('deskripsi_kelebihan_1', $details->deskripsi_kelebihan)
        //     ->seeInField('deskripsi_kekurangan_1', $details->deskripsi_kekurangan);
    }

    public function testUpdateOk()
    {
        // $this->emptyLiquidTables()
        //     ->login();
        //
        // $data = factory(KelebihanKekurangan::class)
        //     ->create();
        // $details = factory(KelebihanKekuranganDetail::class)
        //     ->create(['parent_id' => $data->id]);
        //
        // $this->visit(route('master-data.kelebihan-kekurangan.edit', $data->id))
        //     ->type('UPDATED ' . $data->title, 'judul_kk')
        //     ->type('UPDATED ' . $data->deskripsi, 'deskripsi_kk')
        //     ->press('Update');
        //
        // $this->seeInDatabase(
        //     'kelebihan_kekurangan',
        //     [
        //         'title' => 'UPDATED ' . $data->title,
        //     ]
        // );
    }

    public function testDestroyOk()
    {
    }
}
