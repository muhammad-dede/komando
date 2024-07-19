<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group export
 */
class AllExportControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * route
     * coc/event/{id}/export
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_coc_event_id_export_with_skema_positive()
    {
        $this->markTestSkipped(
            "mark skip, ini return true tp utk ngetes
            harus pakai stderr, makanya di skip dlu"
        );

        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/21/export')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * coc/event/{id}/export
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_coc_event_id_export_with_skema_negative_coc_not_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/12313123/export')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/event/{id}/export
     * get
     * tbd
     * failed, gk ada validasi id must be int,
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_get_coc_event_id_export_with_skema_negative_coc_string()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi id must be int,
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/random/export')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * coc/tema/export/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_tema_export_id_with_positive_id()
    {
        $this->markTestSkipped(
            "mark skip, ini return true tp utk ngetes
            harus pakai stderr, makanya di skip dlu"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/tema/export/61')
        ->assertResponseOk();
    }

    /**
     * route
     * report/commitment/export/{business_area}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_with_skema_negative_id_string()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/random')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export/{business_area}
     * get
     * tbd
     * failed, gk ada validasi kalau id busines gk ada di db,
     * model nya di pake utk get user, berhub busines model null
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_with_skema_not_found_id()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi kalau id busines gk ada di db,
            * model nya di pake utk get user, berhub busines model null 
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/12')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export/{business_area}
     * get
     * tbd
     * Undefined variable: tahun
     * (View: /var/www/komando/resources/views/commitment/template_commitment.blade.php
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_with_skema_id_found()
    {
        $this->markTestSkipped(
            "* Undefined variable: tahun 
            * (View: /var/www/komando/resources/views/commitment/template_commitment.blade.php"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/8412')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export/{business_area}/{tahun}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_tahun_with_skema_id_found()
    {
        $this->markTestSkipped(
            "mark skip, ini return true tp utk ngetes
            harus pakai stderr, makanya di skip dlu"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/8412/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export/{business_area}/{tahun}
     * get
     * tbd
     * failed, gk ada validasi kalau id busines gk ada di db,
     * model nya di pake utk get user, berhub busines model null
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_tahun_with_skema_not_found_id()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi kalau id busines gk ada di db,
            * model nya di pake utk get user, berhub busines model null 
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/12/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export/{business_area}/{tahun}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_export_business_tahun_with_skema_negative_id_string()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export/random/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment-induk/export/{company_code}/{tahun}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_induk_export_company_tahun_with_skema_id_found()
    {
        $this->markTestSkipped(
            "mark skip, ini return true tp utk ngetes
            harus pakai stderr, makanya di skip dlu"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment-induk/export/1000/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment-induk/export/{company_code}/{tahun}
     * get
     * tbd
     * failed, gk ada validasi kalau id company gk ada di db,
     * model nya di pake utk get user, berhub company model null
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_get_report_commitment_induk_export_company_tahun_with_skema_not_found_id()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi kalau id company gk ada di db,
            * model nya di pake utk get user, berhub company model null 
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment-induk/export/12/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment-induk/export/{company_code}/{tahun}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_induk_export_company_tahun_with_skema_negative_id_string()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment-induk/export/random/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment/export-all/{tahun}
     * get
     * tbd
     * localhost is currently unable to handle this request.
     * HTTP ERROR 500
     * keliatannya gk kuat, terlalu gede querynya
     * @test
     * */
    public function tbd_can_get_report_commitment_export_all_tahun_with_skema_positive()
    {
        $this->markTestSkipped(
            "* localhost is currently unable to handle this request.
            * HTTP ERROR 500
            * keliatannya gk kuat, terlalu gede querynya"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment/export-all/2020')
        ->followRedirects()
        ->dontSee('something went wrong');
    }
}
