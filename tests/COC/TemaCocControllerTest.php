<?php

use App\TemaCoc;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @group coc
 */
class TemaCocControllerTest extends TestCase
{
    use DatabaseTransactions;
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->skipAuthorization();
    }

    /**
     * method store
     * ID Testing
     * tbd
     * expected
     * redirect to /coc
     * @test
     * */
    public function tbd_can_store_tema_with_positive_inputed_and_return_success()
    {
        $this->login();
        $this->post('coc/apply-thematic', [
            'start_date' => '20-08-2020',
            'end_date' => '30-08-2020',
            'tema_id' => '1'
        ]);
        $this->assertRedirectedTo('coc');
    }

    /**
     * method store
     * ID Testing
     * tbd
     * expected
     * should return validation error
     * @test
     * */
    public function tbd_can_store_tema_with_all_null_and_return_error()
    {
        $this->login();
        $this->post('coc/apply-thematic', [
            'start_date' => null,
            'end_date' => null,
            'tema_id' => null
        ]);

        $this->assertSessionHasErrors();
    }

    /**
     * method store
     * ID Testing
     * tbd
     * expected
     * should return validation error
     * @test
     * */
    public function tbd_can_validate_date_with_random_string()
    {
        $this->login();
        $this->post('coc/apply-thematic', [
            'start_date' => 'random',
            'end_date' => 'random',
            'tema_id' => '1'
        ]);

        $this->assertSessionHasErrors();
    }

    /**
     * method store
     * ID Testing
     * tbd
     * expected
     * should return validation error
     * ini failed masih bisa insert tanggal lebih besar dari tanggal akhir
     * gk di return validation error
     * @test
     * */
    public function tbd_can_validate_date_with_end_date_more_high_than_start_date()
    {
        $this->markTestSkipped(
            "Should return validation error
            ini failed masih bisa insert tanggal lebih besar dari tanggal akhir
            gk di return validation error"
        );
        $this->login();
        $this->post('coc/apply-thematic', [
            'start_date' => '30-08-2020',
            'end_date' => '10-08-2020',
            'tema_id' => '1'
        ]);
        $this->assertSessionHasErrors();
    }

    /**
     * method store
     * ID Testing
     * tbd
     * expected return findOrFail in some code
     * @test
     * */
    public function tbd_can_return_404_if_tema_not_found()
    {
        $this->login();
        $this->post('coc/apply-thematic', [
            'start_date' => '10-08-2020',
            'end_date' => '30-08-2020',
            'tema_id' => '123123123123'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * method exportTemaCoc
     * ID Testing
     * tbd
     * expected return assertTrue
     *
     * masih belom bisa
     * Laravel Excel method [fake] does not exist
     *
     * */
    public function tbd_can_donwload_if_find_true()
    {
        $this->login();
        Excel::fake();
        $this->actingAs($this->user)
            ->get('coc/tema/export/1');
        $tema_coc = TemaCoc::find('1');
        Excel::assertDownloaded('filename.xlsx', function ($export) use ($tema_coc) {
            return $export->collection($tema_coc)->contains('tema');
        });
    }


    /**
     * method exportTemaCoc
     * ID Testing
     * tbd
     * expected return assertTrue
     * @test
     * */
    public function tbd_cant_donwload_if_find_false()
    {
        $this->login();
        $this->get('coc/tema/export/3')->assertResponseStatus(404);
    }
}
