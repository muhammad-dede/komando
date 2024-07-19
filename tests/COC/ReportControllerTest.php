<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

/**
 * @group commitment
 */
class ReportControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    public function setUp()
    {
        parent::setUp();
        // $this->skipAuthorization();
    }

    /**
     * route
     * report/commitment
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_skema_normal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->visit('report/commitment')
        ->see('Commitment');
    }

    /**
     * route
     * report/commitment
     * get
     *
     * tbd
     *
     * failed karena di dalem controller gk ada validasi busines area
     * kalau user busines area null, maka jadi error
     * line 45
     * so something went wrogn appear
     * @test
     * */
    public function tbd_can_get_report_commitment_skema_no_bussines_area()
    {
        $this->markTestSkipped(
            "* failed karena di dalem controller gk ada validasi busines area
            * kalau user busines area null, maka jadi error
            * line 45
            * so something went wrogn appear"
        );
        $this->skipAuthorization();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $user->business_area = 1234;
        $user->save();
        $this->login($user);
        $this->get('report/commitment')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment
     * get
     *
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_report_commitment_skema_no_company_code()
    {
        $this->skipAuthorization();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $user->company_code = 1234;
        $user->save();
        $this->login($user);
        $this->get('report/commitment')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_report_commitment_skema_normal()
    {
        $this->skipAuthorization();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
        $this->post('report/commitment', [
            "business_area" => "3201"
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment
     * post
     *
     * tbd
     * failed, karena gk ada validasi bussiness area
     * di requestnya, jadi saat hasilnya null, terus modelnya di pakai
     * buat nge get attribute, jadi error
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_report_commitment_skema_bussiness_area_not_found()
    {
        $this->markTestSkipped(
            "* failed, karena gk ada validasi bussiness area
            * di requestnya, jadi saat hasilnya null, terus modelnya di pakai
            * buat nge get attribute, jadi error
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
        $this->post('report/commitment', [
            "business_area" => "1234"
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * commitment/komitmen-pegawai/{tahun}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_komitmen_pegawai_skema_normal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->visit('commitment/komitmen-pegawai/2020')
        ->see('Pernyataan Komitmen');
    }

    /**
     * route
     * commitment/komitmen-pegawai/{tahun}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_komitmen_pegawai_skema_more_than_2020()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/komitmen-pegawai/2022')
        ->followRedirects()
        ->see('Tahun tidak sesuai');
    }

    /**
     * route
     * commitment/komitmen-pegawai/{tahun}
     * get
     *
     * tbd
     * gk ada validasi tahun harus requeired
     * harusnya gk something went wrong, at least 404
     * @test
     * */
    public function tbd_can_get_commitment_komitmen_pegawai_skema_don_have_tahun()
    {
        $this->markTestSkipped(
            "* gk ada validasi tahun harus requeired
            * harusnya gk something went wrong, at least 404"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/komitmen-pegawai/')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * commitment/komitmen-pegawai/{tahun}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_komitmen_pegawai_skema_tahun_string()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/komitmen-pegawai/random')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    public function add_perilaku()
    {
        DB::table('perilaku_pegawai')->delete();
        DB::table('perilaku_pegawai')
        ->insert([
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020],
            ['user_id' => 1, 'pedoman_perilaku_id' => 1, 'do' => 1, 'dont' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020]
        ]);
    }

    /**
     * route
     * commitment/komitmen-pegawai
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_commitment_komitmen_pegawai_skema_normal_14()
    {
        $this->add_perilaku();
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/komitmen-pegawai', [
            "tahun" => "2020",
            "setuju" => "1"
        ])->followRedirects()
        ->see('Komitmen Pegawai 2020 berhasil disimpan. Terimakasih.');
    }

    /**
     * route
     * commitment/komitmen-pegawai
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_commitment_komitmen_pegawai_skema_normal_under_14()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/komitmen-pegawai', [
            "tahun" => "2020",
            "setuju" => "1"
        ])->followRedirects()
        ->see('Anda belum membaca semua Pedoman Perilaku');
    }


    /**
     * route
     * commitment/komitmen-pegawai
     * post
     *
     * tbd
     * failed, gk ada validasi request setuju required and must number
     * @test
     * */
    public function tbd_can_post_commitment_komitmen_pegawai_skema_jml_14_gk_ada_setuju()
    {
        $this->markTestSkipped(
            "failed, gk ada validasi request setuju required and must number"
        );
        $this->add_perilaku();
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/komitmen-pegawai', [
            "tahun" => "2020"
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * commitment
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_skema_cek_sebelum_ttd_true()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment')
        ->assertRedirectedTo('commitment/pedoman-perilaku/tahun/2020');
    }

    /**
     * route
     * commitment
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_skema_cek_sebelum_ttd_false()
    {
        DB::table('komitmen_pegawai')
        ->insert([
            ['user_id' => 1, 'orgeh' => 1234567891, 'plans' => 1, 'setuju' => 1, 'created_at' => '2020-04-28', 'updated_at' => '2020-04-28', 'tahun' => 2020]
        ]);
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment')
        ->see('Komitmen Pegawai');
    }

    /**
     * route
     * commitment/buku
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_buku_skema_normal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/buku')
        ->followRedirects()
        ->see('Buku Pedoman Perilaku dan Etika Bisnis');
    }

    /**
     * route
     * commitment/buku/{id}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_buku_id_skema_normal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/buku/1')
        ->followRedirects()
        ->see('Buku Pedoman Perilaku');
    }

    /**
     * route
     * commitment/buku/{id}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_commitment_buku_id_gak_ditemukan_number()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/buku/1123123')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * commitment/buku/{id}
     * get
     *
     * tbd
     * failed, harusnya id tidak bis menerima string
     * kasih validasi supaya tidak something went wrong
     * @test
     * */
    public function tbd_can_get_commitment_buku_id_string_random()
    {
        $this->markTestSkipped(
            "* failed, harusnya id tidak bis menerima string
            * kasih validasi supaya tidak something went wrong"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/buku/random')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * commitment/pedoman-perilaku/quiz
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_pedoman_perilaku_quiz_have_pedoman_perilaku()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku/quiz', [
            "pedoman_perilaku_id" => "1"
        ])->followRedirects()
        ->see('Komitmen');
    }

    /**
     * route
     * commitment/pedoman-perilaku/quiz
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_pedoman_perilaku_quiz_dont_have_pedoman_perilaku()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku/quiz', [
            "pedoman_perilaku_id" => "1123"
        ])->assertResponseStatus(404);
    }

    /**
     * route
     * commitment/pedoman-perilaku/quiz
     * post
     *
     * tbd
     * failed, harusnya pedoman_perilaku_id di kasih validasi gk boleh string
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_pedoman_perilaku_quiz_string_pedoman_perilaku()
    {
        $this->markTestSkipped(
            "* failed, harusnya pedoman_perilaku_id di kasih validasi gk boleh string
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku/quiz', [
            "pedoman_perilaku_id" => "random"
        ])->assertResponseStatus(404);
    }

    /**
     * route
     * commitment/pedoman-perilaku/answer
     * post
     *
     * tbd
     * failed, harusnya pedoman_perilaku_id di kasih validasi gk boleh string
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_pedoman_perilaku_quiz_answer_no_post()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku/quiz/answer', [
            "jawaban" => null,
            "tahun" => null,
            "pertanyaan_id" => null,
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * reset/commitment
     * get
     *
     * tbd
     * failed, query to longgg
     * @test
     * */
    public function tbd_can_get_reset_commitment_skema_normal()
    {
        $this->markTestSkipped(
            "* failed, query to longgg"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('reset/commitment')
        ->assertResponseOk();
    }

    /**
     * route
     * jumlah-komitmen/{tahun}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_jumlah_komitmen_2020()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('jumlah-komitmen/2020')
        ->assertResponseOk();
    }

    /**
     * route
     * jumlah-komitmen/{tahun}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_jumlah_komitmen_tahun_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('jumlah-komitmen/')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * jumlah-komitmen/{tahun}
     * get
     *
     * tbd
     * failed, harusnya ada validasi int, kalau bukan int then 404
     * krna gk ada maka something went wrong appear
     * @test
     * */
    public function tbd_can_get_jumlah_komitmen_tahun_string()
    {
        $this->markTestSkipped(
            "* failed, harusnya ada validasi int, kalau bukan int then 404
            krna gk ada maka something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('jumlah-komitmen/random')
        ->assertResponseOk();
    }

    /**
     * route
     * resolve/duplicate-commit
     * get
     * failed, ini route tentang delete tapi malah get
     * query nya lama banget
     * tbd
     * @test
     * */
    public function tbd_can_get_resolve_duplicate_commit()
    {
        $this->markTestSkipped(
            "* failed, ini route tentang delete tapi malah get
            * query nya lama banget"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('resolve/duplicate-commit')
        ->assertResponseOk();
    }

    /**
     * route
     * report/rekap-commitment
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_rekap_commitment()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('report/rekap-commitment')
        ->followRedirects()
        ->see('Rekap Komitmen Unit');
    }

    /**
     * route
     * report/commitment-induk
     * get
     * tbd
     * @test
     * */
    public function tbd_can_get_report_commitment_induk()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('report/commitment-induk')
        ->followRedirects()
        ->see('Commitment Company Code');
    }

    /**
     * route
     * report/commitment-induk
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_report_commitment_induk_skema_positif()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('report/commitment-induk', [
            "company_code" => 3200,
        ])->followRedirects()
        ->see('Commitment Company Code');
    }

    /**
     * route
     * report/commitment-induk
     * post
     *
     * tbd
     * failed, gk ada validasi null, biar gk
     * something went wrong
     * @test
     * */
    public function tbd_can_post_report_commitment_induk_skema_null()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi null, biar gk 
            * something went wrong"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('report/commitment-induk', [
            "company_code" => null,
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * report/commitment-induk
     * post
     *
     * tbd
     * failed, gk ada validasi string, biar gk
     * something went wrong
     * @test
     * */
    public function tbd_can_post_report_commitment_induk_skema_string()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi string, biar gk 
            * something went wrong"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('report/commitment-induk', [
            "company_code" => 'random',
        ])->followRedirects()
        ->dontSee('something went wrong');
    }
}
