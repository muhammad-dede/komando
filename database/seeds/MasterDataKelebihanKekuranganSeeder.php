<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\PilarUtamaEnum;
use Carbon\Carbon;
use App\Enum\KelebihanKekuranganStatus;

use App\Models\Liquid\KelebihanKekurangan;
use App\Models\Liquid\KelebihanKekuranganDetail;

class MasterDataKelebihanKekuranganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->data_1();
        $this->data_2();
    }

    protected function data_1() {
        $data = [
            [
                "title" => "Pedoman Perilaku Akhlak",
                "deskripsi" => "18 Point Pedoman Perilaku Akhlak",
                "status" => KelebihanKekuranganStatus::AKTIF,
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ];

        $detail = [
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Cepat menyesuaikan diri untuk menjadi lebih baik",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Cepat menyesuaikan diri untuk menjadi lebih baik",
                "category" => "ADAPTIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Bertindak proaktif",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Bertindak proaktif",
                "category" => "ADAPTIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Terus-menerus melakukan perbaikan mengikuti perkembangan teknologi",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Terus-menerus melakukan perbaikan mengikuti perkembangan teknologi",
                "category" => "ADAPTIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memenuhi janji dan komitmen",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memenuhi janji dan komitmen",
                "category" => "AMANAH",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Bertanggung-jawab atas tugas, keputusan dan tindakan yang dilakukan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Bertanggung-jawab atas tugas, keputusan dan tindakan yang dilakukan",
                "category" => "AMANAH",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Berpegang teguh kepada nilai moral dan etika",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Berpegang teguh kepada nilai moral dan etika",
                "category" => "AMANAH",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menghargai setiap orang apapun latar belakangnya",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menghargai setiap orang apapun latar belakangnya",
                "category" => "HARMONIS",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Membangun lingkungan kerja yang kondusif",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Membangun lingkungan kerja yang kondusif",
                "category" => "HARMONIS",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Suka menolong orang lain",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Suka menolong orang lain",
                "category" => "HARMONIS",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memberi kesempatan kepada berbagai pihak untuk berkontribusi",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memberi kesempatan kepada berbagai pihak untuk berkontribusi",
                "category" => "KOLABORATIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama",
                "category" => "KOLABORATIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Terbuka dalam bekerja sama untuk menghasilkan nilai tambah",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Terbuka dalam bekerja sama untuk menghasilkan nilai tambah",
                "category" => "KOLABORATIF",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah",
                "category" => "KOMPETEN",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Membantu orang lain belajar",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Membantu orang lain belajar",
                "category" => "KOMPETEN",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menyelesaikan tugas dengan kualitas terbaik",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menyelesaikan tugas dengan kualitas terbaik",
                "category" => "KOMPETEN",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menjaga nama baik sesama karyawan, pimpinan, BUMN, dan Negara",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menjaga nama baik sesama karyawan, pimpinan, BUMN, dan Negara",
                "category" => "LOYAL",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Rela berkorban untuk mencapai tujuan yang lebih besar",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Rela berkorban untuk mencapai tujuan yang lebih besar",
                "category" => "LOYAL",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Patuh kepada pimpinan sepanjang tidak bertentangan dengan hukum dan etika",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Patuh kepada pimpinan sepanjang tidak bertentangan dengan hukum dan etika",
                "category" => "LOYAL",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ]
        ];

        DB::transaction(function () use ($data, $detail) {
            DB::table('kelebihan_kekurangan')->where('status', '=', KelebihanKekuranganStatus::AKTIF)->update(['status' => KelebihanKekuranganStatus::TIDAK_AKTIF]);

            foreach ($data as $kelebihanKekurangan) {
                $kk = KelebihanKekurangan::create($kelebihanKekurangan);
                foreach ($detail as $kelebihanKekuranganDetail) {
                    $details = new KelebihanKekuranganDetail($kelebihanKekuranganDetail);
                    $kk->details()->save($details);
                }
            }
        });
    }

    protected function data_2() {
        $data = [
            [
                "title" => "Master Kelebihan dan Kekurangan versi 1 tahun 2020",
                "deskripsi" => "Berdasarkan data Do and Don't Pedoman Perilaku & Etika Bisnis PT PLN (PERSERO) Tahun 2020",
                "status" => KelebihanKekuranganStatus::TIDAK_AKTIF,
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ];

        $detail = [
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "AKTIF mencari hal-hal terbaru yang belum pernah dilakukan sebelumnya untuk membantu PENGEMBANGAN potensi DIRI",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Hanya berpegang pada kebiasaan lama sehingga menghambat pengembangan potensi diri bahkan MENOLAK PERKEMBANGAN terbaru",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menjalankan hasil PEMBELAJARAN bersama rekan kerja & menunjukan sikap ANTUSIAS untuk peningkatan kompetensi yang berdampak pada kinerja organisasi",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menganggap implementasi pembelajaran sebagai BEBAN sehingga menolak melakukan perbaikan di tempat kerja & bersikap SKEPTIS dalam peningkatan kompetensi",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Aktif MENCARI BEST PRACTICE dari individu lainnya maupun lembaga lain untuk perbaikan dalam pekerjaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MERASA lebih UNGGUL bahkan selalu merasa lebih baik dibanding orang lain sehingga tidak merasa perlu melakukan perbaikan dalam pekerjaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Aktif menginisiasi kegiatan BERBAGI PENGALAMAN & ilmu kepada rekan kerja maupun Unit lainnya",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menyimpan sendiri & MENYEMBUNYIKAN PENGETAHUAN yang dimilikinya hanya untuk kepentingan pribadi & tertutup untuk Unit lainnya.",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MEMBERIKAN BANTUAN & umpan balik kepada orang lain untuk menciptakan suasana yang mendukung pembelajaran",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Sengaja MENGHAMBAT & MENGKRITIK secara negatif terhadap upaya peningkatan diri sehingga menghilangkan suasana kondusif untuk pembelajaran",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menganggap masalah dalam sudut pandang yang POSITIF sehingga dapat dimanfaatkan untuk memotivasi diri",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memandang masalah sebagai HAMBATAN yang sulit dipecahkan sehingga menurunkan bahkan menghambat tumbuhnya motivasi diri",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "PANTANG MENYERAH dalam pemecahan masalah & menghadapi tantangan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Sering CEPAT MENYERAH dalam mencari solusi sehingga hanya sekedar tahu permasalahan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "OPTIMAL dalam menggunakan sumber daya yang ada untuk mengatasi tantangan pekerjaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menjadikan keterbatasan sumber daya sebagai HAMBATAN untuk mengatasi tantangan pekerjaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Cepat mencari pemecahan masalah dengan BERFOKUS pada tindakan & solusi ketika terjadi permasalahan sesuai mekanisme yang berlaku",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Mengemukakan alasan & masalah saja untuk sekedar menjawab secara lisan TANPA ada TINDAKAN untuk mencari solusi",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "AKTIF terlibat & BERKONTRIBUSI dalam usaha pemenuhan pencapaian kinerja terbaik perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menunjukkan sikap PASIF dan mentalitas biasa-biasa saja sehingga tidak merasa perlu untuk mencapai kinerja terbaik",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mampu MENYESUAIKAN DIRI terhadap perubahan kebijakan perusahaan yang sedang dicanangkan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menunjukkan RESISTENSI terhadap PERUBAHAN kebijakan perusahaan sehingga menghambat upaya-upaya penyesuaian diri di lingkungan kerja",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENCARI TAHU kebutuhan maupun kepentingan stakeholder dalam membangun ide & gagasan baru",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGABAIKAN KEBUTUHAN & kepentingan stakeholder dalam pengembangan ide & gagasan baru",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Aktif mencari pembelajaran untuk MENINGKATKAN KOMPETENSI diri dengan mempertimbangkan esiensi faktor biaya.",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Mencari - cari alasan untuk menghabiskan biaya pembelajaran TANPA MEMPERDULIKAN keselarasan kompetensi yang dibutuhkan organisasi",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MEMANFAATKAN MASALAH & tantangan sebagai sumber gagasan baru yang berdampak pada keberlanjutan perusahaan di masa depan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menggunakan kebiasaan yang sama secara terus menerus & TIDAK MEMIKIRKAN keberlanjutan perusahaan di masa depan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Bersikap TERBUKA & LUWES terhadap masukan baik internal maupun eksternal sehingga dapat memanfaatkan keberlimpahan ide baru dari berbagai sumber",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Bersikap TERTUTUP bahkan MENOLAK masukan orang lain sehingga gagal memanfaatkan keberlimpahan ide yang berkembang di luar perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Berpartipasi & membangun komunitas yang membangun pemikiran \"out of the box\" maupun mencari TEROBOSAN INOVASI",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Hanya berfokus pada tim internal tanpa upaya membangun relasi dengan pihak eksternal sehingga MENGHAMBAT INOVASI",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mengamati, menggunakan, menerapkan & MEMODIFIKASI KARYA di tempat lain sebagai terobosan inovasi di perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENCONTEK & MENJIPLAK mentah - mentah karya orang lain tanpa mempertimbangkan kecocokan inovasi dengan kondisi perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menunjukkan pemikiran & sikap yang mendukung suasana kerja INOVATIF agar perusahaan dapat mengikuti perkembangan zaman & tetap “sustain”",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MEMBIARKAN PERUSAHAAN tidak mengikuti perubahan zaman & tidak mampu menghadapi tantangan global",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menyampaikan pendapat dan laporan secara JUJUR",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menyembunyikan opini untuk dibicarakan di belakang orang lain & MEMANIPULASI LAPORAN",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "BERSEDIA mengambil risiko dengan mempertimbangkan aspek hukum untuk memberikan nilai tambah bagi perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "TAKUT mengambil risiko bahkan tidak berinistif apapun karena mencari posisi aman dalam bekerja",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "BERKOMITMEN untuk melaksanakan janji yang telah disepakati",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Mengabaikan bahkan MELANGGAR janji yang telah disepakati",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menunjukkan KEDISPLINAN waktu kerja & kepatuhan terhadap aturan perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menggunakan waktu kerja untuk aktivitas di luar kedinasan maupun MELANGGAR aturan perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mempraktekkan UCAPAN sesuai dengan perilaku sehari - hari kepada orang lain",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menunjukkan KESENJANGAN ucapan & tindakan sehingga terlihat tidak konsisten bagi orang lain",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mampu menjadi contoh PANUTAN kepada orang lain dalam berbagai situasi pekerjaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menunjukkan kebiasaan - kebiasaan yang menyebabkan lingkungan kerja TIDAK KONDUSIF",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENJAGA rahasia perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MEMBOCORKAN rahasia perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "AKTIF membangun citra positif korporat",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Berdiam diri saja bahkan MERUSAK citra korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memastikan sasaran individu SELARAS dengan sasaran perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menetapkan sasaran individu yang MENGUNTUNGKAN diri SENDIRI",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "PROAKTIF secara individu melaksanakan program korporat",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGABAIKAN bahkan pesimis dengan program korporat.",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENDUKUNG KONTRIBUSI di dalam unit kerja untuk pencapaian tujuan korporat",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Membuat TUJUAN unit SENDIRI tanpa merasa perlu mendukung tujuan korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENDUKUNG SINERGI antar insan PLN dalam mencapai tujuan korporat",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Membentuk KESEPAKATAN dalam KELOMPOK - KELOMPOK yang berbeda dari tujuan korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Proaktif menempatkan KEPENTINGAN KORPORAT diatas kepentingan pribadi & unit kerja",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Hanya mengutamakan kepentingan EGO SEKTORAL saja tanpa peduli tujuan korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENDUKUNG kebijakan, prosedur & inisiatif program sesuai dengan tujuan korporat",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENENTANG kebijakan, prosedur & inisiatif program sehingga menghambat pencapaian tujuan korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menjadikan kerja sebagai usaha lahir & batin batin sesuai dengan perintah Tuhan untuk mendapatkan KEBAHAGIAAN & KESEIMBANGAN hidup",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Sekedar mencari UPAH & kebutuhan MATERI dalam pekerjaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menyadari bahwa PERBEDAAN disikapi secara POSITIF",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memandang PERBEDAAN sebagai suatu HAMBATAN bahkan menunjukkan sikap tidak toleran",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menunjukkan semangat KERJASAMA & EMPATI kepada rekan kerja",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memilih BEKERJA SENDIRI tanpa peduli kondisi rekan kerja atau menunjukkan sikap silo (terkotak – kotak).",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Membangun SEMANGAT kerja yang tinggi & OPTIMISME pada tim dalam mengabdi kepada perusahaan & negara",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Sering MENGELUH & mengeluarkan beban NEGATIF kepada tim kerja tanpa semangat pengabdian",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menjaga KEBANGGAAN pada korporat dengan menumbuhkan keyakinan bahwa berhasil-gagalnya perusahaan merupakan kontribusi dari setiap individu & tim.",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENYALAHKAN pihak lain & merasa tidak berkepentingan terhadap kegagalan korporat",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memberikan dukungan korporat dalam berbagai situasi untuk MEMAJUKAN REPUTASI perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menghambat aktivitas korporat bahkan menyebabkan KEMUNDURAN REPUTASI perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENGAMBIL TANGGUNG JAWAB perbuatannya sesuai kewenangan demi kepentingan perusahaan dalam situasi apapun",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGHINDARI TANGGUNG JAWAB dalam pekerjaan sehingga menghambat & menyebabkan lambatnya proses kerja",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memberikan usulan terhadap kebijakan,aturan, prosedur & kondisi kerja dengan sikap positif disertai dengan data terbaru & FAKTA yang OBYEKTIF",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memberikan usulan hanya berdasarkan asumsi dengan tindakan yang MENGABAIKAN ETIKA, kewenangan & prosedur",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENJAGA DIRI & lingkungan kerja terhadap potensi pelanggaran peraturan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Mencari celah kesempatan & mempengaruhi orang lain untuk MENGAMBIL KEUNTUNGAN PRIBADI",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "BERANIMENEGUR & melaporkan orang lain yang berpotensi pelanggaran peraturan bahkan menindak pelanggaran sesuai prosedur & kewenangan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MEMBIARKAN orang lain melanggar aturan dalam pekerjaan bahkan ikut serta melakukan PELANGGARAN demi suatu kepentingan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menjaga KESEIMBANGAN hidup antara pemenuhan waktu untuk memenuhi kewajiban dengan aspek kehidupan lainnya",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGABAIKAN aspek kehidupan yang lain demi mengejar pemenuhan kewajiban pekerjaan saja sehingga sulit mencapai keseimbangan hidup",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "BERINISIATIF Berinisiatif menjadi insan yang siap melayani dengan tetap berpedoman pada prosedur yang harus dijalankan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Sekedar MENUNGGU INSTRUKSI atasan dalam bekerja,bahkan menunjukkan sikap minta dilayani",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "PROAKTIF dalam meningkatkan pelayanan, baik internal maupun eksternal dengan tetap mengacu pada ketentuan yang berlaku",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Memberikan pelayanan hanya jika diminta, bersikap ACUH TIDAK ACUH pada kebutuhan internal dan eksternal",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memanfaatkan KEMAMPUAN DIRI dalam berbagai kegiatan yang memberikan dampak positif kepada perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGABAIKAN berbagai kegiatan yang terdapat di perusahaan karena tidak merasa berkepentingan, bahkan tidak mendukung program tersebut",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Menunjukkan upaya–upaya POSITIF dalam menjalankan inisiatif strategis dengan memperhatikan KESELAMATAN KERJA maupun KONDISI LINGKUNGAN",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menjalankan inisiatif strategis dengan sikap yang MENGABAIKAN KESELAMATAN KERJA maupun kondisi LINGKUNGAN bahkan menganggapnya sebagai penghambat.",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Bersedia menjadi PELOPOR dalam pengembangan aset & mendukung keberlanjutan perusahaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MenunjukkanKETIDAKPEKAAN & tidak cakap dalam menjaga aset yang sudah dipercayakan & menghambat keberlanjutan perusahaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Melakukan tindakan kesadaran biaya yang dapat MENGEFISIENKAN biaya pokok penyediaan & meningkatkan pendapatan untuk keuntungan optimal",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Mengabaikan esiensi biaya dan potensi penurunan pendapatan, bahkan melakukan PEMBOROSAN yang merugikan sehingga tidak terlihat adanya tindakan kesadaran biaya",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Memiliki Inisiatif TERLIBAT dalam kegiatan sosial baik internal maupun eksternal dengan mengedepankan tanggung jawab pekerjaan utama",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Acuh bahkan MENGHAMBAT menghambat kegiatan- kegiatan sosial yang ditetapkan perusahaan/Unit",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Proaktif mengantisipasi & MENANGKAP PELUANG bisnis, serta diwujudkan dalam tindakan nyata",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "ACUH terhadap peluang bisnis yang ada, dan bersikap tidak mau tau",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mencari TANTANGAN & PELUANG bisnis PLN ke depan untuk dapat senantiasa tumbuh & berkembang sesuai tuntutan perubahan zaman",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Hanya BERFOKUS pada tantangan bisnis JANGKA PENDEK dan yang telah dilakukan sebelumnya saja sehingga PLN tidak mampu mengikuti perubahan zaman",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "Mengupayakan terwujudnya SINERGI dengan Instansi lain/BUMN dalam menyelesaikan pekerjaan",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "Menghindari bahkan MENOLAK menjalin KERJASAMA dengan instansi/BUMN dalam menyelesaikan pekerjaan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "kelebihan" => null,
                "deskripsi_kelebihan" => "MENDUKUNG program pemerintah dengan berperan sebagai agen pembangunan yang menjaga operasional perusahaan secara esien",
                "kekurangan" => null,
                "deskripsi_kekurangan" => "MENGABAIKAN program pemerintah sebagai agen pembangunan, bertindak tanpa memikirkan kepentingan sosial & esiensi operasional perusahan",
                "category" => "",
                "created_by" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ]
        ];

        DB::transaction(function () use ($data, $detail) {
            foreach ($data as $kelebihanKekurangan) {
                $kk = KelebihanKekurangan::create($kelebihanKekurangan);
                foreach ($detail as $kelebihanKekuranganDetail) {
                    $details = new KelebihanKekuranganDetail($kelebihanKekuranganDetail);
                    $kk->details()->save($details);
                }
            }
        });
    }
}
