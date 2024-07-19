@extends('layout')

@section('css')
    <style>
        #progress_keunggulan, #progress_integritas, #progress_sinergi, #progress_prof, #progress_komit {
            width: 100%;
            background-color: #ddd;
        }

        #bar_keunggulan, #bar_integritas, #bar_sinergi, #bar_prof, #bar_komit {
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    {{--  <h4 class="page-title">Tata Nilai</h4>  --}}
@stop

@section('content')
    <progress class="progress progress-info progress-xs m-t-10" value="60" max="100">60%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                
                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title">Values (Nilai) &nbsp;&nbsp;<span><img src="{{asset('assets/images/ipromise.png')}}" width="150" class="img-responsive"></span></h4>
                    </div>
                </div>

                <div class="row m-t-30">
                    @if($coc->integritas=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/integritas.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Integritas</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Patuh pada aturan Perusahaan, selalu menempatkan kepentingan Perusahaan diatas kepentingan pribadi, konsisten antara ucapan dan tindakan sesuai dengan norma dan aturan yang berlaku. 
                                </p>
                                <p style="font-size: 16px;">
                                    Perilaku Utama :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Jujur dan Dapat Dipercaya</li>
                                    <li>Disiplin, Konsisten dan Memenuhi Komitmen</li>
                                    <li>Berani mengambil keputusan dengan risiko yang terukur</li>
                                </ul>

                                <div id="progress_integritas" class="m-t-30">
                                    <div id="bar_integritas"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_integritas" style="display: none;">
                                    <input id="checkbox_integritas" name="checkbox_integritas" type="checkbox" value="1">
                                    <label for="checkbox_integritas">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->profesional=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->integritas=='1')
                             style="display: none"
                             @endif
                             id="box_prof">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/profesional.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Profesional</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>

                                <p style="font-size: 16px;">
                                    Terus menunjukkan sikap dan meningkatkan kompetensi terbaik serta menjalankan tugas dan fungsinya secara tuntas dengan penuh tanggungjawab.
                                </p>
                                <p style="font-size: 16px;">
                                    Perilaku Utama :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Kompeten dan terus belajar</li>
                                    <li>Bekerja efektif, efisien, tuntas dan penuh tanggung jawab</li>
                                </ul>

                                <div id="progress_prof" class="m-t-30">
                                    <div id="bar_prof"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_prof" style="display: none;">
                                    <input id="checkbox_prof" name="checkbox_prof" type="checkbox" value="1">
                                    <label for="checkbox_prof">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->pelanggan=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->integritas=='1' || $coc->profesional=='1')
                             style="display: none"
                             @endif
                             id="box_komit">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/pelanggan.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Berkomitmen pada Pelanggan</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>

                                <p style="font-size: 16px;">
                                    Senantiasa memberikan pengalaman terbaik yang bernilai tambah bagi pelanggan internal dan ekstrenal.
                                </p>
                                <p style="font-size: 16px;">
                                    Perilaku Utama :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Proaktif dan cepat tanggap terhadap kebutuhan pelanggan</li>
                                    <li>Berwawasan bisnis dan sosial dalam memberi solusi terbaik</li>
                                </ul>

                                <div id="progress_komit" class="m-t-30">
                                    <div id="bar_komit"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_komit" style="display: none;">
                                    <input id="checkbox_komit" name="checkbox_komit" type="checkbox" value="1">
                                    <label for="checkbox_komit">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->sinergi=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->integritas=='1' || $coc->profesional=='1' || $coc->pelanggan=='1')
                             style="display: none"
                             @endif
                             id="box_sinergi">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/sinergi2.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Sinergi</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>

                                <p style="font-size: 16px;">
                                    Membangun kolaborasi dengan berbagai pihak yang dilandasi niat baik dan rasa saling percaya untuk mencapai tujuan organisasi.
                                </p>
                                <p style="font-size: 16px;">
                                    Perilaku Utama :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Saling menghargai dan Menghormati</li>
                                    <li>Bekerjasama untuk mencapai tujuan perusahaan</li>
                                </ul>

                                <div id="progress_sinergi" class="m-t-30">
                                    <div id="bar_sinergi"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_sinergi" style="display: none;">
                                    <input id="checkbox_sinergi" name="checkbox_sinergi" type="checkbox" value="1">
                                    <label for="checkbox_sinergi">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($coc->keunggulan=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->integritas=='1' || $coc->profesional=='1' || $coc->pelanggan=='1' || $coc->sinergi=='1')
                             style="display: none"
                             @endif
                             id="box_keunggulan">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/keunggulan.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Keunggulan</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>

                                <p style="font-size: 16px;">
                                    Memiliki semangat pantang menyerah dan cepat beradaptasi untuk menjadi yang terbaik, dengan mengutamakan kesehatan, keselamatan kerja serta lingkungan hidup.
                                </p>
                                <p style="font-size: 16px;">
                                    Perilaku Utama :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Gigih, gesit dan adaptif</li>
                                    <li>Kreatif, inovatif dan terus melakukan perbaikan</li>
                                    <li>Peduli K3LH</li>
                                </ul>

                                <div id="progress_keunggulan" class="m-t-30">
                                    <div id="bar_keunggulan"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_keunggulan" style="display: none;">
                                    <input id="checkbox_keunggulan" name="checkbox_keunggulan" type="checkbox" value="1">
                                    <label for="checkbox_keunggulan">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    
                    
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
                {!! Form::open(['url'=>'coc/fokus-perilaku/'.$coc->id]) !!}
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

            @if($coc->integritas=='1')
                setTimeout(function(){ move('bar_integritas', 'check_integritas'); }, 500);
            @elseif($coc->integritas!='1' && $coc->profesional=='1')
                setTimeout(function(){ move('bar_prof', 'check_prof', 70); }, 500);
            @elseif($coc->integritas!='1' && $coc->profesional!='1' && $coc->pelanggan=='1')
                setTimeout(function(){ move('bar_komit', 'check_komit', 70); }, 500);
            @elseif($coc->integritas!='1' && $coc->profesional!='1' && $coc->pelanggan!='1' && $coc->sinergi=='1')
                setTimeout(function(){ move('bar_sinergi', 'check_sinergi', 70); }, 500);
            @elseif($coc->integritas!='1' && $coc->profesional!='1' && $coc->pelanggan!='1' && $coc->sinergi!='1' && $coc->keunggulan=='1')
                setTimeout(function(){ move('bar_keunggulan', 'check_keunggulan', 70); }, 500);
            @endif

        });

{{--  
        $('#checkbox_integritas').click(function () {
//            $('#box_komit').fadeToggle();
            @if($coc->profesional=='1')
                move('bar_prof', 'check_prof', 70);
                $('#box_prof').fadeToggle();
            @else
                @if($coc->pelanggan=='1')
                    move('bar_komit', 'check_komit', 70);
                    $('#box_komit').fadeToggle();
                @else
                    $('#btn_next').fadeToggle();
                @endif
            @endif
        });

        $('#checkbox_prof').click(function () {
//            $('#box_4').fadeToggle();
            @if($coc->pelanggan=='1')
                move('bar_komit', 'check_komit', 70);
                $('#box_komit').fadeToggle();
            @else
                $('#btn_next').fadeToggle();
            @endif
        });

        $('#checkbox_komit').click(function () {
            $('#btn_next').fadeToggle();
        });
  --}}

//----------------------------------------------------------------------

        $('#checkbox_integritas').click(function () {
            @if($coc->profesional=='1')
                move('bar_prof', 'check_prof', 70);
                $('#box_prof').fadeToggle();
            @else
                @if($coc->pelanggan=='1')
                    move('bar_komit', 'check_komit', 70);
                    $('#box_komit').fadeToggle();
                @else
                    @if($coc->sinergi=='1')
                        move('bar_sinergi', 'check_sinergi', 70);
                        $('#box_sinergi').fadeToggle();
                    @else
                        @if($coc->keunggulan=='1')
                            move('bar_keunggulan', 'check_keunggulan', 70);
                            $('#box_keunggulan').fadeToggle();
                        @else
                            $('#btn_next').fadeToggle();
                        @endif
                    @endif
                @endif
            @endif
        });

        $('#checkbox_prof').click(function () {
            @if($coc->pelanggan=='1')
                move('bar_komit', 'check_komit', 70);
                $('#box_komit').fadeToggle();
            @else
                @if($coc->sinergi=='1')
                    move('bar_sinergi', 'check_sinergi', 70);
                    $('#box_sinergi').fadeToggle();
                @else
                    @if($coc->keunggulan=='1')
                        move('bar_keunggulan', 'check_keunggulan', 70);
                        $('#box_keunggulan').fadeToggle();
                    @else
                        $('#btn_next').fadeToggle();
                    @endif
                @endif
            @endif
        });

        $('#checkbox_komit').click(function () {
            @if($coc->sinergi=='1')
                move('bar_sinergi', 'check_sinergi', 70);
                $('#box_sinergi').fadeToggle();
            @else
                @if($coc->keunggulan=='1')
                    move('bar_keunggulan', 'check_keunggulan', 70);
                    $('#box_keunggulan').fadeToggle();
                @else
                    $('#btn_next').fadeToggle();
                @endif
            @endif
        });

        $('#checkbox_sinergi').click(function () {
            @if($coc->keunggulan=='1')
                move('bar_keunggulan', 'check_keunggulan', 70);
                $('#box_keunggulan').fadeToggle();
            @else
                $('#btn_next').fadeToggle();
            @endif
        });

        $('#checkbox_keunggulan').click(function () {
            $('#btn_next').fadeToggle();
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