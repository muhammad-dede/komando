@extends('layout')

@section('css')
    <style>
        #progress_pelanggaran{
            width: 100%;
            background-color: #ddd;
        }

        #bar_pelanggaran{
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
    <progress class="progress progress-info progress-xs m-t-10" value="100" max="100">100%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title"><span><img src="{{asset('assets/images/stop.png')}}" width="60" class="img-responsive"></span> &nbsp; Awareness Pelanggaran Disiplin Pegawai, Pedoman Perilaku, dan Etika Bisnis</h4>
                    </div>
                </div>
                
                {{--  <div class="row">
                    <div class="col-md-12" align="center">
                        <img src="{{asset('assets/images/stop.png')}}" width="180" class="img-responsive">
                    </div>
                </div>  --}}

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
                                                    <i class="fa fa-warning {{($pelanggaran->jenis_pelanggaran_id == '3')?'text-danger':'text-warning'}}" style="font-size: 50px; font-weight: bold; text-align: center; "></i>
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    @if($pelanggaran->jenis_pelanggaran_id == '1')
                                                        <h3 align="center"
                                                            style="font-size: 25px; font-weight: bold; text-align: center; " class="text-warning">
                                                            Pelanggaran Disiplin Ringan</h3>
                                                    @elseif($pelanggaran->jenis_pelanggaran_id == '2')
                                                        <h3 align="center"
                                                            style="font-size: 25px; font-weight: bold; text-align: center; " class="text-warning">
                                                            Pelanggaran Disiplin Sedang</h3>
                                                    @elseif($pelanggaran->jenis_pelanggaran_id == '3')
                                                        <h3 align="center"
                                                            style="font-size: 25px; font-weight: bold; text-align: center; " class="text-danger">
                                                            Pelanggaran Disiplin Berat</h3>
                                                    @else
                                                    <h3 align="center"
                                                        style="font-size: 25px; font-weight: bold; text-align: center; " class="text-warning">
                                                        Pelanggaran Disiplin</h3>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 18px;">
                                    {{$pelanggaran->description}}
                                </p>
                                <hr>
                                <div style="font-size: 18px;">
                                <b>Pilihan jenis sanksi yang didapat:</b>
                                @if($pelanggaran->jenis_pelanggaran_id == '1')
                                    <ul>
                                        <li>Teguran Tertulis, yang berlaku untuk jangka waktu 3 (tiga) bulan</li>
                                        <li>Teguran Tertulis, yang berlaku untuk jangka waktu 6 (enam) bulan</li>
                                    </ul>
                                @elseif($pelanggaran->jenis_pelanggaran_id == '2')
                                    <ul>
                                        <li>Peringatan tertulis pertama dengan pemotongan kompensasi tidak tetap berupa pay for position sebesar 25% (dua puluh lima perseratus) yang berlaku untuk jangka waktu 6 (enam) bulan</li>
                                        <li>Peringatan tertulis kedua dengan pemotongan kompensasi tidak tetap berupa pay for position sebesar 50% (lima puluh perseratus) yang berlaku untuk jangka waktu 9 (sembilan) bulan</li>
                                        <li>Peringatan tertulis ketiga dengan pemotongan kompensasi tidak tetap berupa pay for position sebesar 75% (tujuh puluh lima perseratus) yang berlaku untuk jangka waktu 12 (dua belas) bulan</li>
                                        <li>Peringatan tertulis pertama dan terakhir dengan penurunan 1 (satu) person grade dan pemotongan kompensasi tidak tetap berupa pay for position sebesar 100% (seratus perseratus) yang berlaku untuk jangka waktu 12 (dua belas) bulan</li>
                                    </ul>
                                @elseif($pelanggaran->jenis_pelanggaran_id == '3')
                                    <ul>
                                        <li>Pemutusan Hubungan Kerja (PHK)</li>
                                    </ul>
                                @else 
                                
                                @endif

                                </div>
                                <div id="progress_pelanggaran" class="m-t-30">
                                    <div id="bar_pelanggaran"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_pelanggaran" style="display: none;">
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
                {!! Form::open(['url'=>'coc/check-in/'.$coc->id]) !!}
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
                            <i class="fa fa-check"></i> Finish
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
            setTimeout(function(){ move('bar_pelanggaran', 'check_pelanggaran', '{{($count_word*8)/2}}');; }, 500);
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
