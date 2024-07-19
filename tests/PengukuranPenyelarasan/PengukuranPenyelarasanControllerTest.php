<?php

use App\Models\Liquid\Liquid;
use App\Services\PengukuranKeduaService;
use App\Services\PengukuranPertamaService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

/**
 * @group pengukuranpenyelarasan
 */
class PengukuranPenyelarasanControllerTest extends TestCase
{
    use TestHelper;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->emptyLiquidTables();
        $this->add_liquid();
        $this->add_liquid_peserta();
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

    public function add_liquid_peserta()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $user2 = \App\User::where('username', 'iwan.setiawan72')->first();
        DB::table('liquid_peserta')->insert([
            'id' => 100,
            'liquid_id' => 1,
            'atasan_id' => $user->strukturJabatan->pernr,
            'bawahan_id' => $user2->strukturJabatan->pernr,
            'snapshot_jabatan_atasan' => 'MD_UP',
            'snapshot_jabatan_bawahan' => 'SPV',
        ]);
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

    public function add_add_kelebihan_kekurangan()
    {
        DB::table('kelebihan_kekurangan')->insert([
            'id' => 44,
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
            'parent_id' => 44,
            'deskripsi_kelebihan' => "Menganggap masalah dalam sudut pandang yang POSITIF sehingga dapat dimanfaatkan untuk memotivasi diri",
            'deskripsi_kekurangan' => "Menyimpan sendiri & MENYEMBUNYIKAN PENGETAHUAN yang dimilikinya hanya untuk kepentingan pribadi & tertutup untuk Unit lainnya.",
            'created_by' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function login_as_iwan()
    {
        $user = \App\User::where('username', 'iwan.setiawan72')->first();
        $this->login($user);
    }

    public function login_as_fahmi()
    {
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $this->login($user);
    }

    public function login_as_himawan()
    {
        $user = \App\User::where('username', 'himawan.witjaksono')->first();
        $this->login($user);
    }

    /**
     * route
     * dashboard-bawahan/liquid-jadwal
     * get
     * D1
     *
     * @test
     * */
    public function D1_can_get_dashboard_bawahan_liquid_jadwal_with_fahmi()
    {
        $this->markTestSkipped(
            "harusnya fahmi tidak bisa akses, 
            karena tidak punya bawahan dan return 403"
        );
        $this->login_as_fahmi();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertF($bol);
        $this->callCustom('GET', 'dashboard-bawahan/liquid-jadwal')
        ->assertResponseStatus(403);
    }

    /**
     * route
     * dashboard-bawahan/liquid-jadwal
     * get
     * D1
     *
     * @test
     * */
    public function D1_can_get_dashboard_bawahan_liquid_jadwal_with_iwan()
    {
        $this->login_as_iwan();
        $user = \App\User::where('username', 'iwan.setiawan72')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertT($bol);
        $this->callCustom('GET', 'dashboard-bawahan/liquid-jadwal')
            ->assertViewHasAll(['btnActive', 'jadwalLiquid']);
    }

    /**
     * route
     * penilaian?liquid_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_liquid_id_as_fahmi()
    {
        $this->markTestSkipped(
            "harusnya fahmi tidak bisa akses, 
            karena tidak punya bawahan dan return 403"
        );

        $this->login_as_fahmi();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertF($bol);
        $this->callCustom('GET', 'penilaian?liquid_id=1')
        ->assertResponseStatus(403);
    }

    /**
     * route
     * penilaian?liquid_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_liquid_id_as_iwan()
    {
        $this->login_as_iwan();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertT($bol);
    }

    /**
     * route
     * penilaian?liquid_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_liquid_id_as_iwan_with_liquid_string()
    {
        $this->D2_can_access_penilaian_liquid_id_as_iwan();
        $this->get('penilaian?liquid_id=awwwwww')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * penilaian?liquid_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_liquid_id_as_iwan_with_liquid_not_found()
    {
        $this->D2_can_access_penilaian_liquid_id_as_iwan();
        $this->get('penilaian?liquid_id=1232131231')
        ->assertResponseStatus(404);
    }

    /**
     * route
     * penilaian?liquid_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_liquid_id_as_iwan_with_liquid_found()
    {
        $this->D2_can_access_penilaian_liquid_id_as_iwan();
        $this->get('penilaian?liquid_id=1')
        ->followRedirects()
        ->see('Pengukuran Pertama');
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_fahmi()
    {
        $this->markTestSkipped(
            "harusnya fahmi tidak bisa akses, 
            karena tidak punya bawahan dan return 403"
        );

        $this->login_as_fahmi();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertF($bol);
        $this->callCustom('GET', 'penilaian/create?liquid_peserta_id=100')
        ->assertResponseStatus(403);
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_iwan()
    {
        $this->login_as_iwan();
        $user = \App\User::where('username', 'iwan.setiawan72')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertT($bol);
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_iwan_with_id_not_found()
    {
        $this->markTestSkipped(
            "belom ada validasi kalau gk ketemu liquid_peserta"
        );

        $this->D2_can_access_penilaian_create_liquid_peserta_id_as_iwan();
        $this->get('penilaian/create?liquid_peserta_id=101')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_iwan_with_id_string()
    {
        $this->markTestSkipped(
            "belom ada validasi liquid id harus int"
        );

        $this->D2_can_access_penilaian_create_liquid_peserta_id_as_iwan();
        $this->get('penilaian/create?liquid_peserta_id=101')
            ->assertResponseStatus(404);
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_iwan_with_id_found_dont_have_penyelarasan()
    {
        $this->markTestSkipped(
            "dataResolusi->resolusi bikin error kalau dataResolusi kosong"
        );
        $this->D2_can_access_penilaian_create_liquid_peserta_id_as_iwan();
        $this->get('penilaian/create?liquid_peserta_id=100')
            ->followRedirects()
            ->dontSee('something went wrong');
    }

    /**
     * route
     * penilaian/create?liquid_peserta_id=
     * get
     * D2
     *
     * @test
     * */
    public function D2_can_access_penilaian_create_liquid_peserta_id_as_iwan_with_id_found_have_penyelarasan()
    {
        $this->add_penyelarasan_10_by_id_himawan();
        $this->add_add_kelebihan_kekurangan();
        $this->add_kelebihan_kekurangan_detail();
        $this->D2_can_access_penilaian_create_liquid_peserta_id_as_iwan();
        $this->get('penilaian/create?liquid_peserta_id=100')
            ->followRedirects()
            ->see('Penilaian Atasan');
    }

    /**
     * route
     * penilaian/store?liquid_peserta_id=
     * store
     * D2
     *
     * @test
     * */
    public function D2_store_penilaian_create_liquid_peserta_id_as_iwan_with_positive_case()
    {
        //100
        $link = route('penilaian.store').'?liquid_peserta_id=100';
        $this->D2_can_access_penilaian_create_liquid_peserta_id_as_iwan_with_id_found_have_penyelarasan();
        $this->callCustom('POST', $link, [
            'resolusi_1' => '10',
            'resolusi_alasan_1' => 'alasan test',
        ])->followRedirects()
        ->see('Berhasil memberikan penilaian pertama');
    }

    /**
     * route
     * penilaian/show?liquid_peserta_id=
     * get
     * D3
     *
     * @test
     * */
    public function D3_get_show_penilaian_create_liquid_peserta_id_as_iwan_positive_input()
    {
        $link = route('penilaian.show', 10);
        $this->D2_store_penilaian_create_liquid_peserta_id_as_iwan_with_positive_case();
        $this->get('penilaian?liquid_id=1')
        ->followRedirects()
        ->see('Show Pengukuran Pertama');
        $liquid = Liquid::findOrFail(1);
        $dataAtasan = app(PengukuranPertamaService::class)->index(auth()->user(), $liquid);
        foreach ($dataAtasan as $data) {
            $id = $data['pengukuran_pertama']['id'];
            $link = route('penilaian.show', $id);
        }
        $this->callCustom('GET', $link)
        ->followRedirects()
        ->see('Penilaian Atasan');
    }

    /**
     * route
     * penilaian/edit?liquid_peserta_id=
     * get
     * D4
     *
     * @test
     * */
    public function D4_get_show_penilaian_edit_liquid_peserta_id_as_iwan_positive_input()
    {
        $link2 = route('penilaian.edit', 10);
        $this->D2_store_penilaian_create_liquid_peserta_id_as_iwan_with_positive_case();
        $this->get('penilaian?liquid_id=1')
        ->followRedirects()
        ->see('Show Pengukuran Pertama');
        $liquid = Liquid::findOrFail(1);
        $dataAtasan = app(PengukuranPertamaService::class)->index(auth()->user(), $liquid);
        foreach ($dataAtasan as $data) {
            $id = $data['pengukuran_pertama']['id'];
            $link2 = route('penilaian.edit', $id);
        }
        $this->callCustom('GET', $link2)
        ->followRedirects()
        ->see('Penilaian Atasan');
    }

    /**
     * route
     * PUT
     * D4
     *
     * @test
     * */
    public function D4_put_penilaian_edit_liquid_peserta_id_as_iwan_positive_input()
    {
        $this->D4_get_show_penilaian_edit_liquid_peserta_id_as_iwan_positive_input();
        $id = 12;
        $liquid = Liquid::findOrFail(1);
        $dataAtasan = app(PengukuranPertamaService::class)->index(auth()->user(), $liquid);
        foreach ($dataAtasan as $data) {
            $id = $data['pengukuran_pertama']['id'];
        }
        $link = route('penilaian.update', $id);
        $this->callCustom('PUT', $link, [
            'resolusi_1' => '10',
            'resolusi_alasan_1' => 'alasan test',
        ])->followRedirects()
        ->see('Data Pengukuran Pertama Berhasil Diubah');
    }

    //pengukuran-kedua
    /**
     * route
     * pengukuran-kedua
     * get
     * E1
     *
     * @test
     * */
    public function E1_can_access_penilaian_liquid_id_as_fahmi()
    {
        $this->markTestSkipped(
            "harusnya fahmi tidak bisa akses, 
            karena tidak punya bawahan dan return 403"
        );

        $this->login_as_fahmi();
        $user = \App\User::where('username', 'm.fahmi.rizal')->first();
        $arr = $user->dashboardMenu();
        $bol = false;
        foreach ($arr as $v) {
            if ($v === \App\Enum\LiquidMenuEnum::BAWAHAN) {
                $bol = true;
            }
        }
        $this->assertF($bol);
        $this->callCustom('GET', 'pengukuran-kedua')
        ->assertResponseStatus(403);
    }

    /**
     * route
     * pengukuran_kedua
     * get
     * E1
     *
     * @test
     * */
    public function E1_can_access_pengukuran_kedua_as_iwan_with_positive_input()
    {
        $this->D2_can_access_penilaian_liquid_id_as_iwan();
        $this->get('pengukuran-kedua')
        ->followRedirects()
        ->see('Pengukuran Kedua');
    }

    /**
     * route
     * pengukuran_kedua
     * get
     * E1
     *
     * @test
     * */
    public function E1_can_access_pengukuran_kedua_as_iwan_click_input_pengukuran_kedua_negative_case()
    {
        //liquid_peserta_id=100
        $link = route('pengukuran-kedua.create').'?liquid_peserta_id=1000';
        $this->E1_can_access_pengukuran_kedua_as_iwan_with_positive_input();
        $this->get($link)
        ->followRedirects()
        ->dontSee('Pengukuran kedua merupakan evaluasi dari sikap atasan setelah melakukan activity log book.
        Pengukuran ini akan mengevaluasi apakah sikap atasan mengalami peningkatan, sama, atau menurun
        dari pengukuran pertama. Pengukuran menggunakan Skala Likert dengan keterangan sebagai berikut');
    }

    /**
     * route
     * pengukuran_kedua
     * get
     * E1
     *
     * @test
     * */
    public function E1_can_access_pengukuran_kedua_as_iwan_click_input_pengukuran_kedua_penyelarasan_belom_ada()
    {
        //liquid_peserta_id=100
        $this->markTestSkipped(
            "penyelarasan belom ada, jadi get object null, so something went wrong appear"
        );
        $link = route('pengukuran-kedua.create').'?liquid_peserta_id=100';
        $this->E1_can_access_pengukuran_kedua_as_iwan_with_positive_input();
        $this->get($link)
        ->followRedirects()
        ->see('Silahkan beri penilaian pada atasan anda berdasarkan 3 indikator sikap berikut!');
    }

    /**
     * route
     * pengukuran_kedua
     * get
     * E1
     *
     * @test
     * */
    public function E1_can_access_pengukuran_kedua_as_iwan_click_input_pengukuran_kedua_positive_case()
    {
        $this->add_penyelarasan_10_by_id_himawan();
        $this->add_add_kelebihan_kekurangan();
        $this->add_kelebihan_kekurangan_detail();
        $link = route('pengukuran-kedua.create').'?liquid_peserta_id=100';
        $this->E1_can_access_pengukuran_kedua_as_iwan_with_positive_input();
        $this->get($link)
        ->followRedirects()
        ->see('Silahkan beri penilaian pada atasan anda berdasarkan 3 indikator sikap berikut!');
    }

    /**
     * route
     * penilaian/store?liquid_peserta_id=
     * store
     * E2
     *
     * @test
     * */
    public function E2_store_pengukuran_kedua_as_iwan_belom_ada_penyelarasan_di_liquid()
    {
        $this->markTestSkipped(
            "kalau penyelarasan gk ada maka something went wrong appear"
        );
        $link = route('pengukuran-kedua.store').'?liquid_peserta_id=100';
        $this->login_as_iwan();
        // $this->E1_can_access_pengukuran_kedua_as_iwan_click_input_pengukuran_kedua_positive_case();
        $this->callCustom('POST', $link, [
            'resolusi_1' => 'RENDAH_SEKALI',
            'resolusi_alasan_1' => 'alasan test',
        ])->followRedirects()
        ->dontSee('something went wrong');
    }

    /**
     * route
     * penilaian/store?liquid_peserta_id=
     * store
     * E2
     *
     * @test
     * */
    public function E2_store_pengukuran_kedua_as_iwan_with_positive_case()
    {
        //100
        $link = route('pengukuran-kedua.store').'?liquid_peserta_id=100';
        $this->E1_can_access_pengukuran_kedua_as_iwan_click_input_pengukuran_kedua_positive_case();
        $this->callCustom('POST', $link, [
            'resolusi_1' => 'RENDAH_SEKALI',
            'resolusi_alasan_1' => 'alasan test',
        ])->followRedirects()
        ->see('Berhasil memberikan penilaian kedua');
    }

    /**
     * route
     * get
     * E3
     *
     * @test
     * */
    public function E3_get_update_pengukuran_kedua_as_iwan()
    {
        $this->markTestSkipped(
            "karena dataAtasan bentuk nya array bukan collection object, jadi di view error"
        );
        $link2 = route('pengukuran-kedua.edit', 10);
        $this->E2_store_pengukuran_kedua_as_iwan_with_positive_case();
        $liquid = Liquid::query()->forBawahan(auth()->user())->currentYear()->published()->firstOrFail();
        $dataAtasan = app(PengukuranKeduaService::class)->index(auth()->user(), $liquid);
        foreach ($dataAtasan as $data) {
            $id = $data['pengukuran_kedua']['id'];
            $link2 = route('pengukuran-kedua.edit', $id);
        }
        $this->callCustom('GET', $link2)
        ->followRedirects()
        ->see('Penilaian Atasan');
    }

    //penyelarasan
    public function penyelarasan_setting()
    {
        $this->skipAuthorization();
        $this->add_penyelarasan_10_by_id_himawan();
        $this->add_add_kelebihan_kekurangan();
        $this->add_kelebihan_kekurangan_detail();
    }

    /**
     * route
     * get
     * F1
     *
     * @test
     * */
    public function F1_get_penyelerasan_login_as_himawan_with_negative_case()
    {
        $this->markTestSkipped(
            "find or fail nya gk bisa nerima string, di db cuman maunya int"
        );
        $this->penyelarasan_setting();
        $this->login_as_himawan();
        $this->get('penyelarasan?liquid_id=random')
            ->followRedirects()
            ->dontSee('something went wrong');
    }

    /**
     * route
     * get
     * F1
     *
     * @test
     * */
    public function F1_get_penyelerasan_login_as_himawan_with_positive_case()
    {
        $this->penyelarasan_setting();
        $this->login_as_himawan();
        $this->get('penyelarasan?liquid_id=1')
            ->followRedirects()
            ->see('Tabel Info Penyelarasan');
    }

    /**
     * route
     * get
     * F1
     *
     * @test
     * */
    public function F1_get_create_penyelerasan_login_as_himawan_with_positive_case()
    {
        $link = route('penyelarasan.create', ['liquid_id' => 1]);
        $this->F1_get_penyelerasan_login_as_himawan_with_positive_case();
        $this->get($link)
            ->followRedirects()
            ->see('Input Penyelarasan');
    }

    /**
     * route
     * store
     * F1
     *
     * @test
     * */
    public function F1_post_store_create_penyelerasan_login_as_himawan_with_positive_case()
    {
        $link = route('penyelarasan.store').'?liquid_id=1';
        $this->F1_get_create_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('POST', $link, [
            'date_start' => '06-06-2020',
            'date_end' => '08-06-2020',
            'tempat_kegiatan' => 'tes tempat kegiatan',
            'deskripsi' => 'tes deskripsi',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->followRedirects()
        ->see('Berhasil Menambahkan Data Penyelarasan');
    }

    /**
     * route
     * store
     * F2
     *
     * @test
     * */
    public function F2_post_store_create_penyelerasan_login_as_himawan_with_tanggal_ngasal()
    {
        $this->markTestSkipped(
            "date nya cuman required doang, belom ada validasi date,
            kemudian ada carbon parse, itu yang nyebab in error"
        );
        $link = route('penyelarasan.store').'?liquid_id=1';
        $this->F1_get_create_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('POST', $link, [
            'date_start' => 'ngasal',
            'date_end' => 'ngasal',
            'tempat_kegiatan' => 'tes tempat kegiatan',
            'deskripsi' => 'tes deskripsi',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->assertSessionHasErrors();
    }

    /**
     * route
     * store
     * F2
     *
     * @test
     * */
    public function F2_post_store_create_penyelerasan_login_as_himawan_with_null_inputan()
    {
        $link = route('penyelarasan.store').'?liquid_id=1';
        $this->F1_get_create_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('POST', $link, [
            'date_start' => '',
            'date_end' => '',
            'tempat_kegiatan' => '',
            'deskripsi' => '',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->assertSessionHasErrors();
    }

    /**
     * route
     * get
     * F3
     *
     * @test
     * */
    public function F3_get_penyelerasan_login_as_himawan_with_positive_case()
    {
        $this->F1_get_penyelerasan_login_as_himawan_with_positive_case();
    }

    /**
     * route
     * get
     * F7
     *
     * @test
     * */
    public function F7_get_edit_penyelerasan_login_as_himawan_with_positive_case()
    {
        $link = route('penyelarasan.edit', 1);
        $this->F1_get_penyelerasan_login_as_himawan_with_positive_case();
        $this->get($link)
            ->followRedirects()
            ->see('Input Penyelarasan');
    }

    /**
     * route
     * get
     * F7
     *
     * @test
     * */
    public function F7_get_edit_penyelerasan_login_as_himawan_with_negative_case()
    {
        $this->markTestSkipped(
            "findOrFail nya belom bsa handle string, di db gk nerima string, 
            something went wrong appeaer"
        );
        $link = route('penyelarasan.edit', 'string');
        $this->F1_get_penyelerasan_login_as_himawan_with_positive_case();
        $this->get($link)
            ->followRedirects()
            ->dontSee('something went wrong');
    }

    /**
     * route
     * get
     * F7
     *
     * @test
     * */
    public function F7_put_update_penyelerasan_login_as_himawan_with_positive_case()
    {
        $link = route('penyelarasan.update', 1);
        $this->F7_get_edit_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('PUT', $link, [
            'date_start' => '06-06-2020',
            'date_end' => '08-06-2020',
            'tempat_kegiatan' => 'tes tempat kegiatan',
            'deskripsi' => 'tes deskripsi',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->followRedirects()
        ->see('Berhasil Mengubah Data Penyelarasan');
    }

    /**
     * route
     * get
     * F7
     *
     * @test
     * */
    public function F7_put_update_penyelerasan_login_as_himawan_with_tempat_kegiatan_266()
    {
        $this->markTestSkipped(
            "di db max 255, belom ada validasi max 255"
        );
        $link = route('penyelarasan.update', 1);
        $this->F7_get_edit_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('PUT', $link, [
            'date_start' => '06-06-2020',
            'date_end' => '08-06-2020',
            'tempat_kegiatan' => str_random(266),
            'deskripsi' => 'tes deskripsi',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->assertSessionHasErrors();
    }

    /**
     * route
     * get
     * F7
     *
     * @test
     * */
    public function F7_put_update_penyelerasan_login_as_himawan_with_date_ngasal()
    {
        $this->markTestSkipped(
            "date nya cuman required doang, belom ada validasi date,
            kemudian ada carbon parse, itu yang nyebab in error"
        );
        $link = route('penyelarasan.update', 1);
        $this->F7_get_edit_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('PUT', $link, [
            'date_start' => 'ngasal',
            'date_end' => 'ngasal',
            'tempat_kegiatan' => 'tes tempat kegiatan',
            'deskripsi' => 'tes deskripsi',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->assertSessionHasErrors();
    }

    /**
     * route
     * post
     * F7
     *
     * @test
     * */
    public function F7_put_update_penyelerasan_login_as_himawan_with_null_inputan()
    {
        $link = route('penyelarasan.update', 1);
        $this->F7_get_edit_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('PUT', $link, [
            'date_start' => '',
            'date_end' => '',
            'tempat_kegiatan' => '',
            'deskripsi' => '',
            'aksi_nyata_1' => '1',
            'liquid_id' => 1,
        ])->assertSessionHasErrors();
    }

    /**
     * route
     * post
     * F8
     *
     * @test
     * */
    public function F8_delete_penyelerasan_login_as_himawan()
    {
        $link = route('penyelarasan.destroy', 1);
        $this->F1_get_penyelerasan_login_as_himawan_with_positive_case();
        $this->callCustom('DELETE', $link, ['_token' => csrf_token()])
            ->followRedirects()
            ->dontSee('something went wrong');
    }
}
