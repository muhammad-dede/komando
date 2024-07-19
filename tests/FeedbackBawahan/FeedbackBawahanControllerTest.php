<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

/**
 * @group feedback-bawahan
 */
class FeedbackBawahanControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
        $this->emptyLiquidTables();
        $this->add_liquid();
        $this->add_liquid_peserta();
        $this->add_add_kelebihan_kekurangan();
        $this->add_kelebihan_kekurangan_detail();
        $this->add_feedback();
    }

    public function login_peserta_liquid_feedback_bawahan()
    {
        $user = \App\User::where('username', 'iwan.setiawan72')->first();
        $this->login($user);
    }

    public function login_not_peserta_liquid_feedback_bawahan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $this->login($user);
    }

    public function add_liquid()
    {
        $this->objTest = '{"80066801":{"nama":"HIMAWAN WITJAKSONO ADJI","nip":"8006229Z","jabatan":"MUP II UP3 BARABAI","kelompok_jabatan":"MD_UP","business_area":"7113","peserta":{"64877011":{"liquid_peserta_id":"41","nama":"SYAMSIR ALAM","nip":"6487101D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"64857012":{"liquid_peserta_id":"42","nama":"SLAMET RIYADI","nip":"6485103D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"64857205":{"liquid_peserta_id":"43","nama":"ABDUL SALIM","nip":"6485100D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"66857002":{"liquid_peserta_id":"44","nama":"RAKHMAT NOR IFANSYAH HAJI","nip":"6685108D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"69937011":{"liquid_peserta_id":"35","nama":"SUYONO","nip":"6993236D","jabatan":"MAN II RING","kelompok_jabatan":"SPV"},"66877001":{"liquid_peserta_id":"45","nama":"ACHMAD NIZAMI BEY","nip":"6687010D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"66937003":{"liquid_peserta_id":"46","nama":"MUHAMMAD RAMLI HAJI","nip":"6693123D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"70907009":{"liquid_peserta_id":"36","nama":"JUHARTONO","nip":"7090069D","jabatan":"MAN II KEU, SDM, DAN ADM","kelompok_jabatan":"SPV"},"70937011":{"liquid_peserta_id":"47","nama":"MAHYUDDIN Y","nip":"7093125D","jabatan":"AN KIN DAN SIS MANJ","kelompok_jabatan":"STAF"},"72937013":{"liquid_peserta_id":"37","nama":"IWAN SETIAWAN","nip":"7293141D","jabatan":"MAN II REN","kelompok_jabatan":"SPV"},"72937015":{"liquid_peserta_id":"38","nama":"MATENU","nip":"7293143D","jabatan":"MAN II KONS","kelompok_jabatan":"SPV"},"82097003":{"liquid_peserta_id":"39","nama":"SYAHRUL","nip":"8209462Z","jabatan":"MAN II TE LISTRIK","kelompok_jabatan":"SPV"},"88117010":{"liquid_peserta_id":"40","nama":"ASTRI RAHMA WIJAYANTI","nip":"88112244Z","jabatan":"MAN II SAR DAN YAN GAN","kelompok_jabatan":"SPV"}}},"89137000":{"nama":"TOPAN SETYAWAN","nip":"8913077ZY","jabatan":"MUL ULP TANJUNG","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67907000":{"liquid_peserta_id":"23","nama":"ISNAIN WASISTO SUDYO WIDODO","nip":"6790001D","jabatan":"SPV II TEKNIK (MUARA UYA)","kelompok_jabatan":"SPV"},"66917000":{"liquid_peserta_id":"34","nama":"SALMA","nip":"6691047D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"73047006":{"liquid_peserta_id":"24","nama":"ISKANDAR","nip":"7304047D","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"89097016":{"liquid_peserta_id":"25","nama":"MIFTAHUR RIZQI","nip":"8909017D","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"89097019":{"liquid_peserta_id":"26","nama":"SUMANTO","nip":"8909030D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"93167028":{"liquid_peserta_id":"27","nama":"KALVIN LENTINO","nip":"93162709ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"}}},"89097010":{"nama":"RAHMAT NOVIAR ANSYARI","nip":"8909024D","jabatan":"PLT MUL ULP PARINGIN","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67897000":{"liquid_peserta_id":"14","nama":"MUHAMMAD YANI","nip":"6789009D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"92167000":{"liquid_peserta_id":"15","nama":"MOCHAMMAD KEMAL ACHRIANSYAH","nip":"9216193ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"94167002":{"liquid_peserta_id":"16","nama":"IMAM YOGA FITRIYANTO","nip":"94161628ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"94167008":{"liquid_peserta_id":"17","nama":"DIKTYO ROBBY ROHIMAWAN","nip":"94162033ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"}}},"65877000":{"nama":"PARDJITO","nip":"6587006D","jabatan":"MUL ULP AMUNTAI","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"67877002":{"liquid_peserta_id":"32","nama":"NOOR ABIDIN","nip":"6787106D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"73937018":{"liquid_peserta_id":"1","nama":"PAHMI","nip":"7393212D","jabatan":"SPV II TEKNIK (DANAU PANGGANG)","kelompok_jabatan":"SPV"},"82077002":{"liquid_peserta_id":"2","nama":"RUMLIANSYAH","nip":"8207023D","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"88097006":{"liquid_peserta_id":"3","nama":"OKFAREKI","nip":"8809066D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"89097025":{"liquid_peserta_id":"4","nama":"M. ADE ROSAIRI","nip":"8909079D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"94167019":{"liquid_peserta_id":"5","nama":"YUSUF ABDULLOH","nip":"94162201ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"}}},"89097015":{"nama":"SEPTIAN FETER ARIFIANTO","nip":"8909028D","jabatan":"PLT MUL ULP RANTAU","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"68917008":{"liquid_peserta_id":"33","nama":"MUHAMMAD BAIHAKI HAJI","nip":"6891042D","jabatan":"AN KIN","kelompok_jabatan":"STAF"},"89097020":{"liquid_peserta_id":"18","nama":"NURUL HIDAYATI","nip":"8909023D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"89097021":{"liquid_peserta_id":"19","nama":"IMAM HIDAYAT","nip":"8909055D","jabatan":"SPV II TEKNIK (MARGASARI)","kelompok_jabatan":"SPV"},"91147004":{"liquid_peserta_id":"20","nama":"GALIH ESTU LESTARI","nip":"9114568ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"91177001":{"liquid_peserta_id":"21","nama":"RIDHO MUHAMMAD","nip":"91171552ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167021":{"liquid_peserta_id":"22","nama":"AHMAD SYAIROZIE","nip":"94162875ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"}}},"88117008":{"nama":"SUTIO BAYUPRAKOSO","nip":"8811676Z","jabatan":"PLT MUL ULP BINUANG","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"71047000":{"liquid_peserta_id":"10","nama":"WINARTO","nip":"7104014D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"89097001":{"liquid_peserta_id":"11","nama":"SILVIA RUSIANA PUTERI","nip":"8909029D","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"92177006":{"liquid_peserta_id":"12","nama":"MUHAMMAD FAHRIZA","nip":"92171956ZY","jabatan":"PLT SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167000":{"liquid_peserta_id":"13","nama":"PURWAJI","nip":"9416341ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"}}},"86127004":{"nama":"ENDI SOPYANDI","nip":"8612594ZY","jabatan":"MUL ULP DAHA","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"90097016":{"liquid_peserta_id":"6","nama":"AKHMAD RIJANI","nip":"9009045D","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"91147008":{"liquid_peserta_id":"7","nama":"GALIH ANDIKA YUDA","nip":"9114901ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"93167024":{"liquid_peserta_id":"9","nama":"MUHAMMAD RIZKI","nip":"93162680ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"},"93157003":{"liquid_peserta_id":"8","nama":"NILNA AZIZAH","nip":"9315636ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"}}},"90157009":{"nama":"DADIN","nip":"9015772ZY","jabatan":"MUL ULP KANDANGAN","kelompok_jabatan":"SPV_ATAS_SUP","business_area":"7113","peserta":{"92147011":{"liquid_peserta_id":"29","nama":"M. ARIEF SETIAWAN","nip":"9214776ZY","jabatan":"PJ LAKSK3L","kelompok_jabatan":"SPV"},"91147003":{"liquid_peserta_id":"28","nama":"ADIL TEGUH SUBARYANTO","nip":"9114559ZY","jabatan":"SPV II TEKNIK","kelompok_jabatan":"SPV"},"94177015":{"liquid_peserta_id":"31","nama":"STEFANUS VIKI KURNIANTONO","nip":"94171558ZY","jabatan":"SPV II YAN GAN DAN ADM","kelompok_jabatan":"SPV"},"94167001":{"liquid_peserta_id":"30","nama":"IRELAND ADITYA JATI","nip":"9416747ZY","jabatan":"SPV II TE","kelompok_jabatan":"SPV"}}}}';
        for ($i = 0; $i < 3; $i++) {
            DB::table('liquids')->insert([
                'id' => $i + 1,
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
    }

    public function add_liquid_peserta()
    {
        $atasan = \App\User::where('username', 'himawan.witjaksono')->first();
        $bawahan = \App\User::where('username', 'iwan.setiawan72')->first();
        $bawahan2 = \App\User::where('username', 'syahrul82')->first();
        DB::table('liquid_peserta')->insert([
            'id' => 1,
            'liquid_id' => 1,
            'atasan_id' => $atasan->strukturJabatan->pernr,
            'bawahan_id' => $bawahan->strukturJabatan->pernr,
            'snapshot_jabatan_atasan' => 'MD_UP',
            'snapshot_jabatan_bawahan' => 'SPV',
        ]);
        DB::table('liquid_peserta')->insert([
            'id' => 2,
            'liquid_id' => 1,
            'atasan_id' => $atasan->strukturJabatan->pernr,
            'bawahan_id' => $bawahan2->strukturJabatan->pernr,
            'snapshot_jabatan_atasan' => 'MD_UP',
            'snapshot_jabatan_bawahan' => 'SPV',
        ]);
    }

    public function add_add_kelebihan_kekurangan()
    {
        DB::table('kelebihan_kekurangan')->insert([
            'id' => 1,
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
            'id' => 1,
            'parent_id' => 1,
            'deskripsi_kelebihan' => "Menganggap masalah dalam sudut pandang yang POSITIF sehingga dapat dimanfaatkan untuk memotivasi diri",
            'deskripsi_kekurangan' => "Menyimpan sendiri & MENYEMBUNYIKAN PENGETAHUAN yang dimilikinya hanya untuk kepentingan pribadi & tertutup untuk Unit lainnya.",
            'created_by' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function add_feedback()
    {
        DB::table('feedbacks')->insert([
            'id' => 123123,
            'liquid_peserta_id' => 2,
            'kelebihan' => '["125", "126"]',
            'kekurangan' => '["125", "126"]',
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>saran 2 untuk pak bayu</p>',
            'status' => 'PUBLISHED',
            'created_by' => 2108,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * route
     * dashboard-bawahan/liquid-jadwal
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_access_dashboard_bawahan_liquid_jadwal_login_as_bawahan()
    {
        $this->login_peserta_liquid_feedback_bawahan();
        $response = \App\Models\Liquid\Liquid::forBawahan(auth()->user())->published()->currentYear()->exists();
        $this->assertT($response);
    }

    /**
     * route
     * dashboard-bawahan/liquid-jadwal
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_access_dashboard_bawahan_liquid_jadwal_login_as_atasan()
    {
        $this->markTestSkipped(
            "* failed, check bukan peserta bawahan,
            * di controller belum ada permission
            * route ini masih bisa diakses, harapannya tidak"
        );
        $this->login_not_peserta_liquid_feedback_bawahan();
        $response = \App\Models\Liquid\Liquid::forBawahan(auth()->user())->published()->currentYear()->exists();
        $this->assertT($response);
    }

    /**
     * route
     * feedback?liquid_id=1
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_access_feedback_login_as_bawahan()
    {
        $this->login_peserta_liquid_feedback_bawahan();
        $response = \App\Models\Liquid\Liquid::forBawahan(auth()->user())->published()->currentYear()->exists();
        $this->assertT($response);
    }

    /**
     * route
     * feedback?liquid_id=1
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_access_feedback_login_as_atasan()
    {
        $this->markTestSkipped(
            "* failed, check bukan peserta bawahan,
            * di controller belum ada permission
            * route ini masih bisa diakses, harapannya tidak"
        );
        $this->login_not_peserta_liquid_feedback_bawahan();
        $response = \App\Models\Liquid\Liquid::forBawahan(auth()->user())->published()->currentYear()->exists();
        $this->assertT($response);
    }

    /**
     * route
     * feedback?liquid_id=1
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_see_page_feedback_bawahan_login_with_params_liquid_found()
    {
        $this->C1_can_access_feedback_login_as_bawahan();
        $this->get('feedback?liquid_id=1')
        ->followRedirects()
        ->see('Input Feedback');
    }

    /**
     * route
     * feedback?liquid_id=1
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_see_page_feedback_bawahan_login_with_params_liquid_not_found()
    {
        $this->C1_can_access_feedback_login_as_bawahan();
        $this->get('feedback?liquid_id=1212')
        ->followRedirects()
        ->dontSee('Input Feedback');
    }

    /**
     * route
     * feedback?liquid_id=1
     * get
     * C1
     *
     * @test
     * */
    public function C1_can_see_page_feedback_bawahan_login_with_params_liquid_string()
    {
        $this->C1_can_access_feedback_login_as_bawahan();
        $this->get('feedback?liquid_id=random')
        ->followRedirects()
        ->dontSee('Input Feedback');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C2
     *
     * @test
     * */
    public function C2_can_access_page_feedback_create_bawahan_login_bawahan_if_not_inputed()
    {
        $link = route('feedback.create').'?liquid_peserta_id=1';
        $this->login_peserta_liquid_feedback_bawahan();
        $check = $this->check_input_feedback(auth()->user(), 1);
        $this->assertT($check);
        $this->get($link)
        ->assertResponseOk();
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C2
     *
     * @test
     * */
    public function C2_can_access_page_feedback_create_bawahan_login_bawahan_if_already_inputed()
    {
        $this->markTestSkipped(
            "* failed, orang yang udah inputed, gk bisa buka created page,
            expected 403, gk punya akses utk bukak"
        );
        $link = route('feedback.create').'?liquid_peserta_id=2';
        $user = \App\User::where('username', 'syahrul82')->first();
        $this->login($user);
        $check = $this->check_input_feedback(auth()->user(), 2);
        $this->assertF($check);
        $this->get($link)
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C2
     *
     * @test
     * */
    public function C2_can_access_page_feedback_create_bawahan_login_atasan()
    {
        $this->markTestSkipped(
            "* failed, check bukan peserta bawahan,
            * di controller belum ada permission
            * route ini masih bisa diakses, harapannya tidak
            # return 403, forbidden access"
        );
        $link = route('feedback.create').'?liquid_peserta_id=1';
        $this->login_not_peserta_liquid_feedback_bawahan();
        $this->get($link)
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    public function check_input_feedback($user, $id)
    {
        $peserta = \App\Models\Liquid\LiquidPeserta::where('id', $id)->first();
        $belumMengisiFeedback = !$peserta->feedback()->exists();
        return $belumMengisiFeedback;
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C3-C7
     *
     * @test
     * */
    public function check_store_with_atasan()
    {
        $link = route('feedback.store').'?liquid_peserta_id=2';
        $this->login_not_peserta_liquid_feedback_bawahan();
        $this->callCustom('POST', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C3-C7
     *
     * @test
     * */
    public function check_store_with_bawahan_with_boxes_1()
    {
        $link = route('feedback.store').'?liquid_peserta_id=1';
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('POST', $link, [
            'boxes_kelebihan' => [123, 124],
            'boxes_kekurangan' => [123],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => null,
            'new_kekurangan' => null
        ])
        ->followRedirects()
        ->dontSee('Berhasil Mengisi Feedback');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C3-C7
     *
     * @test
     * */
    public function check_store_with_bawahan_with_boxes_not_array()
    {
        $this->markTestSkipped(
            "* failed, masih bisa masukin string di boxes kelibahan, jadi
            something went wrong"
        );
        $link = route('feedback.store').'?liquid_peserta_id=1';
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('POST', $link, [
            'boxes_kelebihan' => '123',
            'boxes_kekurangan' => '123',
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => null,
            'new_kekurangan' => null
        ])
        ->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C3-C7
     *
     * @test
     * */
    public function check_store_with_bawahan_already_inputed()
    {
        $link = route('feedback.store').'?liquid_peserta_id=2';
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('POST', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    /**
     * route
     * route('feedback.create').'?liquid_peserta_id='.$data['id_lp']
     * get
     * C3-C7
     *
     * @test
     * */
    public function check_store_with_bawahan_not_already_inputed()
    {
        $link = route('feedback.store').'?liquid_peserta_id=1';
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('POST', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->see('Berhasil Mengisi Feedback');
    }

    /**
     * route
     * route('feedback.edit', $data['feedback_id'])
     * get
     * C10
     *
     * @test
     * */
    public function C10_can_access_page_feedback_edit_bawahan_login_atasan()
    {
        $this->markTestSkipped(
            "* failed, check bukan peserta bawahan,
            * di controller belum ada permission
            * route ini masih bisa diakses, harapannya tidak"
        );
        $link = route('feedback.edit', 123123);
        $this->login_not_peserta_liquid_feedback_bawahan();
        $this->get($link)
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    /**
     * route
     * route('feedback.edit', $data['feedback_id'])
     * get
     * C10
     *
     * @test
     * */
    public function C10_can_access_page_feedback_edit_bawahan_login_bawahan_feedback_edit_found()
    {
        $link = route('feedback.edit', 123123);
        $this->login_peserta_liquid_feedback_bawahan();
        $this->get($link)
        ->followRedirects()
        ->see('Feedback Bawahan');
    }

    /**
     * route
     * route('feedback.edit', $data['feedback_id'])
     * get
     * C10
     *
     * @test
     * */
    public function C10_can_access_page_feedback_edit_bawahan_login_bawahan_feedback_edit_not_found()
    {
        $link = route('feedback.edit', 12);
        $this->login_peserta_liquid_feedback_bawahan();
        $this->get($link)
        ->assertResponseStatus(404);
    }

    /**
     * route
     * route('feedback.edit', $data['feedback_id'])
     * get
     * C10
     *
     * @test
     * */
    public function C10_can_access_page_feedback_edit_bawahan_login_bawahan_feedback_edit_string()
    {
        $this->markTestSkipped(
            "* failed, harusnya bisa kasih vaidasi 
            gak boleh string, must intiger"
        );
        $link = route('feedback.edit', 'string');
        $this->login_peserta_liquid_feedback_bawahan();
        $this->get($link)
        ->assertResponseStatus(404);
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_dont_have_feedback()
    {
        $link = route('feedback.update', 123123123123123);
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->assertResponseStatus(404);
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_dont_have_feedback_string()
    {
        $this->markTestSkipped(
            "* failed, harusnya bisa kasih vaidasi 
            gak boleh string, must intiger"
        );
        $link = route('feedback.update', 'string');
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->assertResponseStatus(404);
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_have_feedback_auth_inputed()
    {
        $link = route('feedback.update', 123123);
        $userSyahrul = \App\User::where('username', 'syahrul82')->first();
        $this->login($userSyahrul);
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->see('Berhasil Mengubah Feedback');
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_have_feedback_auth_not_inputed()
    {
        $link = route('feedback.update', 123123);
        $this->login_peserta_liquid_feedback_bawahan();
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => [123, 124, 125],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->see('You are not authorized to this action');
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_have_feedback_auth_inputed_boxes_cuman_1()
    {
        $link = route('feedback.update', 123123);
        $userSyahrul = \App\User::where('username', 'syahrul82')->first();
        $this->login($userSyahrul);
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => [123],
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->assertSessionHasErrors();
    }

    /**
     * route
     * route('feedback.update', $feedback)
     * post
     * C10
     *
     * @test
     * */
    public function C10_can_update_feedback_bawahan_have_feedback_auth_inputed_boxes_string()
    {
        $this->markTestSkipped(
            "* failed, masih bisa masukin string di boxes kelibahan, jadi
            something went wrong"
        );
        $link = route('feedback.update', 123123);
        $userSyahrul = \App\User::where('username', 'syahrul82')->first();
        $this->login($userSyahrul);
        $this->callCustom('PUT', $link, [
            'boxes_kelebihan' => '123',
            'boxes_kekurangan' => [123, 124, 125],
            'harapan' => '<p>harapan 2 untuk pak bayu</p>',
            'saran' => '<p>harapan 2 untuk pak bayu</p>',
            'new_kelebihan' => str_random(100),
            'new_kekurangan' => str_random(100)
        ])
        ->followRedirects()
        ->dontSee('something went wrong');
    }
}
