<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * @group logbook
 */
class LogbookControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
        $this->emptyLiquidTables();
        $this->add_liquid();
        $this->add_liquid_peserta();
        $this->add_activity_log_book_10_by_id_himawan();
        $this->add_penyelarasan_10_by_id_himawan();
        $this->add_add_kelebihan_kekurangan();
        $this->add_kelebihan_kekurangan_detail();
    }

    public function add_add_kelebihan_kekurangan()
    {
        DB::table('kelebihan_kekurangan')->insert([
            'id' => 41,
            'title' => 'Master Kelebihan dan Kekurangan versi 1 tahun 2020',
            'deskripsi' => "<p>Berdasarkan data Do and Don't Pedoman Perilaku &amp; Etika Bisnis PT PLN (PERSERO) Tahun 2020</p>",
            'status' => 'AKTIF',
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function add_kelebihan_kekurangan_detail()
    {
        DB::table('kelebihan_kekurangan_detail')->insert([
            'id' => 44,
            'parent_id' => 41,
            'deskripsi_kelebihan' => "Menganggap masalah dalam sudut pandang yang POSITIF sehingga dapat dimanfaatkan untuk memotivasi diri",
            'deskripsi_kekurangan' => "Menyimpan sendiri & MENYEMBUNYIKAN PENGETAHUAN yang dimilikinya hanya untuk kepentingan pribadi & tertutup untuk Unit lainnya.",
            'created_by' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function add_liquid_peserta()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        DB::table('liquid_peserta')->insert([
            'id' => 100,
            'liquid_id' => 1,
            'atasan_id' => $user->strukturJabatan->pernr,
            'bawahan_id' => 69937011,
            'snapshot_jabatan_atasan' => 'MD_UP',
            'snapshot_jabatan_bawahan' => 'SPV',
        ]);
    }

    public function add_liquid()
    {
        $this->objTest = '{"80066801":{"nama":"HIMAWAN WITJAKSONO ADJI","nip":"8006229Z","jabatan":"MUP II UP3 BARABAI","kelompok_jabatan":"MD_UP","business_area":"7113","peserta":{"64877011":{"liquid_peserta_id":"41","nama":"SYAMSIR ALAM","nip":"6487101D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"64857012":{"liquid_peserta_id":"42","nama":"SLAMET RIYADI","nip":"6485103D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"64857205":{"liquid_peserta_id":"43","nama":"ABDUL SALIM","nip":"6485100D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"66857002":{"liquid_peserta_id":"44","nama":"RAKHMAT NOR IFANSYAH HAJI","nip":"6685108D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"69937011":{"liquid_peserta_id":"35","nama":"SUYONO","nip":"6993236D","jabatan":"MAN II RING","kelompok_jabatan":"SPV"},"66877001":{"liquid_peserta_id":"45","nama":"ACHMAD NIZAMI BEY","nip":"6687010D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"66937003":{"liquid_peserta_id":"46","nama":"MUHAMMAD RAMLI HAJI","nip":"6693123D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"70907009":{"liquid_peserta_id":"36","nama":"JUHARTONO","nip":"7090069D","jabatan":"MAN II KEU, SDM, DAN ADM","kelompok_jabatan":"SPV"},"70937011":{"liquid_peserta_id":"47","nama":"MAHYUDDIN Y","nip":"7093125D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"72937013":{"liquid_peserta_id":"37","nama":"IWAN SETIAWAN","nip":"7293141D","jabatan":"MAN II REN","kelompok_jabatan":"SPV"},"72937015":{"liquid_peserta_id":"38","nama":"MATENU","nip":"7293143D","jabatan":"MAN II KONS","kelompok_jabatan":"SPV"},"82097003":{"liquid_peserta_id":"39","nama":"SYAHRUL","nip":"8209462Z","jabatan":"MAN II TE LISTRIK","kelompok_jabatan":"SPV"},"88117010":{"liquid_peserta_id":"40","nama":"ASTRI RAHMA WIJAYANTI","nip":"88112244Z","jabatan":"MAN II SAR DAN YAN GAN","kelompok_jabatan":"SPV"}}},"89137000":{"nama":"TOPAN SETYAWAN","nip":"8913077ZY","jabatan":"MUL ULP TANJUNG","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67907000":{"liquid_peserta_id":"23","nama":"ISNAIN WASISTO SUDYO WIDODO","nip":"6790001D","jabatan":"SPV II TEKNIK (MUARA UYA)","kelompok_jabatan":"SPV"},"66917000":{"liquid_peserta_id":"34","nama":"SALMA","nip":"6691047D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"73047006":{"liquid_peserta_id":"24","nama":"ISKANDAR","nip":"7304047D","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"89097016":{"liquid_peserta_id":"25","nama":"MIFTAHUR RIZQI","nip":"8909017D","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"89097019":{"liquid_peserta_id":"26","nama":"SUMANTO","nip":"8909030D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"93167028":{"liquid_peserta_id":"27","nama":"KALVIN LENTINO","nip":"93162709ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"}}},"89097010":{"nama":"RAHMAT NOVIAR ANSYARI","nip":"8909024D","jabatan":"PLT MUL ULP PARINGIN","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67897000":{"liquid_peserta_id":"14","nama":"MUHAMMAD YANI","nip":"6789009D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"92167000":{"liquid_peserta_id":"15","nama":"MOCHAMMAD KEMAL ACHRIANSYAH","nip":"9216193ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"94167002":{"liquid_peserta_id":"16","nama":"IMAM YOGA FITRIYANTO","nip":"94161628ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"94167008":{"liquid_peserta_id":"17","nama":"DIKTYO ROBBY ROHIMAWAN","nip":"94162033ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"}}},"65877000":{"nama":"PARDJITO","nip":"6587006D","jabatan":"MUL ULP AMUNTAI","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67877002":{"liquid_peserta_id":"32","nama":"NOOR ABIDIN","nip":"6787106D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"73937018":{"liquid_peserta_id":"1","nama":"PAHMI","nip":"7393212D","jabatan":"SPV II TEKNIK (DANAU PANGGANG)","kelompok_jabatan":"SPV"},"82077002":{"liquid_peserta_id":"2","nama":"RUMLIANSYAH","nip":"8207023D","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"88097006":{"liquid_peserta_id":"3","nama":"OKFAREKI","nip":"8809066D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"89097025":{"liquid_peserta_id":"4","nama":"M. ADE ROSAIRI","nip":"8909079D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"94167019":{"liquid_peserta_id":"5","nama":"YUSUF ABDULLOH","nip":"94162201ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"}}},"89097015":{"nama":"SEPTIAN FETER ARIFIANTO","nip":"8909028D","jabatan":"PLT MUL ULP RANTAU","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"68917008":{"liquid_peserta_id":"33","nama":"MUHAMMAD BAIHAKI HAJI","nip":"6891042D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"89097020":{"liquid_peserta_id":"18","nama":"NURUL HIDAYATI","nip":"8909023D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"89097021":{"liquid_peserta_id":"19","nama":"IMAM HIDAYAT","nip":"8909055D","jabatan":"SPV II TEKNIK (MARGASARI)","kelompok_jabatan":"SPV"},"91147004":{"liquid_peserta_id":"20","nama":"GALIH ESTU LESTARI","nip":"9114568ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"91177001":{"liquid_peserta_id":"21","nama":"RIDHO MUHAMMAD","nip":"91171552ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167021":{"liquid_peserta_id":"22","nama":"AHMAD SYAIROZIE","nip":"94162875ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"}}},"88117008":{"nama":"SUTIO BAYUPRAKOSO","nip":"8811676Z","jabatan":"PLT MUL ULP BINUANG","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"71047000":{"liquid_peserta_id":"10","nama":"WINARTO","nip":"7104014D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"89097001":{"liquid_peserta_id":"11","nama":"SILVIA RUSIANA PUTERI","nip":"8909029D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"92177006":{"liquid_peserta_id":"12","nama":"MUHAMMAD FAHRIZA","nip":"92171956ZY","jabatan":"PLT SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167000":{"liquid_peserta_id":"13","nama":"PURWAJI","nip":"9416341ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"}}},"86127004":{"nama":"ENDI SOPYANDI","nip":"8612594ZY","jabatan":"MUL ULP DAHA","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"90097016":{"liquid_peserta_id":"6","nama":"AKHMAD RIJANI","nip":"9009045D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"91147008":{"liquid_peserta_id":"7","nama":"GALIH ANDIKA YUDA","nip":"9114901ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"93167024":{"liquid_peserta_id":"9","nama":"MUHAMMAD RIZKI","nip":"93162680ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"93157003":{"liquid_peserta_id":"8","nama":"NILNA AZIZAH","nip":"9315636ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"}}},"90157009":{"nama":"DADIN","nip":"9015772ZY","jabatan":"MUL ULP KANDANGAN","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"92147011":{"liquid_peserta_id":"29","nama":"M. ARIEF SETIAWAN","nip":"9214776ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"91147003":{"liquid_peserta_id":"28","nama":"ADIL TEGUH SUBARYANTO","nip":"9114559ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"94177015":{"liquid_peserta_id":"31","nama":"STEFANUS VIKI KURNIANTONO","nip":"94171558ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167001":{"liquid_peserta_id":"30","nama":"IRELAND ADITYA JATI","nip":"9416747ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"}}}}';
        DB::table('liquids')->insert([
            'id' => 1,
            'kelebihan_kekurangan_id' => 1,
            'feedback_start_date' => date('Y-m-d H:i:s'),
            'feedback_end_date' => date('Y-m-d H:i:s'),
            'penyelarasan_end_date' => date('Y-m-d H:i:s'),
            'penyelarasan_start_date' => date('Y-m-d H:i:s'),
            'pengukuran_pertama_start_date' => '2020-07-24 00:00:00',
            'pengukuran_pertama_end_date' => '2020-07-25 00:00:00',
            'pengukuran_kedua_end_date' => date('Y-m-d H:i:s'),
            'pengukuran_kedua_start_date' => date('Y-m-d H:i:s'),
            'reminder_aksi_resolusi' => 'MINGGUAN',
            'gathering_end_date' => date('Y-m-d H:i:s'),
            'gathering_start_date' => date('Y-m-d H:i:s'),
            'status' => 'PUBLISHED',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'peserta_snapshot' => $this->objTest
        ]);
    }

    public function add_activity_log_book_10000_by_id_himawan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        for ($i = 0; $i < 1000; $i++) {
            DB::table('activity_log_book')->insert([
               'id' => 10 + $i + 1,
               'liquid_id' => 1,
               'resolusi' => '[44]',
               'start_date' => date('Y-m-d H:i:s'),
               'end_date' => date('Y-m-d H:i:s'),
               'nama_kegiatan' => str_random(100),
               'tempat_kegiatan' => str_random(100),
               'keterangan' => str_random(100),
               'created_by' => $user->id,
               'created_at' => date('Y-m-d H:i:s'),
           ]);
        }
    }

    public function add_activity_log_book_10_by_id_himawan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        for ($i = 0; $i < 10; $i++) {
            DB::table('activity_log_book')->insert([
               'id' => $i + 1,
               'liquid_id' => 1,
               'resolusi' => '[44]',
               'start_date' => date('Y-m-d H:i:s'),
               'end_date' => date('Y-m-d H:i:s'),
               'nama_kegiatan' => str_random(100),
               'tempat_kegiatan' => str_random(100),
               'keterangan' => str_random(100),
               'created_by' => $user->id,
               'created_at' => date('Y-m-d H:i:s'),
           ]);
        }
    }

    public function add_penyelarasan_10_by_id_himawan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        for ($i = 0; $i < 10; $i++) {
            DB::table('penyelarasan')->insert([
               'id' => $i + 1,
               'liquid_id' => 1,
               'resolusi' => '[44]',
               'catatan_kekurangan' => str_random(100),
               'date_start' => date('Y-m-d H:i:s'),
               'date_end' => date('Y-m-d H:i:s'),
               'tempat' => str_random(100),
               'keterangan' => str_random(100),
               'aksi_nyata' => str_random(100),
               'atasan_id' => $user->strukturJabatan->pernr,
               'created_at' => date('Y-m-d H:i:s'),
               'created_by' => $user->id,
           ]);
        }
    }

    public function login_as_atasan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $this->login($user);
    }

    public function login_as_not_atasan()
    {
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
    }

    /**
     * route
     * activity-log
     * get
     * G1
     *
     * @test
     * */
    public function G1_can_get_activity_log_with_user_not_atasan()
    {
        $user = \App\User::where('username', 'kevin.siregar')->first();
        $this->login($user);
        $this->get('activity-log')
        ->followRedirects()
        ->dontSee('Activity Log');
    }

    /**
     * route
     * activity-log
     * get
     * G1
     *
     * @test
     * */
    public function G1_can_get_activity_log_with_user_atasan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $this->login($user);
        $this->get('activity-log')
        ->followRedirects()
        ->see('Activity Log');
    }

    /**
     * route
     * activity-log
     * get
     * G1
     *
     * @test
     * */
    public function G1_can_get_activity_log_user_atasan_much_data()
    {
        $this->add_activity_log_book_10000_by_id_himawan();
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $this->login($user);
        $this->get('activity-log')
        ->assertViewHas('actLogBook');
    }

    /**
     * route
     * activity-log
     * get
     * G4
     *
     * @test
     * */
    public function G4_atasan_can_click_detail_and_show_it()
    {
        $this->G1_can_get_activity_log_with_user_atasan();
        $this->seeElement('#activity1');
    }

    /**
     * route
     * activity-log
     * get
     * G4
     *
     * @test
     * */
    public function G4_non_atasan_can_click_detail_and_show_it()
    {
        $this->login_as_not_atasan();
        $this->get('activity-log')
            ->followRedirects()
            ->see('You are not authorized to this action');
    }

    /**
     * route
     * activity-log
     * get
     * G5
     *
     * @test
     * */
    public function G5_post_delete_activity_log_atasan_found_id()
    {
        $this->login_as_atasan();
        $data = DB::table('activity_log_book')->first();
        $link = route('activity-log.destroy', $data->id);
        $this->callCustom('DELETE', $link, ['_token' => csrf_token()])
        ->followRedirects()
        ->see('Berhasil Menghapus Activity Log Book');
    }

    /**
     * route
     * activity-log
     * get
     * G5
     *
     * @test
     * */
    public function G5_post_delete_activity_log_atasan_not_found_id()
    {
        $this->login_as_atasan();
        $link = route('activity-log.destroy', 123123123);
        $this->callCustom('DELETE', $link, ['_token' => csrf_token()])
        ->assertResponseStatus(404);
    }

    /**
     * route
     * activity-log
     * get
     * G5
     *
     * @test
     * */
    public function G5_post_delete_activity_log_atasan_id_string()
    {
        $this->markTestSkipped(
            "belom ada validasi id gk boleh string, harus int"
        );
        $this->login_as_atasan();
        $link = route('activity-log.destroy', str_random(20));
        $this->callCustom('DELETE', $link, ['_token' => csrf_token()])
        ->assertResponseStatus(404);
    }

    /**
     * route
     * activity-log
     * get
     * G6
     *
     * @test
     * */
    public function G6_akses_edit_view_logbook()
    {
        $link = route('activity-log.edit', 1);
        $this->login_as_atasan();
        $this->get($link)
        ->assertViewHasAll(['data', 'resolusi']);
    }

    /**
     * route
     * activity-log
     * get
     * G6
     *
     * @test
     * */
    public function G6_save_in_edit_view_logbook_skema_normal()
    {
        $link = route('activity-log.update', 1);
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G6_akses_edit_view_logbook();
        $this->callCustom('PUT', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => str_random(100)
        ], ['dokumen' => $file])
        ->assertResponseStatus(302)
        ->followRedirects()
        ->see('Berhasil Mengubah Data Activity Log Book');
    }

    /**
     * route
     * activity-log
     * get
     * G7
     *
     * @test
     * */
    public function G7_save_in_edit_view_logbook_skema_null_deskripsi()
    {
        $this->markTestSkipped(
            "belom ada validasi apapun di BE utk data, minimal required lah"
        );
        $link = route('activity-log.update', 1);
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G6_akses_edit_view_logbook();
        $this->callCustom('PUT', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => null
        ], ['dokumen' => $file])
        ->assertResponseStatus(302)
        ->followRedirects()
        ->dontSee('Berhasil Mengubah Data Activity Log Book');
    }

    /**
     * route
     * activity-log
     * get
     * G8
     *
     * @test
     * */
    public function G8_save_in_edit_view_logbook_tanggal_kebalek()
    {
        $this->markTestSkipped(
            "belom ada validasi apapun di BE utk tanggal kebalek"
        );
        $link = route('activity-log.update', 1);
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G6_akses_edit_view_logbook();
        $this->callCustom('PUT', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => '2020-08-25 00:00:00',
            'end_date' => '2020-09-24 00:00:00',
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => null
        ], ['dokumen' => $file])
        ->assertResponseStatus(302)
        ->followRedirects()
        ->dontSee('Berhasil Mengubah Data Activity Log Book');
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     * */
    public function G9_can_access_as_atasan()
    {
        $this->login_as_atasan();
        $this->get('activity-log/create')
        ->followRedirects()
        ->see('Input Activity Log');
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     * */
    public function G9_can_access_as_not_atasan()
    {
        $this->login_as_not_atasan();
        $this->get('activity-log/create')
        ->followRedirects()
        ->dontSee('Input Activity Log');
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     *
     * */
    public function G9_can_save_activity_log_create_all_data_input()
    {
        $link = route('activity-log.store').'?liquid_id=1';
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G9_can_access_as_atasan();
        $this->callCustom('POST', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => str_random(100)
        ], ['dokumen' => $file])
        ->followRedirects()
        ->see('Berhasil Membuat Activity Log Book');
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     *
     * */
    public function G9_can_save_activity_log_create_all_data_input_with_string_liquid_id()
    {
        $link = route('activity-log.store').'?liquid_id=tes';
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G9_can_access_as_atasan();
        $this->callCustom('POST', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => str_random(100)
        ], ['dokumen' => $file])
        ->assertResponseStatus(404);
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     *
     * */
    public function G10_can_save_activity_log_create_deskripsi_not_input()
    {
        $this->markTestSkipped(
            "belom ada validasi apapun di BE utk data, minimal required lah"
        );
        $link = route('activity-log.store').'?liquid_id=1';
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G9_can_access_as_atasan();
        $this->callCustom('POST', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => null
        ], ['dokumen' => $file])
        ->assertResponseStatus(302);
    }

    /**
     * route
     * activity-log/create
     * get
     * G9
     *
     * @test
     *
     * */
    public function G11_can_save_activity_log_create_tanggal_kebalek()
    {
        $this->markTestSkipped(
            "belom ada validasi tanggal kebalek di BE"
        );
        $link = route('activity-log.store').'?liquid_id=1';
        $file = base_path('tests/data/dummy.jpg');
        $file = new UploadedFile($file, 'dummy.jpg', 'image/jpeg', filesize($file), null, true);
        $this->G9_can_access_as_atasan();
        $this->callCustom('POST', $link, [
            'resolusi' => [44],
            'nama_kegiatan' => str_random(100),
            'start_date' => '2020-08-25 00:00:00',
            'end_date' => '2020-09-24 00:00:00',
            'tempat_kegiatan' => str_random(100),
            'deskripsi' => str_random(100)
        ], ['dokumen' => $file])
        ->assertResponseStatus(302)
        ->followRedirects()
        ->dontSee('Berhasil Membuat Activity Log Book');
    }
}
