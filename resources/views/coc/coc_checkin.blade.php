@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
@stop

@section('title')
    <h4 class="page-title">Code of Conduct</h4>
@stop

@section('content')
    {{--{!! Form::open(['url'=>'coc/check-in/'.$coc->id]) !!}--}}
    {!! Form::open(['url'=>'coc/visi-misi/'.$coc->id, 'id'=>'form_checkin']) !!}
    {{--{!! Form::hidden('coc_id',$coc->id) !!}--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row m-b-30">
                    <div class="col-xs-12">

                        <h3 class="card-title">{{strtoupper($coc->judul)}}</h3>
                        <span class="text-muted">Tema: {{$coc->tema->tema}}</span>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12">
{{--                                            <img src="{{(Auth::user()->foto!='') ? url('user/foto-thumb/'.Auth::user()->id) : url('user/foto-pegawai-thumb/'.Auth::user()->strukturJabatan->nip)}}"--}}
                                            @if(Auth::user()->foto!='')
                                                <img src="{{url('user/foto-thumb')}}" alt="user" class="img-thumbnail" width="128">
                                            @else
                                                <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-thumbnail" width="128">
                                            @endif
                                            {{--<img src="{{(Auth::user()->foto!='') ? url('user/foto-thumb/'.Auth::user()->id) : asset('assets/images/user.jpg')}}"--}}
                                                 {{--alt="user" class="img-thumbnail" width="128">--}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="button-list">
                                                @if($coc->checkAtendant(Auth::user()->id)==null)
                                                    @if($coc->tanggal_jam->format('Ymd') > date('Ymd'))
                                                        <button type="submit" class="btn btn-secondary-outline btn-rounded btn-lg waves-effect " disabled>
                                                                <span class="btn-label">
                                                                    <i class="fa fa-check-circle"></i>
                                                                </span>
                                                            Check In
                                                        </button>
                                                    @elseif(env('OPEN_COC_NAS',true) && $coc->status!='COMP')
                                                        {{--<div class="btn-group">
                                                            <button type="button" class="btn btn-success-outline btn-rounded btn-lg dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="btn-label">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                                Check In
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @foreach($status_list as $status)
                                                                    <a class="dropdown-item" href="#" onclick="$('#status_checkin').val('{{$status->id}}');$('#form_checkin').submit()">{{$status->status}}</a>
                                                                @endforeach
                                                            </div>
                                                        </div>--}}
                                                        {!! Form::hidden('status_checkin', '1', ['id'=>'status_checkin']) !!}


                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="button-list m-t-20">
                                                                    <button type="submit" class="btn btn-success-outline btn-rounded btn-lg waves-effect waves-light">
                                                                        <span class="btn-label">
                                                                            <i class="fa fa-check-circle"></i>
                                                                        </span>
                                                                        Check In
                                                                    </button>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    @else
                                                        {{--@if($coc->tanggal_jam->format('Ymd') > date('Ymd'))--}}
                                                            {{--<button type="submit" class="btn btn-secondary-outline btn-rounded btn-lg waves-effect " disabled>--}}
                                                                {{--<span class="btn-label">--}}
                                                                    {{--<i class="fa fa-check-circle"></i>--}}
                                                                {{--</span>--}}
                                                                {{--Check In--}}
                                                            {{--</button>--}}
                                                        @if(($coc->tanggal_jam->format('Ymd') == date('Ymd') && $coc->status!='COMP') ||
                                                            (@$coc->materi->jenisMateri->jenis=='Nasional' && $coc->status!='COMP' && env('OPEN_COC_NAS',true)))
                                                            {{--<button type="submit" class="btn btn-success-outline btn-rounded btn-lg waves-effect waves-light">--}}
                                                            {{--<span class="btn-label">--}}
                                                            {{--<i class="fa fa-check-circle"></i>--}}
                                                            {{--</span>--}}
                                                            {{--Check In--}}
                                                            {{--</button>--}}
                                                            {{--<div class="btn-group">
                                                                <button type="button" class="btn btn-success-outline btn-rounded btn-lg dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="btn-label">
                                                            <i class="fa fa-check-circle"></i>
                                                        </span>
                                                                    Check In
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    @foreach($status_list as $status)
                                                                        <a class="dropdown-item" href="#" onclick="$('#status_checkin').val('{{$status->id}}');$('#form_checkin').submit()">{{$status->status}}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            {!! Form::hidden('status_checkin', '1', ['id'=>'status_checkin']) !!}--}}


                                                            {!! Form::hidden('status_checkin', '1', ['id'=>'status_checkin']) !!}


                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="button-list m-t-20">
                                                                        <button type="submit" class="btn btn-success-outline btn-rounded btn-lg waves-effect waves-light">
                                                                        <span class="btn-label">
                                                                            <i class="fa fa-check-circle"></i>
                                                                        </span>
                                                                            Check In
                                                                        </button>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        @else
                                                            <button type="submit" class="btn btn-secondary-outline btn-rounded btn-lg waves-effect " disabled>
                                                <span class="btn-label">
                                                    <i class="fa fa-check-circle"></i>
                                                </span>
                                                                Check In
                                                            </button>
                                                        @endif
                                                    @endif


                                                @endif
                                                {{--<a href="{{url('coc/event/'.$coc->id)}}"--}}
                                                {{--class="btn btn-secondary btn-rounded btn-lg waves-effect waves-light">--}}
                                                {{--Skip--}}
                                                {{--<span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>--}}
                                                {{--</span>--}}
                                                {{--</a>--}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 p-20">
                                <div class="text-center">
                                    <h5><b>{{Auth::user()->name}}</b><br><small class="text-muted">{{@Auth::user()->strukturPosisi()->stext}}</small>
                                    </h5>
                                    <p><i class="fa fa-map-marker"></i> {{$coc->lokasi}}</p>
                                    <p><i class="fa fa-clock-o"></i> {{$coc->tanggal_jam->format('d F Y, H:i A')}}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                            </div>

                            {{--<table width="450">--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
{{--                                        <img src="{{($coc->pemateri->user->foto!='') ? url('user/foto/'.$coc->pemateri->user->id) : url('user/foto-pegawai/'.$coc->pemateri->user->strukturJabatan->nip)}}"--}}
                                        {{--<img src="{{(Auth::user()->foto!='') ? url('user/foto/'.Auth::user()->id) : url('user/foto-pegawai/'.Auth::user()->strukturJabatan->nip)}}"--}}
                                             {{--alt="user" class="img-thumbnail" width="128">--}}
                                    {{--</td>--}}
                                    {{--<td style="padding: 20px;">--}}
{{--                                        <p><b>{{$coc->pemateri->cname}}</b><br>{{$coc->pemateri->strukturPosisi->stext}}--}}
                                        {{--<h5><b>{{Auth::user()->name}}</b><br><small class="text-muted">{{Auth::user()->strukturPosisi()->stext}}</small>--}}
                                        {{--</h5>--}}
                                            {{--<p><i class="fa fa-map-marker"></i> {{$coc->lokasi}}</p>--}}
                                            {{--<p><i class="fa fa-clock-o"></i> {{$coc->tanggal_jam->format('d F Y, H:i A')}}</p>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
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
