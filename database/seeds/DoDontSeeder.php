<?php

use Illuminate\Database\Seeder;

class DoDontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_do_dont')->insert(['urutan' => '1', 'judul'=>'Satu Ucapan dan Tindakan', 'deskripsi'=>'Senantiasa menunjukkan perilaku konsisten antara ucapan dan tindakan, disiplin, dan memenuhi komitmen.']);
        DB::table('m_do_dont')->insert(['urutan' => '2', 'judul'=>'Satu Arah dan Tujuan', 'deskripsi'=>'Senantiasa mengacu pada arah dan tujuan perusahaan dalam melaksanakan tugas dan fungsinya.']);
        DB::table('m_do_dont')->insert(['urutan' => '3', 'judul'=>'Satu Jiwa', 'deskripsi'=>'Menunjukkan loyalitas, solidaritas dan semangat kerja tinggi yang dilandaskan nilai-nilai luhur sebagai bagian dari pengabdian tulus kepada perusahaan, negara, dan Ilahi.']);
        DB::table('m_do_dont')->insert(['urutan' => '4', 'judul'=>'Belajar dan Berkembang', 'deskripsi'=>'Menunjukkan inisiatif untuk meningkatkan keahlian dan potensi dirinya serta orang lain.']);
        DB::table('m_do_dont')->insert(['urutan' => '5', 'judul'=>'Gigih dan Gesit', 'deskripsi'=>'Menunjukkan semangat kerja yang tinggi, cepat beradaptasi, proaktif, memberikan respon yang cepat dan tepat, serta pantang menyerah.']);
        DB::table('m_do_dont')->insert(['urutan' => '6', 'judul'=>'Kreatif dan Inovatif', 'deskripsi'=>'Mampu menghasilkan ide-ide/gagasan baru, cara baru, dan berani mengambil terobosan & inovatif serta menjadi pelopor dalam aplikasinya untuk keberlangsungan Perusahaan.']);
        DB::table('m_do_dont')->insert(['urutan' => '7', 'judul'=>'Jujur dan Berani', 'deskripsi'=>'Dapat dipercaya dan berani mengambil risiko demi tercapainya tujuan Perusahaan.']);
        DB::table('m_do_dont')->insert(['urutan' => '8', 'judul'=>'Peduli dan Kompeten', 'deskripsi'=>'Memiliki kepekaan dan kecakapan untuk menjadi pelopor dalam mengubah lingkungan dan kondisi perusahaan  ke arah yang lebih baik.']);
        DB::table('m_do_dont')->insert(['urutan' => '9', 'judul'=>'Berwawasan Sosial dan Bisnis', 'deskripsi'=>'Memahami cara-cara menempatkan diri dan mengambil tindakan yang tepat dalam lingkungan sosial dan berorientasi keberlanjutan bisnis perusahaan.']);
    }
}
