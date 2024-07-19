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
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Kegiatan</th>
                    <th>Status Approval</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                //$x=1;
                ?>
                @foreach($volunteer_list as $volunteer)
                    <tr>
                        <td scope="row">
                            {{--{{$x++}}--}}
                            @if($volunteer->user->foto!='')
                                <img src="{{asset('assets/images/users/foto-thumb/'.$volunteer->user->foto)}}"
                                     alt="user" class="img-fluid img-thumbnail" width="64">
                            @else
                                <img src="{{asset('assets/images/user.jpg')}}" alt="user"
                                     class="img-fluid img-thumbnail" width="64">
                            @endif
                        </td>
                        <td>
                            {{$volunteer->user->strukturJabatan->cname}}
                            <br>
                            <small>{{$volunteer->user->strukturJabatan->nip}}</small>
                            <br>
                            <small>{{$volunteer->user->strukturPosisi()->stext}}
                                , {{$volunteer->user->strukturPosisi()->stxt2}}</small>
                        </td>
                        <td>
                            <a href="{{url('evp/detail/'.@$volunteer->evp->id)}}">{{@$volunteer->evp->nama_kegiatan}}</a>
                            <br>
                            <small><i class="fa fa-calendar"></i> {{@$volunteer->evp->waktu_awal->format('d M Y')}}
                                - {{@$volunteer->evp->waktu_awal->format('d M Y')}}</small>
                            <br>
                            <small><i class="fa fa-globe"></i> {{@$volunteer->evp->jenisEVP->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <i class="fa fa-map-marker"></i> {{@$volunteer->evp->tempat}}
                            </small>
                        </td>
                        <td>
                            <div class="row" style="">
                                @if($volunteer->status=='REJ-AT')
                                    <div class="col-md-1" align="center">
                                        <button class="btn waves-effect btn-danger" title="Rejected">
                                            <i class="fa fa-times"></i></button>
                                        <div class="m-t-10">Atasan</div>
                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                    </div>
                                @else
                                    <div class="col-md-1" align="center">
                                        <button class="btn waves-effect {{(@$volunteer->approval_atasan!=null)?'btn-success':'btn-secondary'}}">
                                            <i class="fa fa-check"></i></button>
                                        <div class="m-t-10">Atasan</div>
                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                    </div>
                                @endif
                                @if($volunteer->status=='REJ-ADM')
                                    <div class="col-md-1" align="center">
                                        <button class="btn waves-effect btn-danger" title="Rejected">
                                            <i class="fa fa-times"></i></button>
                                        <div class="m-t-10">Admin</div>
                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                    </div>
                                @else
                                    <div class="col-md-1" align="center">
                                        <button class="btn waves-effect {{(@$volunteer->approval_admin!=null)?'btn-success':'btn-secondary'}}">
                                            <i class="fa fa-check"></i></button>
                                        <div class="m-t-10">Admin</div>
                                        {{--<small>2018-09-01 19:35:52</small>--}}
                                    </div>
                                @endif
                                @if($volunteer->evp->reg_gm=='1' || $volunteer->evp->jenis_evp_id=='1')
                                    @if($volunteer->status=='REJ-GM')
                                        <div class="col-md-1" align="center">
                                            <button class="btn waves-effect btn-danger" title="Rejected">
                                                <i class="fa fa-times"></i></button>
                                            <div class="m-t-10">GM</div>
                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                        </div>
                                    @else
                                        <div class="col-md-1" align="center">
                                            <button class="btn waves-effect {{(@$volunteer->approval_gm!=null)?'btn-success':'btn-secondary'}}">
                                                <i class="fa fa-check"></i></button>
                                            <div class="m-t-10">GM</div>
                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                        </div>
                                    @endif
                                    @if($volunteer->status=='REJ-PST')
                                        <div class="col-md-1" align="center">
                                            <button class="btn waves-effect btn-danger" title="Rejected">
                                                <i class="fa fa-times"></i></button>
                                            <div class="m-t-10">Pusat</div>
                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                        </div>
                                    @else
                                        <div class="col-md-1" align="center">
                                            <button class="btn waves-effect {{(@$volunteer->approval_pusat!=null)?'btn-success':'btn-secondary'}}">
                                                <i class="fa fa-check"></i></button>
                                            <div class="m-t-10">Pusat</div>
                                            {{--<small>2018-09-01 19:35:52</small>--}}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{url('evp/volunteer/'.$volunteer->id)}}"
                               class="btn btn-info btn-xs waves-effect waves-light"
                               title="More Detail">
                                <i class="fa fa-info-circle"></i>
                            </a>

                            {{--@if($volunteer->status!='REJ-AT' || $volunteer->status!='REJ-AT' || $volunteer->status!='REJ-PST')--}}
                            @if($volunteer->approval_atasan==null && Auth::user()->isStruktural() && $volunteer->status=='REG')
                                {{--<a href="javascript:"--}}
                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                {{--title="Approve"--}}
                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                                {{--<i class="fa fa-check"></i>--}}
                                {{--</a>--}}
                                <a href="javascript:" data-toggle="modal" data-target="#rejectModal"
                                   class="btn btn-danger btn-xs waves-effect waves-light"
                                   title="Reject"
                                   onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('1');">
                                    <i class="fa fa-times"></i>
                                </a>
                            @elseif($volunteer->approval_gm==null && $volunteer->evp->reg_gm=='1' && Auth::user()->isGM() && $volunteer->status=='APRV-AT')
                                {{--<a href="javascript:"--}}
                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                {{--title="Approve"--}}
                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                                {{--<i class="fa fa-check"></i>--}}
                                {{--</a>--}}
                                <a href="javascript:" data-toggle="modal" data-target="#rejectModal"
                                   class="btn btn-danger btn-xs waves-effect waves-light"
                                   title="Reject"
                                   onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('2');">
                                    <i class="fa fa-times"></i>
                                </a>
                            @elseif($volunteer->approval_pusat==null && Auth::user()->can('evp_approve') && $volunteer->status=='APRV-GM')
                                {{--<a href="javascript:"--}}
                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                {{--title="Approve"--}}
                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai tersebut untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                                {{--<i class="fa fa-check"></i>--}}
                                {{--</a>--}}
                                <a href="javascript:" data-toggle="modal" data-target="#rejectModal"
                                   class="btn btn-danger btn-xs waves-effect waves-light"
                                   title="Reject"
                                   onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('3');">
                                    <i class="fa fa-times"></i>
                                </a>
                            @else
                            @endif
                            {{--@endif--}}

                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                            {{--title="Approve"--}}
                            {{--@if($volunteer->approval_atasan==null && Auth::user()->isStruktural())--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                            {{--@elseif($volunteer->approval_gm==null && Auth::user()->isGM())--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                            {{--@elseif($volunteer->approval_pusat==null && Auth::user()->can('evp_approve'))--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                            {{--@else--}}
                            {{--onclick="javascript:">--}}
                            {{--@endif--}}
                            {{--<i class="fa fa-check"></i>--}}
                            {{--</a>--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menolak pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/reject/'.$volunteer->id)}}'}">--}}
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