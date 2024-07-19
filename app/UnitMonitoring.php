<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnitMonitoring extends Model
{
    protected $table = 'm_unit_monitoring';

    protected $fillable = [
        'orgeh',
        'company_code',
        'business_area',
        'nama_unit',
        'target_realisasi_coc',
        'status'
    ];

    public function getPersentaseCoc($selected_week, $selected_bulan, $selected_tahun)
    {
        // get arr orgeh
        $arr_org = [$this->orgeh];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->orgeh));
        // merge with the parent unit

        // $x = 1;
        // foreach($arr_org as $org){
        //     $org = StrukturOrganisasi::where('objid', $org)->first();
        //     echo $x.". ".$org->objid . " - " . $org->stext . "<br>";
        //     $x++;
        // }

        // dd($arr_org);

        if($arr_org == null)
            return null;

        // get start date and end date
        $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
        $startOfWeek->addWeeks($selected_week - 1);
    
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

        // echo $startOfWeek . " - " . $endOfWeek . "<br>";

        // get all CoC in the selected month
        // $coc_list = Coc::with('attendants')
        //     ->withCount('attendants')
        //     ->where('jenis_coc_id', 1)
        //     ->whereIn('orgeh', $arr_org)
        //     ->whereDate('tanggal_jam', '>=', $startOfWeek)
        //     ->whereDate('tanggal_jam', '<=', $endOfWeek)
        //     ->get();

        // get attendance count
        $coc_list = Coc::select('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta', DB::raw('COUNT(attendant.id) as attendants_count'))
            ->leftJoin('attendant', 'attendant.coc_id', '=', 'coc.id')
            ->where('coc.jenis_coc_id', 1)
            ->whereIn('coc.orgeh', $arr_org)
            ->whereDate('coc.tanggal_jam', '>=', $startOfWeek)
            ->whereDate('coc.tanggal_jam', '<=', $endOfWeek)
            ->groupBy('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta')
            ->get();

        // get percentage of attendance
        $total_attendance = $coc_list->sum('attendants_count');
        $total_peserta = $coc_list->sum('jml_peserta');

        // if attendance or peserta is null, return 0
        if($total_peserta == 0 || $total_attendance == 0)
            return 0;

        $percentage = $total_attendance / $total_peserta * 100;
        // dd($total_attendance, $total_peserta, $percentage);

        return $percentage;
    }

    public function getPersentaseCocWeeks($selected_bulan, $selected_tahun)
    {
        // get arr orgeh
        $arr_org = [$this->orgeh];
        // get child unit
        $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($this->orgeh));
        // merge with the parent unit

        // $x = 1;
        // foreach($arr_org as $org){
        //     $org = StrukturOrganisasi::where('objid', $org)->first();
        //     echo $x.". ".$org->objid . " - " . $org->stext . "<br>";
        //     $x++;
        // }

        // dd($arr_org);

        if($arr_org == null)
            return null;

        $arr_week_percentage = array();

        // loop weeks
        for($selected_week = 1; $selected_week <= 5; $selected_week++){
            // get start date and end date
            $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
            $startOfWeek->addWeeks($selected_week - 1);
        
            $endOfWeek = clone $startOfWeek;
            $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

            // echo $startOfWeek . " - " . $endOfWeek . "<br>";

            // get all CoC in the selected month
            // $coc_list = Coc::with('attendants')
            //     ->withCount('attendants')
            //     ->where('jenis_coc_id', 1)
            //     ->whereIn('orgeh', $arr_org)
            //     ->whereDate('tanggal_jam', '>=', $startOfWeek)
            //     ->whereDate('tanggal_jam', '<=', $endOfWeek)
            //     ->get();

            // get attendance count
            $coc_list = Coc::select('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta', DB::raw('COUNT(attendant.id) as attendants_count'))
                ->leftJoin('attendant', 'attendant.coc_id', '=', 'coc.id')
                ->where('coc.jenis_coc_id', 1)
                ->whereIn('coc.orgeh', $arr_org)
                ->whereDate('coc.tanggal_jam', '>=', $startOfWeek)
                ->whereDate('coc.tanggal_jam', '<=', $endOfWeek)
                ->groupBy('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta')
                ->get();

            // get percentage of attendance
            $total_attendance = $coc_list->sum('attendants_count');
            $total_peserta = $coc_list->sum('jml_peserta');

            // if attendance or peserta is null, return 0
            if($total_peserta == 0 || $total_attendance == 0){
                $percentage = 0;
            }
            else{
                $percentage = $total_attendance / $total_peserta * 100;
            }
            // dd($total_attendance, $total_peserta, $percentage);
            // echo $selected_week.". ".$total_attendance . " - " . $total_peserta . " - " . $percentage . "<br>";

            // push to array
            $arr_week_percentage[$selected_week] = $percentage;

        }

        return $arr_week_percentage;
    }

    public function getPersentaseReadMateriWeeks($selected_bulan, $selected_tahun)
    {
        // organisasi
        $organisasi = StrukturOrganisasi::where('objid', $this->orgeh)->first();

        // get arr orgeh
        $arr_org = [$organisasi->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $organisasi->getArrChildOrgeh($organisasi->objid));

        if($arr_org == null)
            return null;

        $arr_nip = User::where('status', 'ACTV')
            ->whereIn('orgeh', $arr_org)
            ->pluck('nip')
            ->toArray();
        
        $total_pegawai = count($arr_nip);

        $arr_week_percentage = array();

        // loop weeks
        for($selected_week = 1; $selected_week <= 5; $selected_week++){

            // get range tanggal
            $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
            $startOfWeek->addWeeks($selected_week - 1);
        
            $endOfWeek = clone $startOfWeek;
            $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

            // get id materi where tanggal between startOfWeek and endOfWeek
            $materi = Materi::where('jenis_materi_id', '1')
                        ->whereDate('tanggal', '>=', $startOfWeek)
                        ->whereDate('tanggal', '<=', $endOfWeek)
                        ->first();

            // get read materi count
            if($materi != null) {
                // $total_reader = $materi->reader()->whereIn('nip', $arr_nip)->distinct()->count('nip');

                $chunks = array_chunk($arr_nip, 1000);

                $total_reader = 0;
                foreach($chunks as $chunk){
                    $reader_list = $materi->reader()
                                        ->whereIn('nip', $chunk)
                                        ->get();
                    $reader_list = $reader_list->unique('user_id');
                    $total_reader += $reader_list->count();
                }

                // $reader_list = $materi->reader()
                //                         ->whereIn('nip', $arr_nip)
                //                         ->get();

                // // // remove duplicate user
                // $reader_list = $reader_list->unique('user_id');
                // $total_reader = $reader_list->count();
            }
            else
                $total_reader = 0;

            // get attendance count
            // $coc_list = Coc::select('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta', DB::raw('COUNT(attendant.id) as attendants_count'))
            //     ->leftJoin('attendant', 'attendant.coc_id', '=', 'coc.id')
            //     ->where('coc.jenis_coc_id', 1)
            //     ->whereIn('coc.orgeh', $arr_org)
            //     ->whereDate('coc.tanggal_jam', '>=', $startOfWeek)
            //     ->whereDate('coc.tanggal_jam', '<=', $endOfWeek)
            //     ->groupBy('coc.id', 'coc.jenis_coc_id', 'coc.orgeh', 'coc.tanggal_jam', 'coc.jml_peserta')
            //     ->get();

            // get percentage of attendance
            // $total_attendance = $coc_list->sum('attendants_count');
            // $total_peserta = $coc_list->sum('jml_peserta');

            // if attendance or peserta is null, return 0
            if($total_pegawai == 0 || $total_reader == 0){
                $percentage = 0;
            }
            else{
                $percentage = $total_reader / $total_pegawai * 100;
            }

            // push to array
            $arr_week_percentage[$selected_week] = $percentage;

        }

        return $arr_week_percentage;
    }

    public function getArrChildOrgeh($orgeh)
    {
        $arr_org = array();
        $org = StrukturOrganisasi::where('status','ACTV')->where('sobid', $orgeh)->whereNotNull('level')->get();
        foreach($org as $o){
            $arr_org[] = $o->objid;
            $arr_org = array_merge($arr_org, $this->getArrChildOrgeh($o->objid));
        }

        // remove duplicate
        $arr_org = array_unique($arr_org);

        return $arr_org;
    }
}
