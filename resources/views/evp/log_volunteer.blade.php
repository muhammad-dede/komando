@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Activity Log : {{$evp->nama_kegiatan}}</h4>
@stop

@section('content')
    {{--    {!! Form::open(['url'=>'evp/create/', 'files'=>true]) !!}--}}
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="relawan-tab" data-toggle="tab" href="#relawan"
                           role="tab" aria-controls="relawan" aria-expanded="true">Relawan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#home"
                           role="tab" aria-controls="home" aria-expanded="true">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tempattanggal-tab" data-toggle="tab" href="#tempattanggal"
                           role="tab" aria-controls="tempattanggal" aria-expanded="true">Tempat & Tanggal</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="unit-tab" data-toggle="tab" href="#unit"--}}
                           {{--role="tab" aria-controls="unit" aria-expanded="true">Unit Kerja</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen"--}}
                           {{--role="tab" aria-controls="dokumen" aria-expanded="true">Dokumen</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="vendor-tab" data-toggle="tab" href="#vendor"--}}
                           {{--role="tab" aria-controls="vendor" aria-expanded="true">Vendor</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="setting-tab" data-toggle="tab" href="#setting"--}}
                           {{--role="tab" aria-controls="setting" aria-expanded="true">Approval</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="workplan-tab" data-toggle="tab" href="#workplan"--}}
                           {{--role="tab" aria-controls="workplan" aria-expanded="true">Workplan</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery"--}}
                           {{--role="tab" aria-controls="gallery" aria-expanded="true">Gallery</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="testimoni-tab" data-toggle="tab" href="#testimoni"--}}
                           {{--role="tab" aria-controls="testimoni" aria-expanded="true">Testimoni</a>--}}
                    {{--</li>--}}

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in  p-20" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-5 col-xs-12">

                                {{--<div class="form-group {{($errors->has('tema'))?'has-danger':''}}">--}}
                                {{--<label for="tema_id" class="form-control-label">Tema</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}


                                <img class="img-fluid" src="{{asset('assets/images/evp/'.$evp->foto)}}"
                                     alt="{{$evp->nama_kegiatan}}">


                            </div>
                            <div class="col-md-7">
                                <div class="form-group {{($errors->has('nama_kegiatan'))?'has-danger':''}}">
                                    <h2>{{$evp->nama_kegiatan}}</h2>
                                    {{--<label for="judul_coc" class="form-control-label">Nama Kegiatan</label>--}}

                                    {{--<div>--}}
                                    {{--{!! Form::text('nama_kegiatan',null, ['class'=>'form-control form-control-danger', 'id'=>'judul', 'placeholder'=>'Nama Kegiatan']) !!}--}}
                                    {{--</div>--}}
                                </div>
                                <div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">
                                    <div style="padding-left: 25px;">
                                        {!! $evp->deskripsi !!}
                                    </div>
                                    {{--<label for="pernr_leader" class="form-control-label">Deskripsi</label>--}}

                                    {{--<div>--}}
                                    {{--{!! Form::textarea('deskripsi', null, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',--}}
                                    {{--'placeholder'=>'Masukkan Deskripsi Program', 'rows'=>'20']) !!}--}}
                                    {{--</div>--}}
                                </div>

                                <div class="form-group {{($errors->has('kriteria_peserta'))?'has-danger':''}} m-t-20">
                                    <label><i class="fa fa-child"></i> Kriteria Peserta:</label>

                                    <div style="padding-left: 25px;">
                                        {!! $evp->kriteria_peserta !!}
                                    </div>
                                    {{--<div>--}}
                                    {{--{!! Form::textarea('kriteria_peserta', null, ['class'=>'form-control form-control-danger', 'id'=>'kriteria_peserta',--}}
                                    {{--'placeholder'=>'Masukkan Kriteria Peserta', 'rows'=>'20']) !!}--}}
                                    {{--</div>--}}
                                </div>

                                <div class="form-group {{($errors->has('jenis_evp_id'))?'has-danger':''}} m-t-20">
                                    <label><i class="fa fa-globe"></i> Jenis EVP</label>

                                    <div style="padding-left: 25px;">
                                        @if($evp->jenis_evp_id=='1')
                                            <span class="label label-danger">Nasional</span>
                                        @else
                                            <span class="label label-success">Lokal</span>
                                        @endif
                                        {{--                                        {!! Form::select('jenis_waktu_evp_id',$jenis_waktu_list, ($evp->jenisEVP->id==1)?'1':'2', ['class'=>'select2 form-control form-control-danger', 'id'=>'jenis_waktu_evp_id', 'readonly']) !!}--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('jenis_waktu_evp_id'))?'has-danger':''}} m-t-20">
                                    <label><i class="fa fa-clock-o"></i> Jenis Waktu</label>

                                    <div style="padding-left: 25px;">
                                        {{$evp->jenisWaktuEVP->description}}
                                        {{--                                        {!! Form::select('jenis_waktu_evp_id',$jenis_waktu_list, ($evp->jenisEVP->id==1)?'1':'2', ['class'=>'select2 form-control form-control-danger', 'id'=>'jenis_waktu_evp_id', 'readonly']) !!}--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('kuota'))?'has-danger':''}} m-t-20">
                                    <label><i class="fa fa-group"></i> Kuota</label>

                                    <div style="padding-left: 25px;">
                                        {{$evp->kuota}} orang
                                        {{--                                        {!! Form::number('kuota',$evp->kuota, ['class'=>'form-control form-control-danger', 'id'=>'kuota', 'placeholder'=>'Kuota']) !!}--}}
                                    </div>
                                </div>

                                {{--<div class="form-group {{($errors->has('tempat'))?'has-danger':''}}">--}}
                                {{--<label for="tempat" class="form-control-label">Lokasi</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('tempat',$evp->tempat, ['class'=>'form-control form-control-danger', 'id'=>'tempat', 'placeholder'=>'Lokasi','readonly']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group" {{($errors->has('waktu_awal') || $errors->has('waktu_akhir'))?'has-danger':''}}>--}}
                                {{--<label>Periode</label>--}}

                                {{--<div>--}}
                                {{--{{$evp->waktu_awal->format('d M Y')}} - {{$evp->waktu_akhir->format('d M Y')}}--}}
                                {{--<div class="input-daterange input-group" id="date-range">--}}
                                {{--<input type="text" class="form-control" name="waktu_awal"--}}
                                {{--value="{{$evp->waktu_awal->format('d-m-Y')}}" disabled/>--}}
                                {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                {{--<input type="text" class="form-control" name="waktu_akhir"--}}
                                {{--value="{{$evp->waktu_akhir->format('d-m-Y')}}" disabled/>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group {{($errors->has('foto'))?'has-danger':''}}">--}}
                                {{--<label for="foto" class="form-control-label">Banner/Foto</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::file('foto', ['class'=>'form-control form-control-danger', 'id'=>'foto']) !!}--}}
                                {{--</div>--}}
                                {{--<small class="text-muted">Format file gambar (JPG,JPEG,PNG). Ukuran file maksimal 1MB.</small>--}}
                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('nama_vendor'))?'has-danger':''}}">--}}
                                {{--<label for="nama_vendor" class="form-control-label">Nama Vendor</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('nama_vendor',$evp->vendor, ['class'=>'form-control form-control-danger', 'id'=>'nama_vendor', 'placeholder'=>'Nama Vendor', 'readonly']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('email_vendor'))?'has-danger':''}}">--}}
                                {{--<label for="email_vendor" class="form-control-label">Email Vendor</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('email_vendor',$evp->email_vendor, ['class'=>'form-control form-control-danger', 'id'=>'email_vendor', 'placeholder'=>'Email Vendor', 'readonly']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="row">--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group {{($errors->has('reg_atasan') || $errors->has('reg_gm'))?'has-danger':''}}">--}}
                                {{--<label for="materi" class="form-control-label">Approval Registrasi</label>--}}

                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="reg_atasan" id="reg_atasan" type="checkbox" value="1"--}}
                                {{--{{($evp->reg_atasan=='1')?'checked':''}}  disabled>--}}
                                {{--<label for="reg_atasan">--}}
                                {{--Atasan Langsung--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="reg_gm" id="reg_gm" type="checkbox" value="1"--}}
                                {{--{{($evp->reg_gm=='1')?'checked':''}} disabled>--}}
                                {{--<label for="reg_gm">--}}
                                {{--General Manager--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group {{($errors->has('keg_atasan') || $errors->has('keg_vendor'))?'has-danger':''}}">--}}
                                {{--<label for="materi" class="form-control-label">Approval Kegiatan</label>--}}

                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="keg_atasan" id="keg_atasan" type="checkbox" value="1"--}}
                                {{--{{($evp->keg_atasan=='1')?'checked':''}} disabled>--}}
                                {{--<label for="keg_atasan">--}}
                                {{--Atasan Langsung--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="keg_vendor" id="keg_vendor" type="checkbox" value="1"--}}
                                {{--{{($evp->keg_vendor=='1')?'checked':''}} disabled>--}}
                                {{--<label for="keg_vendor">--}}
                                {{--Vendor--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}


                                {{--<div class="form-group {{($errors->has('briefing'))?'has-danger':''}}">--}}
                                {{--<label for="judul_coc" class="form-control-label">Briefing</label>--}}

                                {{--<div>--}}
                                {{--<input type="checkbox" name="briefing" data-plugin="switchery" data-color="#039cfd" value="1"  {{($evp->briefing=='1')?'checked':''}} disabled/>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('tanggal_coc'))?'has-danger':''}}">--}}
                                {{--<label for="coc_date"--}}
                                {{--class="form-control-label">Tanggal</label>--}}

                                {{--<div>--}}

                                {{--<div class="input-group">--}}
                                {{--<input type="text" class="form-control form-control-danger" placeholder="dd-mm-yyyy" id="coc_date"--}}
                                {{--name="tanggal_coc" value="{{(old('tanggal_coc'))?old('tanggal_coc'):date('d-m-Y')}}">--}}
                                {{--<span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>--}}
                                {{--</div>--}}

                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group {{($errors->has('jam'))?'has-danger':''}}">--}}
                                {{--<label for="jam"--}}
                                {{--class="form-control-label">Jam</label>--}}

                                {{--<div>--}}

                                {{--<div class="input-group clockpicker" data-placement="top" data-align="top"--}}
                                {{--data-autoclose="true">--}}
                                {{--<input type="text" class="form-control form-control-danger" placeholder="Masukkan Jam" id="jam" name="jam"--}}
                                {{--value="{{old('jam')}}">--}}
                                {{--<span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>--}}
                                {{--</div>--}}

                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group {{($errors->has('jml_peserta'))?'has-danger':''}}">--}}
                                {{--<label for="jml_peserta"--}}
                                {{--class="form-control-label">Perkiraan Jumlah Peserta</label>--}}

                                {{--<div>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('jml_peserta',null, ['class'=>'form-control form-control-danger', 'id'=>'jml_peserta', 'placeholder'=>'Jumlah Peserta']) !!}--}}
                                {{--</div>--}}

                                {{--</div>--}}
                                {{--</div>--}}

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-20" id="tempattanggal"
                         aria-labelledby="tempattanggal-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">

                                <div class="form-group {{($errors->has('tempat'))?'has-danger':''}}">
                                    <label><i class="fa fa-map-marker"></i> Lokasi EVP</label>

                                    <div style="padding-left: 25px;">
                                        {{$evp->tempat}}
                                        {{--                                        {!! Form::text('tempat',$evp->tempat, ['class'=>'form-control form-control-danger', 'id'=>'tempat', 'placeholder'=>'Lokasi']) !!}--}}
                                    </div>
                                </div>
                                <div class="form-group m-t-20 {{($errors->has('waktu_awal') || $errors->has('waktu_akhir'))?'has-danger':''}}">
                                    <label><i class="fa fa-calendar"></i> Tanggal Pelaksanaan Kegiatan</label>

                                    <div style="padding-left: 25px;">
                                        {{@$evp->waktu_awal->format('j F Y')}} - {{@$evp->waktu_akhir->format('j F Y')}}
                                        {{--<div class="input-daterange input-group" id="date-range">--}}
                                        {{--<input type="text" class="form-control" name="waktu_awal"--}}
                                        {{--value="{{@$evp->waktu_awal->format('d-m-Y')}}" readonly/>--}}
                                        {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                        {{--<input type="text" class="form-control" name="waktu_akhir"--}}
                                        {{--value="{{@$evp->waktu_akhir->format('d-m-Y')}}" readonly/>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                                <div class="form-group m-t-20 {{($errors->has('tgl_registrasi_awal') || $errors->has('tgl_registrasi_akhir'))?'has-danger':''}}">
                                    <label><i class="fa fa-calendar"></i> Tanggal Pendaftaran</label>

                                    <div style="padding-left: 25px;">
                                        {{@$evp->tgl_awal_registrasi->format('j F Y')}}
                                        - {{@$evp->tgl_akhir_registrasi->format('j F Y')}}
                                        {{--<div class="input-daterange input-group" id="date-range-reg">--}}
                                        {{--<input type="text" class="form-control" name="tgl_registrasi_awal"--}}
                                        {{--value="{{@$evp->tgl_awal_registrasi->format('d-m-Y')}}" readonly/>--}}
                                        {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                        {{--<input type="text" class="form-control" name="tgl_registrasi_akhir"--}}
                                        {{--value="{{@$evp->tgl_akhir_registrasi->format('d-m-Y')}}" readonly/>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>

                                <div class="form-group m-t-20">
                                    <label><i class="fa fa-calendar"></i> Tanggal Pengumuman</label>

                                    <div style="padding-left: 25px;">
                                        {{@$evp->tgl_pengumuman->format('j F Y')}}
                                        {{--<div class="input-group">--}}
                                        {{--<input type="text" class="form-control form-control-danger"--}}
                                        {{--placeholder="dd-mm-yyyy" id="tgl_pengumuman"--}}
                                        {{--name="tgl_pengumuman"--}}
                                        {{--value="{{@$evp->tgl_pengumuman->format('d-m-Y')}}" readonly>--}}
                                        {{--<span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                {{--<div class="form-group {{($errors->has('briefing'))?'has-danger':''}}">--}}
                                {{--<label for="briefing" class="form-control-label">Briefing</label>--}}

                                {{--<div>--}}
                                {{--<input type="checkbox" name="briefing"--}}
                                {{--{{($evp->briefing=='1')?'checked':''}} data-plugin="switchery"--}}
                                {{--id="briefing"--}}
                                {{--data-color="#039cfd" value="1"/>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                @if($evp->briefing=='1')
                                    <div id="tempat_tanggal_briefing"
                                         style="display: {{($evp->briefing=='1')?'block':'none'}};">
                                        <div class="form-group {{($errors->has('tempat_briefing'))?'has-danger':''}}">
                                            <label><i class="fa fa-map-marker"></i> Lokasi Briefing</label>

                                            <div style="padding-left: 25px;">
                                                {{$evp->tempat_briefing}}
                                                {{--                                            {!! Form::text('tempat_briefing',$evp->tempat_briefing, ['class'=>'form-control form-control-danger', 'id'=>'tempat_briefing', 'placeholder'=>'Lokasi']) !!}--}}
                                            </div>
                                        </div>

                                        <div class="form-group m-t-20">
                                            <label><i class="fa fa-calendar"></i> Tanggal dan Jam Briefing</label>

                                            <div style="padding-left: 25px;">
                                                {{@$evp->tgl_jam_briefing->format('j F Y, H:i')}}
                                                {{--<div class="input-group col-md-6">--}}
                                                {{----}}
                                                {{--<input type="text" class="form-control form-control-danger"--}}
                                                {{--placeholder="dd-mm-yyyy" id="tgl_briefing"--}}
                                                {{--name="tgl_briefing"--}}
                                                {{--value="{{@$evp->tgl_jam_briefing->format('d-m-Y')}}" readonly>--}}
                                                {{--<span class="input-group-addon bg-custom b-0"><i--}}
                                                {{--class="icon-calender"></i></span>--}}
                                                {{--</div>--}}


                                                {{--<div class="input-group clockpicker col-md-6" data-placement="top"--}}
                                                {{--data-align="top"--}}
                                                {{--data-autoclose="true">--}}
                                                {{--<input type="text" class="form-control form-control-danger"--}}
                                                {{--placeholder="Masukkan Jam" id="jam_briefing" name="jam_briefing"--}}
                                                {{--value="{{@$evp->tgl_jam_briefing->format('H:i')}}" readonly>--}}
                                                {{--<span class="input-group-addon"> <span--}}
                                                {{--class="zmdi zmdi-time"></span> </span>--}}
                                                {{--</div>--}}


                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade in p-20" id="unit" role="tabpanel"
                         aria-labelledby="unit-tab">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <label><i class="fa fa-bank"></i> Company Code</label>

                                    <div style="overflow: auto; height: 300px;">
                                        <ul>
                                            @foreach($evp->companyCode()->where('status','ACTV')->orderBy('id', 'asc')->get() as $cc)
                                                <li>{{$cc->company_code}} - {{$cc->description}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    {{--<select name="company_code[]" class="multi-select" multiple=""--}}
                                    {{--id="my_multi_select4">--}}
                                    {{--@foreach($cc_list as $cc)--}}
                                    {{--<option value="{{$cc->id}}" {{($evp->companyCode()->where('id', $cc->id)->first()!=null)?'selected':''}}>{{$cc->company_code}}--}}
                                    {{--- {{$cc->description}}</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}

                                </div>

                                <div class="col-md-6">
                                    <label><i class="fa fa-bank"></i> Business Area</label>

                                    <div style="overflow: auto; height: 300px;">
                                        <ul>
                                            @foreach($evp->businessArea()->where('status','ACTV')->orderBy('id', 'asc')->get() as $ba)
                                                <li>{{$ba->business_area}} - {{$ba->description}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    {{--<select name="business_area[]" class="multi-select" multiple=""--}}
                                    {{--id="my_multi_select3">--}}
                                    {{--@foreach($ba_list as $ba)--}}
                                    {{--<option value="{{$ba->id}}" {{($evp->businessArea()->where('id', $ba->id)->first()!=null)?'selected':''}}>{{$ba->business_area}}--}}
                                    {{--- {{$ba->description}}</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}

                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="relawan" aria-labelledby="relawan-tab">
                        {{--<div class="row">--}}
                            <?php
                            //$jml_peserta = $evp->kuota;
                            ?>

                            {{--<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">--}}
                                {{--<div class="card-box tilebox-two">--}}
                                    {{--<i class="icon-people  pull-xs-right text-muted"></i>--}}
                                    {{--<h6 class="text-muted text-uppercase m-b-15">Jumlah Relawan</h6>--}}
                                    <?php
                                    //$jml_submit = $volunteer_list->count();
                                    ?>
                                    {{--<h2 class="m-b-20"><span data-plugin="counterup">{{$jml_submit}}--}}
                                            {{--/{{$jml_peserta}}--}}
                                            {{--<small>--}}
                                                {{--({{($jml_peserta!=0)?number_format(($jml_submit/$jml_peserta)*100, 2):'0'}}--}}
                                                {{--%)--}}
                                            {{--</small></span>--}}
                                    {{--</h2>--}}
                                    {{--<progress class="progress progress-striped progress-xs progress-info m-b-0"--}}
                                              {{--value="{{($jml_peserta!=0)?(($jml_submit/$jml_peserta)*100):'0'}}"--}}
                                              {{--max="100">--}}
                                    {{--</progress>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        {{--</div>--}}
                        <div class="col-md-12 table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Relawan</th>
                                    <th>Company Code</th>
                                    <th>Business Area</th>
                                    <th>Jumlah Log</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //$x=1;
                                ?>
                                @foreach($volunteer_list as $volunteer)
                                    <tr>
                                        <td scope="row">
                                            {{--{{$x++}}--}}
                                            @if($volunteer->user->foto!='')
                                                <img src="{{asset('assets/images/users/foto-thumb/'.$volunteer->user->foto)}}"
                                                     alt="user" class="img-fluid img-thumbnail" width="64">
                                            @else
                                                <img src="{{asset('assets/images/user.jpg')}}" alt="user"
                                                     class="img-fluid img-thumbnail" width="64">
                                            @endif
                                        </td>
                                        <td>
                                            {{$volunteer->user->strukturJabatan->cname}}
                                            <br>
                                            <small>{{$volunteer->user->strukturJabatan->nip}}</small>
                                            <br>
                                            <small>{{$volunteer->user->strukturPosisi()->stext}}
                                                , {{$volunteer->user->strukturPosisi()->stxt2}}</small>
                                        </td>
                                        <td>
                                            {{@$volunteer->user->company_code}}
                                            - {{@$volunteer->user->companyCode->description}}
                                        </td>
                                        <td>
                                            {{@$volunteer->user->business_area}}
                                            - {{@$volunteer->user->businessArea->description}}
                                        </td>
                                        <td align="right">
                                            {{$volunteer->activityLog->count()}}
                                        </td>
                                        <td>
                                            <a href="{{url('evp/log/list/'.$volunteer->id)}}"
                                               class="btn btn-pink btn-xs waves-effect waves-light"
                                               title="Activity Log">
                                                <i class="fa fa-clock-o"></i>
                                            </a>

                                            {{--@if($volunteer->status!='REJ-AT' || $volunteer->status!='REJ-AT' || $volunteer->status!='REJ-PST')--}}
                                            {{--@if($volunteer->approval_atasan==null && Auth::user()->isStruktural() && $volunteer->status=='REG')--}}
                                                {{--<a href="javascript:"--}}
                                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                                {{--title="Approve"--}}
                                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                                                {{--<i class="fa fa-check"></i>--}}
                                                {{--</a>--}}
                                                {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                                                   {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                                                   {{--title="Reject"--}}
                                                   {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('1');">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                {{--</a>--}}
                                            {{--@elseif($volunteer->approval_admin==null && Auth::user()->can('evp_approve') && $volunteer->status=='APRV-AT')--}}
                                                {{--<a href="javascript:"--}}
                                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                                {{--title="Approve"--}}
                                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai tersebut untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                                                {{--<i class="fa fa-check"></i>--}}
                                                {{--</a>--}}
                                                {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                                                   {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                                                   {{--title="Reject"--}}
                                                   {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('4');">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                {{--</a>--}}
                                            {{--@elseif($volunteer->approval_gm==null && $volunteer->evp->reg_gm=='1' && Auth::user()->isGM() && $volunteer->status=='APRV-AT')--}}
                                                {{--<a href="javascript:"--}}
                                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                                {{--title="Approve"--}}
                                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                                                {{--<i class="fa fa-check"></i>--}}
                                                {{--</a>--}}
                                                {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                                                   {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                                                   {{--title="Reject"--}}
                                                   {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('2');">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                {{--</a>--}}
                                            {{--@elseif($volunteer->approval_pusat==null && Auth::user()->can('evp_approve') && $volunteer->status=='APRV-GM')--}}
                                                {{--<a href="javascript:"--}}
                                                {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                                {{--title="Approve"--}}
                                                {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai tersebut untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                                                {{--<i class="fa fa-check"></i>--}}
                                                {{--</a>--}}
                                                {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                                                   {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                                                   {{--title="Reject"--}}
                                                   {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('3');">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                {{--</a>--}}
                                            {{--@else--}}
                                            {{--@endif--}}
                                            {{--@endif--}}

                                            {{--<a href="javascript:"--}}
                                            {{--class="btn btn-success btn-xs waves-effect waves-light"--}}
                                            {{--title="Approve"--}}
                                            {{--@if($volunteer->approval_atasan==null && Auth::user()->isStruktural())--}}
                                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                                            {{--@elseif($volunteer->approval_gm==null && Auth::user()->isGM())--}}
                                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                                            {{--@elseif($volunteer->approval_pusat==null && Auth::user()->can('evp_approve'))--}}
                                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                                            {{--@else--}}
                                            {{--onclick="javascript:">--}}
                                            {{--@endif--}}
                                            {{--<i class="fa fa-check"></i>--}}
                                            {{--</a>--}}
                                            {{--<a href="javascript:"--}}
                                            {{--class="btn btn-danger btn-xs waves-effect waves-light"--}}
                                            {{--title="Reject"--}}
                                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menolak pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/reject/'.$volunteer->id)}}'}">--}}
                                            {{--<i class="fa fa-times"></i>--}}
                                            {{--</a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        {{--</div>--}}
                        {{--</div>--}}

                    </div>



                </div>


                <div class="row m-t-20">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{url('evp/log')}}';"><i
                                        class="fa fa-times"></i> Close
                            </button>
                            {{--@if(Auth::user()->hasRole('admin_evp') && $evp->jenis_evp_id=='1')--}}
                                {{--<button type="button" class="btn btn-success btn-lg pull-right"--}}
                                        {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin mengajukan daftar relawan yang sudah disetujui ke GM? Jika Ya, sistem akan mengirimkan notifikasi ke GM.')){window.location.href='{{url('evp/approval/send-to-gm/'.$evp->id)}}'}"><i--}}
                                            {{--class="fa fa-send"></i> Send for Approval--}}
                                {{--</button>--}}
                            {{--@endif--}}
                            {{--@elseif(Auth::user()->isGM()&& $evp->jenis_evp_id=='1' && $volunteer_list->count()!=0)--}}
                                {{--<button type="button" class="btn btn-success btn-lg pull-right"--}}
                                        {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui semua relawan untuk mengikuti program EVP?')){window.location.href='{{url('evp/approval/approve-all/'.$evp->id)}}'}"><i--}}
                                            {{--class="fa fa-check"></i> Approve All--}}
                                {{--</button>--}}
                            {{--@endif--}}
                            {{--@if(Auth::user()->checkVolunteer($evp->id))--}}
                                {{--<button type="button" class="btn btn-success btn-lg pull-right"--}}
                                        {{--onclick="window.location.href='{{url('evp/register/'.$evp->id)}}';"><i--}}
                                            {{--class="fa fa-edit"></i> Register Now!--}}
                                {{--</button>--}}
                            {{--@endif--}}
                            {{--<button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>--}}
                            {{--Save--}}
                            {{--</button>--}}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    {!! Form::close() !!}--}}
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {

//            $("#do_dont").select2();
//            $("#jenis_coc_id").select2();
            $('#datatable').DataTable({});
//            $("#company_code").select2();
//            $("#business_area").select2();
//            $("#orgeh").select2();
//            $("#materi_id").select2();
//            $("#tema_id").select2();
//            jQuery('#coc_date').datepicker({
//                autoclose: true,
//                todayHighlight: true,
//                format: 'dd-mm-yyyy'
//            });
//            $('.clockpicker').clockpicker({
//                donetext: 'Done'
//            });
//            jQuery('#date-range').datepicker({
//                autoclose: true,
//                toggleActive: true,
//                todayHighlight: true,
//                format: 'dd-mm-yyyy'
//            });

            tinymce.init({
                selector: '#deskripsi', height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });

            tinymce.init({
                selector: '#kriteria_peserta', height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });

        });


    </script>
@stop