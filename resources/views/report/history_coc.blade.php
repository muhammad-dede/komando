@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

@stop

@section('title')
    <h4 class="page-title">History CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('report/history-coc/export')}}" id="post" type="submit"
                           class="btn btn-success w-lg waves-effect waves-light">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                <div class="row m-t-20">
                @if(Auth::user()->attendant->count()==0)
                    <div align="center" class="m-t-20">
                        <h1 class="fa fa-hourglass-half" style="font-size: 250px;color: #dadada;"></h1>
                    </div>
                @else
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="center hidden-xs" style="text-align: center">Tanggal CoC</th>
                                <th class="center hidden-xs" style="text-align: center">Check-In</th>
                                <th class="center" style="text-align: center">Judul</th>
                                <th class="center hidden-xs" style="text-align: center">Pemateri</th>
                                <th class="center hidden-xs" style="text-align: center">Lokasi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                bPaginate: true,
                ajax: {
                    url: "{{ route('data_table.report.history.coc') }}",
                    dataType: "json",
                    type: "GET"
                },
                columns: [
                    {"data":"tanggal_coc"},
                    {"data":"check_in"},
                    {"data":"judul"},
                    {"data":"pemateri"},
                    {"data":"lokasi"}
                ]
            });
        });

    </script>
@stop
