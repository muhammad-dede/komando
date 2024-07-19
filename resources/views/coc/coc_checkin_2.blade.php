@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'coc/visi-misi/'.$coc->id, 'id'=>'form_checkin']) !!}
    {!! Form::hidden('status_checkin', '1', ['id'=>'status_checkin']) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row m-b-30">

                    <div class="col-xs-12">

                        <div class="row" align="center">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 col-xs-12 align-center" align="center">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6" align="right">
                                        @if(Auth::user()->foto!='')
                                            <img src="{{url('user/foto-thumb')}}" alt="user" class="img-responsive img-circle pull-xs-right" width="110" style="border: 10px solid #f0f0f0">
                                        @else
                                            <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-responsive img-circle pull-xs-right" width="110" style="border: 10px solid #f0f0f0">
                                        @endif
                                    </div>
                                    <div class="col-md-6 col-xs-6" align="left">
                                        <img src="{{asset('assets/images/coc_komando.png')}}" alt="user" class="img-responsive img-circle pull-xs-left" width="110" style="border: 10px solid #f0f0f0">
                                    </div>
                                </div>

                                <div class="row p-20">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="text-align: center;">
                                            @if($coc->tanggal_jam->format('Ymd') > date('Ymd'))
                                                <h4 class="text-warning"><i class="fa fa-warning"></i> Maaf, check-in belum dapat dilakukan.</h4>
                                            @elseif($coc->status == 'COMP')
                                                <h4 class="text-warning"><i class="fa fa-warning"></i> Maaf, room CoC sudah ditutup.</h4>
                                            @elseif($coc->status == 'CANC')
                                                <h4 class="text-danger"><i class="fa fa-warning"></i> Maaf, room CoC dibatalkan oleh Admin.</h4>    
                                            @elseif($is_attendant == false)
                                                <h4 class="text-warning"><i class="fa fa-warning"></i> Anda akan melakukan check-in lintas bidang.</h4>
                                            @elseif($sudah_checkin!=null)
                                                <h4 class="text-warning"><i class="fa fa-warning"></i> Anda sudah melakukan check-in.</h4>
                                            @else
                                                <h4><i class="fa fa-caret-down"></i> Anda akan check-in di:</h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div style="padding: 10px; margin-top: -10px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td width="50" style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    <i class="icon-home" style="font-size:20px;"></i>
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    <span class="text-help text-uppercase m-b-5 m-t-5">{{$coc->organisasi->stext}} </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    <i class="icon-speech" style="font-size:20px;"></i>
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    <span class="text-help text-uppercase m-b-5 m-t-5">{{$coc->judul}} </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    <i class="icon-user" style="font-size:20px;"></i>
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    <span class="text-help m-b-5 m-t-5">Leader : {{$coc->leader->name}} </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; border-bottom: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    <i class="icon-calender" style="font-size:20px;"></i>
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; border-bottom: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    @if($coc->tanggal_jam->format('Ymd') > date('Ymd'))
                                                        <span class="text-danger m-b-5 m-t-5">{{$tanggal_coc->format('l / j F Y')}}, jam {{$tanggal_coc->format('H:i')}} ({{$selisih_hari}} hari lagi)</span>
                                                    @else
                                                        <span class="text-help m-b-5 m-t-5">{{$tanggal_coc->format('l / j F Y')}}, jam {{$tanggal_coc->format('H:i')}}</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; border-bottom: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    <i class="icon-location-pin" style="font-size:20px;"></i>
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; border-bottom: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    <span class="text-help m-b-5 m-t-5">{{$coc->lokasi}}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                                <div class="row">
                                    @if($coc->tanggal_jam->format('Ymd') > date('Ymd') || $coc->status=='CANC')
                                        <div class="col-md-12 col-xs-12">
                                            <div class="button-list m-t-20" align="center">
                                                <button type="button" class="btn btn-secondary waves-effect waves-light btn-lg text-muted" onclick="window.location.href ='{{url('coc')}}'">
                                                    <span class="btn-label"><i class="fa fa-times"></i>
                                                    </span>Cancel</button>
                                            </div>
                                        </div>
                                    @else
                                    <div class="col-md-6 col-xs-6">
                                        <div class="button-list m-t-20" align="center">
                                            <button type="button" class="btn btn-secondary waves-effect waves-light btn-lg text-muted" onclick="window.location.href ='{{url('coc')}}'">
                                                <span class="btn-label"><i class="fa fa-times"></i>
                                                </span>Cancel</button>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        @if($coc->status=='COMP' || $sudah_checkin!=null)
                                            <div class="button-list m-t-20" align="center">
                                                <button type="button" class="btn btn-primary waves-effect waves-light btn-lg" onclick="window.location.href ='{{url('coc/event/'.$coc->id)}}'">
                                                    <span class="btn-label"><i class="fa fa-eye"></i>
                                                    </span>Detail</button>
                                            </div>
                                        @elseif($is_attendant == false)
                                            <div class="button-list m-t-20" align="center">
                                                <button type="button" class="btn btn-success waves-effect waves-light btn-lg" id="checkin-btn">
                                                    <span class="btn-label"><i class="fa fa-check"></i>
                                                    </span>Check In</button>
                                            </div>
                                        @else
                                            <div class="button-list m-t-20" align="center">
                                                <button type="submit" class="btn btn-success waves-effect waves-light btn-lg" id="checkin-btn">
                                                    <span class="btn-label"><i class="fa fa-check"></i>
                                                    </span>Check In</button>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stop

@section('javascript')
            <!-- Raty-fa -->
    <script src="{{asset('assets/plugins/raty-fa/jquery.raty-fa.js')}}"></script>
    <!-- Init -->
    <script src="{{asset('assets/pages/jquery.rating.js')}}"></script>

    <script>

        @if($is_attendant == false)
        //Parameter
        $('#checkin-btn').click(function () {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan melakukan check-in pada room CoC di lintas bidang. Pastikan room CoC yang Anda pilih sudah benar. Klik Ya untuk melanjutkan.",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Lanjutkan!",
                cancelButtonText: "Tidak",
                closeOnConfirm: false
            }, function () {
                //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                $('#form_checkin').submit();
            });
        });
        @endif

    </script>

@stop
