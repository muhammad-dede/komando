@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Employee Volunteer Program</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xs-12">

                    <div class="card">
                        {{--<div class="card-block">--}}
                        {{--<h4 class="card-title">Filter</h4>--}}
                        {{--<h6 class="card-subtitle text-muted">Filter</h6>--}}
                        {{--</div>--}}

                        <div class="card-block">

                            @if(Auth::user()->can('evp_create'))
                                {{--<div class="button-list">--}}
                                {{--<a href="{{url('evp/create')}}"--}}
                                {{--class="btn btn-primary waves-effect waves-light"><i--}}
                                {{--class="fa fa-child"></i> Create Program</a>--}}
                                {{--</div>--}}

                                <div class="btn-group">

                                    <button type="button"
                                            class="btn btn-success dropdown-toggle waves-effect waves-light btn-block"
                                            data-toggle="dropdown" aria-expanded="true"><i class="fa fa-child"></i>
                                        Create Program<span class="m-l-5"></span></button>
                                    <div class="dropdown-menu">
                                        {{--                                        @if(Auth::user()->can('input_materi_pusat'))--}}
                                        <a class="dropdown-item" href="{{url('evp/create/1')}}">Nasional</a>
                                        {{--@endif--}}
                                        {{--                                        @if(Auth::user()->can('input_materi_gm'))--}}
                                        <a class="dropdown-item" href="{{url('evp/create/2')}}">Lokal</a>
                                        {{--@endif--}}
                                    </div>
                                </div>
                            @endif
                            <h4 class="card-title m-t-20">Filter</h4>

                            {{--<div class="form-group">--}}
                            {{--<label>Company Code</label>--}}
                            {{--<select name="wilayah" id="wilayah" class="form-control select2">--}}
                            {{--<option value="all">All</option>--}}
                            {{--@foreach($lokasi_list as $lokasi)--}}
                            {{--<option value="{{$lokasi}}">{{$lokasi}}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                            {{--<label>Business Area</label>--}}
                            {{--<select name="wilayah" id="wilayah" class="form-control select2">--}}
                            {{--<option value="all">All</option>--}}
                            {{--@foreach($lokasi_list as $lokasi)--}}
                            {{--<option value="{{$lokasi}}">{{$lokasi}}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {!! Form::open(['url'=>'evp/program', 'class'=>'form-control']) !!}
                            <div class="form-group">
                                <label>Lokasi</label>
                                <select name="lokasi" id="wilayah" class="form-control select2">
                                    <option value="all">All</option>
                                    @foreach($lokasi_list as $lokasi)
                                        <option value="{{$lokasi}}" {{(@$lokasi_selected==$lokasi)?'selected':''}}>{{$lokasi}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Kegiatan</label>
                                {!! Form::text('nama_kegiatan',@$nama_kegiatan,['class'=>'form-control', 'placeholder'=>'Nama Kegiatan', 'id'=>'nama_kegiatan']) !!}
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                    {{--<input name="my_unit" id="my_unit" type="checkbox" value="1">--}}
                                    {{--<label for="my_unit">--}}
                                        {{--Tampilkan program di unit saya saja--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <button type="submit" name="search" class="btn btn-primary" id="search"><i
                                            class="fa fa-search"></i>
                                    Search
                                </button>
                            </div>
                            {!! Form::close() !!}
                            <br>
                            <br>
                            {{--<p class="card-text">Some quick example text to build on the card title and make--}}
                            {{--up the bulk of the card's content.</p>--}}
                            {{--<a href="#" class="card-link">Card link</a>--}}
                            {{--<a href="#" class="card-link">Another link</a>--}}
                        </div>
                    </div>

                </div>
                <!-- end col -->

            </div>
        </div>
        <div class="col-md-8 col-xs-12" id="list_evp">
            @if($evp_list->count()==0)
                <div class="card">
                    <div class="card-block">
                        Maaf, pencarian program dengan Nama Kegiatan <b>'{{$nama_kegiatan}}'</b> tidak ditemukan.
                    </div>
                </div>
            @else
                @foreach($evp_list as $evp)
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-xs-12">

                            <div class="card">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-xs-10">
                                            @if($evp->jenis_evp_id=='1')
                                                <span class="label label-danger"><i
                                                            class="fa fa-globe"></i> Nasional</span>
                                            @else
                                                <span class="label label-success"><i
                                                            class="fa fa-globe"></i> Lokal</span>
                                            @endif
                                            <h4 class="card-title m-t-10"><a href="{{url('evp/detail/'.$evp->id)}}"
                                                                             style="color: black; text-decoration-color: black;">{{$evp->nama_kegiatan}}</a>
                                            </h4>
                                            <h6 class="card-subtitle text-muted">
                                                <i class="fa fa-calendar"></i>
                                                Pendaftaran: {{$evp->tgl_awal_registrasi->format('d M Y')}}
                                                - {{$evp->tgl_akhir_registrasi->format('d M Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <i class="fa fa-map-marker"></i> Lokasi: {{$evp->tempat}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                {{--<i class="fa fa-globe"></i> {{$evp->jenisEVP->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                                            </h6>
                                        </div>
                                        <div class="col-xs-2">
                                            @if(Auth::user()->can('evp_edit'))
                                                @if($evp->jenis_evp_id=='2' || ($evp->jenis_evp_id=='1' && (Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat'))))
                                                <div class="btn-group pull-right">
                                                    <button type="button"
                                                            class="btn btn-secondary dropdown-toggle waves-effect"
                                                            data-toggle="dropdown" aria-expanded="true"><i
                                                                class="fa fa-ellipsis-v"></i>
                                                        <span class="m-l-5"></span></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                           href="{{url('evp/edit/'.$evp->id)}}">Edit</a>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <a href="{{url('evp/detail/'.$evp->id)}}"
                                   style="color: black; text-decoration-color: black;">
                                    <img class="img-fluid" src="{{asset('assets/images/evp/'.$evp->foto)}}"
                                         alt="{{$evp->nama_kegiatan}}">
                                </a>

                                <div class="card-block">
                                    <p class="card-text">
                                        {!! $evp->deskripsi !!}
                                    </p>

                                    <p class="card-text"><b>Kriteria:</b></p>
                                    {!! $evp->kriteria_peserta !!}
                                    {{--<a href="{{url('evp/download/1')}}" class="btn btn-info"><i class="fa fa-download"></i> Dokumen</a>--}}
                                    <div class="btn-group">

                                        <button type="button"
                                                class="btn btn-primary dropdown-toggle waves-effect waves-light btn-block"
                                                data-toggle="dropdown" aria-expanded="true"><i
                                                    class="fa fa-download"></i>
                                            Download<span class="m-l-5"></span></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" target="_blank"
                                               href="{{asset('assets/doc/evp/'.$evp->dokumen)}}">Dokumen</a>
                                            <a class="dropdown-item" target="_blank"
                                               href="{{asset('assets/doc/evp/'.$evp->data_diri)}}">Form
                                                Data Diri</a>
                                            <a class="dropdown-item" target="_blank"
                                               href="{{asset('assets/doc/evp/'.$evp->surat_pernyataan)}}">Surat
                                                Pernyataan</a>
                                        </div>
                                    </div>
                                    {{--<a href="{{asset('assets/doc/evp/'.$evp->dokumen)}}" target="_blank"--}}
                                    {{--class="btn btn-primary"><i class="fa fa-download"></i> Dokumen</a>--}}
                                    {{--<a href="{{asset('assets/doc/evp/'.$evp->data_diri)}}" target="_blank"--}}
                                    {{--class="btn btn-primary"><i class="fa fa-download"></i> Form Data Diri</a>--}}
                                    {{--<a href="{{asset('assets/doc/evp/'.$evp->surat_pernyataan)}}" target="_blank"--}}
                                    {{--class="btn btn-primary"><i class="fa fa-download"></i> Surat Pernyataan</a>--}}
                                    <a href="{{url('evp/detail/'.$evp->id)}}" class="btn btn-info"><i
                                                class="fa fa-info-circle"></i> More Info</a>
                                    {{--<a href="{{url('evp/detail/'.$evp->id)}}" class="btn btn-info"><i--}}
                                    {{--class="fa fa-share-alt"></i> Share</a>--}}

                                    {{--@if(Auth::user()->checkVolunteer($evp->id))--}}
                                        <a href="{{url('evp/register/'.$evp->id)}}" class="btn btn-success"><i
                                                    class="fa fa-edit"></i> Registrasi</a>
                                    {{--@endif--}}
                                </div>
                            </div>

                        </div>
                        <!-- end col -->

                    </div>
                @endforeach
            @endif
            {{--<div class="row">--}}
            {{--<div class="col-sm-12 col-lg-12 col-xs-12">--}}

            {{--<div class="card">--}}
            {{--<div class="card-block">--}}
            {{--<h4 class="card-title">Terang untuk Saudaraku Papua - Off-Site</h4>--}}
            {{--<h6 class="card-subtitle text-muted">Periode: Agustus 2018 - September 2018, Lokasi: -</h6>--}}
            {{--</div>--}}
            {{--<img class="img-fluid" src="{{asset('assets/images/big/papua-03.png')}}" alt="papua">--}}
            {{--<div class="card-block">--}}
            {{--<p class="card-text">Program ini bertujuan membangun jiwa patriotik, pantang menyerah,--}}
            {{--meningkatkan moral dan kepuasan hidup, meningkatkan kepemimpinan, serta sebagai perwujudan--}}
            {{--Employee Volunteer Program (EVP). PLN mengajak para pegawai untuk menjadi Relawan PLN--}}
            {{--membangun Papua Terang, "Terang untuk Saudaraku Papua", saatnya sebagai pegawai PLN--}}
            {{--untuk membuktikan bahwa masih ada niat mulia untuk bekerja dengan memandang Tuhan sebagai--}}
            {{--stakeholder, masih ada jiwa gotong royong dan semangat kebersamaan untuk saling memberi dan--}}
            {{--memuliakan.--}}
            {{--</p>--}}
            {{--<p class="card-text"><b>Kriteria:</b></p>--}}
            {{--<ol type="1">--}}
            {{--<li>Mempunyai passion kepedulian terhadap lingkungan dan teman yang tinggi</li>--}}
            {{--<li>Memiliki Smartphone  dan mempunyai akun Instagram, Twitter dan Facebook</li>--}}
            {{--<li>Mampu berkomunikasi dengan baik dan bekerjasama dengan Tim</li>--}}
            {{--<li>Mampu mengadministrasikan kegiatan dengan baik</li>--}}
            {{--<li>Mendapatkan izin dari atasan</li>--}}
            {{--</ol>--}}
            {{--                            <a href="{{url('evp/download/2')}}" class="btn btn-info"><i class="fa fa-download"></i> Dokumen</a>--}}
            {{--<a href="{{asset('assets/doc/evp-papua.pdf')}}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Dokumen</a>--}}
            {{--<a href="{{asset('assets/doc/form_data_pribadi_dan_surat_pernyataan_kesehatan.docx')}}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Form Data Diri</a>--}}
            {{--<a href="{{asset('assets/doc/lampiran_evp.docx')}}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Surat Pernyataan</a>--}}
            {{--<a href="{{url('evp/register/2')}}" class="btn btn-success"><i class="fa fa-edit"></i> Registrasi Ulang</a>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--</div><!-- end col -->--}}

            {{--</div>--}}
        </div>
    </div>

@stop

@section('javascript')
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#wilayah").select2();

            {{--$("#wilayah").change(function () {--}}
            {{--$.ajax({--}}
            {{--type: 'GET',--}}
            {{--url: '{{url('ajax/get-evp/')}}' + '/' + $('#wilayah').val(),--}}
            {{--success: function (data) {--}}
            {{--//                        console.log(data);--}}
            {{--$("#list_evp").html(data);--}}
            {{--}--}}
            {{--});--}}
            {{--});--}}

            {{--$("#search").click(function () {--}}
            {{--$.ajax({--}}
            {{--type: 'GET',--}}
            {{--url: '{{url('ajax/get-evp/')}}' + '/' + $('#wilayah').val(),--}}
            {{--success: function (data) {--}}
            {{--//                        console.log(data);--}}
            {{--$("#list_evp").html(data);--}}
            {{--}--}}
            {{--});--}}
            {{--});--}}

        });

    </script>
@stop