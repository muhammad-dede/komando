@extends('layout')

@section('css')
    <style>
        #progress_isu_nasional{
            width: 100%;
            background-color: #ddd;
        }

        #bar_isu_nasional{
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    {{--  <h4 class="page-title">Pelanggaran Disiplin</h4>  --}}
@stop

@section('content')
    <progress class="progress progress-info progress-xs m-t-10" value="80" max="100">80%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title"><span><img src="{{asset('assets/images/globe-grid.png')}}" width="60" class="img-responsive"></span> &nbsp; Isu Strategis</h4>
                    </div>
                </div>
                @if($isu_nasional->jenis_isu_nasional_id==1)
                <div class="row m-t-30">
                    {{-- image isu strategis --}}
                    <div class="col-sm-12">
                        <a href="{{asset($isu_nasional->image)}}" target="_blank"><img src="{{asset($isu_nasional->image)}}" width="100%" class="img-responsive"></a>
                    </div>
                    <div id="progress_isu_nasional" class="m-t-30">
                        <div id="bar_isu_nasional"></div>
                    </div>
                    <div class="checkbox checkbox-primary m-t-30" id="check_isu_nasional" style="display: none;">
                        <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                        <label for="checkbox_do">
                            Saya sudah membaca dan memahami
                        </label>
                    </div>
                </div>
                @else
                <div class="row m-t-30">
                    <div class="col-sm-3">
                    </div>
                        <div class="col-sm-6">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td align="center">
                                                    <i class="fa fa-warning text-danger" style="font-size: 50px; font-weight: bold; text-align: center; "></i>
                                                </td>
                                                <td style="padding-left: 20px;">
                                                        <h3 align="center"
                                                            style="font-size: 25px; font-weight: bold; text-align: center; " class="text-danger">
                                                            {!! $isu_nasional->header !!}
                                                        </h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 18px;">
                                    <b>{!! $isu_nasional->sub_header !!}</b>
                                </p>
                                <p style="font-size: 18px;">
                                    {!! $isu_nasional->description !!}
                                </p>
                                <hr>
                                <div style="font-size: 18px;">
                                    {!! $isu_nasional->sanksi !!}
                                </div>
                                <div id="progress_isu_nasional" class="m-t-30">
                                    <div id="bar_isu_nasional"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_isu_nasional" style="display: none;">
                                    <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                                    <label for="checkbox_do">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    <div class="col-sm-3">
                    </div>
                </div>
                @endif
                <div class="row m-t-30">
                    <div class="col-md-12">
                        <small>Status :</small>
                        @if($status_checkin=='1')
                            <span class="label label-success"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                        @else
                            <span class="label label-danger"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                        @endif
                    </div>
                </div>
                {!! Form::open(['url'=>'coc/pelanggaran/'.$coc->id]) !!}
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
            setTimeout(function(){ move('bar_isu_nasional', 'check_isu_nasional', '{{$delay}}');; }, 500);
        });

       $('#checkbox_do').click(function () {
//            if ($('#checkbox_do').is(':checked')) {
                $('#btn_next').fadeToggle();
//            }
        });

        function move(bar, check, interval=50) {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById(bar);
                var width = 1;
                var id = setInterval(frame, interval);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById(check).style.display = 'block';
                        document.getElementById(bar).style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }

    </script>
@stop