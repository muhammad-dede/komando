@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Rekap Komitmen Unit</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <div class="button-list" align="right">
                    <a href="{{url('report/rekap-commitment/export')}}" id="post" type="submit"
                       class="btn btn-success waves-effect waves-light">
                        <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                </div>
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="150" style="text-align: center">Company Code</th>
                                <th style="text-align: center">Unit</th>
                                {{--<th style="text-align: center">Jumlah Pegawai</th>--}}
                                @for($y=env('START_YEAR',2018);$y<=date('Y');$y++)
                                    <th style="text-align: center">Komitmen {{$y}}</th>
                                @endfor
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                        <!-- end row -->
                    </div>
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
            $("#company_code").select2();
            $("#tahun").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: {!! json_encode($datatable->getColumns()) !!},
            });
        });

    </script>

@stop
