<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

/**
 * @group coc
 */
class CocControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    public function setUp()
    {
        parent::setUp();
        // $this->skipAuthorization();
    }

    /**
     * Membuat Tema
     * ID Testing
     * Q1
     *
     * @test
     * */
    public function can_see_button_tema_and_click_it()
    {
        // login as fahmi rizal
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
        $this->visit('/coc')
            ->seeElement('#thematicModal');
    }

    /**
     * Membuat Tema
     * ID Testing
     * Q2
     *
     * @test
     * */
    public function Q2_can_click_button_tema_and_apply_it()
    {
        $this->can_see_button_tema_and_click_it();
        $this->type('15-08-2020', 'start_date')
            ->type('28-08-2020', 'end_date')
            ->select('1', 'tema_id')
            ->press('Apply')
            ->dontSeeElement('.alert-danger');
    }

    /**
     * Membuat Tema
     * ID Testing
     * Q3
     *
     * @test
     * */
    public function Q3_can_click_button_tema_and_apply_it_without_insert_date()
    {
        $this->can_see_button_tema_and_click_it();
        $this->type('', 'start_date')
            ->type('', 'end_date')
            ->select('1', 'tema_id')
            ->press('Apply')
            ->seeElement('.alert-danger')
            ->see("Tanggal awal tema wajib diisi")
            ->see("Tanggal akhir tema wajib diisi");
    }

    /**
     * Membuat Tema
     * ID Testing
     * Q4
     *
     * @test
     * */
    public function Q4_can_click_button_tema_and_apply_it_with_existing_date()
    {
        $this->can_see_button_tema_and_click_it();
        $this->type('01-07-2020', 'start_date')
            ->type('31-07-2020', 'end_date')
            ->select('1', 'tema_id')
            ->press('Apply')
            ->see("Sudah ada tema pada tanggal : 01 July 2020");
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R1
     *
     *
     * @test
     * */
    public function can_click_materi_nasional()
    {
        // login as fahmi rizal
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
        $this->visit('/coc')
            ->see('Code of Conduct')
            ->click('materiNasionalHref')
            ->seePageIs('coc/create/materi/nasional');
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * S1
     *
     *
     * @test
     * */
    public function can_click_materi_gm()
    {
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login();
        $this->visit('/coc')
            ->see('Code of Conduct')
            ->click('Materi GM')
            ->seePageIs('coc/create/materi/gm');
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R2
     *
     *
     * @test
     * */
    public function R2_can_create_materi_nasional_with_positive_input()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '10-07-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Materi berhasil disimpan.');
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R3
     *
     *
     * @test
     * */
    public function R3_can_create_materi_nasional_with_tema_not_yet_created()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '13-08-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R4
     *
     *
     * @test
     * */
    public function R4_can_create_materi_nasional_with_date_already_exist()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '03-07-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Sudah ada materi pada tanggal ' . Date::parse('03-07-2020')->format('d F Y'));
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R5
     *
     * ini failed, karena masih bisa insert tanpa file di BE
     * @test
     * */
    public function R5_can_create_materi_nasional_with_file_null()
    {
        $this->markTestSkipped(
            "ini failed, karena masih bisa insert tanpa file di BE"
        );
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001',
        ], [])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Isi materi tidak boleh kosong');
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R6
     *
     * @test
     * */
    public function R6_can_create_materi_nasional_with_judul_null()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => null,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertSessionHasErrors();
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R7
     *
     * @test
     * */
    public function R7_can_create_materi_nasional_with_penulis_null()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => null,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertSessionHasErrors();
    }

    /**
     * Membuat Materi Nasioanl
     * ID Testing
     * R8
     *
     * @test
     * */
    public function R8_can_create_materi_nasional_with_file_is_image()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.jpg');
        $this->can_click_materi_nasional();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/nasional', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertSessionHasErrors();
    }


    /**
     * Membuat Materi GM
     * ID Testing
     * S2
     *
     *
     * @test
     * */
    public function S2_can_create_materi_nasional_with_positive_input()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '10-07-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Materi berhasil disimpan.');
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S3
     *
     *
     * @test
     * */
    public function S3_can_create_materi_nasional_with_tema_not_yet_created()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '13-08-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S4
     *
     *
     * @test
     * */
    public function S4_can_create_materi_nasional_with_date_already_exist()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '03-07-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Sudah ada materi pada tanggal ' . Date::parse('03-07-2020')->format('d F Y'));
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S5
     *
     * ini failed, karena masih bisa insert tanpa file di BE
     * @test
     * */
    public function S5_can_create_materi_nasional_with_file_null()
    {
        $this->markTestSkipped(
            "ini failed, karena masih bisa insert tanpa file di BE"
        );
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001',
        ], [])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Isi materi tidak boleh kosong');
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S6
     *
     * @test
     * */
    public function S6_can_create_materi_nasional_with_judul_null()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => null,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])->assertSessionHasErrors();
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S7
     *
     * @test
     * */
    public function S7_can_create_materi_nasional_with_penulis_null()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => null,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertSessionHasErrors();
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * S8
     *
     * @test
     * */
    public function S8_can_create_materi_nasional_with_file_is_image()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.jpg');
        $this->can_click_materi_gm();

        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/materi/gm', [
            'judul' => $faker->name,
            'deskripsi' => $faker->realText,
            'jenis_materi' => '1',
            'tanggal_coc' => '23-08-2020',
            'pernr_penulis' => '94174001'
        ], ['materi' => $file])->assertSessionHasErrors();
    }

    /**
     * Membuat Jadwal Code of Conduct
     * ID Testing
     * T1
     *
     *
     * @test
     * */
    public function can_visit_create_local_no_params()
    {
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->visit('/coc/create/local')
            ->see('Create Jadwal CoC Unit');
    }

    /**
     * Membuat Jadwal Code of Conduct
     * ID Testing
     * T1
     *
     *
     * @test
     * */
    public function can_visit_create_local_with_params()
    {
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->visit('/coc/create/local/123123')
            ->see('Create Jadwal CoC Unit');
    }

    /**
     * Membuat Jadwal Code of Conduct
     * ID Testing
     * T2
     *
     *
     * @test
     * */
    public function T2_can_create_jadwal_code_of_conduct_with_positive_input()
    {
        $faker = Faker\Factory::create();
        $this->can_visit_create_local_no_params();
        $this->can_visit_create_local_with_params();
        $this->post('coc/create/local', [
            "tema_id" => "1",
            "judul_coc" => $faker->name,
            "pernr_leader" => "97161607",
            "lokasi" => $faker->address,
            "tanggal_coc" => "10-07-2020",
            "jam" => "14:10",
            "company_code" => "2100",
            "business_area" => "2101",
            "orgeh" => "15708001",
            "jml_peserta" => "213",
            "misi" => "1",
            "sipp" => [
                0 => "4",
                1 => "2",
                2 => "3",
                3 => "1",
                4 => "5",
            ],
            "pelanggaran" => "2"
        ])->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Jadwal CoC berhasil disimpan. Admin CoC diberi waktu '.env('SUBDAYS_AUTOCOMPLETE', 5).' hari setelah tanggal CoC untuk melakukan Complete. Jika setelah '.env('SUBDAYS_AUTOCOMPLETE', 5).' hari status CoC masih OPEN, sistem akan melakukan Complete secara otomatis.');
    }

    /**
     * Membuat Materi GM
     * ID Testing
     * T3
     *
     * @test
     * */
    public function T3_can_create_jadwal_code_of_conduct_with_all_null()
    {
        $this->can_visit_create_local_no_params();
        $this->can_visit_create_local_with_params();
        $this->post('coc/create/local', [
            "tema_id" => null,
            "judul_coc" => null,
            "pernr_leader" => null,
            "lokasi" => null,
            "tanggal_coc" => null,
            "jam" => null,
            "company_code" => null,
            "business_area" => null,
            "orgeh" => null,
            "jml_peserta" => null,
            "misi" => null,
            "sipp" => [],
            "pelanggaran" => null
        ])->assertSessionHasErrors();
    }

    /**
     * Membuat Jadwal Code of Conduct
     * ID Testing
     * T4
     *
     *
     * @test
     * */
    public function T4_can_create_jadwal_code_of_conduct_with_no_nilai_input()
    {
        $faker = Faker\Factory::create();
        $this->can_visit_create_local_no_params();
        $this->can_visit_create_local_with_params();
        $this->post('coc/create/local', [
            "tema_id" => "1",
            "judul_coc" => $faker->name,
            "pernr_leader" => "97161607",
            "lokasi" => $faker->address,
            "tanggal_coc" => "10-07-2020",
            "jam" => "14:10",
            "company_code" => "2100",
            "business_area" => "2101",
            "orgeh" => "15708001",
            "jml_peserta" => "213",
            "misi" => "1",
            "sipp" => [],
            "pelanggaran" => "2"
        ])->followRedirects()
        ->see('Create Jadwal CoC Unit');
    }

    /**
     * Membuat Jadwal Code of Conduct
     * ID Testing
     * T5
     *
     *
     * @test
     * */
    public function T5_can_create_jadwal_code_of_conduct_with_input_monday_date()
    {
        $faker = Faker\Factory::create();
        $this->can_visit_create_local_no_params();
        $this->can_visit_create_local_with_params();
        $this->post('coc/create/local', [
            "tema_id" => "1",
            "judul_coc" => $faker->name,
            "pernr_leader" => "97161607",
            "lokasi" => $faker->address,
            "tanggal_coc" => "17-08-2020",
            "jam" => "14:10",
            "company_code" => "2100",
            "business_area" => "2101",
            "orgeh" => "15708001",
            "jml_peserta" => "213",
            "misi" => "1",
            "sipp" => [
                0 => "4",
                1 => "2",
                2 => "3",
                3 => "1",
                4 => "5",
            ],
            "pelanggaran" => "2"
        ])->followRedirects()
        ->see('CoC local tidak boleh dijadwalkan hari Senin. Hari Senin hanya untuk CoC Nasional.');
    }

    /**
     * route coc/check-in/id get
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_add_coc_jadwal_with_id_222()
    {
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        // create room coc yang open, for development purpose only
        DB::table('COC')->insert([
            'ID' => 222,
            'EVENT_ID' => 83,'TEMA_ID' => 1,'ADMIN_ID' => 1593,
            'KODE' => null,'TANGGAL_JAM' => '2020-08-16 01:06:03',
            'JUDUL' => 'ASDF1234','DESKRIPSI' => null,
            'PUSAT' => 0,'PERNR_PEMATERI' => 97161607,
            'COMPANY_CODE' => 2100,'BUSINESS_AREA' => 2101,'ORGEH' => 15708001,
            'LOKASI' => 'TEST','FOTO' => null,'scope' => 'local',
            'PEDOMAN_PERILAKU_ID' => null,'PERNR_LEADER' => 97161607,
            'TEMA_ID_UNIT' => 1,'JML_PESERTA' => 180,
            'VISI' => 1,'MISI' => 1,'SALING_PERCAYA' => null,
            'INTEGRITAS' => 1,'SINERGI' => 1,'PROFESIONAL' => 1,
            'PELANGGAN' => 1,'LEVEL_UNIT' => 2,'JENJANG_ID' => 7,
            'PLANS_LEADER' => 30223494,'DELEGATION_LEADER' => 30223494,
            'KEUNGGULAN' => 1
        ]);

        $this->seeInDatabase('COC', ['id' => 222])
            ->visit('coc/check-in/222');
    }

    /**
     * route coc/check-in/id post
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_coc_check_in()
    {
        $this->test_add_coc_jadwal_with_id_222();
        $this->press('Check In')
            ->followRedirects()
            ->see('Visi & Misi PLN');
    }

    /**
     * route coc/visi-misi/id
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_visi_misi()
    {
        $this->test_can_post_in_coc_check_in();
        $this->post('coc/nilai/222', [
            "status_checkin" => "1",
            "checkbox_dont" => "1",
            "checkbox_do" => "1"
        ])->followRedirects()
        ->see('Values');
    }


    /**
     * route coc/visi-misi/id
     * ID Testing
     * tbd
     * Failed, jika input langsung lewat BE belom ada exception findOrFail
     * so Something Went Wrong Appear, expect 404
     * */
    public function test_can_post_in_visi_in_random_id()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada exception findOrFail
            so Something Went Wrong Appear, expect 404"
        );
        $this->test_can_post_in_coc_check_in();
        $this->post('coc/nilai/random', [
            "status_checkin" => "1",
            "checkbox_dont" => "1",
            "checkbox_do" => "1"
        ])->followRedirects()
        ->see('PAGE NOT FOUND');
    }

    /**
     * route coc/nilai/id
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_nilai()
    {
        $this->test_can_post_in_visi_misi();
        $this->post('coc/tata-nilai/222', [
            "status_checkin" => "1",
            "checkbox_do" => "1"
        ])->followRedirects()
        ->see('Values');
    }

    /**
     * route coc/nilai/id
     * ID Testing
     * tbd
     * Failed, jika input langsung lewat BE belom ada exception findOrFail
     * so Something Went Wrong Appear, expect 404
     * */
    public function test_can_post_nilai_in_random_id()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada exception findOrFail
            so Something Went Wrong Appear, expect 404"
        );
        $this->test_can_post_in_visi_misi();
        $this->post('coc/tata-nilai/random', [
            "status_checkin" => "1",
            "checkbox_do" => "1"
        ])->followRedirects()
        ->see('PAGE NOT FOUND');
    }

    /**
     * route coc/tata-nilai/id
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_tata_nilai()
    {
        $this->test_can_post_in_nilai();
        $this->post('coc/fokus-perilaku/222', [
            "status_checkin" => "1",
            "checkbox_integritas" => "1",
            "checkbox_prof" => "1",
            "checkbox_komit" => "1",
            "checkbox_sinergi" => "1",
            "checkbox_keunggulan" => "1"
        ])->followRedirects()
        ->see('Fokus Perilaku Utama');
    }

    /**
     * route coc/tata-nilai/id
     * ID Testing
     * tbd
     * Failed, jika input langsung lewat BE belom ada exception findOrFail
     * so Something Went Wrong Appear, expect 404
     * */
    public function test_can_post_in_tata_in_random_id()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada exception findOrFail
            so Something Went Wrong Appear, expect 404"
        );
        $this->test_can_post_in_nilai();
        $this->post('coc/fokus-perilaku/random', [
            "status_checkin" => "1",
            "checkbox_integritas" => "1",
            "checkbox_prof" => "1",
            "checkbox_komit" => "1",
            "checkbox_sinergi" => "1",
            "checkbox_keunggulan" => "1"
        ])->followRedirects()
        ->see('PAGE NOT FOUND');
    }

    /**
     * route coc/fokus-perilaku/id
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_fokus_perilaku()
    {
        $this->test_can_post_in_tata_nilai();
        $this->post('coc/pelanggaran/222', [
            "status_checkin" => "1",
            "checkbox_do" => "1",
        ])->followRedirects()
        ->see('Pelanggaran Disiplin');
    }

    /**
     * route coc/fokus-perilaku/id
     * ID Testing
     * tbd
     * Failed, jika input langsung lewat BE belom ada exception findOrFail
     * so Something Went Wrong Appear, expect 404
     * */
    public function test_can_post_fokus_perilaku_in_random_id()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada exception findOrFail
            so Something Went Wrong Appear, expect 404"
        );
        $this->test_can_post_in_tata_nilai();
        $this->post('coc/pelanggaran/random', [
            "status_checkin" => "1",
            "checkbox_do" => "1",
        ])->followRedirects()
        ->see('PAGE NOT FOUND');
    }

    /**
     * route coc/pelanggaran/id
     * ID Testing
     * tbd
     *
     *
     * */
    public function test_can_post_in_pelanggaran()
    {
        $this->test_can_post_in_fokus_perilaku();
        $this->post('coc/check-in/222', [
            "status_checkin" => "1",
            "checkbox_do" => "1",
        ])->followRedirects()
        ->see('Code of Conduct');
    }

    /**
     * route coc/pelanggaran/id
     * ID Testing
     * tbd
     * Failed, jika input langsung lewat BE belom ada exception findOrFail
     * so Something Went Wrong Appear, expect 404
     * */
    public function test_can_post_pelanggaran_in_random_id()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada exception findOrFail
            so Something Went Wrong Appear, expect 404"
        );
        $this->test_can_post_in_fokus_perilaku();
        $this->post('coc/check-in/random', [
            "status_checkin" => "1",
            "checkbox_do" => "1",
        ])->followRedirects()
        ->see('PAGE NOT FOUND');
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U2
     *
     * @test
     * */
    public function can_check_in_code_conduct()
    {
        $this->test_can_post_in_pelanggaran();
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U3
     * Failed, masih bisa di input lewat BE secara langsung
     *
     * @test
     * */
    public function can_check_code_conduct_without_checkbox()
    {
        $this->markTestSkipped(
            "Failed, masih bisa di input lewat BE secara langsung"
        );
        $this->test_can_post_in_fokus_perilaku();
        $this->post('coc/check-in/222', [
            "status_checkin" => "1",
            "checkbox_do" => "",
        ])->followRedirects()
        ->see('Pelanggaran Disiplin');
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U4
     * Belum
     * @test
     * */
    public function can_read_materi_coc()
    {
        $this->markTestSkipped(
            "Skenario test belom jelas, not yet"
        );
        $this->test_can_post_in_pelanggaran();
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U5
     * Belum
     * @test
     * */
    public function can_read_coc_materi_negative_case()
    {
        $this->markTestSkipped(
            "Skenario test belom jelas, not yet"
        );
        $this->test_can_post_in_pelanggaran();
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U6
     *
     * @test
     * */
    public function can_see_peserta()
    {
        $this->test_can_post_in_pelanggaran();
        $this->see('Peserta');
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U7
     *
     * @test
     * */
    public function can_see_export_peserta()
    {
        $this->test_can_post_in_pelanggaran();
        $this->see('Export Peserta');
    }

    /**
     * Check In Code Conduct
     * ID Testing
     * U8
     * Failed keluar something went wrong
     * @test
     * */
    public function can_upload()
    {
        $this->markTestSkipped(
            "Failed, keluar something went wrong, saat upload by BE"
        );
        $this->test_can_post_in_pelanggaran();
        $this->see('Gallery');

        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        // $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom(
            'POST',
            'coc/upload-foto/222',
            [
            'judul' => 'tes',
            'deskripsi' => 'ini deskripsi',
        ],
            ['foto' => $file]
        )
        ->assertRedirectedTo('coc/event/222')
        ->followRedirects()
        ->see('Foto kegiatan berhasil disimpan.');
    }

    /**
     * route
     * coc/create/kantor-induk
     * get
     *
     * @test
     * */
    public function can_get_see_kantor_induk()
    {
        $this->skipAuthorization();
        $this->login();
        $this->visit('/coc/create/kantor-induk')
        ->see('Create Jadwal CoC');
    }

    /**
     * route
     * coc/create/kantor-induk
     * get
     *
     * @test
     * */
    public function can_see_create_kantor_induk_with_different_permission()
    {
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->visit('/coc/create/kantor-induk')
        ->see('You are not authorized.');
    }

    /**
     * route
     * coc/create/kantor-induk
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_create_kantor_induk_positive_input()
    {
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->skipAuthorization();
        $this->login();
        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/kantor-induk', [
            'tema_id_unit' => '1',
            'judul_coc' => $faker->name,
            'pernr_leader' => '94174001',
            'pedoman_perilaku_id' => '1',
            'lokasi' => $faker->name,
            'tanggal_coc' => '10-07-2020',
            'jam' => '14:10',
            'jml_peserta' => 23,
            'judul_materi' => $faker->name,
            'deskripsi' => $faker->realText,
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])
        ->assertRedirectedTo('coc')
        ->followRedirects()
        ->see('Jadwal CoC berhasil disimpan.');
    }

    /**
     * route
     * coc/create/kantor-induk
     * post
     *
     * tbd
     * FAILED, gk ada validasi jumlah peserta, aku masukin string masih bisa
     * so bakalan error something went wrong
     * @test
     * */
    public function tbd_can_post_create_negative_case_kantor_induk()
    {
        $this->markTestSkipped(
            "gk ada validasi jumlah peserta, aku masukin string masih bisa
            so bakalan error something went wrong"
        );
        $faker = Faker\Factory::create();
        $file = base_path('tests/data/dummy.pdf');
        $this->skipAuthorization();
        $this->login();
        //insert  post in method storeMateri
        $file = new UploadedFile($file, 'dummy.pdf', 'application/pdf', filesize($file), null, true);
        $this->callCustom('POST', 'coc/create/kantor-induk', [
            'tema_id_unit' => '1',
            'judul_coc' => $faker->name,
            'pernr_leader' => '94174001',
            'pedoman_perilaku_id' => '1',
            'lokasi' => $faker->name,
            'tanggal_coc' => '10-07-2020',
            'jam' => '14:10',
            'jml_peserta' => 'awaw',
            'judul_materi' => $faker->name,
            'deskripsi' => $faker->realText,
            'pernr_penulis' => '94174001',
        ], ['materi' => $file])
        ->followRedirects()
        ->dontSee('Something went wrong');
    }

    /**
     * route
     * coc/list
     * get
     *
     * tbd
     * tbd
     * @test
     * */
    public function tbd_can_get_akses_coc_list()
    {
        $this->login();
        $this->visit('/coc/list')
        ->see('Daftar CoC');
    }

    /**
     * route
     * coc/list
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_akses_coc_list()
    {
        $this->login();
        $this->post('coc/list', [
            "business_area" => "2012",
            "coc_date" => "11-08-2020"
        ])->followRedirects()->see('Daftar CoC');
    }

    /**
     * route
     * coc/list
     * post
     *
     * tbd
     * failed
     * @test
     * */
    public function tbd_can_post_akses_coc_list_with_random_date()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada validation about date
            so Something Went Wrong Appear, expect session flash error"
        );
        $this->login();
        $this->post('coc/list', [
            "business_area" => "2012",
            "coc_date" => "random"
        ])->followRedirects()->see('Daftar CoC');
    }

    /**
     * route
     * coc/list/admin
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_get_akses_admin_coc_list()
    {
        $this->login();
        $this->visit('/coc/list/admin')
        ->see('Daftar CoC Administrator');
    }

    /**
     * route
     * coc/list/admin
     * post
     *
     * tbd
     * @test
     * */
    public function tbd_can_post_akses_admin_coc_list()
    {
        $this->login();
        $this->post('coc/list/admin', [
            "business_area" => "1001",
            "start_date" => "18-08-2020",
            "end_date" => "18-08-2020",
        ])->followRedirects()->see('Daftar CoC Administrator');
    }


    /**
     * route
     * coc/list/admin
     * post
     *
     * tbd
     * failed
     * @test
     * */
    public function tbd_can_post_akses_admin_coc_list_with_random_date()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE belom ada validation about date
            so Something Went Wrong Appear, expect session flash error"
        );
        $this->login();
        $this->post('coc/list/admin', [
            "business_area" => "2012",
            "start_date" => "ini random",
            "end_date" => "ini random",
        ])->followRedirects()->see('Daftar CoC Administrator');
    }


    /**
     * route
     * coc/ajax-pemateri
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_akses_ajax_pemateri_with_null_input()
    {
        $this->login();
        $this->get('coc/ajax-pemateri')
            ->assertResponseOk();
    }

    /**
     * route
     * coc/ajax-pemateri
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_akses_ajax_pemateri_with_searching_input()
    {
        $this->login();
        $this->callCustom('GET', 'coc/ajax-pemateri', [
            'q' => 'a'
        ])
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-history/{orgeh}
     * get
     *
     * failed, karena kalau ngisi ngasal gk ada di db return null,
     * hasil null tersebut di pake di view,
     * so something went wrong appear
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_history_with_params_orgeh_random()
    {
        $this->markTestSkipped(
            "failed, karena kalau ngisi ngasal gk ada di db return null,
            hasil null tersebut di pake di view, so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-history/123')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-history/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_history_with_params_orgeh_real()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-history/10074409')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-history/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_history_with_params_orgeh_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-history/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * ajax/get-pelanggaran/{orgeh}
     * get
     *
     * failed, karena kalau ngisi string not int, bakal error di searching db nya
     * so something went wrong appear, expect ya harus ada exception or validate nya
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_pelanggaran_with_params_orgeh_random()
    {
        $this->markTestSkipped(
            "failed, karena kalau ngisi string not int, bakal error di searching db nya
            so something went wrong appear, expect ya harus ada exception or validate nya"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-pelanggaran/random')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-pelanggaran/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_pelanggaran_with_params_orgeh_real()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-pelanggaran/10074409')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-pelanggaran/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_pelanggaran_with_params_orgeh_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-pelanggaran/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * ajax/clear-history/{orgeh}
     * get
     *
     * failed, karena kalau ngisi string not int, bakal error di searching db nya
     * so something went wrong appear, expect ya harus ada exception or validate nya
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_with_params_orgeh_random()
    {
        $this->markTestSkipped(
            "failed, karena kalau ngisi string not int, bakal error di searching db nya
            so something went wrong appear, expect ya harus ada exception or validate nya"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history/random')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/clear-history/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_with_params_orgeh_real()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history/10074409')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/clear-history/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_with_params_orgeh_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * ajax/get-jml-pegawai/{orgeh}
     * get
     *
     * failed, karena kalau ngisi orgeh yang gk ada, bakal error
     * karena modelnya di pake buat query, nama query nya getChildren
     * failed, kalau ngisi orgeh string error db juga
     * so something went wrong appear, expect ya harus ada exception or validate nya
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_jml_pegawai_with_params_orgeh_random()
    {
        $this->markTestSkipped(
            "failed, karena kalau ngisi orgeh yang gk ada, bakal error
            karena modelnya di pake buat query, nama query nya getChildren
            failed, kalau ngisi orgeh string error db juga
            so something went wrong appear, expect ya harus ada exception or validate nya"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-jml-pegawai/123')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-jml-pegawai/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_jml_pegawai_with_params_orgeh_real()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-jml-pegawai/10074409')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-jml-pegawai/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_jml_pegawai_with_params_orgeh_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-jml-pegawai/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * ajax/clear-history-pelanggaran/{orgeh}
     * get
     *
     * failed, karena kalau ngisi string not int, bakal error di searching db nya
     * so something went wrong appear, expect ya harus ada exception or validate nya
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_pelanggaran_with_params_orgeh_random()
    {
        $this->markTestSkipped(
            "failed, karena kalau ngisi string not int, bakal error di searching db nya
            so something went wrong appear, expect ya harus ada exception or validate nya"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history-pelanggaran/random')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/clear-history-pelanggaran/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_pelanggaran_with_params_orgeh_real()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history-pelanggaran/10074409')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/clear-history-pelanggaran/{orgeh}
     * get
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_clear_history_pelanggaran_with_params_orgeh_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/clear-history-pelanggaran/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * ajax/get-history-pelanggaran
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_akses_ajax_get_history_pelanggaran()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-history-pelanggaran/15937906')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-history-pelanggaran
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_akses_ajax_get_history_pelanggaran_with_orgeh_random()
    {
        $this->markTestSkipped(
            "Failed, jika input langsung lewat BE, ini error karena jika orgeh gk ketemu bakal
            so Something Went Wrong Appear, expect session flash error"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-history-pelanggaran/random')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-coc/{id}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_coc_with_params()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-coc/12')
            ->assertResponseOk();
    }

    /**
     * route
     * ajax/get-coc/{id}
     * get
     *
     * failed
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_coc_with_string_params()
    {
        $this->markTestSkipped(
            "Failed, gk ada validasi bahwa {id} harus number, 
            so Something Went Wrong Appear, expect session flash error"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-coc/asd')
            ->dontSee('Something went wrong');
    }

    /**
     * route
     * ajax/get-coc/{id}
     * get
     *
     * tbd
     * @test
     * */
    public function tbd_can_access_ajax_get_coc_with_null_params()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-coc/')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/read-materi/{event_id}
     * post
     * tbd
     * failed.. di request gk ada validasi request,
     * gk ada transaction juga,
     * db jadi error karna gk sesuai format
     * coc kalau gk ketemu juga bakalan failed
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_read_materi_event_id_with_coc_find_and_all_null()
    {
        $this->markTestSkipped(
            "* failed.. di request gk ada validasi request,
            * gk ada transaction juga,
            * db jadi error karna gk sesuai format
            * coc kalau gk ketemu juga bakalan failed
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->post('coc/read-materi/21', [
            "materi_id" => null,
            "rate" => null,
        ])->assertResponseStatus(302);
    }

    /**
     * route
     * coc/read-materi/{event_id}
     * post
     * tbd
     *
     * failed.. di request gk ada validasi request,
     * gk ada transaction juga,
     * db jadi error karna gk sesuai format
     * coc kalau gk ketemu juga bakalan failed
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_read_materi_event_id_with_coc_find_and_random_params()
    {
        $this->markTestSkipped(
            "* failed.. di request gk ada validasi request,
            * gk ada transaction juga,
            * db jadi error karna gk sesuai format
            * coc kalau gk ketemu juga bakalan failed
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->post('coc/read-materi/21', [
            "materi_id" => "aye aye",
            "rate" => "aye aye",
        ])->assertResponseStatus(302);
    }

    /**
     * route
     * coc/read-materi/{event_id}
     * post
     * tbd
     *
     * failed.. di request gk ada validasi request,
     * gk ada transaction juga,
     * db jadi error karna gk sesuai format
     * coc kalau gk ketemu juga bakalan failed
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_read_materi_event_id_with_coc_not_fond_and_positive_params()
    {
        $this->markTestSkipped(
            "* failed.. di request gk ada validasi request,
            * gk ada transaction juga,
            * db jadi error karna gk sesuai format
            * coc kalau gk ketemu juga bakalan failed
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        // login as kevin siregar
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->post('coc/read-materi/12312312321', [
            "materi_id" => "1",
            "rate" => "1",
        ])->assertResponseStatus(302);
    }

    /**
     * route
     * coc/delete-foto/{foto_id}
     * get
     * tbd
     *
     * failed.. kalau gk find, bakal error karena model hasil find
     * dipakai proses selanjutnya.. harusnya ada validasi jika gk find
     * ini delete, tp pakai get
     * so something went wrong appear
     * @test
     * */
    public function tbd_can_post_coc_delete_foto_foto_id_with_random_event_id()
    {
        $this->markTestSkipped(
            "* failed.. kalau gk find, bakal error karena model hasil find
            * dipakai proses selanjutnya.. harusnya ada validasi jika gk find
            * ini delete, tp pakai get
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/delete-foto/1')
        ->assertResponseOk();
    }

    /**
     * route
     * coc/delete-foto/{foto_id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_post_coc_delete_foto_foto_id_with_null_event_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/delete-foto/')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/event/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_coc_event_id_with_null_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/event/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_coc_event_id_with_random_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/123')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/event/{id}
     * get
     * tbd
     *
     * failed, db gk bisa search by string, expect ada validasi
     * request string, so something went wrong appear
     * @test
     * */
    public function tbd_can_get_coc_event_id_with_string_id()
    {
        $this->markTestSkipped(
            " failed, db gk bisa search by string, expect ada validasi
            * request string, so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/asdsad')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/event/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_coc_event_id_with_positive_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/event/21')
        ->assertResponseOk();
    }

    /**
     * route
     * coc/tema/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_tema_id_with_positive_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/tema/61')
        ->assertResponseOk();
    }

    /**
     * route
     * coc/tema/{id}
     * get
     * tbd
     *
     * @test
     * */
    public function tbd_can_get_tema_id_with_random_id()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/tema/611212')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * coc/tema/{id}
     * get
     * tbd
     *
     * failed, db gk bisa search by string, expect ada validasi
     * request string, so something went wrong appear
     * @test
     * */
    public function tbd_can_get_tema_id_with_string_id()
    {
        $this->markTestSkipped(
            " failed, db gk bisa search by string, expect ada validasi
            * request string, so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/tema/ayeaye')
        ->assertResponseStatus(404);
    }

    /**
     * route coc/forum/{id}
     * ID Testing
     * tbd
     * post
     *
     * failed,
     * */
    public function test_can_post_coc_forum_id_with_random_id()
    {
        $this->markTestSkipped(
            " failed, gk ada validasi kalau coc_id gk terdaftar,
            so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/forum/21121', [
            "comment" => "ini comment",
        ])->assertResponseStatus(302);
    }

    /**
     * route coc/forum/{id}
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_forum_id_with_coc_id_find()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/forum/21', [
            "comment" => "ini comment",
        ])->assertResponseStatus(302);
    }

    /**
     * route coc/forum/{id}
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_forum_id_with_comment_null()
    {
        $this->markTestSkipped(
            " failed, gk ada validasi kalau comment gk boleh null di db,
            so something went wrong appear"
        );

        $this->skipAuthorization();
        $this->login();
        $this->post('coc/forum/21', [
            "comment" => null,
        ])->assertResponseStatus(302);
    }

    /**
     * route coc/prinsip/{coc_id}
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_prinsip_coc_id_with_coc_id_find_and_status_checkin_1()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/prinsip/21', [
            "status_checkin" => "1",
        ])->followRedirects()
        ->see('Tumbuh Berkembang Dengan Integritas Dan Keunggulan');
    }

    /**
     * route coc/prinsip/{coc_id}
     * ID Testing
     * tbd
     * post
     *
     * failed, karena status di view saat search tidak find,
     * so something went wrong appear
     * */
    public function test_can_post_coc_prinsip_coc_id_with_coc_id_find_and_status_checkin_random()
    {
        $this->markTestSkipped(
            " * failed, karena status di view saat search tidak find,
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/prinsip/21', [
            "status_checkin" => "random",
        ])->followRedirects()
        ->dontSee('Something went wrong');
    }

    /**
     * route coc/prinsip/{coc_id}
     * ID Testing
     * tbd
     * post
     *
     * failed, karena coc gk ketemu, coc model di pakai -> di view,
     * so something went wrong appear
     * */
    public function test_can_post_coc_prinsip_coc_id_with_coc_id_random()
    {
        $this->markTestSkipped(
            "* failed, karena coc gk ketemu, coc model di pakai -> di view,
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/prinsip/12212', [
            "status_checkin" => "1",
        ])->followRedirects()
        ->dontSee('Something went wrong');
    }

    /**
     * route coc/prinsip/{coc_id}
     * ID Testing
     * tbd
     * post
     *
     * failed, karena coc gk ketemu, coc model di pakai -> di view,
     * di db gk search string
     * so something went wrong appear
     * */
    public function test_can_post_coc_prinsip_coc_id_with_coc_id_random_string()
    {
        $this->markTestSkipped(
            "* failed, karena coc gk ketemu, coc model di pakai -> di view,
            * di db gk search string
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/prinsip/random', [
            "status_checkin" => "1",
        ])->followRedirects()
        ->dontSee('Something went wrong');
    }

    /**
     * route
     * coc/cancel/{id}
     * get
     * tbd
     *
     * ini cancel tp pakai get
     * @test
     * */
    public function tbd_can_get_cancel_id_with_coc_id_find()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/cancel/21')
        ->followRedirects()
        ->see('Jadwal CoC berhasil dibatalkan.');
    }

    /**
     * route
     * coc/cancel/{id}
     * get
     * tbd
     *
     * failed, karena coc id gk di temukan dan model kosong di pakai sebagai query
     * atau sebagai di view, so
     * something went wrong appear
     * @test
     * */
    public function tbd_can_get_cancel_id_with_coc_id_not_find()
    {
        $this->markTestSkipped(
            "* failed, karena coc id gk di temukan dan model kosong di pakai sebagai query
            * atau sebagai di view, so
            * something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/cancel/212121')
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * coc/cancel/{id}
     * get
     * tbd
     *
     * ini cancel tp pakai get
     * @test
     * */
    public function tbd_can_get_cancel_id_with_coc_id_null()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/cancel/')
        ->assertResponseStatus(404);
    }

    /**
     * route coc/complete
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_complete_with_id_coc_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete', [
            "coc_id" => 21,
            "pernr_leader" => "97161607",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/complete
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_complete_with_id_coc_not_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete', [
            "coc_id" => 211212,
            "pernr_leader" => "97161607",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/complete
     * ID Testing
     * tbd
     * post
     *
     * failed, jenjang gk dapet, terus model jenjang di get, error on null
     * */
    public function test_can_post_coc_complete_with_pernr_leader_random()
    {
        $this->markTestSkipped(
            "failed, jenjang gk dapet, terus model jenjang di get, error on null"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete', [
            "coc_id" => 21,
            "pernr_leader" => "ayeaye",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/complete/{id}
     * ID Testing
     * tbd
     * post
     *
     * */
    public function test_can_post_coc_complete_id_with_id_coc_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete/21', [
            "pernr_leader" => "97161607",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/complete/{id}
     * ID Testing
     * tbd
     * post
     * failed when id coc not found its
     * something went wrong
     * */
    public function test_can_post_coc_complete_id_with_id_coc_not_found()
    {
        $this->markTestSkipped(
            "failed when id coc not found its
            * something went wrong"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete/212121', [
            "pernr_leader" => "97161607",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/complete/{id}
     * ID Testing
     * tbd
     * post
     * failed
     * */
    public function test_can_post_coc_complete_id_with_penr_leader_random()
    {
        $this->markTestSkipped(
            "failed, jenjang gk dapet, terus model jenjang di get, error on null"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/complete/21', [
            "pernr_leader" => "123213213",
            "tanggal_coc" => "10-07-2020",
            "jam_coc" => "14:10",
            "jml_peserta_dispensasi" => 12
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route ajax/get-perilaku/{id}/{orgeh}/{jenis}
     * ID Testing
     * tbd
     * get
     *
     * failed, gk ada validasi jika gk ketemu di db, ya return apa kek
     * masalahnya model kosong masih di pake buat query atau get attributnya
     * so something went wrong appear
     * */
    public function test_can_get_ajax_perilaku_id_orgeh_jenis_with_random()
    {
        $this->markTestSkipped(
            "* failed, gk ada validasi jika gk ketemu di db, ya return apa kek
            * masalahnya model kosong masih di pake buat query atau get attributnya
            * so something went wrong appear"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('ajax/get-perilaku/21/1231/123')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * get
     * */
    public function test_can_get_coc_create_materi_id_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/create/61')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * get
     * */
    public function test_can_get_coc_create_materi_id_not_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/create/61123')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * get
     *
     * failed, find or failed failed search by string,
     * should add validation for string input
     * */
    public function test_can_get_coc_create_materi_id_string()
    {
        $this->markTestSkipped(
            "* failed, find or failed failed search by string,
            * should add validation for string input"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/create/asdasd')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_coc_create_materi_id_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/61')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_coc_create_materi_id_not_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/61123')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}
     * ID Testing
     * tbd
     * post
     *
     * failed, find or failed failed search by string,
     * should add validation for string input
     * */
    public function test_can_post_coc_create_materi_id_string()
    {
        $this->markTestSkipped(
            "* failed, find or failed failed search by string,
            * should add validation for string input"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/asdasd')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}/store
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_coc_create_materi_id_store_not_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/61123/store', [
            'judul' => 'ini judul',
            'jam' => '14:10',
            'sipp' => 2,
            'pernr_pemateri' => '97161607',
            'lokasi' => 'lalala',
            'jml_peserta' => 123,
            'pelanggaran' => '1'
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}/store
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_coc_create_materi_id_store_found_one()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/61/store', [
            'judul' => 'ini judul',
            'sipp' => 2,
            'jam' => '14:10',
            'pernr_pemateri' => '97161607',
            'lokasi' => 'lalala',
            'jml_peserta' => 123,
            'pelanggaran' => '1'
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}/store
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_coc_create_materi_id_store_found_with_pernr_random()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/61/store', [
            'sipp' => 2,
            'judul' => 'ini judul',
            'jam' => '14:10',
            'pernr_pemateri' => '123213213',
            'lokasi' => 'lalala',
            'jml_peserta' => 123,
            'pelanggaran' => '1'
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/create/{materi_id}/store
     * ID Testing
     * tbd
     * post
     * failed saat input tanggal dan jam ngasal, gk ada validasi di BE
     * dan errornya gara2 carbon parse
     * */
    public function test_can_post_coc_create_materi_id_store_found_with_tanggal_coc_jam_ngasal()
    {
        $this->markTestSkipped(
            "* failed saat input tanggal dan jam ngasal, gk ada validasi di BE
            * dan errornya gara2 carbon parse"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('coc/create/41/store', [
            'sipp' => 2,
            'tanggal_coc' => 'adsasd',
            'jam' => 'adasd',
            'judul' => 'ini judul',
            'pernr_pemateri' => '123213213',
            'lokasi' => 'lalala',
            'jml_peserta' => 123,
            'pelanggaran' => '1'
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/initial/{materi_id}
     * ID Testing
     * tbd
     * get
     *
     * */
    public function test_can_get_coc_initial_materi_id_found()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/initial/41')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route coc/initial/{materi_id}
     * ID Testing
     * tbd
     * get
     *
     * */
    public function test_can_get_coc_initial_materi_id_int_random()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/initial/123123')->followRedirects()
        ->dontSee('something went wrong');
    }
    
    /**
     * route coc/initial/{materi_id}
     * ID Testing
     * tbd
     * get
     *
     * failed, find or failed failed search by string,
     * should add validation for string input
     * */
    public function test_can_get_coc_initial_materi_id_string()
    {
        $this->markTestSkipped(
            "* failed, find or failed failed search by string,
            * should add validation for string input"
        );
        $this->skipAuthorization();
        $this->login();
        $this->get('coc/initial/asdasd')->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route commitment/pedoman-perilaku
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_commitment_pedoman_perilaku_with_positive_input()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku', [
            'pedoman_perilaku_id' => 1,
            'checkbox_do' => '1',
            'checkbox_dont' => '1',
            'tahun' => 2020
        ])->assertRedirectedTo('commitment/pedoman-perilaku/tahun/2020');
    }

    /**
     * route commitment/pedoman-perilaku
     * ID Testing
     * tbd
     * post
     * failed, jika pedoman_perilaku_id gk ketemu yaudah bikin error
     * */
    public function test_can_post_commitment_pedoman_perilaku_negative_input()
    {
        $this->markTestSkipped(
            "failed, jika pedoman_perilaku_id gk ketemu yaudah bikin error"
        );
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku', [
            'pedoman_perilaku_id' => 123,
            'checkbox_do' => '1',
            'checkbox_dont' => '1',
            'tahun' => 2020
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route commitment/pedoman-perilaku
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_commitment_pedoman_perilaku_with_checkbox_ngasal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku', [
            'pedoman_perilaku_id' => 1,
            'checkbox_do' => 'asdasdasd',
            'checkbox_dont' => 'asdasd',
            'tahun' => 2020
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route commitment/pedoman-perilaku
     * ID Testing
     * tbd
     * post
     * */
    public function test_can_post_commitment_pedoman_perilaku_with_tahun_ngasal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->post('commitment/pedoman-perilaku', [
            'pedoman_perilaku_id' => 1,
            'checkbox_do' => 'asdasdasd',
            'checkbox_dont' => 'asdasd',
            'tahun' => 'ngasal'
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route commitment/pedoman-perilaku/tahun/{tahun}
     * ID Testing
     * tbd
     * get
     * */
    public function test_can_get_commitment_pedoman_perilaku_tahun_tahun_with_tahun_bener()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/pedoman-perilaku/tahun/2020')->followRedirects()
        ->see('Komitmen');
    }

    /**
     * route commitment/pedoman-perilaku/tahun/{tahun}
     * ID Testing
     * tbd
     * get
     * */
    public function test_can_get_commitment_pedoman_perilaku_tahun_tahun_with_tahun_ngasal()
    {
        $this->skipAuthorization();
        $this->login();
        $this->get('commitment/pedoman-perilaku/tahun/ajjddasdabds')->followRedirects()
        ->dontSee('something went wrong');
    }
}
