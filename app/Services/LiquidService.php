<?php

namespace App\Services;

use App\Enum\LiquidPermission;
use App\Enum\LiquidStatus;
use App\Enum\RangeNilaiPengukuran;
use App\Enum\RolesEnum;
use App\Events\LiquidPublished;
use App\Exceptions\IncompleteLiquidAttributes;
use App\Models\Liquid\BusinessArea;
use App\Models\Liquid\Feedback;
use App\Models\Liquid\KelebihanKekuranganDetail;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\PengukuranKedua;
use App\Models\Liquid\PengukuranPertama;
use App\Models\Liquid\Penyelarasan;
use App\PA0001;
use App\StrukturJabatan;
use App\User;
use App\CompanyCode;
use App\Utils\BusinessAreaUtil;
use App\Utils\CompanyCodeUtil;
use App\Utils\UnitKerjaUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LiquidService
{
    const JADWAL_ADMIN = 1;
    const JADWAL_ATASAN = 2;
    const JADWAL_BAWAHAN = 3;

    public function jadwal($for, $params)
    {
        $query = Liquid::query()->published();

        if ($for instanceof User) {
            $liquids = $query->forUser($for);
        } else {
            $liquids = $query->forUnit($for);
        }

        $liquids = $liquids->when($params->year, function ($query) use ($params) {
                $year = $params->year;

                return $query->where(function ($query) use ($year) {
                    return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'")
                        ->orWhereRaw("EXTRACT(YEAR FROM CREATED_AT) = '$year'");
                });
            })->get();

        return $this->toCalendarArray($liquids, self::JADWAL_ADMIN);
    }

    public function jadwalAtasan(User $atasan)
    {
        $liquids = Liquid::query()->published()->forAtasan($atasan)->get();

        return $this->toCalendarArray($liquids, self::JADWAL_ATASAN);
    }

    public function jadwalBawahan(User $bawahan)
    {
        $liquids = Liquid::query()->published()->forBawahan($bawahan)->get();

        return $this->toCalendarArray($liquids, self::JADWAL_BAWAHAN);
    }

    public function jadwalBawahanTahunAktif(User $bawahan)
    {
        $liquids = Liquid::currentYear()->published()->forBawahan($bawahan)->first();

        return $liquids->toArray();
    }

    public function listPersonel(Liquid $liquid)
    {
        return collect(
            DB::table('m_struktur_jabatan')
                ->join('pa0001', 'pa0001.pernr', '=', 'm_struktur_jabatan.pernr')
                ->whereIn('pa0001.PERSG', [1, 0])
                ->whereIn('pa0001.GSBER', $liquid->businessAreas->pluck('business_area'))
                ->get()
        );
    }

    public function listAtasan(Liquid $liquid, $exclude = false)
    {
        return collect(
            DB::table('v_liquid_atasan')
                ->join('pa0032', 'v_liquid_atasan.pernr', '=', 'pa0032.pernr')
                ->whereIn('GSBER', $liquid->businessAreas->pluck('business_area'))
                ->get()
        );
    }

    public function listBawahan(Liquid $liquid, $atasanId = null)
    {
        //TODO logic untuk mendapatkan bawahan saat ini belum jelas
        $activePersonalNumbers = PA0001::query()
            ->whereIn('PERSG', [1, 0])
            ->whereIn('GSBER', $liquid->businessAreas->pluck('business_area'))
            ->limit(999)
            ->get()
            ->pluck('pernr');

        $bawahanPersonalNumbers = $liquid->peserta()->where('atasan_id', $atasanId)->pluck('bawahan_id');

        return StrukturJabatan::query()
            ->whereIn('pernr', $activePersonalNumbers)
            ->whereNotIn('pernr', $bawahanPersonalNumbers)
            ->get();
    }

    public function listPeserta(Liquid $liquid)
    {
        if (request()->has('search')) {
            $searchKey = strtoupper(request()->get('search'));
            $data = [];
            $foundBawahan = [];
            foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
                // $data[$pernr] = $atasan;
                foreach ($atasan['peserta'] as $pernrBawahan => $bawahan) {
                    // array keys peserta bawahan
                    foreach (array_keys($bawahan) as $keyBawahan) {
                        if (strpos(strtoupper($bawahan[$keyBawahan]), $searchKey) !== false) {
                            $foundBawahan[$pernr][$pernrBawahan] = $bawahan;
                        }
                    }
                }
                unset($data[$pernr]['peserta']);
                if (array_key_exists($pernr, $foundBawahan)) {
                    $setData = array();
                    $setData['nama'] = $atasan['nama'];
                    $setData['nip'] = $atasan['nip'];
                    $setData['jabatan'] = $atasan['jabatan'];
                    $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                    $setData['business_area'] = $atasan['business_area'];
                    $setData['jml_bawahan'] = count($atasan['peserta']);
                    $data[$pernr] = $setData;
                    $data[$pernr]['peserta'] = $foundBawahan[$pernr];
                }else{
                    if (strpos(strtoupper($atasan['nama']), $searchKey) !== false) {
                        $setData = array();
                        $setData['nama'] = $atasan['nama'];
                        $setData['nip'] = $atasan['nip'];
                        $setData['jabatan'] = $atasan['jabatan'];
                        $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                        $setData['business_area'] = $atasan['business_area'];
                        $setData['jml_bawahan'] = count($atasan['peserta']);
                        $data[$pernr] = $setData;
                        $data[$pernr]['peserta'] = $atasan['peserta'];
                    }
                }
            }
            return $data;
        }else{
            $data = [];
            $dataBawahan = [];
            if(!empty($liquid->peserta_snapshot)){
                foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
                    $setData = array();
                    $setData['nama'] = $atasan['nama'];
                    $setData['nip'] = $atasan['nip'];
                    $setData['jabatan'] = $atasan['jabatan'];
                    $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                    $setData['business_area'] = $atasan['business_area'];
                    $setData['jml_bawahan'] = count($atasan['peserta']);
                    $data[$pernr] = $setData;
                    // $data[$pernr] = $atasan;
                    $data[$pernr]['peserta'] = $atasan['peserta'];
                }
            }
            return $data;
        }

        // return $liquid->peserta_snapshot ? $liquid->peserta_snapshot : [];
    }

    public function listPesertaLessThan_3(Liquid $liquid){
        $data = [];
        $dataBawahan = [];
        if(!empty($liquid->peserta_snapshot)){
            foreach ($liquid->peserta_snapshot as $pernr => $atasan) {
                if(count($atasan['peserta']) < 3) {
                    $setData = array();
                    $setData['nama'] = $atasan['nama'];
                    $setData['nip'] = $atasan['nip'];
                    $setData['jabatan'] = $atasan['jabatan'];
                    $setData['kelompok_jabatan'] = $atasan['kelompok_jabatan'];
                    $setData['business_area'] = $atasan['business_area'];
                    $setData['jml_bawahan'] = count($atasan['peserta']);
                    $data[$pernr] = $setData;
                    // $data[$pernr] = $atasan;
                    $data[$pernr]['peserta'] = $atasan['peserta'];
                }
            }
        }
        return $data;
    }

    public function listUnitKerja_old(User $user)
    {
        $query = BusinessArea::query()
            ->with('companyCode');

        if ($user->can(LiquidPermission::VIEW_ALL_UNIT)) {
            // show all unit kerja
        } elseif ($user->hasRole(RolesEnum::ADMIN_HTD)) {
            $businessArea = (new UnitKerjaUtil)->shiftingBusinessArea($user);
            $array = (new BusinessAreaUtil)->generateOptions($user, $businessArea);

            $collect = collect();

            foreach ($array as $key => $value) {
                if ($key !== 0) {
                    $collect->push((object) [
                        'business_area' => $key,
                        'description' => $value,
                    ]);
                }
            }

            return $collect;
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_INDUK)) {
            $query->where('company_code', $user->company_code);
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_PELAKSANA)) {
            $query->where('business_area', $user->business_area);
        } else {
            return collect();
        }

        // remove businessa area yang tidak mempunya company code
        // meskipun secara DB sudah ada FK, tapi ada error di production:
        // http://sentry.pln.co.id/share/issue/517e0fb81851472da79747205767cac8/
        return $query->get()->reject(function ($item) {
            return $item->companyCode === null;
        });
    }

    public function listUnitKerja(User $user)
    {
        $query = BusinessArea::query()
            ->with('companyCode');
        
        if ($user->can(LiquidPermission::VIEW_ALL_UNIT)) {
            // show all unit kerja
        } elseif ($user->hasRole(RolesEnum::ADMIN_HTD)) {
            $businessArea = (new UnitKerjaUtil)->shiftingBusinessArea($user);
            $query->whereIn('business_area', $businessArea);
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_INDUK)) {
            $query->where('company_code', $user->company_code);
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_PELAKSANA)) {
            $query->where('business_area', $user->business_area);
        } else {
            return collect();
        }
        // remove businessa area yang tidak mempunya company code
        // meskipun secara DB sudah ada FK, tapi ada error di production:
        // http://sentry.pln.co.id/share/issue/517e0fb81851472da79747205767cac8/
        return $query->get()->reject(function ($item) {
            return $item->companyCode === null;
        });
    }

    public function listUnitKerja_2(User $user)
    {
        $query = BusinessArea::query()
            ->with('companyCode');

        if ($user->can(LiquidPermission::VIEW_ALL_UNIT)) {
            // show all unit kerja
        } elseif ($user->hasRole(RolesEnum::ADMIN_HTD)) {
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
            // $businessArea = (new UnitKerjaUtil)->shiftingBusinessArea($user);
            foreach($companyCode as $key => $value) {
                if($value != "1000") {
                    $array[] = $value;
                }
            }
            $query->whereIn('company_code', $array);
            // return $array;
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_INDUK)) {
            $query->where('company_code', $user->company_code);
        } elseif ($user->can(LiquidPermission::VIEW_UNIT_PELAKSANA)) {
            $query->where('business_area', $user->business_area);
        } else {
            return collect();
        }
        // remove businessa area yang tidak mempunya company code
        // meskipun secara DB sudah ada FK, tapi ada error di production:
        // http://sentry.pln.co.id/share/issue/517e0fb81851472da79747205767cac8/
        return $query->get()->reject(function ($item) {
            return $item->companyCode === null;
        });
    }

    public function listUnitKerjaCompany_HTDArea(User $user)
    {
        $query = CompanyCode::query();
        if ($user->hasRole(RolesEnum::ADMIN_HTD)) {
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
            $query->whereIn('company_code', $companyCode);
        }
        // remove businessa area yang tidak mempunya company code
        // meskipun secara DB sudah ada FK, tapi ada error di production:
        // http://sentry.pln.co.id/share/issue/517e0fb81851472da79747205767cac8/
        return $query->get()->reject(function ($item) {
            return $item->businessArea === null;
        });
    }

    public function listDivisiPusat()
    {
        $oneHour = 60;

        return Cache::remember('listDivisiPusat', $oneHour, function () {
            return collect(DB::table('v_divisi_pusat')->where('status', 'ACTV')->get())->pluck('stext', 'objid')->prepend('LAINNYA', 0);
        });
    }

    public function tambahAtasanDanGenerateBawahan(Liquid $liquid, $atasan, $jabatan)
    {
        return DB::transaction(function () use ($liquid, $atasan, $jabatan) {
            foreach ($atasan as $idAtasan) {
                $listBawahan = $this->listBawahan($liquid, $idAtasan)->take(1);
                $snapshotAtasan = DB::table('v_snapshot_pegawai')->where('pernr', $idAtasan)->first();

                foreach ($listBawahan as $bawahan) {
                    $snapshotBawahan = DB::table('v_snapshot_pegawai')->where('pernr', $bawahan->pernr)->first();

                    $dataPeserta = [
                        'liquid_id' => $liquid->getKey(),
                        'atasan_id' => $idAtasan,
                        'bawahan_id' => $bawahan->pernr,
                        'SNAPSHOT_JABATAN_ATASAN' => $jabatan,
                        'SNAPSHOT_NAMA_ATASAN' => data_get($snapshotAtasan, 'nama'),
                        'SNAPSHOT_NIP_ATASAN' => data_get($snapshotAtasan, 'nip'),
                        'SNAPSHOT_JABATAN2_ATASAN' => data_get($snapshotAtasan, 'jabatan'),
                        'SNAPSHOT_JABATAN_BAWAHAN' => null,
                        'SNAPSHOT_NAMA_BAWAHAN' => data_get($snapshotBawahan, 'nama'),
                        'SNAPSHOT_NIP_BAWAHAN' => data_get($snapshotBawahan, 'nip'),
                        'SNAPSHOT_JABATAN2_BAWAHAN' => data_get($snapshotAtasan, 'jabatan'),
                        'SNAPSHOT_UNIT_CODE' => data_get($snapshotAtasan, 'unit_code'),
                        'SNAPSHOT_UNIT_NAME' => data_get($snapshotAtasan, 'unit_name'),
                        'SNAPSHOT_PLANS' => data_get($snapshotAtasan, 'plans'),
                        'SNAPSHOT_ORGEH_1' => data_get($snapshotAtasan, 'orgeh1'),
                        'SNAPSHOT_ORGEH_2' => data_get($snapshotAtasan, 'orgeh2'),
                        'SNAPSHOT_ORGEH_3' => data_get($snapshotAtasan, 'orgeh3')
                    ];

                    if (!LiquidPeserta::where($dataPeserta)->exists()) {
                        $peserta = new LiquidPeserta();
                        $peserta->fill($dataPeserta);
                        $peserta->save();
                    }
                }
            }
            $liquid->generatePesertaSnapshot();
        });
    }

    public function gantiAtasan(Liquid $liquid, StrukturJabatan $atasanLama, StrukturJabatan $atasanBaru)
    {
        return DB::transaction(function () use ($liquid, $atasanLama, $atasanBaru) {
            $snapshotAtasan = DB::table('v_snapshot_pegawai')->where('pernr', $atasanBaru->getKey())->first();

            LiquidPeserta::query()
                ->where([
                    'liquid_id' => $liquid->getKey(),
                    'atasan_id' => $atasanLama->pernr,
                ])
                ->update([
                    'atasan_id' => $atasanBaru->pernr,
                    'SNAPSHOT_JABATAN2_ATASAN' => data_get($snapshotAtasan, 'jabatan'),
                    'SNAPSHOT_NAMA_ATASAN' => data_get($snapshotAtasan, 'nama'),
                    'SNAPSHOT_NIP_ATASAN' => data_get($snapshotAtasan, 'nip'),
                ]);

            $liquid->generatePesertaSnapshot();

            return true;
        });
    }

    public function tambahPeserta(Liquid $liquid, $listBawahan, StrukturJabatan $atasan, $snapshotJabatan)
    {
        return DB::transaction(function () use ($liquid, $listBawahan, $atasan, $snapshotJabatan) {
            $existingPeserta = LiquidPeserta::where('atasan_id', $atasan->pernr)->first();

            if (!$existingPeserta) {
                throw new \DomainException("Peserta liquid dengan atasan ID {$atasan->pernr} tidak ditemukan");
            }

            foreach ($listBawahan as $bawahanId) {
                $snapshotPeserta = DB::table('v_snapshot_pegawai')->where('pernr', $bawahanId)->first();

                if (!$snapshotPeserta) {
                    continue;
                }

                $dataPeserta = [
                    'liquid_id' => $liquid->getKey(),
                    'atasan_id' => $atasan->getKey(),
                    'bawahan_id' => $bawahanId,
                    'SNAPSHOT_JABATAN_ATASAN' => data_get($existingPeserta, 'snapshot_jabatan_atasan'),
                    'SNAPSHOT_NAMA_ATASAN' => data_get($existingPeserta, 'snapshot_nama_atasan'),
                    'SNAPSHOT_NIP_ATASAN' => data_get($existingPeserta, 'snapshot_nip_atasan'),
                    'SNAPSHOT_JABATAN2_ATASAN' => data_get($existingPeserta, 'snapshot_jabatan2_atasan'),
                    'SNAPSHOT_JABATAN_BAWAHAN' => $snapshotJabatan,
                    'SNAPSHOT_NAMA_BAWAHAN' => data_get($snapshotPeserta, 'nama'),
                    'SNAPSHOT_NIP_BAWAHAN' => data_get($snapshotPeserta, 'nip'),
                    'SNAPSHOT_JABATAN2_BAWAHAN' => data_get($snapshotPeserta, 'jabatan'),
                    'SNAPSHOT_UNIT_CODE' => data_get($snapshotPeserta, 'unit_code'),
                    'SNAPSHOT_UNIT_NAME' => data_get($snapshotPeserta, 'unit_name'),
                    'SNAPSHOT_PLANS' => data_get($snapshotPeserta, 'plans'),
                    'SNAPSHOT_ORGEH_1' => data_get($existingPeserta, 'snapshot_orgeh_1'),
                    'SNAPSHOT_ORGEH_2' => data_get($existingPeserta, 'snapshot_orgeh_2'),
                    'SNAPSHOT_ORGEH_3' => data_get($existingPeserta, 'snapshot_orgeh_3')
                ];

                $peserta = new LiquidPeserta();
                $peserta->fill($dataPeserta);
                $peserta->save();
            }

            $liquid->generatePesertaSnapshot();
        });
    }

    public function hapusPeserta(Liquid $liquid, LiquidPeserta $peserta)
    {
        return DB::transaction(function () use ($peserta, $liquid) {
            $peserta->feedback()->delete();
            $peserta->pengukuranPertama()->delete();
            $peserta->pengukuranKedua()->delete();
            $peserta->delete();

            $liquid->generatePesertaSnapshot();
        });
    }

    public function hapusPesertaBulk(Liquid $liquid, array $ids)
    {
        return DB::transaction(function () use ($ids, $liquid) {
            foreach ($ids as $id) {
                Feedback::query()->where('liquid_peserta_id', $id)->delete();
                PengukuranPertama::query()->where('liquid_peserta_id', $id)->delete();
                PengukuranKedua::query()->where('liquid_peserta_id', $id)->delete();
                LiquidPeserta::query()->where('id', $id)->delete();
            }

            $liquid->generatePesertaSnapshot();
        });
    }

    public function hapusAtasan(Liquid $liquid, $idAtasan)
    {
        return DB::transaction(function () use ($idAtasan, $liquid) {
            LiquidPeserta::where('atasan_id', $idAtasan)->delete();

            $liquid->generatePesertaSnapshot();
        });
    }

    public function syncBusinessAreaDanPeserta(Liquid $liquid, array $areas)
    {
        return DB::transaction(function () use ($liquid, $areas) {
            $existingPeserta = $this->listPeserta($liquid);
            $changes = $liquid->businessAreas()->sync($areas);

            if (!$changes) {
                return null;
            }

            if (empty($existingPeserta)) {
                return $this->generateAtasanDanBawahan($liquid);
            } else {
                $deleted = 0;
                foreach ($existingPeserta as $pernrAtasan => $dataAtasan) {
                    $businessAreaAtasan = array_get($dataAtasan, 'business_area');

                    if (!in_array($businessAreaAtasan, $areas)) {
                        $deleted += $liquid->peserta()->where('atasan_id', $pernrAtasan)->delete();
                    }
                }

                $peserta = DB::table('v_liquid_peserta_snapshot')->where('liquid_id', $liquid->getKey())->count();
                if ($peserta == 0) {
                    return $this->generateAtasanDanBawahan($liquid);
                } else {
                    $liquid->generatePesertaSnapshot();

                    return $deleted;
                }
            }
        });
    }

    public function publish(Liquid $liquid)
    {
        $unit_in_request = $liquid->businessAreas->pluck('business_area')->toArray();

        $units = [];
        foreach ($unit_in_request as $unit) {
            $existingLiquids = Liquid::whereHas('businessAreas', function ($q) use ($unit) {
                $q->where('liquid_business_area.business_area', $unit);
            })->published()
                ->whereYear('feedback_start_date', '=', $liquid->feedback_start_date->format('Y'))
                ->where('id', '<>', $liquid->id)
                ->exists();

            if ($existingLiquids) {
                array_push($units, $unit);
            }
        }

        $unit_has_liquids = BusinessArea::whereIn('business_area', $units)->pluck('description');

        if (!$unit_has_liquids->isEmpty()) {
            throw new IncompleteLiquidAttributes(
                implode(", ", $unit_has_liquids->toArray()),
                IncompleteLiquidAttributes::UNIT_SUDAH_PUNYA_LIQUID
            );
        }
        if ($liquid->businessAreas->isEmpty()) {
            throw new IncompleteLiquidAttributes('', IncompleteLiquidAttributes::UNIT_KERJA_BELUM_DIISI);
        }
        if ($liquid->getMedia()->isEmpty()) {
            throw new IncompleteLiquidAttributes('', IncompleteLiquidAttributes::DOKUMEN_BELUM_DIISI);
        }
        if ($liquid->peserta->isEmpty()) {
            throw new IncompleteLiquidAttributes('', IncompleteLiquidAttributes::PESERTA_BELUM_DIISI);
        }

        $liquid->status = LiquidStatus::PUBLISHED;

        $saved = $liquid->save();
        if ($saved) {
            event(new LiquidPublished($liquid));
        }

        return $saved;
    }

    protected function generateAtasanDanBawahan(Liquid $liquid)
    {
        $mappingTabelPenilaiAtasan = config('liquid.mapping_jabatan_penilai');

        $areas = $liquid->businessAreas->pluck('business_area');
        if ($areas->isEmpty()) {
            return false;
        }

        $jabatans = collect(DB::table('v_liquid_atasan')
            ->whereIn('GSBER', $areas)
            ->distinct()
            ->get(['jabatan']))->pluck('jabatan');

        $gsber = $areas->transform(function ($item) {
            return "'$item'";
        })->implode(',');
        foreach ($jabatans as $jabatanAtasan) {
            $listTabelBawahan = array_get($mappingTabelPenilaiAtasan, $jabatanAtasan, []);
            foreach ($listTabelBawahan as $jabatanBawahan => $tabel) {
                // Menggunakan insert..select untuk optimasi query agar tidak timeout
                $query = "
                    insert into LIQUID_PESERTA (
                        liquid_id,
                        ATASAN_ID,
                        BAWAHAN_ID,
                        SNAPSHOT_JABATAN_ATASAN,
                        SNAPSHOT_JABATAN_BAWAHAN,
                        SNAPSHOT_NAMA_ATASAN,
                        SNAPSHOT_NAMA_BAWAHAN,
                        SNAPSHOT_NIP_ATASAN,
                        SNAPSHOT_NIP_BAWAHAN,
                        SNAPSHOT_JABATAN2_ATASAN,
                        SNAPSHOT_JABATAN2_BAWAHAN,
                        SNAPSHOT_UNIT_CODE,
                        SNAPSHOT_UNIT_NAME,
                        SNAPSHOT_PLANS,
                        SNAPSHOT_ORGEH_1,
                        SNAPSHOT_ORGEH_2,
                        SNAPSHOT_ORGEH_3
                    )
                    select
                           {$liquid->getKey()},
                           PERNR_ATASAN,
                           PERNR_BAWAHAN,
                           '{$jabatanAtasan}',
                           '{$jabatanBawahan}',
                           jabatan_atasan.CNAME,
                           jabatan_bawahan.CNAME,
                           jabatan_atasan.NIP,
                           jabatan_bawahan.NIP,
                           posisi_atasan.STEXT,
                           posisi_bawahan.STEXT,
                           UNIT_ATASAN.BUSINESS_AREA,
                           UNIT_ATASAN.DESCRIPTION,
                           v_liquid_atasan.PLANS,
                           ORG.objid as ORGEH_1,
                           ORG2.objid as ORGEH_2,
                           ORG3.objid as ORGEH_3
                    from v_liquid_atasan
                    JOIN {$tabel} on (v_liquid_atasan.pernr = PERNR_ATASAN AND JABATAN = '{$jabatanAtasan}')
                    LEFT JOIN M_STRUKTUR_ORGANISASI ORG on (ORG.OBJID = v_liquid_atasan.ORGEH)
                    LEFT JOIN M_STRUKTUR_ORGANISASI ORG2 on (ORG2.OBJID = ORG.SOBID)
                    LEFT JOIN M_STRUKTUR_ORGANISASI ORG3 on (ORG3.OBJID = ORG2.SOBID)
                    LEFT JOIN M_BUSINESS_AREA UNIT_ATASAN on (UNIT_ATASAN.BUSINESS_AREA = v_liquid_atasan.GSBER)
                     LEFT JOIN M_STRUKTUR_JABATAN jabatan_atasan on (jabatan_atasan.PERNR = PERNR_ATASAN)
                     LEFT JOIN M_STRUKTUR_POSISI posisi_atasan
                          on (posisi_atasan.OBJID = jabatan_atasan.PLANS and posisi_atasan.RELAT = 3)
                     LEFT JOIN PA0001 PA0001_atasan on (PA0001_atasan.PERNR = PERNR_ATASAN)
                     LEFT JOIN M_STRUKTUR_JABATAN jabatan_bawahan on (jabatan_bawahan.PERNR = PERNR_BAWAHAN)
                     LEFT JOIN M_STRUKTUR_POSISI posisi_bawahan
                          on (posisi_bawahan.OBJID = jabatan_bawahan.PLANS and posisi_bawahan.RELAT = 3)
                     LEFT JOIN PA0001 PA0001_bawahan on (PA0001_bawahan.PERNR = PERNR_BAWAHAN)
                    where v_liquid_atasan.gsber IN ($gsber)
                ";
                DB::statement(DB::raw($query));
            }
        }

        Artisan::call('liquid:hapus-peserta-duplikat', ['liquid' => $liquid->getKey()]);

        $liquid->generatePesertaSnapshot();
    }

    public function getJadwalProgressStatus(Liquid $liquid)
    {
        return [
            'feedback' => strtotime(Carbon::parse($liquid->feedback_end_date))
            < strtotime('now') ? 'completed' : (
                strtotime(Carbon::parse($liquid->feedback_start_date))
            <= strtotime('now') && strtotime(Carbon::parse($liquid->feedback_end_date))
            >= strtotime('now') ? 'active' : ''
            ),
            'penyelarasan' => strtotime(Carbon::parse($liquid->penyelarasan_end_date))
            < strtotime('now') ? 'completed' : (
                strtotime(Carbon::parse($liquid->penyelarasan_start_date))
            <= strtotime('now') && strtotime(Carbon::parse($liquid->penyelarasan_end_date))
            >= strtotime('now') ? 'active' : ''
            ),
            'pengukuran_pertama' => strtotime(Carbon::parse($liquid->pengukuran_pertama_end_date))
            < strtotime('now') ? 'completed' : (
                strtotime(Carbon::parse($liquid->pengukuran_pertama_start_date))
            <= strtotime('now') && strtotime(Carbon::parse($liquid->pengukuran_pertama_end_date))
            >= strtotime('now') ? 'active' : ''
            ),
            'pengukuran_kedua' => strtotime(Carbon::parse($liquid->pengukuran_kedua_end_date))
            < strtotime('now') ? 'completed' : (
                strtotime(Carbon::parse($liquid->pengukuran_kedua_start_date))
            <= strtotime('now') && strtotime(Carbon::parse($liquid->pengukuran_kedua_end_date))
            >= strtotime('now') ? 'active' : ''
            ),
        ];
    }

    public function getGeneralInformation($unit, $divisi, $params = null)
    {
        $liquids = Liquid::query()
            ->activeForUnit($unit, $params)
            // ->currentYear()
            ->get();

        $listDivisiPusat = $this->listDivisiPusat()->keys();

        $pesertasCount =
            $atasansCount =
            $persenFeedbacks =
            $persenPenyelarasan =
            $persenPengukuranPertama =
            $persenPengukuranKedua = 0;

        foreach ($liquids as $liquid) {
            $liqPesertaId = collect();
            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    })
                    ->get();
            } else {
                $atasanWithPeserta = $liquid->peserta()->get();
            }

            $atasansCount += count($atasanWithPeserta->groupBy('atasan_id'));
            foreach ($atasanWithPeserta->groupBy('atasan_id') as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                collect($pesertaFiltered)
                    ->map(function ($peserta) use ($liqPesertaId) {
                        return $liqPesertaId->push($peserta['liquid_peserta_id']);
                    });

                $pesertasCount += count($pesertaFiltered);
            }
            $persenFeedbacks += Feedback::whereIn('liquid_peserta_id', $liqPesertaId->toArray())
                ->count();
            $persenPengukuranPertama += PengukuranPertama::whereIn('liquid_peserta_id', $liqPesertaId->toArray())
                ->count();
            $persenPengukuranKedua += PengukuranKedua::whereIn('liquid_peserta_id', $liqPesertaId->toArray())
                ->count();
            $persenPenyelarasan += $liquid
                ->penyelarasan()
                ->whereIn('atasan_id', $atasanWithPeserta->pluck('atasan_id')->toArray())
                ->count();
        }

        if ($pesertasCount !== 0) {
            $persenFeedbacks = $this->percentage((int) $persenFeedbacks, $pesertasCount);
            $persenPengukuranPertama = $this->percentage((int) $persenPengukuranPertama, $pesertasCount);
            $persenPengukuranKedua = $this->percentage((int) $persenPengukuranKedua, $pesertasCount);
        }

        if ($atasansCount !== 0) {
            $persenPenyelarasan = $this->percentage((int) $persenPenyelarasan, $atasansCount);
        }

        return [
            'atasans' => $atasansCount,
            'bawahans' => $pesertasCount,
            'persentase_feedback' => $persenFeedbacks,
            'persentase_penyelarasan' => $persenPenyelarasan,
            'persentase_pengukuran_pertama' => $persenPengukuranPertama,
            'persentase_pengukuran_kedua' => $persenPengukuranKedua
        ];
    }

    public function percentage($devided, $devider)
    {
        $devided /= $devider;

        if ($devided >= 1) {
            return 100;
        }

        return ceil($devided * 100);
    }

    public function hasLiquidBawahan()
    {
        if(auth()->user()->strukturJabatan!=null){
            $result = array_unique(auth()
                ->user()
                ->strukturJabatan
                ->bawahans
                ->pluck('liquid_id')
                ->toArray());

            return count($result);
        }

        return null;

    }

    public function hasLiquidAtasan()
    {
        if(auth()->user()->strukturJabatan!=null){
            $result = array_unique(auth()
                ->user()
                ->strukturJabatan
                ->atasans
                ->pluck('liquid_id')
                ->toArray());

            return count($result);
        }

        return null;
    }

    //TODO optimasi query
    public function getHistoryInformation($unit, $divisi, $year = null)
    {
        ini_set('memory_limit', -1);
        Cache::forget('liquid_information_history_'.auth()->user()->id);
        Cache::forget('liquid_information_history_jabatan_key_'.auth()->user()->id);

        $liquids = Liquid::query()
            ->with('logBook')
            ->activeForUnit($unit)
            ->when($year, function ($query) use ($year) {
                return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'");
            })
            ->get();
        $listDivisiPusat = $this->listDivisiPusat()->keys();
        $dataAtasanGroupedJabatan = [];
        $dataAtasan = [];

        foreach ($liquids as $liquid) {
            $dataPerAtasan = [];
            // Jika liquid pusat, maka filter peserta berdasar divisi user yang login

            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {

                        // $divisi === "0", artinya memilih divisi LAINNYA,
                        // Ini adalah peserta tambahan diluar divisi pusat, ciri2nya:
                        // 1. snapshot_jabatan_atasan/snapshot_jabatan_bawahan (GM, EVP, dll) null
                        // 2. selain itu, kita harus mengecek apakah peserta ini orgehnya masuk ke list orget pusat atau tidak
                        // Note: orgeh ada 3 level dan kita tidak tahu seorang user ada di level mana, jadinya kita cek satu persatu ke setiap level orgeh
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    })
                    ->get();
            } else {
                $atasanWithPeserta = $liquid->peserta()->with('feedback')->get();
            }

            foreach ($atasanWithPeserta->groupBy('atasan_id') as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                if (empty($pesertaFiltered)) {
                    continue;
                }

                $totalFeedback = 0;
                $totalPengukuranPertama = 0;
                $totalPengukuranKedua = 0;
                $pesertas->load('feedback', 'pengukuranPertama', 'pengukuranKedua', 'atasan');

                $dataPerAtasan[$i]['peserta'] = $pesertaFiltered;

                $dataPerAtasan[$i]['pernr'] = $i;
                $dataPerAtasan[$i]['atasan_snapshot'] = $liquid->peserta_snapshot[$i];
                $dataPerAtasan[$i]['peserta_count'] = count($dataPerAtasan[$i]['peserta']);
                $dataPerAtasan[$i]['liquid_id'] = $liquid->id;
                $dataPerAtasan[$i]['liquid_status'] = $liquid->getCurrentSchedule();
                $dataPerAtasan[$i]['has_penyelarasan'] = Penyelarasan::where('liquid_id', $liquid->id)->where('atasan_id', $i)->exists();
                $dataPerAtasan[$i]['force_pengukuran_kedua'] = object_get($pesertas->first(), 'force_pengukuran_kedua');

                $arrDiv = array_diff_key(
                    array_flip($pesertas->pluck('bawahan_id')->toArray()),
                    $pesertaFiltered
                );

                $pesertaFeedback = [];
                $pesertaNonFeedback = [];
                $pesertaPengukuranPertama = [];
                $pesertaNonPengukuranPertama = [];
                $pesertaPengukuranKedua = [];
                $pesertaNonPengukuranKedua = [];

                foreach ($pesertas as $key => $peserta) {
                    if (!array_has($arrDiv, $peserta->bawahan_id)) {
                        $dataPerAtasan[$i]['feedback_count'] = $totalFeedback += (int) ($peserta->feedback !== null);
                        $dataPerAtasan[$i]['pengukuran_pertama_count'] = $peserta->pengukuranPertama
                            ? $totalPengukuranPertama += 1
                            : $totalPengukuranPertama += 0;
                        $dataPerAtasan[$i]['pengukuran_kedua_count'] = $peserta->pengukuranKedua
                            ? $totalPengukuranKedua += 1
                            : $totalPengukuranKedua += 0;
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_feedback'] = !empty($peserta->feedback);
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_pengukuran_pertama'] = !empty($peserta->pengukuranPertama);
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_pengukuran_kedua'] = !empty($peserta->pengukuranKedua);

                        $name = $pesertaFiltered[$peserta->bawahan_id]['nama'];
                        $nip = $pesertaFiltered[$peserta->bawahan_id]['nip'];

                        if (!empty($peserta->feedback)) {
                            $pesertaFeedback[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonFeedback[] = $name . '/' . $nip;
                        }

                        if (!empty($peserta->pengukuranPertama)) {
                            $pesertaPengukuranPertama[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonPengukuranPertama[] = $name . '/' . $nip;
                        }

                        if (!empty($peserta->pengukuranKedua)) {
                            $pesertaPengukuranKedua[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonPengukuranKedua[] = $name . '/' . $nip;
                        }

                        $dataPerAtasan[$i]['peserta_done_feedback'] = $pesertaFeedback;
                        $dataPerAtasan[$i]['peserta_unfinished_feedback'] = $pesertaNonFeedback;
                        $dataPerAtasan[$i]['peserta_done_pengukuran_pertama'] = $pesertaPengukuranPertama;
                        $dataPerAtasan[$i]['peserta_unfinished_pengukuran_pertama'] = $pesertaNonPengukuranPertama;
                        $dataPerAtasan[$i]['peserta_done_pengukuran_kedua'] = $pesertaPengukuranKedua;
                        $dataPerAtasan[$i]['peserta_unfinished_pengukuran_kedua'] = $pesertaNonPengukuranKedua;
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['id'] = $peserta->id;
                        $dataPerAtasan[$i]['activity_log_book'] = [];

                        if (! empty($liquid->logBook) && $peserta->has('atasan')) {
                            foreach ($liquid->logBook as $logBook) {
                                if (isset($logBook->creatorAtasan->id) && isset($peserta->atasan->user->id)) {
                                    if ((int)$logBook->creatorAtasan->id === (int)$peserta->atasan->user->id) {
                                        $dataPerAtasan[$i]['activity_log_book'][] = $logBook;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $data = collect($dataPerAtasan)->sortBy('atasan_snapshot.nama')->toArray();

            if (!empty($data)) {
                $dataAtasan[] = $data;
            }
        }

        if (\Illuminate\Support\Facades\Route::currentRouteName() !== 'dashboard-admin.liquid-peserta.download') {
            foreach ($dataAtasan as $index => $atasans) {
                foreach ($atasans as $pernr => $atasan) {
                    if (isset($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                        if (!is_null($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                            $dataAtasanGroupedJabatan[$index][$atasan['atasan_snapshot']['kelompok_jabatan']]
                                [$pernr] = $atasan;
                        } else {
                            $dataAtasanGroupedJabatan[$index]['uncategorized']
                                [$pernr] = $atasan;
                        }
                    } else {
                        $dataAtasanGroupedJabatan[$index]['uncategorized']
                            [$pernr] = $atasan;
                    }
                }
            }

            Cache::forever('liquid_information_history_'.auth()->user()->id, $dataAtasanGroupedJabatan);

            $jabatanKey = array_map(function ($item) {
                return array_keys($item);
            }, $dataAtasanGroupedJabatan);

            Cache::forever('liquid_information_history_jabatan_key_'.auth()->user()->id, $jabatanKey);

            return $dataAtasanGroupedJabatan;
        }

        return $dataAtasan;
    }

    public function getHistoryInformation_forpeserta($unit, $divisi, $params = null)
    {
        ini_set('memory_limit', -1);
        Cache::forget('liquid_information_history_'.auth()->user()->id);
        Cache::forget('liquid_information_history_jabatan_key_'.auth()->user()->id);

        $liquids = Liquid::query()
            ->activeForUnit($unit, $params)
            // ->currentYear()
            ->get();
        $listDivisiPusat = $this->listDivisiPusat()->keys();
        $dataAtasanGroupedJabatan = [];
        $dataAtasan = [];

        foreach ($liquids as $liquid) {
            $dataPerAtasan = [];
            // Jika liquid pusat, maka filter peserta berdasar divisi user yang login

            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {

                        // $divisi === "0", artinya memilih divisi LAINNYA,
                        // Ini adalah peserta tambahan diluar divisi pusat, ciri2nya:
                        // 1. snapshot_jabatan_atasan/snapshot_jabatan_bawahan (GM, EVP, dll) null
                        // 2. selain itu, kita harus mengecek apakah peserta ini orgehnya masuk ke list orget pusat atau tidak
                        // Note: orgeh ada 3 level dan kita tidak tahu seorang user ada di level mana, jadinya kita cek satu persatu ke setiap level orgeh
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    })
                    ->get();
            } else {
                $atasanWithPeserta = $liquid->peserta()->with('feedback')->get();
            }

            foreach ($atasanWithPeserta->groupBy('atasan_id') as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                if (empty($pesertaFiltered)) {
                    continue;
                }

                $totalFeedback = 0;
                $totalPengukuranPertama = 0;
                $totalPengukuranKedua = 0;
                $pesertas->load('feedback', 'pengukuranPertama', 'pengukuranKedua', 'atasan');

                $dataPerAtasan[$i]['peserta'] = $pesertaFiltered;

                $dataPerAtasan[$i]['pernr'] = $i;
                $dataPerAtasan[$i]['atasan_snapshot'] = $liquid->peserta_snapshot[$i];
                $dataPerAtasan[$i]['peserta_count'] = count($dataPerAtasan[$i]['peserta']);
                $dataPerAtasan[$i]['liquid_id'] = $liquid->id;
                $dataPerAtasan[$i]['liquid_status'] = $liquid->getCurrentSchedule();
                $dataPerAtasan[$i]['has_penyelarasan'] = Penyelarasan::where('liquid_id', $liquid->id)->where('atasan_id', $i)->exists();
                $dataPerAtasan[$i]['force_pengukuran_kedua'] = object_get($pesertas->first(), 'force_pengukuran_kedua');

                $arrDiv = array_diff_key(
                    array_flip($pesertas->pluck('bawahan_id')->toArray()),
                    $pesertaFiltered
                );

                $pesertaFeedback = [];
                $pesertaNonFeedback = [];
                $pesertaPengukuranPertama = [];
                $pesertaNonPengukuranPertama = [];
                $pesertaPengukuranKedua = [];
                $pesertaNonPengukuranKedua = [];

                foreach ($pesertas as $key => $peserta) {
                    if (!array_has($arrDiv, $peserta->bawahan_id)) {
                        $dataPerAtasan[$i]['feedback_count'] = $totalFeedback += (int) ($peserta->feedback !== null);
                        $dataPerAtasan[$i]['pengukuran_pertama_count'] = $peserta->pengukuranPertama
                            ? $totalPengukuranPertama += 1
                            : $totalPengukuranPertama += 0;
                        $dataPerAtasan[$i]['pengukuran_kedua_count'] = $peserta->pengukuranKedua
                            ? $totalPengukuranKedua += 1
                            : $totalPengukuranKedua += 0;
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_feedback'] = !empty($peserta->feedback);
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_pengukuran_pertama'] = !empty($peserta->pengukuranPertama);
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['has_pengukuran_kedua'] = !empty($peserta->pengukuranKedua);

                        $name = $pesertaFiltered[$peserta->bawahan_id]['nama'];
                        $nip = $pesertaFiltered[$peserta->bawahan_id]['nip'];

                        if (!empty($peserta->feedback)) {
                            $pesertaFeedback[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonFeedback[] = $name . '/' . $nip;
                        }

                        if (!empty($peserta->pengukuranPertama)) {
                            $pesertaPengukuranPertama[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonPengukuranPertama[] = $name . '/' . $nip;
                        }

                        if (!empty($peserta->pengukuranKedua)) {
                            $pesertaPengukuranKedua[] = $name . '/' . $nip;
                        } else {
                            $pesertaNonPengukuranKedua[] = $name . '/' . $nip;
                        }

                        $dataPerAtasan[$i]['peserta_done_feedback'] = $pesertaFeedback;
                        $dataPerAtasan[$i]['peserta_unfinished_feedback'] = $pesertaNonFeedback;
                        $dataPerAtasan[$i]['peserta_done_pengukuran_pertama'] = $pesertaPengukuranPertama;
                        $dataPerAtasan[$i]['peserta_unfinished_pengukuran_pertama'] = $pesertaNonPengukuranPertama;
                        $dataPerAtasan[$i]['peserta_done_pengukuran_kedua'] = $pesertaPengukuranKedua;
                        $dataPerAtasan[$i]['peserta_unfinished_pengukuran_kedua'] = $pesertaNonPengukuranKedua;
                        $dataPerAtasan[$i]['peserta'][(int)$peserta->bawahan_id]['id'] = $peserta->id;
                        $dataPerAtasan[$i]['activity_log_book'] = [];

                        if (! empty($liquid->logBook) && $peserta->has('atasan')) {
                            foreach ($liquid->logBook as $logBook) {
                                if (isset($logBook->creatorAtasan->id) && isset($peserta->atasan->user->id)) {
                                    if ((int)$logBook->creatorAtasan->id === (int)$peserta->atasan->user->id) {
                                        $dataPerAtasan[$i]['activity_log_book'][] = $logBook;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $data = collect($dataPerAtasan)->sortBy('atasan_snapshot.nama')->toArray();

            if (!empty($data)) {
                $dataAtasan[] = $data;
            }
        }

        if (\Illuminate\Support\Facades\Route::currentRouteName() !== 'dashboard-admin.liquid-peserta.download') {
            foreach ($dataAtasan as $index => $atasans) {
                foreach ($atasans as $pernr => $atasan) {
                    if (isset($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                        if (!is_null($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                            $dataAtasanGroupedJabatan[$index][$atasan['atasan_snapshot']['kelompok_jabatan']]
                                [$pernr] = $atasan;
                        } else {
                            $dataAtasanGroupedJabatan[$index]['uncategorized']
                                [$pernr] = $atasan;
                        }
                    } else {
                        $dataAtasanGroupedJabatan[$index]['uncategorized']
                            [$pernr] = $atasan;
                    }
                }
            }

            Cache::forever('liquid_information_history_'.auth()->user()->id, $dataAtasanGroupedJabatan);

            $jabatanKey = array_map(function ($item) {
                return array_keys($item);
            }, $dataAtasanGroupedJabatan);

            Cache::forever('liquid_information_history_jabatan_key_'.auth()->user()->id, $jabatanKey);

            return $dataAtasanGroupedJabatan;
        }

        return $dataAtasan;
    }

    public function getHistoryInformationLessThan_3($unit, $divisi, $params = null)
    {
        Cache::forget('liquid_information_history_less_than_3_'.auth()->user()->id);
        Cache::forget('liquid_information_history_jabatan_key_less_than_3_'.auth()->user()->id);

        $liquids = Liquid::query()
            ->activeForUnit($unit, $params)
            // ->currentYear()
            ->get();
        $listDivisiPusat = $this->listDivisiPusat()->keys();
        $dataAtasanGroupedJabatan = [];
        $dataAtasan = [];

        foreach ($liquids as $liquid) {
            $dataPerAtasan = [];
            // Jika liquid pusat, maka filter peserta berdasar divisi user yang login

            if ($liquid->isPusat()) {
                $atasanWithPeserta = $liquid->peserta()
                    ->where(function ($query) use ($divisi, $listDivisiPusat) {

                        // $divisi === "0", artinya memilih divisi LAINNYA,
                        // Ini adalah peserta tambahan diluar divisi pusat, ciri2nya:
                        // 1. snapshot_jabatan_atasan/snapshot_jabatan_bawahan (GM, EVP, dll) null
                        // 2. selain itu, kita harus mengecek apakah peserta ini orgehnya masuk ke list orget pusat atau tidak
                        // Note: orgeh ada 3 level dan kita tidak tahu seorang user ada di level mana, jadinya kita cek satu persatu ke setiap level orgeh
                        if ($divisi === '0') {
                            $query->whereNotIn('snapshot_orgeh_1', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_2', $listDivisiPusat)
                                ->whereNotIn('snapshot_orgeh_3', $listDivisiPusat);
                        } else {
                            $query->where('snapshot_orgeh_1', $divisi)
                                ->orWhere('snapshot_orgeh_2', $divisi)
                                ->orWhere('snapshot_orgeh_3', $divisi);
                        }
                    })
                    ->get();
            } else {
                $atasanWithPeserta = $liquid->peserta()->with('feedback')->get();
            }

            foreach ($atasanWithPeserta->groupBy('atasan_id') as $i => $pesertas) {
                if (!isset($liquid->peserta_snapshot[$i])) {
                    continue;
                }

                // Filter bawahan sesuai data riil dari hasil query ke tabel liquid_peserta
                $bawahanIds = $pesertas->pluck('bawahan_id')->toArray();
                $pesertaFiltered = collect($liquid->peserta_snapshot[$i]['peserta'])->filter(function ($atasan, $key) use ($bawahanIds) {
                    return in_array($key, $bawahanIds);
                })->toArray();

                if (empty($pesertaFiltered)) {
                    continue;
                }

                $totalBawahan = count($pesertaFiltered);
                if($totalBawahan < 3) {
                    $totalFeedback = 0;
                    $totalPengukuranPertama = 0;
                    $totalPengukuranKedua = 0;
                    $pesertas->load('feedback', 'pengukuranPertama', 'pengukuranKedua', 'atasan');

                    $dataPerAtasan[$i]['peserta'] = $pesertaFiltered;

                    $dataPerAtasan[$i]['pernr'] = $i;
                    $dataPerAtasan[$i]['atasan_snapshot'] = $liquid->peserta_snapshot[$i];
                    $dataPerAtasan[$i]['peserta_count'] = count($dataPerAtasan[$i]['peserta']);
                    $dataPerAtasan[$i]['liquid_id'] = $liquid->id;
                    $dataPerAtasan[$i]['liquid_status'] = $liquid->getCurrentSchedule();
                    $dataPerAtasan[$i]['has_penyelarasan'] = Penyelarasan::where('liquid_id', $liquid->id)->where('atasan_id', $i)->exists();
                    $dataPerAtasan[$i]['force_pengukuran_kedua'] = object_get($pesertas->first(), 'force_pengukuran_kedua');

                    $arrDiv = array_diff_key(
                        array_flip($pesertas->pluck('bawahan_id')->toArray()),
                        $pesertaFiltered
                    );
                }
            }

            $data = collect($dataPerAtasan)->sortBy('atasan_snapshot.nama')->toArray();

            if (!empty($data)) {
                $dataAtasan[] = $data;
            }
        }

        if (\Illuminate\Support\Facades\Route::currentRouteName() !== 'dashboard-admin.liquid-peserta.download') {
            foreach ($dataAtasan as $index => $atasans) {
                foreach ($atasans as $pernr => $atasan) {
                    if (isset($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                        if (!is_null($atasan['atasan_snapshot']['kelompok_jabatan'])) {
                            $dataAtasanGroupedJabatan[$index][$atasan['atasan_snapshot']['kelompok_jabatan']]
                                [$pernr] = $atasan;
                        } else {
                            $dataAtasanGroupedJabatan[$index]['uncategorized']
                                [$pernr] = $atasan;
                        }
                    } else {
                        $dataAtasanGroupedJabatan[$index]['uncategorized']
                            [$pernr] = $atasan;
                    }
                }
            }

            Cache::forever('liquid_information_history_less_than_3_'.auth()->user()->id, $dataAtasanGroupedJabatan);

            $jabatanKey = array_map(function ($item) {
                return array_keys($item);
            }, $dataAtasanGroupedJabatan);

            Cache::forever('liquid_information_history_jabatan_key_less_than_3_'.auth()->user()->id, $jabatanKey);

            return $dataAtasanGroupedJabatan;
        }

        return $dataAtasan;
    }

    public function getLiquidWithFeedbacksAtasan(Liquid $liquid)
    {
        return $liquid->peserta()
            ->whereHas('feedback', function ($q) {
                $q->where('atasan_id', auth()->user()->strukturJabatan->pernr);
            })
            ->get()
            ->map(function ($item) {
                return $item->feedback;
            })
            ->toArray();
    }

    public function getDashboardAtasanDataChart(User $user, $params)
    {
        $liquid = Liquid::query()->activeForUnit($user->business_area)->forYear($params->year)->first();

        if (!$liquid) {
            return [
                'kelebihan_terbanyak_label' => null,
                'kekurangan_terbanyak_label' => null,
                'voter_kelebihan_terbanyak' => null,
                'voter_kekurangan_terbanyak' => null,
                'voter' => null,
                'sum_all_voter' => null,
            ];
        }

        $choosenKelebihan = [];
        $choosenKekurangan = [];
        $fiveMaxKelebihan = [];
        $fiveMaxKekurangan = [];
        $kkDetailsKelebihan = [];
        $kkDetailsKekurangan = [];
        $voterKelebihan = [];
        $voterKekurangan = [];

        $feedbacks = $liquid->peserta()
            ->has('feedback')
            ->where('atasan_id', $user->strukturJabatan->pernr)
            ->get()
            ->map(function ($item) {
                return $item->feedback;
            });

        foreach ($feedbacks as $feedback) {
            if (!empty($feedback)) {
                $choosenKekurangan =
                    array_merge(
                        $choosenKekurangan,
                        $feedback['kekurangan']
                    );

                $choosenKelebihan =
                    array_merge(
                        $choosenKelebihan,
                        $feedback['kelebihan']
                    );

                foreach ($feedback['kekurangan'] as $kekurangan) {
                    $voterKekurangan[] = KelebihanKekuranganDetail::withTrashed()
                        ->findOrFail($kekurangan)
                        ->deskripsi_kekurangan;
                }

                foreach ($feedback['kelebihan'] as $kelebihan) {
                    $voterKelebihan[] = KelebihanKekuranganDetail::withTrashed()
                        ->findOrFail($kelebihan)
                        ->deskripsi_kelebihan;
                }
            }
        }

        $voterKekurangan = array_count_values($voterKekurangan);
        $voterKelebihan = array_count_values($voterKelebihan);
        $choosenKekurangan = array_count_values($choosenKekurangan);
        $choosenKelebihan = array_count_values($choosenKelebihan);

        for ($i = 0; $i < 5; $i++) {
            if (!empty($choosenKekurangan)) {
                $max = max($choosenKekurangan);
                $index = array_search($max, $choosenKekurangan);
                $fiveMaxKekurangan[] = $index;
                unset($choosenKekurangan[$index]);
            }

            if (!empty($choosenKelebihan)) {
                $max = max($choosenKelebihan);
                $index = array_search($max, $choosenKelebihan);
                $fiveMaxKelebihan[] = $index;
                unset($choosenKelebihan[$index]);
            }
        }

        for ($i = 0; $i < count($fiveMaxKekurangan); $i++) {
            $kkDetailsKekurangan[] = KelebihanKekuranganDetail::withTrashed()
                ->findOrFail($fiveMaxKekurangan[$i])
                ->deskripsi_kekurangan;
        }

        for ($i = 0; $i < count($fiveMaxKelebihan); $i++) {
            $kkDetailsKelebihan[] = KelebihanKekuranganDetail::withTrashed()
                ->findOrFail($fiveMaxKelebihan[$i])
                ->deskripsi_kelebihan;
        }

        return [
            'kelebihan_terbanyak_label' => $kkDetailsKelebihan,
            'kekurangan_terbanyak_label' => $kkDetailsKekurangan,
            'voter_kelebihan_terbanyak' => array_values($voterKelebihan),
            'voter_kekurangan_terbanyak' => array_values($voterKekurangan),
            'voter' => count($feedbacks),
            'sum_all_voter' => $liquid->peserta()
                ->where('atasan_id', $user->strukturJabatan->pernr)
                ->count('bawahan_id'),
        ];
    }

    public function resolusi($atasanId, $params, $liquid = null)
    {   
        $year = $params->feedback_start_date->format('Y');
        if ($liquid === null) {
            // codingan lama : $liquid = Liquid::query()->activeForUser($atasanId)->forYear($year)->first();
            // mengganti scope activeForUser dengan forAtasan, karena scope activeForUser masih terdapat filter currentYear
            $liquid = Liquid::query()->forAtasan(auth()->user())->published()->forYear($year)->first();
        }

        if ($liquid === null) {
            return collect();
        }

        $penyelarasan = $liquid->penyelarasan()->where('atasan_id', $atasanId)->first();
        if ($penyelarasan === null) {
            return collect();
        }

        $resolusi = KelebihanKekuranganDetail::withTrashed()->whereIn('id', $penyelarasan->resolusi)->get();
        $data = [];
        foreach ($resolusi as $index => $res) {
            $data[$res->getKey()] = [
                'index' => $index,
                'id' => $res->getKey(),
                'label' => 'Resolusi '.($index + 1),
                'resolusi' => $res->deskripsi_kelebihan,
                'resolusi_kekurangan' => $res->deskripsi_kekurangan,
                'aksi_nyata' => array_get($penyelarasan->aksi_nyata, $index),
                'pengukuran_pertama' => [],
                'pengukuran_kedua' => [],
            ];
        }

        $listPeserta = $liquid->peserta()
            ->with('pengukuranPertama', 'pengukuranKedua')
            ->where('atasan_id', $atasanId)
            ->get();
        foreach ($listPeserta as $peserta) {
            $pengukuranPertama = $peserta->pengukuranPertama;
            if ($pengukuranPertama) {
                foreach ($pengukuranPertama->penilaian() as $kkDetail => $nilai) {
                    if (isset($data[$kkDetail])) {
                        $data[$kkDetail]['pengukuran_pertama'][] = $nilai;
                    }
                }
            }

            $pengukuranKedua = $peserta->pengukuranKedua;
            if ($pengukuranKedua) {
                foreach ($pengukuranKedua->penilaian() as $kkDetail => $nilai) {
                    if (isset($data[$kkDetail])) {
                        $data[$kkDetail]['pengukuran_kedua'][] = $nilai;
                    }
                }
            }
        }

        return collect($data)->transform(function ($item) {
            $item['avg_pengukuran_pertama'] = collect($item['pengukuran_pertama'])->avg();
            $item['avg_pengukuran_kedua'] = collect($item['pengukuran_kedua'])->avg();
            $item['jumlah_penilai_pengukuran_pertama'] = $item['jumlah_penilai_pengukuran_kedua'] = [
                RangeNilaiPengukuran::SANGAT_JARANG => 0,
                RangeNilaiPengukuran::JARANG => 0,
                RangeNilaiPengukuran::KADANG_KADANG => 0,
                RangeNilaiPengukuran::SERING => 0,
                RangeNilaiPengukuran::SANGAT_SERING => 0,
            ];
            foreach ($item['pengukuran_pertama'] as $nilai) {
                $item['jumlah_penilai_pengukuran_pertama'][app_nilai_to_range($nilai)] += 1;
            }
            foreach ($item['pengukuran_kedua'] as $nilai) {
                $item['jumlah_penilai_pengukuran_kedua'][app_nilai_to_range($nilai)] += 1;
            }

            return $item;
        });
    }

    public function countAvgPenilaian($penilaian)
    {
        $counted = array_count_values($penilaian);
        $avg = [];

        foreach ($counted as $index => $item) {
            $exploded = explode(':', $index)[1];
            $avg[] = ($item * $exploded) / $item;
        }

        return $avg;
    }

    public function getLiquidWithPenilaian(Liquid $liquid)
    {
        return $liquid->peserta()
            ->where(
                'atasan_id',
                auth()->user()
                    ->strukturJabatan
                    ->pernr
            )
            ->with('feedback')
            ->with('pengukuranPertama')
            ->with('pengukuranKedua')
            ->get();
    }

    public function getHistoryPenilaianAtasan(User $atasan)
    {
        $penilaian = [];
        $pengukuranPertama = [];
        $pengukuranKedua = [];
        $userPernr = $atasan->strukturJabatan->pernr;

        $liquids = Liquid::query()->published()->forUser($atasan)->get();

        if ($liquids->isEmpty()) {
            return collect();
        }

        foreach ($liquids as $key => $liquid) {
            $liquidWithPenilaian = $this->getLiquidWithPenilaian($liquid);
            $key = $liquid->getKey();

            if (!empty($liquidWithPenilaian)) {
                foreach ($liquidWithPenilaian as $i => $peserta) {
                    if (!empty($liquid->peserta_snapshot)) {
                        $penilaian[$key]['atasan'] = $liquid->peserta_snapshot[$userPernr];
                        $penilaian[$key]['atasan']['business_area'] = BusinessArea::where(
                            'business_area',
                            DB::table('v_liquid_peserta_snapshot')
                                ->where('liquid_id', $liquid->id)
                                ->where('nip_atasan', $penilaian[$key]['atasan']['nip'])
                                ->first()
                                ->business_area_atasan
                        )
                            ->first();
                    }

                    if (!empty($peserta->pengukuranPertama->resolusi)) {
                        $pengukuranPertama = array_merge($pengukuranPertama, $peserta->pengukuranPertama->resolusi);
                    }

                    if (!empty($peserta->pengukuranKedua->resolusi)) {
                        $pengukuranKedua = array_merge($pengukuranKedua, $peserta->pengukuranKedua->resolusi);
                    }
                }

                $penilaian[$key]['liquid'] = $liquid;
                $penilaian[$key]['pengukuran_pertama'] = $this->countAvgPenilaian($pengukuranPertama);
                $penilaian[$key]['pengukuran_kedua'] = $this->countAvgPenilaian($pengukuranKedua);
                $penilaian[$key]['resolusi'] = $this->resolusi($userPernr, $liquid);
            }
        }

        return collect($penilaian)->filter(function ($item) {
            return isset($item['liquid']) && isset($item['atasan']);
        })->toArray();
    }

    public function getHistoryPenilaianBawahan()
    {
        $penilaian = [];
        $pengukuranPertama = [];
        $pengukuranKedua = [];

        $liquids = Liquid::query()->published()->currentYear()->forBawahan(auth()->user())->get();
        $pesertaAtasan = collect();

        foreach ($liquids as $liquid) {
            $eS = $liquid->peserta()->where(
                'bawahan_id',
                auth()
                    ->user()
                    ->strukturJabatan
                    ->pernr
            )
                ->with('feedback')
                ->with('pengukuranPertama')
                ->with('pengukuranKedua')
                ->get();

            if ($eS) {
                foreach ($eS as $e) {
                    $pesertaAtasan->push($e);
                }
            }
        }

        if ($pesertaAtasan->isEmpty()) {
            return [];
        }

        foreach ($pesertaAtasan as $index => $atasan) {
            if (! empty($atasan->liquid)) {
                $snapshotAtasan = $atasan->liquid->peserta_snapshot[$atasan->atasan_id];
                $penilaian[$index]['atasan'] = $snapshotAtasan;

                $unit = BusinessArea::where('business_area', $snapshotAtasan['business_area'])->first();
                $penilaian[$index]['atasan']['unit_code'] = data_get($unit, 'business_area');
                $penilaian[$index]['atasan']['unit_name'] = data_get($unit, 'description');
                $penilaian[$index]['liquid'] = $atasan->liquid;
            }

            if (! empty($atasan->pengukuranPertama->resolusi)) {
                $pengukuranPertama = array_merge($pengukuranPertama, $atasan->pengukuranPertama->resolusi);
            }

            if (! empty($atasan->pengukuranKedua->resolusi)) {
                $pengukuranKedua = array_merge($pengukuranKedua, $atasan->pengukuranKedua->resolusi);
            }

            $kelebihan = $kekurangan = [];
            if (! empty($atasan->feedback)) {
                $kelebihan = $atasan->feedback->getKelebihanAsArray();
                $kekurangan = $atasan->feedback->getKekuranganAsArray();
            }

            $penilaian[$index]['feedback_kelebihan'] = $kelebihan;
            $penilaian[$index]['feedback_kekurangan'] = $kekurangan;
            $penilaian[$index]['pengukuran_pertama'] = $this->countAvgPenilaian($pengukuranPertama);
            $penilaian[$index]['pengukuran_kedua'] = $this->countAvgPenilaian($pengukuranKedua);
            $penilaian[$index]['penilaian'] = $atasan->getPenilaian();
        }

        return $penilaian;
    }

    private function toCalendarArray($liquids, $mode)
    {
        $jadwal = collect();
        $id = 1;

        foreach ($liquids as $liquid) {
            $linkToFeedback = in_array($mode, [self::JADWAL_BAWAHAN]);
            $linkToPenyelarasan = in_array($mode, [self::JADWAL_ATASAN]);
            $linkToPenilaian = in_array($mode, [self::JADWAL_BAWAHAN]);

            // feedback
            $jadwal->push([
                'id' => $id++,
                'title' => 'Pelaksanaan Feedback',
                'allDay' => 1,
                'start' => $liquid->feedback_start_date->toAtomString(),
                'end' => Carbon::parse($liquid->feedback_end_date)->addDays(1)->toAtomString(),
                'className' => 'bg-success',
                'url' => $linkToFeedback
                    ? route('feedback.index', ['liquid_id' => $liquid->id])
                    : '',
            ]);

            // penyelarasan
            $jadwal->push([
                'id' => $id++,
                'title' => 'Pelaksanaan Penyelarasan',
                'allDay' => 1,
                'start' => $liquid->penyelarasan_start_date->toAtomString(),
                'end' => Carbon::parse($liquid->penyelarasan_end_date)->addDays(1)->toAtomString(),
                'className' => 'bg-red',
                'url' => $linkToPenyelarasan ? url('penyelarasan')."?liquid_id=$liquid->id" : '',
            ]);

            // pengukuran 1
            $jadwal->push([
                'id' => $id++,
                'title' => 'Pengukuran Pertama',
                'allDay' => 1,
                'start' => $liquid->pengukuran_pertama_start_date->toAtomString(),
                'end' => Carbon::parse($liquid->pengukuran_pertama_end_date)->addDays(1)->toAtomString(),
                'className' => 'bg-purple',
                'url' => $linkToPenilaian
                    ? url('penilaian')."?liquid_id=$liquid->id"
                    : '',
            ]);

            // pengukuran 2
            $jadwal->push([
                'id' => $id++,
                'title' => 'Pengukuran Kedua',
                'allDay' => 1,
                'start' => $liquid->pengukuran_kedua_start_date->toAtomString(),
                'end' => Carbon::parse($liquid->pengukuran_kedua_end_date)->addDays(1)->toAtomString(),
                'className' => 'bg-orange',
                'url' => $linkToPenilaian
                    ? url('pengukuran-kedua')
                    : '',
            ]);
        }

        return $jadwal->toArray();
    }
}
