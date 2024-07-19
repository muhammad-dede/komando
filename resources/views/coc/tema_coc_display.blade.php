@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-8">
                        <h2>{{$tema_coc->tema->tema}}</h2>
                        <span class="text-muted"><i
                                    class="fa fa-calendar"></i> {{$tema_coc->start_date->format('d F Y')}} &nbsp;&nbsp;-&nbsp;&nbsp; {{$tema_coc->end_date->format('d F Y')}}</span>
                    </div>
                    <div class="col-md-4">
                        <a href="{{url('coc/tema/export/'.$tema_coc->id)}}" id="post" type="submit"
                           class="btn btn-success  waves-effect waves-light pull-right">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                {{--<th width="50" style="text-align: center">No.</th>--}}
                                <th style="text-align: center">Judul</th>
                                <th style="text-align: center">Penulis Materi</th>
                                <th style="text-align: center">CoC Leader</th>
                                <th style="text-align: center">Company Code</th>
                                <th style="text-align: center">Business Area</th>
                                <th style="text-align: center">Jml. Peserta</th>
                                <th style="text-align: center">Tanggal</th>
                                <th style="text-align: center">Status</th>
                            </tr>
                            </thead>


                            <tbody>
                            {{--@if(isset($user_list))--}}
                            <?php
                            //$x=1;
                            //$jml_perilaku = App\PedomanPerilaku::all()->count();
                            ?>
                            @foreach($tema_coc->tema->coc as $coc)
                                <tr>
                                    {{--<td align="center">{{$x++}}</td>--}}
                                    <td><a href="{{url('coc/event/'.$coc->id)}}">{{@$coc->judul}}</a></td>
                                    <td>
                                        @if($coc->scope=='nasional')
                                            Kantor Pusat
                                        @else
                                            <div class="pull-left" style="margin-right: 15px;">
                                                <img src="{{(@$coc->materi->penulis->user->foto!='') ? url('user/foto/'.@$coc->materi->penulis->user->id) : url('user/foto-pegawai/'.@$coc->materi->penulis->nip)}}"
                                                     alt="User"
                                                     class="img-thumbnail" width="45">
                                            </div>
                                            <div class="pull-left">

                                                {{@$coc->materi->penulis->cname}}<br>
                                                <small class="text-muted">{{@$coc->materi->penulis->strukturPosisi->stext}}</small>

                                            </div>
                                        @endif

                                    </td>
                                    <td>
                                        @if($coc->scope=='nasional')
                                            Kantor Pusat
                                        @else
                                            <div class="pull-left" style="margin-right: 15px;">
                                                <img src="{{(@$coc->leader->user->foto!='') ? url('user/foto/'.@$coc->leader->user->id) : url('user/foto-pegawai/'.@$coc->leader->nip)}}"
                                                     alt="User"
                                                     class="img-thumbnail" width="45">
                                            </div>
                                            <div class="pull-left">

                                                {{@$coc->leader->cname}}<br>
                                                <small class="text-muted">{{@$coc->leader->strukturPosisi->stext}}</small>

                                            </div>
                                        @endif

                                    </td>
                                    <td>{{@$coc->company_code}} - {{@$coc->companyCode->description}}</td>
                                    <td>{{@$coc->business_area}} - {{@$coc->businessArea->description}}</td>
                                    <td align="center">{{@$coc->attendants->count()}}</td>
                                    <td>
                                        {{@$coc->tanggal_jam->format('Y-m-d h:i')}}
                                        <br>
                                        <small class="text-muted">{{@$coc->tanggal_jam->diffForHumans()}}</small>
                                    </td>
                                    <td>
                                        @if($coc->status=='COMP')
                                            <span class="label label-success">{{@$coc->status}}</span>
                                        @else
                                            <span class="label label-primary">{{@$coc->status}}</span>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            {{--@endif--}}

                            </tbody>
                        </table>
                        <!-- end row -->
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-md-6">
                        <a href="{{url('coc')}}" type="button" class="btn btn-primary btn-lg pull-left"><i
                                    class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6">
                        {{--<button id="btn_next" type="submit" class="btn btn-primary btn-lg disabled pull-right" disabled>Next <i class="fa fa-chevron-circle-right"></i></button>--}}
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
            $("#business_area").select2();
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
        });

    </script>
@stop