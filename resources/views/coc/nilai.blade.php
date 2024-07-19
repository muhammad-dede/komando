@extends('layout')

@section('css')
    <style>
        #progress_values{
            width: 100%;
            background-color: #ddd;
        }

        #bar_values{
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    {{--  <h4 class="page-title">Values (Nilai)</h4>  --}}
@stop

@section('content')
    <progress class="progress progress-info progress-xs m-t-10" value="40" max="100">40%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title">Values (Nilai) &nbsp;&nbsp;<span><img src="{{asset('assets/images/ipromise.png')}}" width="150" class="img-responsive"></span></h4>
                    </div>
                </div>

                {{--  <div class="row">
                    <div class="col-md-12" align="center">
                        <h4 class="page-title">Values (Nilai)</h4> <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="180" class="img-responsive">
                    </div>
                </div>  --}}

                <div class="row m-t-20">
{{--                    @if($coc->sinergi=='1' || $coc->saling_percaya=='1')--}}
                    <div class="col-md-3 col-xs-12">
                        <div class="card card-block">
                            
                            <h3 align="center"style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                Integritas
                            </h3>
                            <p align="center">
                                <i>Integrity</i>
                            </p>
                            <hr>
                            <div align="center">
                                <img src="{{asset('assets/images/integritas.png')}}" width="70" align="center">
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="card card-block">
                            
                            <h3 align="center"style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                Profesionalisme
                            </h3>
                            <p align="center">
                                <i>Professionalism</i>
                            </p>
                            <hr>
                            <div align="center">
                                <img src="{{asset('assets/images/profesional.png')}}" width="70" align="center">
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="card card-block">
                            
                            <h3 align="center"style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                Berkomitmen pada Pelanggan
                            </h3>
                            <p align="center">
                                <i>Commited to Customer</i>
                            </p>
                            <hr>
                            <div align="center">
                                <img src="{{asset('assets/images/pelanggan.png')}}" width="70" align="center">
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="card card-block">
                            
                            <h3 align="center"style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                Sinergi
                            </h3>
                            <p align="center">
                                <i>Synergy</i>
                            </p>
                            <hr>
                            <div align="center">
                                <img src="{{asset('assets/images/sinergi2.png')}}" width="70" align="center">
                            </div>
                    
                        </div>
                    </div>
{{--                    @endif--}}
                </div>
                <div class="row m-t-10">
                    <div class="col-md-4" style="margin-right: 70px;"></div>
                    <div class="col-md-3 col-xs-12">
                        <div class="card card-block">
                            
                            <h3 align="center"style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                Keunggulan
                            </h3>
                            <p align="center">
                                <i>Excellence</i>
                            </p>
                            <hr>
                            <div align="center">
                                <img src="{{asset('assets/images/keunggulan.png')}}" width="70" align="center">
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                

                <div class="m-b-20">
                    <div id="progress_values" class="m-t-30">
                        <div id="bar_values"></div>
                    </div>
                    <div class="checkbox checkbox-primary m-t-30" id="check_values" style="display: none;">
                        <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                        <label for="checkbox_do">
                            Saya sudah membaca dan memahami
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <small>Status :</small>
                        @if($status_checkin=='1')
                            <span class="label label-success"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                        @else
                            <span class="label label-danger"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                        @endif
                    </div>
                </div>
                {!! Form::open(['url'=>'coc/tata-nilai/'.$coc->id]) !!}
                {!! Form::hidden('status_checkin', $status_checkin, ['id'=>'status_checkin']) !!}
                <div class="row m-t-20">
                    <div class="col-md-6 col-xs-6">
                        <a href="{{url('coc/check-in/'.$coc->id)}}" type="button"
                           class="btn btn-primary btn-lg pull-left">
                            <i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <button id="btn_next" type="submit" class="btn btn-primary btn-lg pull-right"
                                style="display: none;">
                            Next <i class="fa fa-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(window).load(function(){
            setTimeout(function(){ move_values(); }, 500);
        });

       $('#checkbox_do').click(function () {
//            if ($('#checkbox_do').is(':checked')) {
                $('#btn_next').fadeToggle();
//            }
        });

        function move_values() {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById('bar_values');
                var width = 1;
                var id = setInterval(frame, 40);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById("check_values").style.display = 'block';
                        document.getElementById("bar_values").style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }

    </script>
@stop