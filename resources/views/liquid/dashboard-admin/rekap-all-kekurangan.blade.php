@extends('layout')

@php
    $index = 0;
    $param1 = [
        'counter' => $data['counter'],
        'voter' => $data['voter'],
        'labels'=> $data['kekurangan_labels'],
        'labelChart' => 'Jumlah ' . $config->kurangShort,
    ];

    $param2 = [
        'counter' => $data['counter'],
        'labels'=> $data['kekurangan_labels'],
        'labelChart' => 'Jumlah ' . $config->kurangShort,
    ];
@endphp

@section('title')
    @include('components.form-filter-report-liquid-2', ['title' => 'Rekap Semua ' . $config->kurang])
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
            <div class="card-box width-full">
                @include('liquid.dashboard-admin._nav-report')

                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            {{-- <a href="{{ route('dashboard-admin.rekap-kekurangan.download', request()->only('periode', 'company_code', 'unit_code')) }}"
                                class="btn btn-success btn-block">
                                <em class="fa fa-download"></em>
                                Export Xls
                            </a> --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-lg-6 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 mar-t-1rem">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-blue">
                                        <tr>
                                            <td>No</td>
                                            <td>{{ $config->kurang }}</td>
                                            <td>Presentase</td>
                                            <td>Jumlah {{ $config->kurangShort }}</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['kekurangan'] as $kekurangan)
                                            <tr>
                                                <td>{{ $index += 1 }}</td>
                                                <td>{{ $kekurangan['kekurangan'] }}</td>
                                                <td>
                                                    {{ round(($kekurangan['jml_data'] * 100) / $data['voter'], 2) }} %
                                                </td>
                                                <td>{{ $kekurangan['jml_data'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-xs-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#bar" role="tab" aria-selected="true">
                                    Bar Chart
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#line" role="tab" aria-selected="false">
                                    Line Chart
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pad-1rem">
                            <div class="tab-pane fade show active in" id="bar" role="tabpanel">
                                @include('components.horizontal-bar-chart', $param1)
                            </div>

                            <div class="tab-pane fade show" id="line" role="tabpanel">
                                @include('components.line-chart', $param2)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .select2 {
            width: 100% !important;
        }

        div.table-responsive>div.dataTables_wrapper>div.row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            column-span: all;
        }
    </style>
@stop

@section('javascript')
    <script src="https://unpkg.com/html2canvas@1.0.0-alpha.12/dist/html2canvas.js"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>
@stop
