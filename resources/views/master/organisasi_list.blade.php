@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6">
            <h4 class="page-title">Organisasi</h4>
        </div>
        <div class="col-md-6">
            {{--<span class="pull-right text-muted m-t-20">User Management / User</span>--}}
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {{--<div class="col-sm-12 space20">--}}
                    {{--<a href="{{url('user-management/user/create')}}" class="btn btn-wide btn-primary"><i--}}
                                {{--class="fa fa-plus"></i> Add New User</a>--}}
                {{--</div>--}}
                <div class="col-md-12">
                    {{--<h5 class="over-title margin-bottom-15">Basic <span class="text-bold">Data Table</span></h5>--}}
                    {{--<p>--}}
                    {{--DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, and will add advanced interaction controls to any HTML table.--}}
                    {{--</p>--}}
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="center">Kode Organisasi</th>
                            <th class="center">Posisi</th>
                            <th class="center">Parent Kode Organisasi</th>
                            <th class="center">Parent Posisi</th>
                            <th class="center">Level Unit</th>
                        </tr>
                        </thead>
                        {{--<tbody>--}}
                        {{--@foreach($organisasi_list as $wa)--}}
                            {{--<tr>--}}
                                {{--<td><a href="{{url('master/jabatan/'.$wa->id)}}">{{$wa->objid}}</a></td>--}}
                                {{--<td class="hidden-xs">{{$wa->stext}}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
//            $('#datatable').DataTable();
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('master-data/organisasi/server-processing')}}',
                columns: [
                    { data: 'objid', name: 'objid' },
                    { data: 'stext', name: 'stext' },
                    { data: 'sobid', name: 'sobid' },
                    { data: 'stxt2', name: 'stxt2' },
                    { data: 'level', name: 'level' }
                ]
            });
        });

    </script>

@stop