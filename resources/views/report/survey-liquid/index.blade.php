@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@section('title')
    @php
        $payload = [
            'title' => 'Survey LIQUID',
            'jabatan' => false,
            'periode' => false,
            'status' => true
        ];
    @endphp

    @include('components.filter.lengkap', $payload)
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
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
                                <th class="align-center color-white vertical-middle" style="min-width: 100px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($liquids as $liquid)
                                <tr>
                                    <td>{{ $liquid->unit_view }}</td>
                                    <td>{{ $liquid->jadwal_view }}</td>
                                    <td>{{ $liquid->penyelarasan_view }}</td>
                                    <td>{{ $liquid->pengukuran_pertama_view }}</td>
                                    <td>{{ $liquid->pengukuran_kedua_view }}</td>
                                    <td>{{ $liquid->atasan_view }}</td>
                                    <td>{{ $liquid->bawahan_view }}</td>
                                    <td>{!! $liquid->status_view !!}</td>
                                    <td>{{ $liquid->admin_view }}</td>
                                    <td align="center">
                                        @if ($liquid->status->isPublished)
                                            <a
                                                href="{{ route('report.survey-liquid.show', $liquid) }}"
                                                data-toggle="tooltip" title="Lihat Detail"
                                                class="badge badge-primary"
                                            >
                                               <em class="fa fa-eye fa-2x"></em>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

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
