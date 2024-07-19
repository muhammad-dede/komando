<?php

use Illuminate\Database\Seeder;

class PLNTerbaikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_pln_terbaik')->insert(['urutan' => '1', 'nomor_urut'=>'I', 'tipe'=>'1', 'pedoman_perilaku'=>'Visi dan Misi', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '2', 'nomor_urut'=>'II', 'tipe'=>'1', 'pedoman_perilaku'=>'Tata Nilai Perusahaan', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '3', 'nomor_urut'=>'III', 'tipe'=>'1', 'pedoman_perilaku'=>'Benturan Kepentingan', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '4', 'nomor_urut'=>'IV', 'tipe'=>'1', 'pedoman_perilaku'=>'Pemberian dan Penerimaan Gratifikasi (Hadiah, Jamuan, Hiburan dan Pemberian Donasi)', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '5', 'nomor_urut'=>'V', 'tipe'=>'1', 'pedoman_perilaku'=>'Kepedulian Terhadap Keselamatan, Kesehatan Kerja, Keamanan dan Lingkungan Hidup', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '6', 'nomor_urut'=>'VI', 'tipe'=>'1', 'pedoman_perilaku'=>'Kesempatan yang Sama untuk Mendapatkan Pekerjaan dan Promosi', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '7', 'nomor_urut'=>'VII', 'tipe'=>'1', 'pedoman_perilaku'=>'Integritas Laporan Keuangan', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '8', 'nomor_urut'=>'VIII', 'tipe'=>'1', 'pedoman_perilaku'=>'Perlindungan Informasi Perusahaan dan Intangible Asset', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '9', 'nomor_urut'=>'IX', 'tipe'=>'1', 'pedoman_perilaku'=>'Whistle Blower System', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '10', 'nomor_urut'=>'X', 'tipe'=>'1', 'pedoman_perilaku'=>'Perlindungan Harta Perusahaan', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '11', 'nomor_urut'=>'XI', 'tipe'=>'1', 'pedoman_perilaku'=>'Kegiatan Sosial Politik', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '12', 'nomor_urut'=>'XII', 'tipe'=>'1', 'pedoman_perilaku'=>'Etika Terkait Stakeholder', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '13', 'nomor_urut'=>'XIII', 'tipe'=>'1', 'pedoman_perilaku'=>'Mekanisme Pedoman Penegakan Perilaku Termasuk Pelaporan Atas Pelanggaran', 'deskripsi'=>'-']);
        DB::table('m_pln_terbaik')->insert(['urutan' => '14', 'nomor_urut'=>'XIV', 'tipe'=>'1', 'pedoman_perilaku'=>'Pelanggaran dan Sanksi', 'deskripsi'=>'-']);
    }
}
