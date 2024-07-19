@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <style>
        #datatable td {
            text-align: left;
        }
    </style>    
@stop

@section('title-prod')
    <h4 class="page-title">Mohon maaf, sedang perbaikan, untuk sementara data pengisian akan disampaikan melalui PIC terkait setiap pukul 09.00 wib</h4>
@stop
@section('title')
    <h4 class="page-title">Monitoring Baca Materi Pegawai</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/monitoring-baca-materi-pegawai', 'method' => 'get']) !!}
                    <div class="form-group row">
                        <div class="col-md-4">
                            {!!
                                Form::select(
                                    'orgeh',
                                    $unit_list,
                                    $selected_unit,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'orgeh',
                                    ]
                                )
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!
                                Form::select(
                                    'bulan',
                                    $bulan_list,
                                    $selected_bulan,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'bulan',
                                    ]
                                )
                            !!}
                        </div>
                        <div class="col-md-1">
                            {!!
                                Form::select(
                                    'tahun',
                                    $tahun_list,
                                    $selected_tahun,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'tahun',
                                    ]
                                )
                            !!}
                        </div>
                        <div class="col-md-2">
                            {!!
                                Form::select(
                                    'minggu_ke',
                                    [1 => 'Minggu 1', 2 => 'Minggu 2', 3 => 'Minggu 3', 4 => 'Minggu 4', 5 => 'Minggu 5'],
                                    $selected_minggu,
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'minggu_ke',
                                    ]
                                )
                            !!}
                        </div>
                        <div class="col-md-2 button-list">
                            <button class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>

                        {{-- <div class="col-md-5 button-list">
                            <button class="btn btn-primary">
                                <i class="fa fa-search"></i> Search
                            </button>

                            <a href="{{ url("report/briefing-coc/export/$cc_selected/$startDate/$endDate?company_code=") }}"
                                id="post"
                                type="submit"
                                target="_blank" rel="noopener"
                                class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-file-excel-o"></i> Export
                            </a>
                        </div> --}}
                    </div>
                {!! Form::close() !!}

                <div class="row m-t-30">
                    <div class="col-md-4">
                        <small>Data range:</small> <br>{{ $startOfWeek->format('d/m/Y') }} - {{ $endOfWeek->format('d/m/Y') }}
                    </div>
                    <div class="col-md-4">
                        @if($materi!=null)
                        <small>Materi:</small> <br>{{ @$materi->judul.' ('.@$materi->tanggal->format('d/m/Y').')' }}
                        @endif
                    </div>
                    <div class="col-md-4">
                        <small>Persentase: </small>
                        <br>
                        <span id="persentase_baca"></span>
                        {{-- {{ $total_pegawai_baca }} / {{ $total_pegawai}} (%) --}}
                    </div>
                </div>

                @if($materi!=null)
                <div class="col-md-12 align-right" align="right">
                    <a href="{{url('report/monitoring-baca-materi-pegawai/export?orgeh='.$selected_unit.'&bulan='.$selected_bulan.'&tahun='.$selected_tahun.'&minggu_ke='.$selected_minggu)}}" id="export_peserta" 
                        class="btn btn-success waves-effect waves-light align-right">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export Peserta
                    </a>
                </div>
                @endif
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Nama</th>
                                    <th style="text-align: center">NIP</th>
                                    <th style="text-align: center">Unit</th>
                                    <th style="text-align: center">Bidang</th>
                                    <th style="text-align: center">Jabatan</th>
                                    <th style="text-align: center">
                                        Baca Materi
                                    </th>
                                </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });

            $('#bulan').select2();
            $('#tahun').select2();
            $('#minggu_ke').select2();
            $('#orgeh').select2();

            // let t = $('#datatable').DataTable();

            $('#datatable').DataTable({
                language: {
                    processing: '<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('data_table.report.baca.materi.pegawai') }}",
                    dataType: "json",
                    data: function (d) {
                        d.bulan = $('#bulan').val();
                        d.tahun = $('#tahun').val();
                        d.orgeh = $('#orgeh').val();
                        d.minggu_ke = $('#minggu_ke').val();
                    },
                    type: "GET"
                },
                columns: [
                    { "data":"nama" },
                    { "data":"nip" },
                    { "data":"unit" },
                    { "data":"bidang" },
                    { "data":"jabatan" },
                    { "data":"baca_materi" },
                ],
                "order": [[ 0, "asc" ]]
            });

            // ajax to get total percentage
            $.ajax({
                url: "{{ route('report.baca.materi.pegawai.persentase') }}",
                type: "GET",
                data: {
                    bulan: $('#bulan').val(),
                    tahun: $('#tahun').val(),
                    minggu_ke: $('#minggu_ke').val(),
                    orgeh: $('#orgeh').val(),
                },
                success: function(data) {
                    $('#persentase_baca').html(data.persentase);
                }
            });

        });
    </script>
@stop
