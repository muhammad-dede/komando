@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <style>
        #datatable td:not(:first-child) {
            text-align: center;
        }
    </style>    
@stop

@section('title')
    <h4 class="page-title">Monitoring Check-In CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {!! Form::open(['url'=>'report/monitoring-checkin-coc', 'method' => 'get']) !!}
                    <div class="form-group row">
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
                        <div class="col-md-2">
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
                        <div class="col-md-5 button-list">
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
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Divisi / Unit Kerja</th>
                                    <th style="text-align: center">Target</th>
                                    <th style="text-align: center">
                                        Minggu 1
                                        <br>
                                        <small>{{ $arr_date_week[1]['start'] }} - {{ $arr_date_week[1]['end'] }}</small>
                                    </th>
                                    <th style="text-align: center">
                                        Minggu 2
                                        <br>
                                        <small>{{ $arr_date_week[2]['start'] }} - {{ $arr_date_week[2]['end'] }}</small>
                                    </th>
                                    <th style="text-align: center">
                                        Minggu 3
                                        <br>
                                        <small>{{ $arr_date_week[3]['start'] }} - {{ $arr_date_week[3]['end'] }}</small>
                                    </th>
                                    <th style="text-align: center">
                                        Minggu 4
                                        <br>
                                        <small>{{ $arr_date_week[4]['start'] }} - {{ $arr_date_week[4]['end'] }}</small>
                                    </th>
                                    <th style="text-align: center">
                                        Minggu 5
                                        <br>
                                        <small>{{ $arr_date_week[5]['start'] }} - {{ $arr_date_week[5]['end'] }}</small>
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

            // let t = $('#datatable').DataTable();


            $('#datatable').DataTable({
                createdRow: function(row, data, dataIndex) {
                    // Assuming the target is in the second column (index 1)
                    var target = parseFloat($(row).children(':nth-child(2)').text());

                    // Loop through each td element in the row
                    $(row).children('td').each(function(index, td) {
                        // Skip the first two columns
                        if (index > 1) {
                            // Get the value of the td element
                            var value = parseFloat($(td).text());

                            // If the value is less than the target, change the background color to red
                            if (value < target) {
                                $(td).css('background-color', '#ffc6be');
                            }
                        }
                    });
                },
                language: {
                    processing: '<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('data_table.report.monitoring.coc') }}",
                    dataType: "json",
                    data: function (d) {
                        // let userCC = "{{ auth()->user()->company_code }}";

                        // d.start_date = $('input[name=start_date]').val();
                        // d.end_date = $('input[name=end_date]').val();

                        // d.company_code = $('#company_code').val() == 0
                        //     ? userCC
                        //     : $('#company_code').val();
                        d.bulan = $('#bulan').val();
                        d.tahun = $('#tahun').val();
                    },
                    type: "GET"
                },
                columns: [
                    { "data":"nama_unit" },
                    { "data":"target" },
                    { "data":"minggu_1" },
                    { "data":"minggu_2" },
                    { "data":"minggu_3" },
                    { "data":"minggu_4" },
                    { "data":"minggu_5" },
                ],
                "order": [[ 0, "asc" ]]
            });
        });
    </script>
@stop
