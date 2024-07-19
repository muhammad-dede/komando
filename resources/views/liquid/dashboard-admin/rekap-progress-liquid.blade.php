@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
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

@section('title')
    @include('components.form-filter-report-liquid-3', ['title' => 'Rekap Progress Liquid'])
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
                            <a href="{{ route('dashboard-admin.rekap-progress-liquid.download', request()->only('periode', 'company_code', 'unit_code','divisi','jabatan','search')) }}" class="btn btn-success btn-block">
                                <em class="fa fa-download"></em>
                                Export Xls
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mar-t-1rem">
                    <table class="datatable table table-striped table-bordered">
                        <thead class="thead-blue">
                            <tr>
                                <td>Organisasi</td>
                                <td>Nama Jabatan</td>
                                <td>Jumlah Atasan</td>
                                <td>Jumlah Bawahan</td>
                                <td>Feedback</td>
                                <td>Penyelarasan</td>
                                <td>Pengukuran 1</td>
                                <td>Pengukuran 2</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataProgressLiquid as $key => $val)
                            <tr>
                                <td>{{ $val['unit_name'] }}</td>
                                <td>{{ $val['jabatan'] }}</td>
                                <td>{{ $val['jml_atasan'] }}</td>
                                <td>{{ $val['jml_bawahan'] }}</td>
                                <td>{{ $val['has_feedback']."/".$val['jml_bawahan']." (".$val['persent_feedback']."%)" }}</td>
                                <td>{{ $val['has_penyelarasan']."/".$val['jml_atasan']." (".$val['persent_penyelarasan']."%)" }}</td>
                                <td>{{ $val['has_pengukuran_1']."/".$val['jml_bawahan']." (".$val['persent_pengukuran_1']."%)" }}</td>
                                <td>{{ $val['has_pengukuran_2']."/".$val['jml_bawahan']." (".$val['persent_pengukuran_2']."%)" }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {

            $(`.datatable`).DataTable({
                "searching": false,
                "paging": false,
                "ordering": false,
            });
        });
    </script>
@stop
