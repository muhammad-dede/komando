@extends('layout')

@section('css')
    <style>
        #progress_sinergi, #progress_prof, #progress_komit {
            width: 100%;
            background-color: #ddd;
        }

        #bar_sinergi, #bar_prof, #bar_komit {
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    <h4 class="page-title">Tata Nilai</h4>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="48" max="100">48%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12" align="center">
                        <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="180" class="img-responsive">
                    </div>
                </div>
                <div class="row m-t-30">
                    @if($coc->sinergi=='1' || $coc->saling_percaya=='1')
                        <div class="col-sm-4">
                            <div class="card card-block">
                                {{--<h3 class="card-title"><span class="icon-like"></span> Do</h3>--}}
                                {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-up"></span> Do</h3>--}}
                                <div class="row">
                                    {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                    {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                    {{--</div>--}}
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('assets/images/sinergi.png')}}" width="70">
                                                </td>
                                                <td style="padding-left: 20px;">
                                                    <h3 align="center"
                                                        style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">
                                                        Sinergi</h3>
                                                </td>
                                            </tr>
                                        </table>
                                        {{--<i class="icon-like text-primary center"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;"></i>--}}
                                    </div>
                                </div>
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                                <hr>
                                {{--<h3 align="center"--}}
                                    {{--style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">--}}
                                    {{--Sinergi</h3>--}}
                                {{--<hr>--}}
                                <p style="font-size: 18px;">
                                    Bekerja sama dengan produktif dengan seluruh pihak terkait dilandasi sikap saling menghargai, dan menghormati.
                                </p>
                                {{--<p class="card-text">--}}
                                <div id="progress_sinergi" class="m-t-30">
                                    <div id="bar_sinergi"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_sinergi" style="display: none;">
                                    <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                                    <label for="checkbox_do">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                                {{--</p>--}}
                                {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                            </div>
                        </div>
                    @endif
                    @if($coc->profesional=='1' || $coc->integritas=='1')
                        <div class="col-sm-4"
                             @if($coc->sinergi=='1')
                             style="display: none"
                             @endif
                             id="box_dont">
                            <div class="card card-block">
                                {{--<h3 class="card-title"><span class="icon-like"></span> Do</h3>--}}
                                {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-up"></span> Do</h3>--}}
                                <div class="row">
                                    {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                    {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                    {{--</div>--}}
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        {{--<i class="icon-like text-primary center"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;"></i>--}}
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
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                                <hr>
                                {{--<h3 align="center"--}}
                                    {{--style="color: #01a3bc; font-size: 25px; f/st-weight: bold; text-align: center; ">--}}
                                    {{--Profesional</h3>--}}
                                {{--<hr>--}}

                                <p style="font-size: 18px;">
                                    Cerdas, tuntas, antusias dan akurat dalam melihat aspek bisnis untuk memberikan nilai tambah bagi Perusahaan dalam mencapai kinerja terbaik secara efektif dan efisien.
                                </p>

                                {{--<p class="card-text">--}}
                                <div id="progress_prof" class="m-t-30">
                                    <div id="bar_prof"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_prof" style="display: none;">
                                    <input id="checkbox_dont" name="checkbox_dont" type="checkbox" value="1">
                                    <label for="checkbox_dont">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                                {{--</p>--}}
                                {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                            </div>
                        </div>
                    @endif
                    @if($coc->pelanggan=='1' || $coc->peduli=='1' || $coc->pembelajar=='1')
                        <div class="col-sm-4"
                             @if($coc->sinergi=='1' || $coc->profesional=='1')
                             style="display: none"
                             @endif
                             id="box_3">
                            <div class="card card-block">
                                {{--<h3 class="card-title"><span class="icon-like"></span> Do</h3>--}}
                                {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-up"></span> Do</h3>--}}
                                <div class="row">
                                    {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                    {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                    {{--</div>--}}
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        {{--<img src="{{asset('assets/images/pelanggan.png')}}" width="70">--}}
                                        {{--<i class="icon-like text-primary center"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;"></i>--}}
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
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                                <hr>
                                {{--<h3 align="center"--}}
                                    {{--style="color: #01a3bc; font-size: 25px; font-weight: bold; text-align: center; ">--}}
                                    {{--Berkomitmen pada Pelanggan</h3>--}}
                                {{--<hr>--}}

                                <p style="font-size: 18px;">
                                    Komitmen memberikan pengalaman terbaik (dari sisi produk, layanan, dan tarif) bagi pelanggan, baik pelanggan internal maupun pelanggan eksternal.
                                </p>

                                {{--<p class="card-text">--}}
                                <div id="progress_komit" class="m-t-30">
                                    <div id="bar_komit"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_komit" style="display: none;">
                                    <input id="checkbox_3" name="checkbox_3" type="checkbox" value="1">
                                    <label for="checkbox_3">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                                {{--</p>--}}
                                {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                            </div>
                        </div>
                    @endif
                    @if($coc->saling_percaya=='2')
                        <div class="col-sm-3">
                            <div class="card card-block">
                                {{--<h3 class="card-title"><span class="icon-like"></span> Do</h3>--}}
                                {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-up"></span> Do</h3>--}}
                                <div class="row">
                                    {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                    {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                    {{--</div>--}}
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/s.png')}}" width="100">
                                        {{--<i class="icon-like text-primary center"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;"></i>--}}
                                    </div>
                                </div>
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                                <hr>
                                <h3 align="center" class="text-danger"
                                    style="font-size: 30px; font-weight: bold; text-align: center; ">
                                    <span style="font-size: 50px;">S</span>aling Percaya <i>(Mutual Trust)</i></h3>
                                <hr>
                                <ol style="font-size: 18px;">
                                    <li>Saling menghargai.</li>
                                    <li>Beritikad baik.</li>
                                    <li>Transparan.</li>
                                </ol>

                                {{--<p class="card-text">--}}
                                <div class="checkbox checkbox-primary m-t-30">
                                    <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                                    <label for="checkbox_do">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                                {{--</p>--}}
                                {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                            </div>
                        </div>
                    @endif
                    @if($coc->integritas=='2')
                        <div class="col-sm-3"
                             @if($coc->saling_percaya=='1')
                             style="display: none"
                             @endif
                             id="box_dont">
                            <div class="card card-block">
                                {{--<h3 class="card-title"><span class="icon-dislike"></span> Don't</h3>--}}
                                {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-down"></span> Don't</h3>--}}
                                {{--<h3 class="card-title"><span class="fa fa-thumbs-o-down"></span> Don't</h3>--}}
                                <div class="row">
                                    {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                    {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                    {{--</div>--}}
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/i.png')}}" width="100">
                                        {{--<i class="icon-rocket text-success"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center;"></i>--}}
                                    </div>
                                </div>
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                                <hr>
                                <h3 align="center" class="text-warning"
                                    style="font-size: 30px; font-weight: bold; text-align: center; ">
                                    <span style="font-size: 50px;">I</span>ntegritas <i>(Integrity)</i></h3>
                                <hr>

                                <ol style="font-size: 18px;">
                                    <li>Jujur dan menjaga komitmen.</li>
                                    <li>Taat aturan dan bertanggung jawab.</li>
                                    <li>Keteladanan.</li>
                                </ol>

                                {{--<p class="card-text">--}}

                                <div class="checkbox checkbox-primary m-t-30">
                                    <input id="checkbox_dont" name="checkbox_dont" type="checkbox" value="1">
                                    <label for="checkbox_dont">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>

                                {{--</p>--}}
                                {{--<a href="{{url('commitment/pertanyaan')}}" class="btn btn-primary">Next</a>--}}

                            </div>
                            {{--<div class="row pull-right" id="btn_next" style="display: none">--}}
                            {{--<div class="col-md-12">--}}
                            {{--<a href="{{url('commitment/pedoman-perilaku/pertanyaan')}}" class="btn btn-primary btn-lg">Next <i--}}
                            {{--class="fa fa-chevron-circle-right"></i></a>--}}
                            {{--<button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-chevron-circle-right"></i></button>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    @endif
                    @if($coc->peduli=='2')
                        <div class="col-sm-3"
                             @if($coc->saling_percaya=='1' || $coc->integritas=='1')
                             style="display: none"
                             @endif
                             id="box_3">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/p.png')}}" width="100">
                                        {{--<i class="icon-rocket text-success"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center;"></i>--}}
                                    </div>
                                </div>
                                <hr>
                                <h3 align="center" class="text-primary"
                                    style="font-size: 30px; font-weight: bold; text-align: center; ">
                                    <span style="font-size: 50px;">P</span>eduli <i>(Care)</i></h3>
                                <hr>

                                <ol style="font-size: 18px;">
                                    <li>Proaktif dan saling membantu.</li>
                                    <li>Memberi yang terbaik.</li>
                                    <li>Menjaga Citra Perusahaan.</li>
                                </ol>

                                <div class="checkbox checkbox-primary m-t-30">
                                    <input id="checkbox_3" name="checkbox_dont" type="checkbox" value="1">
                                    <label for="checkbox_3">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($coc->pembelajar=='2')
                        <div class="col-sm-3"
                             @if($coc->saling_percaya=='1' || $coc->integritas=='1' || $coc->peduli=='1')
                             style="display: none"
                             @endif
                             id="box_4">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/pp.png')}}" width="100">
                                        {{--<i class="icon-rocket text-success"--}}
                                        {{--style="font-size: 60px; font-weight: bold; text-align: center;"></i>--}}
                                    </div>
                                </div>
                                <hr>
                                <h3 align="center" class="text-success"
                                    style="font-size: 30px; font-weight: bold; text-align: center; ">
                                    <span style="font-size: 50px;">P</span>embelajar <i>(Continous Learning)</i></h3>
                                <hr>

                                <ol style="font-size: 18px;">
                                    <li>Belajar berkelanjutan dan beradaptasi.</li>
                                    <li>Berbagi pengetahuan dan pengalaman.</li>
                                    <li>Berinovasi.</li>
                                </ol>

                                <div class="checkbox checkbox-primary m-t-30">
                                    <input id="checkbox_4" name="checkbox_dont" type="checkbox" value="1">
                                    <label for="checkbox_4">
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
                {!! Form::open(['url'=>'coc/pedoman-perilaku/'.$coc->id]) !!}
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

            @if($coc->sinergi=='1')
                setTimeout(function(){ move('bar_sinergi', 'check_sinergi'); }, 500);
            @elseif($coc->profesional=='1' && $coc->sinergi!='1')
                setTimeout(function(){ move('bar_prof', 'check_prof', 70); }, 500);
            @elseif($coc->pelanggan=='1' && $coc->sinergi!='1' && $coc->profesional!='1')
                setTimeout(function(){ move('bar_komit', 'check_komit', 70); }, 500);
            @endif

        });

        {{--$('#checkbox_do').click(function () {--}}
            {{--if ($('#checkbox_do').is(':checked')) {--}}
{{--//                $('html, body').animate({scrollTop: 0}, 'fast');--}}
            {{--}--}}
            {{--@if($coc->integritas=='1')--}}
            {{--$('#box_dont').fadeToggle();--}}
            {{--@else--}}
            {{--@if($coc->peduli=='1')--}}
            {{--$('#box_3').fadeToggle();--}}
            {{--@else--}}
            {{--@if($coc->pembelajar=='1')--}}
            {{--$('#box_4').fadeToggle();--}}
            {{--@else--}}
            {{--$('#btn_next').fadeToggle();--}}
            {{--@endif--}}
            {{--@endif--}}
            {{--@endif--}}
        {{--});--}}
        $('#checkbox_do').click(function () {
//            $('#box_3').fadeToggle();
            @if($coc->profesional=='1')
            move('bar_prof', 'check_prof', 70);
            $('#box_dont').fadeToggle();
            @else
            @if($coc->pelanggan=='1')
            move('bar_komit', 'check_komit', 70);
            $('#box_3').fadeToggle();
            @else
            $('#btn_next').fadeToggle();
            @endif
            @endif
        });
        $('#checkbox_dont').click(function () {
//            $('#box_4').fadeToggle();
            @if($coc->pelanggan=='1')
            move('bar_komit', 'check_komit', 70);
            $('#box_3').fadeToggle();
            @else
            $('#btn_next').fadeToggle();
            @endif
        });
        $('#checkbox_3').click(function () {
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