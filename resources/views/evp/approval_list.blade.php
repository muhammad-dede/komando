@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Approval EVP</h4>
@stop

@section('content')
    {{--<div class="row">--}}
    {{--<div class="col-xs-12">--}}
    <div class="card-box">
        <div class="col-md-12 table-responsive p-20">
            {{--<table class="table">--}}
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Kegiatan</th>
                    {{--<th>Waktu</th>--}}
                    <th>Lokasi</th>
                    <th>Jenis</th>
                    <th>Kuota</th>
                    <th>Jml. Pendaftar</th>
                    <th>Jml. Approved</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $x = 1;
                ?>
                @foreach($evp_list as $evp)
                    <tr>
                        <td scope="row">
                            {{$x++}}
                        </td>
                        <td>
                            <a href="{{url('evp/detail/'.@$evp->id)}}" target="_blank">{{@$evp->nama_kegiatan}}</a>
                            <br>
                            <small>
                            <i class="fa fa-calendar"></i> {{@$evp->waktu_awal->format('d M Y')}}
                            - {{@$evp->waktu_awal->format('d M Y')}}</small>
                            {{--<br>--}}
                            {{--<small>--}}
                            {{--<i class="fa fa-globe"></i> {{@$evp->jenisEVP->description}}--}}
                            {{--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                            {{--<i class="fa fa-map-marker"></i> {{@$evp->tempat}}--}}
                            {{--</small>--}}
                        </td>
                        {{--<td>--}}
                            {{--{{@$evp->waktu_awal->format('d M Y')}}--}}
                            {{--- {{@$evp->waktu_awal->format('d M Y')}}--}}
                        {{--</td>--}}
                        <td>
                            {{@$evp->tempat}}
                        </td>
                        <td>
                            @if($evp->jenis_evp_id=='1')
                                <span class="label label-danger" style="font-size: 11px"><i
                                            class="fa fa-globe"></i> Nasional</span>
                            @else
                                <span class="label label-success" style="font-size: 11px"><i
                                            class="fa fa-globe"></i> Lokal</span>
                            @endif
                        </td>
                        <td align="right">
                            {{number_format($evp->kuota,0,',','.')}}
                        </td>
                        <td align="right">
                            {{number_format($evp->volunteers->count(),0,',','.')}}
                        </td>
                        <td align="right">
                            <div class="row" style="">
                                <div class="col-md-1" align="center">
                                    <button class="btn waves-effect btn-secondary" title="Jumlah relawan yang disetujui oleh atasan">
                                        {{number_format($evp->volunteers()->whereNotNull('approval_atasan')->count(),0,',','.')}}</button>
                                    <div class="m-t-10">Atasan</div>
                                </div>
                                <div class="col-md-1" align="center">
                                    <button class="btn waves-effect btn-secondary" title="Jumlah relawan yang disetujui oleh admin unit induk">
                                        {{number_format($evp->volunteers()->whereNotNull('approval_admin')->count(),0,',','.')}}</button>
                                    <div class="m-t-10">Admin</div>
                                </div>
                                @if($evp->jenis_evp_id=='1')
                                <div class="col-md-1" align="center">
                                    <button class="btn waves-effect btn-secondary" title="Jumlah relawan yang disetujui oleh GM">
                                        {{number_format($evp->volunteers()->whereNotNull('approval_gm')->count(),0,',','.')}}</button>
                                    <div class="m-t-10">GM</div>
                                </div>
                                <div class="col-md-1" align="center">
                                    <button class="btn waves-effect btn-secondary" title="Jumlah relawan yang disetujui oleh Kantor Pusat">
                                        {{number_format($evp->volunteers()->whereNotNull('approval_pusat')->count(),0,',','.')}}</button>
                                    <div class="m-t-10">Pusat</div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{url('evp/approval/volunteer/'.$evp->id)}}"
                               class="btn btn-success btn-xs waves-effect waves-light"
                               title="Approval">
                                <i class="fa fa-check"></i>
                            </a>
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                            {{--title="Approve">--}}
                            {{--                               onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                            {{--<i class="fa fa-check"></i>--}}
                            {{--</a>--}}
                            {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                            {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('1');"--}}
                            {{-->--}}
                            {{--<i class="fa fa-times"></i>--}}
                            {{--</a>--}}

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{--</div>--}}
    {{--</div>--}}

    <!-- sample modal content -->
    <div id="rejectModal" class="modal fade" role="dialog" aria-labelledby="rejectModalLabel"
         aria-hidden="true">
        {!! Form::open(['url'=>'evp/reject', 'id'=>'form_reject']) !!}
        {!! Form::hidden('volunteer_id', '', ['id'=>'volunteer_id']) !!}
        {!! Form::hidden('approver', '', ['id'=>'approver']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Reject</h4>
                </div>
                <form id="form_ruling">
                    <div class="modal-body">
                        <div class="m-l-20">
                            {{--<div class="form-group">--}}
                            {{--<label>Kegiatan</label>--}}

                            {{--<div>--}}
                            {{--{!! Form::text('kegiatan_wp',null,['class'=>'form-control', 'id'=>'kegiatan_wp']) !!}--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                            {{--<label>Kegiatan</label>--}}

                            {{--<div>--}}
                            {{--{!! Form::text('kegiatan_wp',null,['class'=>'form-control', 'id'=>'kegiatan_wp']) !!}--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                            {{--<label>Lokasi</label>--}}

                            {{--<div>--}}
                            {{--{!! Form::text('lokasi_wp',null,['class'=>'form-control', 'id'=>'lokasi_wp']) !!}--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label>Masukkan keterangan / alasan penolakan</label>

                                <div>
                                    {!! Form::textarea('alasan_ditolak',null,['class'=>'form-control', 'id'=>'alasan_ditolak']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_submit"><i
                                    class="fa fa-paper-plane"></i>
                            Sumbit
                        </button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Close
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>

        {!! Form::close() !!}
                <!-- /.modal-dialog -->
        {{--{!! Form::close() !!}--}}
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#datatable').DataTable();

        });


    </script>
@stop