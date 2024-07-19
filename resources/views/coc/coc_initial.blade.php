@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'coc/create/'.$materi->id]) !!}
    {{--{!! Form::hidden('coc_id',$coc->id) !!}--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row m-b-30">
                    <div class="col-xs-12">

                        <h3 class="card-title">{{strtoupper($materi->judul)}}</h3>
                        <span class="text-muted">Tema: {{$materi->tema->tema}}</span>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12" align="center">
                                            @if($materi->energize_day=='1')
                                                <img src="{{asset('assets/images/PLN.png')}}" alt="Energize Day"  class="img-fluid img-thumbnail" width="128">
                                            @elseif(@$materi->penulis->user->foto!='')
                                                <img src="{{asset('assets/images/users/foto-thumb/'.@$materi->penulis->user->foto)}}" alt="user"  class="img-fluid img-thumbnail" width="128">
                                            @else
                                                <img src="{{asset('assets/images/user.jpg')}}" alt="user"  class="img-fluid img-thumbnail" width="128">
                                            @endif
{{--                                            <img src="{{(@$materi->penulis->user->foto!='') ? url('user/foto-thumb/'.@$materi->penulis->user->id) : url('user/foto-pegawai-thumb/'.@$materi->penulis->nip)}}"--}}
                                            {{--<img src="{{(@$materi->penulis->user->foto!='') ? url('user/foto-thumb/'.@$materi->penulis->user->id) : asset('assets/images/user.jpg')}}"--}}
                                                 {{--alt="user" class="img-thumbnail" width="128">--}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="button-list m-t-20">
                                                <button type="submit" class="btn btn-success-outline btn-rounded btn-lg waves-effect waves-light">
                                                    <span class="btn-label">
                                                        <i class="zmdi zmdi-calendar-alt"></i>
                                                    </span>
                                                    Create Jadwal CoC
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 p-20 text-left">
                                @if($materi->energize_day=='1')
                                    <h5><b>Energize Day</b></h5>
                                @else
                                <label>Penulis</label>
                                <h5><b>{{@$materi->penulis->cname}}</b><br><small class="text-muted">{{@$materi->penulis->strukturPosisi->stext}}</small>
                                </h5>
                                @endif
                                <p><i class="fa fa-clock-o"></i> {{@$materi->event->start->format('d F Y')}}</p>
                            </div>

                            <div class="col-md-3">
                            </div>
                            {{----------------------------------------------}}

                            {{--<table width="450">--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
                                        {{--<img src="{{(@$materi->penulis->user->foto!='') ? url('user/foto/'.@$materi->penulis->user->id) : url('user/foto-pegawai/'.@$materi->penulis->nip)}}"--}}
                                             {{--alt="user" class="img-thumbnail" width="128">--}}
                                    {{--</td>--}}
                                    {{--<td style="padding: 20px;">--}}
                                        {{--<label>Penulis</label>--}}
                                        {{--<h5><b>{{@$materi->penulis->cname}}</b><br><small class="text-muted">{{@$materi->penulis->strukturPosisi->stext}}</small>--}}
                                        {{--</h5>--}}
                                            {{--<p><i class="fa fa-clock-o"></i> {{@$materi->event->start->format('d F Y')}}</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                        </div>
                        {{--<div class="button-list m-t-20">--}}
                                    {{--<button type="submit" class="btn btn-success-outline btn-rounded btn-lg waves-effect waves-light">--}}
                                {{--<span class="btn-label">--}}
                                    {{--<i class="zmdi zmdi-calendar-alt"></i>--}}
                                {{--</span>--}}
                                    {{--Create Jadwal CoC--}}
                                {{--</button>--}}

                        {{--</div>--}}
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
