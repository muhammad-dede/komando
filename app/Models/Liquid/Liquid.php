<?php

namespace App\Models\Liquid;

use App\Enum\LiquidStatus;
use App\Models\Traits\Auditable;
use App\Models\Traits\LiquidHasCreator;
use App\Models\Traits\LiquidHasScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Liquid extends Model implements HasMedia
{
    use Auditable;
    use HasMediaTrait;
    use LiquidHasScope;
    use LiquidHasCreator;
    use SoftDeletes;

    protected $fillable = [
        'feedback_start_date',
        'feedback_end_date',
        'penyelarasan_start_date',
        'penyelarasan_end_date',
        'pengukuran_pertama_start_date',
        'pengukuran_pertama_end_date',
        'pengukuran_kedua_start_date',
        'pengukuran_kedua_end_date',
        'gathering_start_date',
        'gathering_end_date',
        'gathering_location',
        'reminder_aksi_resolusi',
        'kelebihan_kekurangan_id',
        'keterangan',
        'link_meeting',
    ];

    protected $dates = [
        'feedback_start_date',
        'feedback_end_date',
        'penyelarasan_start_date',
        'penyelarasan_end_date',
        'pengukuran_pertama_start_date',
        'pengukuran_pertama_end_date',
        'pengukuran_kedua_start_date',
        'pengukuran_kedua_end_date',
        'gathering_start_date',
        'gathering_end_date',
        'deleted_at',
    ];

    protected $casts = [
        'peserta_snapshot' => 'array',
        'feedback_start_date' => 'date',
        'feedback_end_date' => 'date',
        'penyelarasan_start_date' => 'date',
        'penyelarasan_end_date' => 'date',
        'pengukuran_pertama_start_date' => 'date',
        'pengukuran_pertama_end_date' => 'date',
        'pengukuran_kedua_start_date' => 'date',
        'pengukuran_kedua_end_date' => 'date',
    ];

    public function businessAreas()
    {
        return $this->belongsToMany(BusinessArea::class, 'liquid_business_area', 'liquid_id', 'business_area');
    }

    public function peserta()
    {
        return $this->hasMany(LiquidPeserta::class, 'liquid_id');
    }

    public function kelebihanKekurangan()
    {
        return $this->belongsTo(KelebihanKekurangan::class, 'kelebihan_kekurangan_id');
    }

    public static function createByFormInput($input)
    {
        $input = collect($input);

        $data = [
            'feedback_start_date' => Carbon::parse($input->get('feedback_start_date')),
            'feedback_end_date' => Carbon::parse($input->get('feedback_end_date')),
            'penyelarasan_start_date' => Carbon::parse($input->get('penyelarasan_start_date')),
            'penyelarasan_end_date' => Carbon::parse($input->get('penyelarasan_end_date')),
            'pengukuran_pertama_start_date' => Carbon::parse($input->get('pengukuran_pertama_start_date')),
            'pengukuran_pertama_end_date' => Carbon::parse($input->get('pengukuran_pertama_end_date')),
            'pengukuran_kedua_start_date' => Carbon::parse($input->get('pengukuran_kedua_start_date')),
            'pengukuran_kedua_end_date' => Carbon::parse($input->get('pengukuran_kedua_end_date')),
            'reminder_aksi_resolusi' => $input->get('reminder_aksi_resolusi'),
            'kelebihan_kekurangan_id' => $input->get('kelebihan_kekurangan_id'),
        ];

        return static::create($data);
    }

    public function generatePesertaSnapshot()
    {
        $snapshotPeserta = [];
        $rawPeserta = DB::table('v_liquid_peserta_snapshot')->where('liquid_id', $this->getKey())->get();

        foreach ($rawPeserta as $peserta) {
            $atasanKey = $peserta->atasan_id;

            if (! isset($snapshotPeserta[$atasanKey])) {
                $snapshotPeserta[$atasanKey] = [
                    'nama' => mb_convert_encoding($peserta->nama_atasan, 'UTF-8', 'UTF-8'),
                    'nip' => $peserta->nip_atasan,
                    'jabatan' => mb_convert_encoding($peserta->jabatan_atasan, 'UTF-8', 'UTF-8'),
                    'kelompok_jabatan' => $peserta->kelompok_jabatan_atasan,
                    'business_area' => $peserta->business_area_atasan,
                    'peserta' => [],
                ];
            }

            $snapshotPeserta[$atasanKey]['peserta'][$peserta->bawahan_id] = [
                'liquid_peserta_id' => $peserta->id,
                'nama' => mb_convert_encoding($peserta->nama_bawahan, 'UTF-8', 'UTF-8'),
                'nip' => $peserta->nip_bawahan,
                'jabatan' => mb_convert_encoding($peserta->jabatan_bawahan, 'UTF-8', 'UTF-8'),
                'kelompok_jabatan' => $peserta->kelompok_jabatan_bawahan,
            ];
        }

        $this->peserta_snapshot = $snapshotPeserta;

        return $this->save();
    }

    public function penyelarasan()
    {
        return $this->hasMany(Penyelarasan::class, 'liquid_id');
    }

    public function getGatheringStartDate()
    {
        if ($this->gathering_start_date) {
            return $this->gathering_start_date->format('d-m-Y');
        }

        return null;
    }

    public function getGatheringEndDate()
    {
        if ($this->gathering_end_date) {
            return $this->gathering_end_date->format('d-m-Y');
        }

        return null;
    }

    public function isPusat()
    {
        return $this->businessAreas()->where('m_business_area.business_area', '1001')->exists();
    }

    public function isPublished()
    {
        return $this->status == LiquidStatus::PUBLISHED;
    }

    public function logBook()
    {
        return $this->hasMany(ActivityLogBook::class, 'liquid_id');
    }

    public function getCurrentSchedule()
    {
        $liquid = $this;
        $today = strtotime(Carbon::today());
        $before_feedback = $today < strtotime(Carbon::parse($liquid->feedback_start_date));
        $feedback = $today >= strtotime(Carbon::parse($liquid->feedback_start_date))
            && $today <= strtotime(Carbon::parse($liquid->feedback_end_date));
        $penyelarasan = $today >= strtotime(Carbon::parse($liquid->penyelarasan_start_date))
            && $today <= strtotime(Carbon::parse($liquid->penyelarasan_end_date));
        $pengukuranPertama = $today >= strtotime(Carbon::parse($liquid->pengukuran_pertama_start_date))
            && $today <= strtotime(Carbon::parse($liquid->pengukuran_pertama_end_date));
        $pengukuranKedua = $today >= strtotime(Carbon::parse($liquid->pengukuran_kedua_start_date))
            && $today <= strtotime(Carbon::parse($liquid->pengukuran_kedua_end_date));

        if ($liquid->status === LiquidStatus::DRAFT) {
            return LiquidStatus::DRAFT_STATUS;
        } else {
            if ($before_feedback) {
                return LiquidStatus::PUBLISHED_STATUS;
            } else {
                if ($penyelarasan) {
                    return LiquidStatus::PENYELARASAN;
                } else {
                    if ($pengukuranPertama) {
                        return LiquidStatus::PENGUKURAN_PERTAMA;
                    } else {
                        if ($pengukuranKedua) {
                            return LiquidStatus::PENGUKURAN_KEDUA;
                        } else {
                            if ($feedback) {
                                return LiquidStatus::FEEDBACK_BERLANGSUNG;
                            } else {
                                return LiquidStatus::SELESAI;
                            }
                        }
                    }
                }
            }
        }
    }

    public function surveyQuestionDetail()
    {
        return $this->hasMany('App\SurveyQuestionDetail', 'liquids_id');
    }
}
