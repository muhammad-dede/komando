@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    {{--{!! Form::open(['url'=>'coc/check-in/'.$coc->id]) !!}--}}
    {{-- {!! Form::open(['url'=>'coc/visi-misi/'.$coc->id, 'id'=>'form_checkin']) !!} --}}
    {{--{!! Form::hidden('coc_id',$coc->id) !!}--}}
    {{-- {!! Form::hidden('status_checkin', '1', ['id'=>'status_checkin']) !!} --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row m-b-30">

                    <div class="col-xs-12">

                        {{-- <h3 class="card-title">{{strtoupper($coc->judul)}}</h3>
                        <span class="text-muted">Tema: {{$coc->tema->tema}}</span>
                        <hr> --}}

                        <div class="row" align="center">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 col-xs-12 align-center" align="center">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12" align="center">
                                        <img src="{{asset('assets/images/coc_komando.png')}}" alt="user" class="img-responsive img-circle" width="110" style="border: 10px solid #f0f0f0">
                                    </div>
                                </div>

                                <div class="row p-20">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="text-align: center;">
                                            <h4 class="text-warning"><i class="fa fa-warning"></i> Tidak ada CoC Nasional Hari ini.</h4>
                                        </div>
                                    </div>
                                </div>

                                <div style="padding: 10px; margin-top: 10px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td width="50" style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="bg-icon pull-xs-left" style="margin-right:20px; height:30px;">
                                                    {{-- <i class="icon-home" style="font-size:20px;"></i> --}}
                                                </div>
                                            </td>
                                            <td style="padding-top: 10px; padding-bottom: 10px; border-top: 2px solid #f0f0f0; ">
                                                <div class="text-xs-left">
                                                    {{-- <span class="text-help text-uppercase m-b-5 m-t-5">Tidak ada CoC Nasional hari ini. </span> --}}
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="button-list m-t-20" align="center">
                                            <button type="button" class="btn btn-secondary waves-effect waves-light btn-lg text-muted" onclick="window.location.href ='{{url('coc')}}'">
                                                <span class="btn-label"><i class="fa fa-chevron-left"></i>
                                                </span>Back</button>
                                        </div>
                                    </div>
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

@stop
