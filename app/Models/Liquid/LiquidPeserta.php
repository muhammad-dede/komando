<?php

namespace App\Models\Liquid;

use App\Services\LiquidService;
use App\StrukturJabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiquidPeserta extends Model
{
    use SoftDeletes;

    protected $table = 'liquid_peserta';

    public $timestamps = false;

    protected $fillable = [
        'liquid_id',
        'atasan_id',
        'bawahan_id',
        'SNAPSHOT_JABATAN_ATASAN',
        'SNAPSHOT_JABATAN_BAWAHAN',
        'SNAPSHOT_NAMA_ATASAN' ,
        'SNAPSHOT_NIP_ATASAN' ,
        'SNAPSHOT_JABATAN2_ATASAN' ,
        'SNAPSHOT_NAMA_BAWAHAN' ,
        'SNAPSHOT_NIP_BAWAHAN' ,
        'SNAPSHOT_JABATAN2_BAWAHAN' ,
        'SNAPSHOT_UNIT_CODE' ,
        'SNAPSHOT_UNIT_NAME' ,
        'SNAPSHOT_PLANS' ,
        'SNAPSHOT_ORGEH_1',
        'SNAPSHOT_ORGEH_2',
        'flag_self_service',

    ];

    protected $casts = [
        'force_pengukuran_kedua' => 'boolean'
    ];

    public function liquid()
    {
        return $this->belongsTo(Liquid::class, 'liquid_id');
    }

    public function atasan()
    {
        return $this->belongsTo(StrukturJabatan::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->belongsTo(StrukturJabatan::class, 'bawahan_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'liquid_peserta_id');
    }

    public function pengukuranPertama()
    {
        return $this->hasOne(PengukuranPertama::class, 'liquid_peserta_id');
    }

    public function pengukuranKedua()
    {
        return $this->hasOne(PengukuranKedua::class, 'liquid_peserta_id');
    }

    public function getPenilaian()
    {
        $ids = [];
        $penyelarasan = Penyelarasan::where('liquid_id', $this->liquid->id)->where('atasan_id', $this->atasan_id)->first();
        if ($penyelarasan) {
            $ids = $penyelarasan->resolusi;
        }

        $resolusi = KelebihanKekuranganDetail::withTrashed()->whereIn('id', $ids)->get();

        $return = [];
        $penilaianPertama = $this->getNilaiPengukuranPertama();
        $penilaianKedua = $this->getNilaiPengukuranKedua();

        foreach ($resolusi as $item) {
            $return[] = [
                'id' => $item->id,
                'resolusi' => $item->deskripsi_kelebihan,
                'nilai_1' => data_get($penilaianPertama, "{$item->id}.nilai"),
                'nilai_2' => data_get($penilaianKedua, "{$item->id}.nilai"),
            ];
        }

        return $return;
    }

    /**
     * FYI, resolusi (hasil penilaian) disimpan dalam format ["123:5","124:6","120:7"], ID_KELEBIHAN_KEKURANGAN:skor
     * fungsi ini mengubah format di atas menjadi array yang lebih friendly untuk diakses
     */
    public function getNilaiPengukuranPertama()
    {
        $result = [];
        if ($this->pengukuranPertama) {
            $raw = $this->pengukuranPertama->resolusi;
            foreach ($raw as $part) {
                list($id, $nilai) = explode(":", $part);
                $result[$id] = [
                    'id' => $id,
                    'label' => KelebihanKekuranganDetail::withTrashed()->where('id', $id)->value('deskripsi_kelebihan'),
                    'nilai' => $nilai
                ];
            }
        }

        return $result;
    }

    /**
     * FYI, resolusi (hasil penilaian) disimpan dalam format ["123:5","124:6","120:7"], ID_KELEBIHAN_KEKURANGAN:skor
     * fungsi ini mengubah format di atas menjadi array yang lebih friendly untuk diakses
     */
    public function getNilaiPengukuranKedua()
    {
        $result = [];
        if ($this->pengukuranKedua) {
            $raw = $this->pengukuranKedua->resolusi;
            foreach ($raw as $part) {
                list($id, $nilai) = explode(":", $part);
                $result[$id] = [
                    'id' => $id,
                    'label' => KelebihanKekuranganDetail::withTrashed()->where('id', $id)->value('deskripsi_kelebihan'),
                    'nilai' => $nilai
                ];
            }
        }

        return $result;
    }

    public function getSnapshotAtasan()
    {
        $peserta = app(LiquidService::class)->listPeserta($this->liquid);

        $data = array_get($peserta, $this->atasan_id);
        $data['unit'] = [
            'code' => array_get($data, 'business_area'),
            'name' => BusinessArea::where('business_area', array_get($data, 'business_area'))->value('description')
        ];

        return $data;
    }

    public function getDivisiPusat($fallback = false)
    {
        $listDivisiPusat = app(LiquidService::class)->listDivisiPusat();

        if ($this->snapshot_orgeh_1) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_1);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        if ($this->snapshot_orgeh_2) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_2);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        if ($this->snapshot_orgeh_3) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_3);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        if ($fallback) {
            return @$this->atasan->strukturOrganisasi->stxt2;
        }

        return null;
    }
}
