<?php

namespace App\Http\Controllers\Api;

use App\Coc;
use App\CompanyCode;
use App\Http\Controllers\Controller;
use App\Materi;
use App\RealisasiCoc;
use App\Role;
use App\Services\Datatable;
use App\StrukturOrganisasi;
use App\UnitMonitoring;
use App\User;
use App\Utils\UnitKerjaUtil;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class ReportController extends Controller
{
    protected $limit;
    protected $start;
    protected $order;
    protected $dir;
    protected $searchVal;

    public function __construct()
    {
        $this->start = request()->input('start');
        $this->limit = request()->input('length');
        $this->order = request()->input('order.0.column');
        $this->dir = request()->input('order.0.dir');
        $this->searchVal = request()->input('search.value');
    }

    public function fetchHistoryCoc()
    {
        $columns = [
            'tanggal_coc',
            'check_in',
            'judul',
            'pemateri',
            'lokasi'
        ];
        $totalData = Auth::user()->attendant()->count();

        $totalFiltered = $totalData;

        if (!empty($this->searchVal)) {
            $userAttendantsQuery = Auth::user()->attendant()->with(['coc', 'coc.pemateri'])
                ->orWhere('check_in', 'LIKE', '%'.$this->searchVal.'%')
                ->orWhere('lokasi', 'LIKE', '%'.$this->searchVal.'%')
                ->orWhereHas('coc', function ($q) {
                    $q->orWhere('tanggal_jam', 'LIKE', '%'.$this->searchVal.'%')
                        ->orWhere('lokasi', 'LIKE', '%'.$this->searchVal.'%')
                        ->orWhere('judul', 'LIKE', '%'.$this->searchVal.'%')
                        ->orWhereHas('pemateri', function ($q) {
                            $q->orWhere('cname', 'LIKE', '%'.$this->searchVal.'%');
                        });
                });
            $totalFiltered = $userAttendantsQuery->count();
            $userAttendants = $userAttendantsQuery->offset($this->start)
                    ->limit($this->limit)
                    ->get();
        } else {
            $userAttendants = Auth::user()->attendant()->with(['coc', 'coc.pemateri'])
                ->offset($this->start)
                ->limit($this->limit)
                ->get();
        }

        $data = collect();
        if (!empty($userAttendants)) {
            foreach ($userAttendants as $attendant) {
                $nestedData['tanggal_coc'] = $attendant->coc->tanggal_jam->format('Y-m-d h:i');
                $nestedData['check_in'] = $attendant->check_in->format('Y-m-d h:i');
                $nestedData['judul'] = '<a href="'.url('coc/event/'.$attendant->coc->id).'">'.$attendant->coc->judul.'</a>';
                if ($attendant->coc->scope != 'nasional') {
                    $nestedData['pemateri'] = '<div class="pull-left" style="margin-right: 15px;">';
                    if (@$attendant->coc->pemateri->user->foto != '') {
                        $userPhoto = url('user/foto-thumb/'.@$attendant->coc->pemateri->user->id);
                    } else {
                        $userPhoto = asset('assets/images/user.jpg');
                    }
                    $nestedData['pemateri'] .= '<img src="'.$userPhoto.'" alt="User" class="img-thumbnail" width="45">';
                    $nestedData['pemateri'] .= '</div>';
                    $nestedData['pemateri'] .= '<div class="pull-left">';
                    $nestedData['pemateri'] .= @$attendant->coc->pemateri->cname;
                    $nestedData['pemateri'] .= '<br><small class="text-muted">'.@$attendant->coc->pemateri->nip.'-'.@$attendant->coc->pemateri->strukturPosisi->stext;
                    $nestedData['pemateri'] .= '</small>';
                    $nestedData['pemateri'] .= '</div>';
                } else {
                    $nestedData['pemateri'] = '';
                }
                $nestedData['lokasi'] = $attendant->coc->lokasi;

                $data->push($nestedData);
            }
        }

        if (! is_null($this->order)) {
            $sortBy = $columns[$this->order];

            if ($this->dir === 'asc') {
                $data = $data->sortBy($sortBy);
            } elseif ($this->dir === 'desc') {
                $data = $data->sortByDesc($sortBy);
            }
        }

        $jsonData = [
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data->values()->toArray()
        ];

        return response()->json($jsonData);
    }

    public function fetchReportCoc(Request $request)
    {
        $columns = [
            'no',
            'judul_coc',
            'tema',
            'jenis',
            'coc_leader',
            'unit_bidang',
            'lokasi',
            'tanggal_jam',
            'peserta',
            'persentase_peserta',
            'admin_coc',
            'status'
        ];
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        $user = Auth::user();
        $ba_selected = request('business_area');

        if (empty($ba_selected)) {
            $ba_selected = (new UnitKerjaUtil)->shiftingBusinessArea($user);
        } else {
            $ba_selected = [$ba_selected];
        }

        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('business_area', $ba_selected)
            ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
            ->where('status', $request->status_coc)
            ->get(['id'])
            ->toArray();

        $totalFiltered = 0;
        $totalData = Coc::whereIn('id', $coc_unit)->count();

        if (! empty($this->searchVal)) {
            $keyword = strtolower($this->searchVal);
            $coc_list = Coc::query()
                ->where(function ($q) use ($coc_unit) {
                    $q->whereIn('coc.id', $coc_unit);
                })
                ->where(function ($q) use ($keyword) {
                    $q->orWhere(DB::raw('lower(judul)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere(DB::raw('lower(lokasi)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere(DB::raw('lower(status)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere(DB::raw('lower(tanggal_jam)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere(DB::raw('lower(jml_peserta)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere(DB::raw('lower(jml_peserta_dispensasi)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhereHas('tema', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(tema)'), 'LIKE', '%'.$keyword.'%');
                    })
                    ->orWhereHas('jenis', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(jenis)'), 'LIKE', '%'.$keyword.'%');
                    })
                    ->orWhereHas('realisasi', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(realisasi)'), 'LIKE', '%'.$keyword.'%')
                            ->orWhereHas('leader', function ($q) use ($keyword) {
                                $q->orWhere(DB::raw('lower(cname)'), 'LIKE', '%'.$keyword.'%')
                                    ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                                    ->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                        $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                                    });
                            });
                    })
                    ->orWhereHas('leader', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(cname)'), 'LIKE', '%'.$keyword.'%')
                            ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                            ->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                            });
                    })
                    ->orWhereHas('organisasi', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                    })
                    ->orWhereHas('admin', function ($q) use ($keyword) {
                        $q->orWhere(DB::raw('lower(name)'), 'LIKE', '%'.$keyword.'%')
                            ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                            ->orWhereHas('strukturJabatan', function ($q) use ($keyword) {
                                $q->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                    $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                                });
                            });
                    });
                })
                ->offset($this->start)
                ->limit($this->limit)
                ->get();

            $totalFiltered = count($coc_list);
        } else {
            $coc_list = Coc::whereIn('id', $coc_unit)->offset($this->start)->limit($this->limit)->get();
            $totalFiltered = count($coc_unit);
        }

        $data = collect();
        if (! empty($coc_list)) {
            $index = 0;
            foreach ($coc_list as $coc) {
                $nestedData['no'] = $index += 1;
                $nestedData['judul_coc'] = '<a href="'.url('coc/event/'.$coc->id).'">';
                $nestedData['judul_coc'] .= $coc->judul;
                $nestedData['judul_coc'] .= '</a>';
                $nestedData['tema'] = $coc->tema->tema;
                $nestedData['jenis'] = @$coc->jenis->jenis;
                if (! is_null($coc->realisasi)) {
                    $cName = $coc->realisasi->leader->cname;
                    $nip = @$coc->realisasi->leader->nip;
                    $posisi = @$coc->realisasi->leader->strukturPosisi->stext;
                    $tanggal_jam = @$coc->realisasi->realisasi->format('Y-m-d H:i');
                } else {
                    $cName = @$coc->leader->cname;
                    $nip = @$coc->leader->nip;
                    $posisi = @$coc->leader->strukturPosisi->stext;
                    $tanggal_jam = @$coc->tanggal_jam->format('Y-m-d H:i');
                }
                $nestedData['coc_leader'] = sprintf(
                    '%s <br><small class="text-muted">%s</small>',
                    $cName,
                    "$nip - $posisi"
                );
                $nestedData['unit_bidang'] = @$coc->organisasi->stext;
                $nestedData['lokasi'] = @$coc->lokasi;
                $nestedData['tanggal_jam'] = $tanggal_jam;
                $nestedData['peserta'] = sprintf(
                    '%s/%s',
                    $coc->attendants->count(),
                    $coc->jml_peserta - $coc->jml_peserta_dispensasi
                );
                $nestedData['persentase_peserta'] = number_format(($coc->attendants->count() / ($coc->jml_peserta - $coc->jml_peserta_dispensasi)) * 100, 2).'%';
                $nestedData['admin_coc'] = sprintf(
                    '%s <br><small class="text-muted">%s - %s </small>',
                    @$coc->admin->name,
                    @$coc->admin->nip,
                    @$coc->admin->strukturPosisi()->stext
                );

                if ($coc->status === 'OPEN') {
                    $label = 'label-success';
                } elseif ($coc->status === 'CANC') {
                    $label = 'label-danger';
                } else {
                    $label = 'label-primary';
                }
                $nestedData['status'] = sprintf(
                    '<span class="label %s">%s</span>',
                    $label,
                    $coc->status
                );

                $data->push($nestedData);
            }
        }

        if (! is_null($this->order)) {
            $sortBy = $columns[$this->order];

            if ($this->dir === 'asc') {
                $data = $data->sortBy($sortBy);
            } elseif ($this->dir === 'desc') {
                $data = $data->sortByDesc($sortBy);
            }
        }

        $jsonData = [
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data->values()->toArray()
        ];

        return response()->json($jsonData);
    }

    public function fetchStatusCocCompanyCode (Request $request)
    {
        $columns = [
            'no',
            'business_area',
            'judul_coc',
            'tema',
            'jenis',
            'coc_leader',
            'unit_bidang',
            'lokasi',
            'tanggal_jam',
            'peserta',
            'persentase_peserta',
            'admin_coc',
            'status'
        ];
        $now = Carbon::now()->format('d-m-Y');
        $tgl_awal = Date::parse(request('start_date', $now));
        $tgl_akhir = Date::parse(request('end_date', $now));

        $user = Auth::user();
        $cc_selected = request('company_code');

        if (empty($cc_selected)) {
            $cc_selected = (new UnitKerjaUtil())->shiftingCompanyCode($user, false);
        } else {
            $cc_selected = [$cc_selected];
        }

        // kode eloquent atau query builder atau semacamnya
        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('company_code', $cc_selected)
            ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
            ->where('status', $request->status_coc)
            ->get(['id'])
            ->toArray();

        $totalData = Coc::whereIn('id', $coc_unit)->count();
        if (! empty($this->searchVal)) {
            $keyword = strtolower($this->searchVal);
            $coc_list = Coc::query()
                ->where(function ($q) use ($coc_unit) {
                    $q->whereIn('coc.id', $coc_unit);
                })
                ->where(function ($q) use ($keyword) {
                    $q->orWhere(DB::raw('lower(judul)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(business_area)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(lokasi)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(status)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(tanggal_jam)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(jml_peserta)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhere(DB::raw('lower(jml_peserta_dispensasi)'), 'LIKE', '%'.$keyword.'%')
                        ->orWhereHas('businessArea', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(description)'), 'LIKE', '%'.$keyword.'%');
                        })
                        ->orWhereHas('tema', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(tema)'), 'LIKE', '%'.$keyword.'%');
                        })
                        ->orWhereHas('jenis', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(jenis)'), 'LIKE', '%'.$keyword.'%');
                        })
                        ->orWhereHas('realisasi', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(realisasi)'), 'LIKE', '%'.$keyword.'%')
                                ->orWhereHas('leader', function ($q) use ($keyword) {
                                    $q->orWhere(DB::raw('lower(cname)'), 'LIKE', '%'.$keyword.'%')
                                        ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                                        ->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                            $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                                        });
                                });
                        })
                        ->orWhereHas('leader', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(cname)'), 'LIKE', '%'.$keyword.'%')
                                ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                                ->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                    $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                                });
                        })
                        ->orWhereHas('organisasi', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                        })
                        ->orWhereHas('admin', function ($q) use ($keyword) {
                            $q->orWhere(DB::raw('lower(name)'), 'LIKE', '%'.$keyword.'%')
                                ->orWhere(DB::raw('lower(nip)'), 'LIKE', '%'.$keyword.'%')
                                ->orWhereHas('strukturJabatan', function ($q) use ($keyword) {
                                    $q->orWhereHas('strukturPosisi', function ($q) use ($keyword) {
                                        $q->orWhere(DB::raw('lower(stext)'), 'LIKE', '%'.$keyword.'%');
                                    });
                                });
                        });
                })
                ->offset($this->start)
                ->limit($this->limit)
                ->get();

            $totalFiltered = count($coc_list);
        } else {
            $coc_list = Coc::whereIn('id', $coc_unit)->offset($this->start)->limit($this->limit)->get();
            $totalFiltered = count($coc_unit);
        }

        $data = collect();
        if (! empty($coc_list)) {
            $index = 0;
            foreach ($coc_list as $coc) {
                $nestedData['no'] = $index += 1;
                $nestedData['business_area'] = sprintf(
                    '%s - %s',
                    $coc->business_area,
                    @$coc->businessArea->description
                );
                $nestedData['judul_coc'] = '<a href="'.url('coc/event/'.$coc->id).'">';
                $nestedData['judul_coc'] .= $coc->judul;
                $nestedData['judul_coc'] .= '</a>';
                $nestedData['tema'] = $coc->tema->tema;
                $nestedData['jenis'] = @$coc->jenis->jenis;
                if (! is_null($coc->realisasi)) {
                    $cName = $coc->realisasi->leader->cname;
                    $nip = @$coc->realisasi->leader->nip;
                    $posisi = @$coc->realisasi->leader->strukturPosisi->stext;
                    $tanggal_jam = @$coc->realisasi->realisasi->format('Y-m-d H:i');
                } else {
                    $cName = @$coc->leader->cname;
                    $nip = @$coc->leader->nip;
                    $posisi = @$coc->leader->strukturPosisi->stext;
                    $tanggal_jam = @$coc->tanggal_jam->format('Y-m-d H:i');
                }
                $nestedData['coc_leader'] = sprintf(
                    '%s <br><small class="text-muted">%s</small>',
                    $cName,
                    "$nip - $posisi"
                );
                $nestedData['unit_bidang'] = @$coc->organisasi->stext;
                $nestedData['lokasi'] = @$coc->lokasi;
                $nestedData['tanggal_jam'] = $tanggal_jam;
                $nestedData['peserta'] = sprintf(
                    '%s/%s',
                    $coc->attendants->count(),
                    $coc->jml_peserta - $coc->jml_peserta_dispensasi
                );
                $nestedData['persentase_peserta'] = number_format(($coc->attendants->count() / ($coc->jml_peserta - $coc->jml_peserta_dispensasi)) * 100, 2).'%';
                $nestedData['admin_coc'] = sprintf(
                    '%s <br><small class="text-muted">%s - %s </small>',
                    @$coc->admin->name,
                    @$coc->admin->nip,
                    @$coc->admin->strukturPosisi()->stext
                );

                if ($coc->status === 'OPEN') {
                    $label = 'label-success';
                } elseif ($coc->status === 'CANC') {
                    $label = 'label-danger';
                } else {
                    $label = 'label-primary';
                }
                $nestedData['status'] = sprintf(
                    '<span class="label %s">%s</span>',
                    $label,
                    $coc->status
                );

                $data->push($nestedData);
            }
        }

        if (! is_null($this->order)) {
            $sortBy = $columns[$this->order];

            if ($this->dir === 'asc') {
                $data = $data->sortBy($sortBy);
            } elseif ($this->dir === 'desc') {
                $data = $data->sortByDesc($sortBy);
            }
        }

        $jsonData = [
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data->values()->toArray()
        ];

        return response()->json($jsonData);
    }

    public function fetchPersentaseCoc(Request $request)
    {
        $columns = [
            'nip',
            'admin',
            'unit',
            'divisi',
            'coc_open',
            'coc_comp',
            'rencana_peserta',
            'dispen_peserta',
            'peserta',
            'check_in',
            'persen_checkin',
            'baca_materi',
            'persen_baca_materi'
        ];
        $user = Auth::user();
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        if($user->hasRole('admin_pusat') || $user->hasRole('root')) {
            $company_code = '8200';
        } else {
            $company_code = $user->company_code;
        }

        $company_code = request('company_code', $company_code);

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();

        if ($cc_selected->company_code != '1000') {
            unset($columns['divisi']);
        }

        $callback = function ($query) use ($company_code) {
            $query->where('company_code', $company_code);
        };

        $role_admin_pusat = Role::with(['users' => $callback])->find(3);
        $role_admin_ki = Role::with(['users' => $callback])->find(6);
        $role_admin_unit = Role::with(['users' => $callback])->find(4);

        $user_admin_pusat = $role_admin_pusat->users;
        $user_admin_ki = $role_admin_ki->users;
        $user_admin_unit = $role_admin_unit->users;

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $users->merge($user_admin_pusat);
        $totalData = $users->count();

        $data = collect();

        foreach ($users as $user) {
            $nestedData['nip'] = $user->nip;
            $nestedData['admin'] = $user->name;
            $nestedData['unit'] = sprintf(
                '%s - %s',
                $user->business_area,
                $user->businessArea->description
            );

            if ($cc_selected->company_code == '1000') {
                $nestedData['divisi'] = $user->getDivisi();
            }

            $nestedData['coc_open'] = $user->coc()
                ->where('status', 'OPEN')
                ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
                ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
                ->where('jenis_coc_id', 1)
                ->count();

            $coc = $user->coc()
                ->where('status', 'COMP')
                ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
                ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
                ->where('jenis_coc_id', 1)
                ->get();

            $nestedData['coc_comp'] = $coc
                ->count();

            $nestedData['rencana_peserta'] = $coc->sum('jml_peserta');
            $nestedData['dispen_peserta'] = $coc->sum('jml_peserta_dispensasi');
            $nestedData['peserta'] = $coc->sum('jml_peserta') - $coc->sum('jml_peserta_dispensasi');
            $nestedData['check_in'] = \App\Coc::getJumlahCheckin($user->id, $tgl_awal, $tgl_akhir, 1);
            $nestedData['baca_materi'] = \App\Coc::getJumlahBacaMateri($user->id, $tgl_awal, $tgl_akhir, 1);

            if ($nestedData['peserta'] !== 0) {
                $nestedData['persen_checkin'] = number_format($nestedData['check_in'] / $nestedData['peserta'] * 100, 2) . '%';
                $nestedData['persen_baca_materi'] = number_format($nestedData['baca_materi'] / $nestedData['peserta'] * 100, 2) . '%';
            } else {
                $nestedData['persen_checkin'] = $nestedData['persen_baca_materi'] = '0%';
            }

            $data->push($nestedData);
        }

        if (! is_null($this->order)) {
            $sortBy = $columns[$this->order];

            if ($this->dir === 'asc') {
                $data = $data->sortBy($sortBy);
            } elseif ($this->dir === 'desc') {
                $data = $data->sortByDesc($sortBy);
            }
        }

        if (! empty($this->searchVal)) {
            $data = $data->filter(function ($item) {
                $itemKeys = array_keys($item);

                foreach ($itemKeys as $index) {
                    $bool = strpos($item[$index], $this->searchVal) !== false
                        ? true
                        : false;

                    if ($bool) {
                        return $bool;
                    }
                }
            });
        }

        $totalFiltered = $data->count();
        $data = $data->slice(intval($this->start), intval($this->limit));

        $jsonData = [
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data->values()->toArray()
        ];

        return response()->json($jsonData);
    }

    public function fetchBriefingCocOld(Request $request)
    {
        return new JsonResponse([
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => []
        ]);

        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        $company_code = CompanyCode::where('company_code', $request->company_code)->first();

        if (! is_null($company_code)) {
            $query = $company_code->realisasi()
                ->whereDate('realisasi', '>=', $tgl_awal->format('Y-m-d'))
                ->whereDate('realisasi', '<=', $tgl_akhir->format('Y-m-d'))
                ->orderBy('realisasi', 'asc');
        }

        if (isset($query)) {
            $datatable = Datatable::make($query)
            ->rowView('datatable-row-views.report.briefing_coc_row')
            ->columns(
                [
                    ['data' => 'tema', 'searchable' => false, 'orderable' => true],
                    ['data' => 'organisasi', 'searchable' => false, 'orderable' => true],
                    ['data' => 'jenjang_jabatan', 'searchable' => false, 'orderable' => true],
                    ['data' => 'judul_coc', 'searchable' => false, 'orderable' => true],
                    ['data' => 'narasumber', 'searchable' => false, 'orderable' => true],
                    ['data' => 'unit', 'searchable' => false, 'orderable' => true],
                    ['data' => 'admin_coc', 'searchable' => false, 'orderable' => true],
                    ['data' => 'target_pelaksanaan', 'searchable' => false, 'orderable' => true],
                    ['data' => 'realisasi', 'searchable' => false, 'orderable' => true]
                ]
            );

            $datatable->search(function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->where(function ($q) use ($keyword) {
                    $q->orwhereHas('coc', function ($q2) use ($keyword) {
                        $q2->orWhere(DB::raw('lower(tanggal_jam)'), 'like', "%$keyword%")
                            ->orWhere(DB::raw('lower(judul)'), 'like', "%$keyword%")
                            ->orwhereHas('tema', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('lower(tema)'), 'like', "%$keyword%");
                            })
                            ->orwhereHas('admin', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('lower(name)'), 'like', "%$keyword%");
                            });
                    })
                        ->orwhereHas('jenjangJabatan', function ($q2) use ($keyword) {
                            $q2->orWhere(DB::raw('lower(jenjang_jabatan)'), 'like', "%$keyword%");
                        })
                        ->orwhereHas('leader', function ($q2) use ($keyword) {
                            $q2->orWhere(DB::raw('lower(cname)'), 'like', "%$keyword%")
                                ->orWhere(DB::raw('lower(nip)'), 'like', "%$keyword%")
                                ->orwhereHas('strukturPosisi', function ($q3) use ($keyword) {
                                    $q3->orWhere(DB::raw('lower(stext)'), 'like', "%$keyword%");
                                });
                        })
                        ->orwhereHas('businessArea', function ($q2) use ($keyword) {
                            $q2->orWhere(DB::raw('lower(description)'), 'like', "%$keyword%");
                        });
                });
            });

            return $datatable->toJson();
        }
    }

    public function fetchBriefingCoc(Request $request)
    {
        if (empty($request->company_code)) {
            return new JsonResponse([
                'draw' => intval(request()->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        $startDate = Date::parse($request->start_date);
        $endDate = Date::parse($request->end_date);
        $query = RealisasiCoc::with(['coc', 'jenjangJabatan', 'leader', 'businessArea'])->whereIn('company_code', [$request->company_code])
            ->whereDate('realisasi', '>=', $startDate->format('Y-m-d'))
            ->whereDate('realisasi', '<=', $endDate->format('Y-m-d'))
            ->orderBy('realisasi', 'asc');

        return Datatable::make($query)
            ->rowView('datatable-row-views.report.briefing_coc_row')
            ->columns([
                ['searchable' => false, 'orderable' => true, 'data' => 'tema'],
                ['searchable' => false, 'orderable' => true, 'data' => 'organisasi'],
                ['searchable' => false, 'orderable' => true, 'data' => 'jenjang_jabatan'],
                ['searchable' => false, 'orderable' => true, 'data' => 'judul_coc'],
                ['searchable' => false, 'orderable' => true, 'data' => 'narasumber'],
                ['searchable' => false, 'orderable' => true, 'data' => 'unit'],
                ['searchable' => false, 'orderable' => true, 'data' => 'admin_coc'],
                ['searchable' => false, 'orderable' => true, 'data' => 'target_pelaksanaan'],
                ['searchable' => false, 'orderable' => true, 'data' => 'realisasi'],
            ])->search(function ($query, $keyword) {
                $keyword = strtolower($keyword);

                $query->where(function ($q) use ($keyword) {
                    $q->orWhereHas('coc', function ($q2) use ($keyword) {
                        $q2->orWhere(DB::raw('LOWER(tanggal_jam)'), 'like', "%$keyword%")
                            ->orWhere(DB::raw('LOWER(judul)'), 'like', "%$keyword%")
                            ->orwhereHas('tema', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('LOWER(tema)'), 'like', "%$keyword%");
                            })
                            ->orwhereHas('admin', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('LOWER(name)'), 'like', "%$keyword%");
                            });
                    })->orWhereHas('jenjangJabatan', function ($q2) use ($keyword) {
                        $q2->orWhere(DB::raw('LOWER(jenjang_jabatan)'), 'like', "%$keyword%");
                    })->orWhereHas('leader', function ($q2) use ($keyword) {
                        $q2->orWhere(DB::raw('LOWER(name)'), 'like', "%$keyword%")
                            ->orWhere(DB::raw('LOWER(nip)'), 'like', "%$keyword%")
                            ->orWhereHas('strukturPosisi', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('LOWER(stext)'), 'like', "%$keyword%");
                            });
                    })->orWhereHas('businessArea', function ($q2) use ($keyword) {
                        $q2->orWhere(DB::raw('LOWER(description)'), 'like', "%$keyword%");
                    });
                });
            })->toJson();
    }

    public function fetchMonitoringCheckinCoc(Request $request)
    {
        // if (empty($request->company_code)) {
        //     return new JsonResponse([
        //         'draw' => intval(request()->input('draw')),
        //         'recordsTotal' => 0,
        //         'recordsFiltered' => 0,
        //         'data' => []
        //     ]);
        // }

        // dd($request->all());

        $request = request();
        $search = $request->search;

        $selected_bulan = $request->bulan;
        $selected_tahun = $request->tahun;

        // get all CoC in the selected month
        // $coc = Coc::where('scope', 'local')
        //     ->whereMonth('tanggal_jam', $selected_bulan)
        //     ->whereYear('tanggal_jam', $selected_tahun)
        //     ->get();
        
        $query = UnitMonitoring::where('status', 'ACTV')
            ->where(function ($query) use ($search) {
                return $query->when(! empty($search), function ($query) use ($search) {
                    $keyword = strtolower($search['value']);
                    return $query->where(function ($query) use ($keyword) {
                        return $query->where('lower(nama_unit)', 'like', "%$keyword%");
                    });
                });
            })
            ->orderBy('orgeh', 'asc');

        $datatable = Datatable::make($query)
            // ->rowView('components.datatable-columns.monitoring', compact('coc'))
            ->rowView('components.datatable-columns.monitoring_checkin_coc', compact('selected_bulan', 'selected_tahun'))
            ->columns([
                ['data' => 'nama_unit', 'searchable' => false],
                ['data' => 'target', 'searchable' => false],
                ['data' => 'minggu_1', 'searchable' => false],
                ['data' => 'minggu_2', 'searchable' => false],
                ['data' => 'minggu_3', 'searchable' => false],
                ['data' => 'minggu_4', 'searchable' => false],
                ['data' => 'minggu_5', 'searchable' => false],
            ]);

        return $datatable->toJson();
    }


    public function fetchPersentaseBacaMateri(Request $request)
    {

        $request = request();
        $search = $request->search;

        $selected_bulan = $request->bulan;
        $selected_tahun = $request->tahun;
        
        $query = UnitMonitoring::where('status', 'ACTV')
            ->where(function ($query) use ($search) {
                return $query->when(! empty($search), function ($query) use ($search) {
                    $keyword = strtolower($search['value']);
                    return $query->where(function ($query) use ($keyword) {
                        return $query->where('lower(nama_unit)', 'like', "%$keyword%");
                    });
                });
            })
            ->orderBy('orgeh', 'asc');

        $datatable = Datatable::make($query)
            // ->rowView('components.datatable-columns.monitoring', compact('coc'))
            ->rowView('components.datatable-columns.persentase_baca_materi', compact('selected_bulan', 'selected_tahun'))
            ->columns([
                ['data' => 'nama_unit', 'searchable' => false],
                ['data' => 'target', 'searchable' => false],
                ['data' => 'minggu_1', 'searchable' => false],
                ['data' => 'minggu_2', 'searchable' => false],
                ['data' => 'minggu_3', 'searchable' => false],
                ['data' => 'minggu_4', 'searchable' => false],
                ['data' => 'minggu_5', 'searchable' => false],
            ]);

        return $datatable->toJson();
    }

    public function fetchMonitoringBacaMateriPegawai(Request $request)
    {

        $request = request();
        $search = $request->search;

        $selected_bulan = $request->bulan;
        $selected_tahun = $request->tahun;
        $selected_minggu = $request->minggu_ke;
        $selected_unit = $request->orgeh;

        // get range tanggal
        $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
        $startOfWeek->addWeeks($selected_minggu - 1);
    
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

        // get id materi where tanggal between startOfWeek and endOfWeek
        $materi = Materi::where('jenis_materi_id', '1')
                    ->whereDate('tanggal', '>=', $startOfWeek)
                    ->whereDate('tanggal', '<=', $endOfWeek)
                    ->first();

        if($materi!=null)
            $materi_id = $materi->id;
        else
            $materi_id = 0;

        // get organisasi 
        $organisasi = StrukturOrganisasi::where('objid', $selected_unit)->first();
        // get arr orgeh
        $arr_org = [$organisasi->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $organisasi->getArrChildOrgeh($organisasi->objid));

        // $list_pegawai = User::where('status', 'ACTV')->whereIn('orgeh', $arr_org)->get();
        
        $query = User::with(['readMateri'])
            ->where('status', 'ACTV')
            ->whereIn('orgeh', $arr_org)
            ->where(function ($query) use ($search) {
                return $query->when(! empty($search), function ($query) use ($search) {
                    $keyword = strtolower($search['value']);
                    return $query->where(function ($query) use ($keyword) {
                        return $query->where('lower(name)', 'like', "%$keyword%")
                                    ->orWhere(DB::raw('LOWER(nip)'), 'like', "%$keyword%")
                                    ->orWhere(DB::raw('LOWER(bidang)'), 'like', "%$keyword%")
                                    ->orWhere(DB::raw('LOWER(jabatan)'), 'like', "%$keyword%");
                    });
                });
            })
            ->orderBy('business_area', 'asc');

        $datatable = Datatable::make($query)
            // ->rowView('components.datatable-columns.monitoring', compact('coc'))
            ->rowView('components.datatable-columns.monitoring_baca_materi_pegawai', compact('materi_id'))
            ->columns([
                ['data' => 'nama', 'searchable' => false],
                ['data' => 'nip', 'searchable' => false],
                ['data' => 'unit', 'searchable' => false],
                ['data' => 'bidang', 'searchable' => false],
                ['data' => 'jabatan', 'searchable' => false],
                ['data' => 'baca_materi', 'searchable' => false],
            ]);

        return $datatable->toJson();
    }

    public function fetchPersentaseBacaMateriPegawai(Request $request){
        $request = request();

        $selected_bulan = $request->bulan;
        $selected_tahun = $request->tahun;
        $selected_minggu = $request->minggu_ke;
        $orgeh = $request->orgeh;

        // get id materi where tanggal between startOfWeek and endOfWeek
        // get range tanggal
        $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
        $startOfWeek->addWeeks($selected_minggu - 1);
    
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

        // get id materi where tanggal between startOfWeek and endOfWeek
        $materi = Materi::where('jenis_materi_id', '1')
                    ->whereDate('tanggal', '>=', $startOfWeek)
                    ->whereDate('tanggal', '<=', $endOfWeek)
                    ->first();

        // get total pegawai
        $organisasi = StrukturOrganisasi::where('objid', $orgeh)->first();
        $arr_nip = $organisasi->getArrNIPPegawai();
        $total_pegawai = count($arr_nip);

        if($materi == null)
            return new JsonResponse([
                'persentase' => ['0% ( 0 / '.$total_pegawai.' )']
            ]);

        // $materi_id = $materi->materi_id;

        // // get total pegawai baca materi
        // $materi = Materi::find($materi_id);
        $total_pegawai_baca = $materi->getJumlahPegawaiReadMateri($arr_nip);

        $persentase = $total_pegawai_baca / $total_pegawai * 100;

        $string_persen = number_format($persentase, 2).'% ( '.$total_pegawai_baca.' / '.$total_pegawai.' )';

        return new JsonResponse([
            'persentase' => [$string_persen]
        ]);

    }
}
