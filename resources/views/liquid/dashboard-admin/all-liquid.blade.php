@extends('layout')

@inject('liquidService', 'App\Services\LiquidService')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@stop

@section('title')
    <div class="row">
        <div class="lh-70 col-md-3 col-lg-4 col-xs-12">
            <h4 class="page-title">Daftar LIQUID</h4>
        </div>

        <div class="col-md-9 col-lg-8 col-xs-12">
            <form action="{{ url('dashboard-admin/liquid-all') }}" method="get">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <div class="float-right width-full" style="margin-top: 18px; margin-bottom: 15px;">
                            <select
                                name="unit_code"
                                tabindex="-1"
                                aria-hidden="true"
                                class="select2 form-control form-control-danger"
                            >
                                <option disabled>
                                    Filter Unit
                                </option>
                                @foreach ($coCodeList as $key => $value)
                                    <option
                                        value="{{ $key }}"
                                        {{ $key == $unitCode ? 'selected' : '' }}
                                    >
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-12">
                        <div class="lh-70 float-right width-full">
                            <select class="select2 form-control form-control-danger" name="status"
                                    tabindex="-1" aria-hidden="true">
                                <option value="" {{ empty(request()->status) ? 'selected' : '' }} disabled>
                                    Filter Status
                                </option>

                                @foreach (\App\Enum\LiquidStatus::toDropdownArray() as $label)
                                    <option value="{{ $label }}"
                                            {{ request()->status === $label ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <div class="float-right width-full" style="margin-top: 18px; margin-bottom: 15px;">
                            <select class="select2 form-control" name="year">
                                <option>Filter Periode</option>
                                @foreach ($years as $item)
                                    <option value="{{ $item->year }}" {{ $item->isSelected ? 'selected' : '' }}>{{ $item->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <button type="submit" class="btn btn-primary width-full" style="margin-top: 18px; margin-bottom: 15px;">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="card-box width-full">
            <div class="table-responsive">
                <table class="datatable table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th class="color-white vertical-middle" style="min-width: 170px;">Unit</th>
                        <th class="color-white vertical-middle">Jadwal Feedback</th>
                        <th class="color-white vertical-middle">Penyelarasan</th>
                        <th class="color-white vertical-middle">Pengukuran Pertama</th>
                        <th class="color-white vertical-middle">Pengukuran Kedua</th>
                        <th class="color-white vertical-middle">Jumlah Atasan</th>
                        <th class="color-white vertical-middle">Jumlah Bawahan</th>
                        <th class="color-white vertical-middle">Status</th>
                        <th class="color-white vertical-middle">Admin</th>
                        <th class="align-center color-white vertical-middle" style="min-width: 100px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($liquids as $liquid)
                        <tr>
                            <td>
                                @foreach ($liquid->businessAreas as $area)
                                    <div>{{ "$area->business_area - $area->description" }}</div>
                                @endforeach
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($liquid->feedback_start_date)->format('d F Y')
                                    .' - '. \Carbon\Carbon::parse($liquid->feedback_end_date)->format('d F Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($liquid->penyelarasan_start_date)->format('d F Y')
                                .' - '. \Carbon\Carbon::parse($liquid->penyelarasan_end_date)->format('d F Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($liquid->pengukuran_pertama_start_date)->format('d F Y')
                                    .' - '. \Carbon\Carbon::parse($liquid->pengukuran_pertama_end_date)->format('d F Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($liquid->pengukuran_kedua_start_date)->format('d F Y')
                                    .' - '. \Carbon\Carbon::parse($liquid->pengukuran_kedua_end_date)->format('d F Y') }}
                            </td>
                            <td>
                                {{ $liquid->peserta()
                                    ->distinct('atasan_id')
                                    ->count('atasan_id') }}
                            </td>
                            <td>
                                {{ $liquid->peserta()
                                    ->distinct('bawahan_id')
                                    ->count('bawahan_id') }}
                            </td>
                            <td>
                                @if ($liquid->status === \App\Enum\LiquidStatus::PUBLISHED)
                                    @if ($liquid->getCurrentSchedule()
                                        === \App\Enum\LiquidStatus::SELESAI)
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">
											{{ $liquid->getCurrentSchedule() }}
										</span>
                                    @endif
                                @else
                                    <span class="badge badge-warning">{{ \App\Enum\LiquidStatus::DRAFT_STATUS }}</span>
                                @endif
							</td>
							<td>
								{{ $liquid->creator_nip.' - '.$liquid->creator_name }}
							</td>
                            <td align="center">
                                @if ($liquid->status === \App\Enum\LiquidStatus::DRAFT
                                    || $liquid->status === \App\Enum\LiquidStatus::PUBLISHED)
                                    <a href="{{ url("liquid/$liquid->id/show") }}" class="badge badge-primary"
                                       data-toggle="tooltip" title="Lihat Detail"><em
                                                class="fa fa-eye fa-2x"></em></a>
                                    <a href="{{ url("liquid/$liquid->id/edit") }}" class="badge badge-warning"
                                       data-toggle="tooltip" title="Edit"><em
                                                class="fa fa-pencil fa-2x"></em></a>
                                    @if($liquid->status === \App\Enum\LiquidStatus::DRAFT)
                                        <form class="badge"  action="{{ url("liquid/$liquid->id/destroy") }}" method="post">
                                            <a href="#" class="badge-danger"
                                               data-toggle="tooltip" title="Delete">
                                                <button type="submit" class="btn btn-danger btn-sm" name="delete-button"
                                                        onclick="return confirm('Anda yakin untuk menghapus liquid ini?')">
                                                    <em class="fa fa-trash fa-2x"></em>
                                                </button>
                                            </a>
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ url("liquid/$liquid->id/show") }}" class="badge badge-primary"
                                       data-toggle="tooltip" title="Lihat Detail"><em
                                                class="fa fa-eye fa-2x"></em></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
            $('.datatable').DataTable();
        });
    </script>
@stop
