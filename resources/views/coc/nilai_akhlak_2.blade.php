@extends('layout')

@section('css')
    <style>
        #progress_amanah, #progress_kompeten, #progress_harmonis, #progress_loyal, #progress_adaptif, #progress_kolaboratif {
            width: 100%;
            background-color: #ddd;
        }

        #bar_amanah, #bar_kompeten, #bar_harmonis, #bar_loyal, #bar_adaptif, #bar_kolaboratif {
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
    <progress class="progress progress-info progress-xs m-t-10" value="48" max="100">48%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                
                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title">Values (Nilai) &nbsp;&nbsp;<span><img src="{{asset('assets/images/akhlak2.png')}}" width="150" class="img-responsive"></span></h4>
                    </div>
                </div>

                <div class="row m-t-10">
                    @if($coc->akhlak_amanah=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_amanah.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Memenuhi janji dan komitmen</li>
                                    <li>Bertanggung jawab atas tugas, keputusan dan tindakan yang dilakukan</li>
                                    <li>Berpegang teguh kepada nilai moral dan etika</li>
                                </ul>

                                <div id="progress_amanah" class="m-t-30">
                                    <div id="bar_amanah"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_amanah" style="display: none;">
                                    <input id="checkbox_amanah" name="checkbox_amanah" type="checkbox" value="1">
                                    <label for="checkbox_amanah">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->akhlak_kompeten=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->akhlak_amanah=='1')
                             style="display: none"
                             @endif
                             id="box_kompeten">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_kompeten.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>

                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah</li>
                                    <li>Membantu orang lain belajar</li>
                                    <li>Menyelesaikan tugas dengan kualitas terbaik</li>    
                                </ul>

                                <div id="progress_kompeten" class="m-t-30">
                                    <div id="bar_kompeten"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_kompeten" style="display: none;">
                                    <input id="checkbox_kompeten" name="checkbox_kompeten" type="checkbox" value="1">
                                    <label for="checkbox_kompeten">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->akhlak_harmonis=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->akhlak_amanah=='1' || $coc->akhlak_kompeten=='1')
                             style="display: none"
                             @endif
                             id="box_harmonis">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_harmonis.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Menghargai setiap orang apapun latar belakangnya</li>
                                    <li>Suka menolong orang lain</li>
                                    <li>Membangun lingkungan kerja yang kondusif</li>
                                </ul>

                                <div id="progress_harmonis" class="m-t-30">
                                    <div id="bar_harmonis"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_harmonis" style="display: none;">
                                    <input id="checkbox_harmonis" name="checkbox_harmonis" type="checkbox" value="1">
                                    <label for="checkbox_harmonis">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->akhlak_loyal=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->akhlak_amanah=='1' || $coc->akhlak_kompeten=='1' || $coc->akhlak_harmonis=='1')
                             style="display: none"
                             @endif
                             id="box_loyal">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_loyal.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Menjaga nama baik sesama karyawan, Pimpinan, BUMN dan Negara</li>
                                    <li>Rela berkorban untuk mencapai tujuan yang lebih besar</li>
                                    <li>Patuh kepada Pimpinan sepanjang tidak bertentangan dengan hukum dan etika</li>
                                </ul>

                                <div id="progress_loyal" class="m-t-30">
                                    <div id="bar_loyal"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_loyal" style="display: none;">
                                    <input id="checkbox_loyal" name="checkbox_loyal" type="checkbox" value="1">
                                    <label for="checkbox_loyal">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($coc->akhlak_adaptif=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->akhlak_amanah=='1' || $coc->akhlak_kompeten=='1' || $coc->akhlak_harmonis=='1' || $coc->akhlak_loyal=='1')
                             style="display: none"
                             @endif
                             id="box_adaptif">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_adaptif.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Cepat menyesuaikan diri untuk menjadi lebih baik</li>
                                    <li>Terus-menerus melakukan perbaikan mengikuti perkembangan teknologi</li>
                                    <li>Bertindak proaktif</li>
                                </ul>

                                <div id="progress_adaptif" class="m-t-30">
                                    <div id="bar_adaptif"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_adaptif" style="display: none;">
                                    <input id="checkbox_adaptif" name="checkbox_adaptif" type="checkbox" value="1">
                                    <label for="checkbox_adaptif">
                                        Saya sudah membaca dan memahami
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($coc->akhlak_kolaboratif=='1')
                        <div class="col-sm-4 col-xs-12 m-t-10"
                             @if($coc->akhlak_amanah=='1' || $coc->akhlak_kompeten=='1' || $coc->akhlak_harmonis=='1' || $coc->akhlak_loyal=='1' || $coc->akhlak_adaptif=='1')
                             style="display: none"
                             @endif
                             id="box_kolaboratif">
                            <div class="card card-block">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 center" align="center">
                                        <img src="{{asset('assets/images/desc_kolaboratif.png')}}" class="img-fluid" style="width: 350px;">
                                    </div>
                                </div>
                                <hr>
                                <p style="font-size: 16px;">
                                    Panduan Perilaku :
                                </p>
                                <ul style="font-size: 16px;">
                                    <li>Memberi kesempatan kepada berbagai pihak untuk berkontribusi </li>
                                    <li>Terbuka untuk bekerja sama untuk menghasilkan nilai tambah </li>
                                    <li>Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama</li>
                                </ul>

                                <div id="progress_kolaboratif" class="m-t-30">
                                    <div id="bar_kolaboratif"></div>
                                </div>
                                <div class="checkbox checkbox-primary m-t-30" id="check_kolaboratif" style="display: none;">
                                    <input id="checkbox_kolaboratif" name="checkbox_kolaboratif" type="checkbox" value="1">
                                    <label for="checkbox_kolaboratif">
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

            @if($coc->akhlak_amanah=='1')
                setTimeout(function(){ move('bar_amanah', 'check_amanah'); }, 500);
            @elseif($coc->akhlak_amanah!='1' && $coc->akhlak_kompeten=='1')
                setTimeout(function(){ move('bar_kompeten', 'check_kompeten', 70); }, 500);
            @elseif($coc->akhlak_amanah!='1' && $coc->akhlak_kompeten!='1' && $coc->akhlak_harmonis=='1')
                setTimeout(function(){ move('bar_harmonis', 'check_harmonis', 70); }, 500);
            @elseif($coc->akhlak_amanah!='1' && $coc->akhlak_kompeten!='1' && $coc->akhlak_harmonis!='1' && $coc->akhlak_loyal=='1')
                setTimeout(function(){ move('bar_loyal', 'check_loyal', 70); }, 500);
            @elseif($coc->akhlak_amanah!='1' && $coc->akhlak_kompeten!='1' && $coc->akhlak_harmonis!='1' && $coc->akhlak_loyal!='1' && $coc->akhlak_adaptif=='1')
                setTimeout(function(){ move('bar_adaptif', 'check_adaptif', 70); }, 500);
            @elseif($coc->akhlak_amanah!='1' && $coc->akhlak_kompeten!='1' && $coc->akhlak_harmonis!='1' && $coc->akhlak_loyal!='1' && $coc->akhlak_adaptif!='1' && $coc->akhlak_kolaboratif=='1')
                setTimeout(function(){ move('bar_kolaboratif', 'check_kolaboratif', 70); }, 500);
            @endif

        });

//----------------------------------------------------------------------


        $('#checkbox_amanah').click(function () {
            @if($coc->akhlak_kompeten=='1')
                move('bar_kompeten', 'check_kompeten', 70);
                $('#box_kompeten').fadeToggle();
            @else
                @if($coc->akhlak_harmonis=='1')
                    move('bar_harmonis', 'check_harmonis', 70);
                    $('#box_harmonis').fadeToggle();
                @else
                    @if($coc->akhlak_loyal=='1')
                        move('bar_loyal', 'check_loyal', 70);
                        $('#box_loyal').fadeToggle();
                    @else
                        @if($coc->akhlak_adaptif=='1')
                            move('bar_adaptif', 'check_adaptif', 70);
                            $('#box_adaptif').fadeToggle();
                        @else
                            @if($coc->akhlak_kolaboratif=='1')
                                move('bar_kolaboratif', 'check_kolaboratif', 70);
                                $('#box_kolaboratif').fadeToggle();
                            @else
                                $('#btn_next').fadeToggle();
                            @endif
                        @endif
                    @endif
                @endif
            @endif    
        });

        $('#checkbox_kompeten').click(function () {
            @if($coc->akhlak_harmonis=='1')
                move('bar_harmonis', 'check_harmonis', 70);
                $('#box_harmonis').fadeToggle();
            @else
                @if($coc->akhlak_loyal=='1')
                    move('bar_loyal', 'check_loyal', 70);
                    $('#box_loyal').fadeToggle();
                @else
                    @if($coc->akhlak_adaptif=='1')
                        move('bar_adaptif', 'check_adaptif', 70);
                        $('#box_adaptif').fadeToggle();
                    @else
                        @if($coc->akhlak_kolaboratif=='1')
                            move('bar_kolaboratif', 'check_kolaboratif', 70);
                            $('#box_kolaboratif').fadeToggle();
                        @else
                            $('#btn_next').fadeToggle();
                        @endif
                    @endif
                @endif
            @endif
        });

        $('#checkbox_harmonis').click(function () {
            @if($coc->akhlak_loyal=='1')
                move('bar_loyal', 'check_loyal', 70);
                $('#box_loyal').fadeToggle();
            @else
                @if($coc->akhlak_adaptif=='1')
                    move('bar_adaptif', 'check_adaptif', 70);
                    $('#box_adaptif').fadeToggle();
                @else
                    @if($coc->akhlak_kolaboratif=='1')
                        move('bar_kolaboratif', 'check_kolaboratif', 70);
                        $('#box_kolaboratif').fadeToggle();
                    @else
                        $('#btn_next').fadeToggle();
                    @endif
                @endif
            @endif
        });

        $('#checkbox_loyal').click(function () {
            @if($coc->akhlak_adaptif=='1')
                move('bar_adaptif', 'check_adaptif', 70);
                $('#box_adaptif').fadeToggle();
            @else
                @if($coc->akhlak_kolaboratif=='1')
                    move('bar_kolaboratif', 'check_kolaboratif', 70);
                    $('#box_kolaboratif').fadeToggle();
                @else
                    $('#btn_next').fadeToggle();
                @endif
            @endif
        });

        $('#checkbox_adaptif').click(function () {
            @if($coc->akhlak_kolaboratif=='1')
                move('bar_kolaboratif', 'check_kolaboratif', 70);
                $('#box_kolaboratif').fadeToggle();
            @else
                $('#btn_next').fadeToggle();
            @endif
        });

        $('#checkbox_kolaboratif').click(function () {
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