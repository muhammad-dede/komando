@extends('layout')

@section('css')
    <style>
        #progress_prinsip{
            width: 100%;
            background-color: #ddd;
        }

        #bar_prinsip{
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    <h4 class="page-title">Prinsip (Belief)</h4>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="40" max="100">40%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12" align="center">
                        <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="180" class="img-responsive">
                    </div>
                </div>
                <div class="row m-t-30">
{{--                    @if($coc->sinergi=='1' || $coc->saling_percaya=='1')--}}

                    <div class="col-sm-3">
                    </div>
                        <div class="col-sm-6">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td align="center">
{{--                                                    <img src="{{asset('assets/images/sinergi.png')}}" width="70">--}}
                                                    <i class="fa fa-heart" style="color: #01a3bc; font-size: 50px; font-weight: bold; text-align: center; "></i>
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Tumbuh Berkembang Dengan Integritas Dan Keunggulan</h3>
                                                    <p align="center">
                                                        <i>Grow with Integrity and Excellence</i>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 18px;">
                                    PLN senantiasa tumbuh dan berkembang menjadi perusahaan terbaik karena Insan PLN menjaga
                                    martabat dan berintegritas, serta selalu berupaya mencapai keunggulan menuju kesempurnaan
                                    dalam menyediakan Listrik untuk kehidupan yang lebih baik.
                                </p>
                                <div id="progress_prinsip" class="m-t-30">
                                    <div id="bar_prinsip"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_prinsip" style="display: none;">
                                    <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                                    <label for="checkbox_do">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
{{--                    @endif--}}
                    <div class="col-sm-3">
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
            setTimeout(function(){ move_prinsip(); }, 500);
        });

       $('#checkbox_do').click(function () {
//            if ($('#checkbox_do').is(':checked')) {
                $('#btn_next').fadeToggle();
//            }
        });

        function move_prinsip() {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById('bar_prinsip');
                var width = 1;
                var id = setInterval(frame, 100);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById("check_prinsip").style.display = 'block';
                        document.getElementById("bar_prinsip").style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }

    </script>
@stop