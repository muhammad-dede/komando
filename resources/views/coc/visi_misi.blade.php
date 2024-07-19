@extends('layout')

@section('css')
    <style>
        #progress_visi, #progress_misi {
            width: 100%;
            background-color: #ddd;
        }

        #bar_visi, #bar_misi {
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    {{--  <h4 class="page-title">Visi & Misi PLN</h4>  --}}
@stop

@section('content')
    <progress class="progress progress-info progress-xs m-t-10" value="16" max="100">16%</progress>
    
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12 m-t-10 m-b-10">
                        <h4 class="card-title">Visi & Misi PLN</h4>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card card-block">
                            {{--<h3 class="card-title"><span class="icon-like"></span> Do</h3>--}}
                            {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-up"></span> Do</h3>--}}
                            <div class="row">
                                {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                {{--</div>--}}
                                <div class="col-md-12 col-xs-12 center" align="center">
                                    <i class="icon-globe text-primary center"
                                       style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;"></i>
                                </div>
                            </div>
                            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                            <hr>
                            <h1 align="center" class="text-primary"
                                style="font-size: 30px; font-weight: bold; text-align: center; ">
                                VISI</h1>
                            <hr>
                            <h4 class="center" align="center">
                                "Menjadi Perusahaan Listrik Terkemuka se-Asia Tenggara dan #1 Pilihan Pelanggan untuk Solusi Energi"
                            </h4>

                            {{--<div style="overflow: auto; height: 200px;">--}}
                                {{--<label class="m-t-20" style="font-size: 18px;">Ciri Perusahaan Kelas Dunia :</label>--}}
                                {{--<ol style="font-size: 16px;">--}}
                                    {{--@foreach($pedoman->perilaku()->where('jenis',1)->get() as $perilaku)--}}
                                    {{--<li>Merupakan barometer standar kualitas pelayanan dunia.</li>--}}
                                    {{--<li>Memiliki cakrawala pemikiran yang mutakhir.</li>--}}
                                    {{--<li>Terdepan dalam pemanfaatan teknologi.</li>--}}
                                    {{--<li>Haus akan kesempurnaan kerja dan perilaku.</li>--}}
                                    {{--<li>Merupakan Perusahaan idaman bagi pencari kerja.</li>--}}
                                    {{--@endforeach--}}
                                    {{--<ol style="font-size: 18px;color: #2ea36e; ">--}}
                                    {{--<ol style="font-size: 18px;color: #7f7f7f; ">--}}
                                {{--</ol>--}}

                                {{--<label class="m-t-20" style="font-size: 18px;">Tumbuh Kembang :</label>--}}
                                {{--<ol style="font-size: 16px;">--}}
                                    {{--<li>Mampu mengantisipasi berbagai peluang dan tantangan usaha.</li>--}}
                                    {{--<li>Konsisten dalam pengembangan standar kinerja.</li>--}}
                                {{--</ol>--}}

                                {{--<label class="m-t-20" style="font-size: 18px;">Unggul :</label>--}}
                                {{--<ol style="font-size: 16px;">--}}
                                    {{--<li>Terbaik, terkemuka dan mutakhir dalam bisnis kelistrikan.</li>--}}
                                    {{--<li>Fokus dalam usaha mengoptimalkan potensi insani.</li>--}}
                                    {{--<li>Peningkatan kualitas input, proses dan output produk dan jasa pelayanan secara--}}
                                        {{--berkesinambungan.--}}
                                    {{--</li>--}}
                                {{--</ol>--}}

                                {{--<label class="m-t-20" style="font-size: 18px;">Terpercaya :</label>--}}
                                {{--<ol style="font-size: 16px;">--}}
                                    {{--<li>Memegang teguh etika bisnis.</li>--}}
                                    {{--<li>Konsisten memenuhi standar layanan yang dijanjikan.</li>--}}
                                    {{--<li>Menjadi perusahaan favorit para pihak yang berkepentingan.</li>--}}
                                {{--</ol>--}}

                                {{--<label class="m-t-20" style="font-size: 18px;">Potensi Insani :</label>--}}
                                {{--<ol style="font-size: 16px;">--}}
                                    {{--<li>Berorientasi pada pemenuhan standar etika dan kualitas.</li>--}}
                                    {{--<li>Kompeten, profesional dan berpengalaman.</li>--}}
                                {{--</ol>--}}
                            {{--</div>--}}

                            {{--<p class="card-text">--}}
                            <div id="progress_visi" class="m-t-30">
                                <div id="bar_visi"></div>
                            </div>
                            <div class="checkbox checkbox-primary m-t-30 " id="check_visi" style="display:none;">
                                <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                                <label for="checkbox_do">
                                    Saya sudah membaca dan memahami
                                </label>
                            </div>
                            {{--</p>--}}
                            {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                        </div>
                    </div>
                    @if($coc->misi=='1')
                    <div class="col-sm-6" style="display: none" id="box_dont">
                        <div class="card card-block">
                            {{--<h3 class="card-title"><span class="icon-dislike"></span> Don't</h3>--}}
                            {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-down"></span> Don't</h3>--}}
                            {{--<h3 class="card-title"><span class="fa fa-thumbs-o-down"></span> Don't</h3>--}}
                            <div class="row">
                                {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
                                {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
                                {{--</div>--}}
                                <div class="col-md-12 col-xs-12 center" align="center">
                                    <i class="icon-energy text-success"
                                       style="font-size: 60px; font-weight: bold; text-align: center;"></i>
                                </div>
                            </div>
                            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                            <hr>
                            <h1 align="center" class="text-success"
                                style="font-size: 30px; font-weight: bold; text-align: center; ">
                                MISI</h1>
                            <hr>

                            <ol style="font-size: 18px;">

                                {{--@foreach($pedoman->perilaku()->where('jenis',2)->get() as $perilaku)--}}
                                <li>
                                    {{--{!! $perilaku->perilaku !!}--}}
                                    Menjalankan bisnis kelistrikan dan bidang lain yang terkait, berorientasi pada
                                    kepuasan
                                    pelanggan, anggota perusahaan dan pemegang saham.
                                </li>
                                <li>Menjadikan tenaga listrik sebagai media untuk meningkatkan kualitas kehidupan
                                    masyarakat.
                                </li>
                                <li>Mengupayakan agar tenaga listrik menjadi pendorong kegiatan ekonomi.</li>
                                <li>Menjalankan kegiatan usaha yang berwawasan lingkungan.</li>
                                {{--@endforeach--}}
                            </ol>

                            {{--<p class="card-text">--}}

                            <div id="progress_misi" class="m-t-30">
                                <div id="bar_misi"></div>
                            </div>
                            <div class="checkbox checkbox-primary m-t-30" id="check_misi" style="display:none;">
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
            setTimeout(function(){ move_visi(); }, 500);
        });

        @if($coc->misi=='1')
        $('#checkbox_do').click(function () {
            if ($('#checkbox_do').is(':checked')) {
//                $('html, body').animate({scrollTop: 0}, 'fast');
                move_misi();
            }
            $('#box_dont').fadeToggle();
        });
        $('#checkbox_dont').click(function () {
            $('#btn_next').fadeToggle();
        });
        @else
       $('#checkbox_do').click(function () {
//            if ($('#checkbox_do').is(':checked')) {
                $('#btn_next').fadeToggle();
//            }
        });
        @endif

        function move_visi() {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById('bar_visi');
                var width = 1;
                var id = setInterval(frame, 50);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById("check_visi").style.display = 'block';
                        document.getElementById("bar_visi").style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }

        function move_misi() {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById('bar_misi');
                var width = 1;
                var id = setInterval(frame, 120);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById("check_misi").style.display = 'block';
                        document.getElementById("bar_misi").style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }
    </script>
@stop