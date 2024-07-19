@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Laporan Briefing CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/briefing-coc', 'method' => 'get']) !!}
                    <div class="form-group row">
                        <div class="col-md-4">
                            {!!
                                Form::select(
                                    'company_code',
                                    $coCodeList,
                                    request('company_code'),
                                    [
                                        'class' => 'form-control select2',
                                        'id' => 'company_code',
                                    ]
                                )
                            !!}
                        </div>

                        <div class="col-md-3">
                            <div class="input-daterange input-group" id="date-range">
                                <input
                                    type="text"
                                    name="start_date"
                                    placeholder="dd-mm-yyyy"
                                    value="{{ $startDate }}"
                                    autocomplete="off"
                                    class="form-control"
                                />

                                <span class="input-group-addon bg-custom b-0">to</span>

                                <input
                                    type="text"
                                    name="end_date"
                                    placeholder="dd-mm-yyyy"
                                    value="{{ $endDate }}"
                                    autocomplete="off"
                                    class="form-control"
                                />
                            </div>
                        </div>

                        <div class="col-md-5 button-list">
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
                        </div>
                    </div>
                {!! Form::close() !!}

                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Tema Utama</th>
                                    <th style="text-align: center">Level Organisasi</th>
                                    <th style="text-align: center">Jenjang Jabatan</th>
                                    <th style="text-align: center">Judul CoC</th>
                                    <th style="text-align: center">Narasumber</th>
                                    <th style="text-align: center">Unit/Area/Rayon</th>
                                    <th style="text-align: center">Admin CoC</th>
                                    <th style="text-align: center">Target Pelaksanaan CoC</th>
                                    <th style="text-align: center">Realisasi</th>
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

            $('#company_code').select2();

            let t = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('data_table.report.briefing.coc') }}",
                    dataType: "json",
                    data: function (d) {
                        let userCC = "{{ auth()->user()->company_code }}";

                        d.start_date = $('input[name=start_date]').val();
                        d.end_date = $('input[name=end_date]').val();

                        d.company_code = $('#company_code').val() == 0
                            ? userCC
                            : $('#company_code').val();
                    },
                    type: "GET"
                },
                columns: [
                    { "data":"tema" },
                    { "data":"organisasi" },
                    { "data":"jenjang_jabatan" },
                    { "data":"judul_coc" },
                    { "data":"narasumber" },
                    { "data":"unit" },
                    { "data":"admin_coc" },
                    { "data":"target_pelaksanaan" },
                    { "data":"realisasi" }
                ],
                "order": [[ 0, "asc" ]]
            });
        });
    </script>
@stop
