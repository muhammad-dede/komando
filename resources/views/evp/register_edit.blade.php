@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Edit Registrasi</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'evp/volunteer/'.$volunteer->id.'/edit', 'files'=>true]) !!}
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
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                           role="tab" aria-controls="home" aria-expanded="true">Registrasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen"
                           role="tab" aria-controls="dokumen" aria-expanded="true">Dokumen Persyaratan</a>
                    </li>
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" id="status-tab" data-toggle="tab" href="#status"--}}
                    {{--role="tab" aria-controls="status" aria-expanded="true">Status Log</a>--}}
                    {{--</li>--}}

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="home"
                         aria-labelledby="home-tab">
                        <div class="row m-t-20">
                            <div class="col-md-7 col-xs-12">

                                {{--<div class="form-group {{($errors->has('tema'))?'has-danger':''}}">--}}
                                {{--<label for="tema_id" class="form-control-label">Tema</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('nama_kegiatan'))?'has-danger':''}} m-t-10">
                                    <h4 class="card-title">{{$evp->nama_kegiatan}} &nbsp;<a
                                                href="{{url('evp/detail/'.$evp->id)}}" class="primary"
                                                style="font-size: 14px;" title="Click here for detail program"
                                                target="_blank"><i class="fa fa-external-link"></i></a></h4>

                                    <h6 class="card-subtitle text-muted">
                                        <i class="fa fa-calendar"></i> {{$evp->waktu_awal->format('d M Y')}}
                                        - {{$evp->waktu_akhir->format('d M Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-globe"></i> {{$evp->jenisEVP->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-map-marker"></i> Lokasi: {{$evp->tempat}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </h6>
                                    {{--<label for="judul_coc" class="form-control-label">Nama Kegiatan</label>--}}

                                    {{--<div>--}}
                                    {{--{!! Form::hidden('evp_id', $evp->id) !!}--}}
                                    {{--{!! Form::text('nama_kegiatan',$evp->nama_kegiatan, ['class'=>'form-control form-control-danger', 'id'=>'judul', 'placeholder'=>'Nama Kegiatan', 'readonly']) !!}--}}
                                    {{--</div>--}}
                                </div>

                                <div class="form-group {{($errors->has('answer_tertarik'))?'has-danger':''}}" style="margin-top: 20px;">
                                    <h5 for="answer_tertarik" class="form-control-label"><i class="fa fa-comments-o" style="font-size: 25px;"></i> Jelaskan mengapa Anda tertarik untuk
                                        menjadi relawan pada kegiatan ini? <span class="text-danger">*</span></h5>

                                    <div style="padding: 10px 10px 10px 10px;">
                                {{--<div class="form-group {{($errors->has('answer_tertarik'))?'has-danger':''}}"--}}
                                     {{--style="margin-top: 50px;">--}}
                                    {{--<h5 for="answer_tertarik" class="form-control-label">{{$volunteer->nama}} tertarik--}}
                                        {{--untuk--}}
                                        {{--menjadi relawan pada kegiatan ini karena:</h5>--}}

                                    {{--<div class="p-20">--}}
                                        {{--{!! $volunteer->answer_tertarik !!}--}}
                                        {!! Form::textarea('answer_tertarik', $volunteer->answer_tertarik, ['class'=>'form-control form-control-danger', 'id'=>'answer_tertarik',
                                        'placeholder'=>'Masukkan jawaban Anda', 'rows'=>'10']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('answer_tepat'))?'has-danger':''}}" style="margin-top: 20px;">
                                    <h5 for="answer_tepat" class="form-control-label"><i class="fa fa-comments-o" style="font-size: 25px;"></i> Jelaskan mengapa Anda adalah relawan
                                        yang tepat untuk kegiatan ini? <span class="text-danger">*</span></h5>

                                    <div style="padding: 10px 10px 10px 10px;">
                                {{--<div class="form-group {{($errors->has('kriteria_peserta'))?'has-danger':''}}">--}}
                                    {{--<h5 for="answer_tepat" class="form-control-label">Menurut {{$volunteer->nama}}, yang--}}
                                        {{--bersangkutan adalah relawan--}}
                                        {{--yang tepat untuk kegiatan ini karena:</h5>--}}

                                    {{--<div class="p-20">--}}
                                        {{--                                        {!! $volunteer->answer_tepat !!}--}}
                                        {!! Form::textarea('answer_tepat', $volunteer->answer_tepat, ['class'=>'form-control form-control-danger', 'id'=>'answer_tepat',
                                        'placeholder'=>'Masukkan jawaban Anda', 'rows'=>'10']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5 hidden-md-down">
                                <div class="form-group" align="center">
                                    @if($volunteer->user->foto!='')
                                        <img class="img-fluid img-thumbnail"
                                             src="{{asset('assets/images/users/foto/'.$volunteer->user->foto)}}"
                                             alt="user" width="200">
                                    @else
                                        <img class="img-fluid img-thumbnail" src="{{asset('assets/images/user.jpg')}}"
                                             alt="user" width="200">
                                    @endif
                                    <h3 class="m-t-10">{{$volunteer->nama}}</h3>
                                </div>
                                <div class="form-group">
                                    <label for="nama" class="form-control-label">NIP</label>

                                    <div>
                                        {!! Form::text('nama',$volunteer->nip, ['class'=>'form-control form-control-danger', 'id'=>'nama', 'placeholder'=>'Nama', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jabatan" class="form-control-label">Jabatan</label>

                                    <div>
                                        {!! Form::text('jabatan',$volunteer->jabatan, ['class'=>'form-control form-control-danger', 'id'=>'jabatan', 'placeholder'=>'Jabatan', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="bidang" class="form-control-label">Bidang</label>

                                    <div>
                                        {!! Form::text('bidang',$volunteer->bidang, ['class'=>'form-control form-control-danger', 'id'=>'bidang', 'placeholder'=>'Bidang', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="business_area" class="form-control-label">Business Area</label>

                                    <div>
                                        {!! Form::text('business_area',$volunteer->business_area.' - '.Auth::user()->businessArea->description, ['class'=>'form-control form-control-danger', 'id'=>'business_area', 'placeholder'=>'Business Area', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="company_code" class="form-control-label">Company Code</label>

                                    <div>
                                        {!! Form::text('company_code',$volunteer->company_code.' - '.Auth::user()->companyCode->description, ['class'=>'form-control form-control-danger', 'id'=>'company_code', 'placeholder'=>'Company Code', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group m-t-20 ">
                                    <label for="atasan" class="form-control-label">Atasan Langsung / Jabatan
                                        (NIP)</label>

                                    <div>
                                        {!! Form::hidden('pernr_atasan', $pernr_atasan) !!}
                                        {!! Form::text('atasan',$atasan->cname.' / '.$atasan->strukturPosisi->stext.' ('.$atasan->nip.')', ['class'=>'form-control form-control-danger', 'id'=>'atasan', 'placeholder'=>'Atasan Langsung', 'readonly']) !!}
                                    </div>
                                </div>
                                @if($evp->reg_gm=='1')
                                <div class="form-group ">
                                    <label for="gm" class="form-control-label">General Manager (NIP)</label>

                                    <div>
                                        {!! Form::hidden('pernr_gm', $gm->pernr) !!}
                                        {!! Form::text('gm',$gm->cname.' ('.$gm->nip.')', ['class'=>'form-control form-control-danger', 'id'=>'gm', 'placeholder'=>'General Manager', 'readonly']) !!}
                                    </div>
                                </div>
                                @endif
                                {{--<div class="form-group" {{($errors->has('waktu_awal') || $errors->has('waktu_akhir'))?'has-danger':''}}>--}}
                                {{--<label>Tanggal</label>--}}

                                {{--<div>--}}
                                {{--<div class="input-daterange input-group" id="date-range">--}}
                                {{--<input type="text" class="form-control" name="waktu_awal"/>--}}
                                {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                {{--<input type="text" class="form-control" name="waktu_akhir"/>--}}
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
                                {{--{!! Form::text('nama_vendor',null, ['class'=>'form-control form-control-danger', 'id'=>'nama_vendor', 'placeholder'=>'Nama Vendor']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('email_vendor'))?'has-danger':''}}">--}}
                                {{--<label for="email_vendor" class="form-control-label">Email Vendor</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('email_vendor',null, ['class'=>'form-control form-control-danger', 'id'=>'email_vendor', 'placeholder'=>'Email Vendor']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="row">--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group {{($errors->has('reg_atasan') || $errors->has('reg_gm'))?'has-danger':''}}">--}}
                                {{--<label for="materi" class="form-control-label">Approval Registrasi</label>--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="reg_atasan" id="reg_atasan" type="checkbox" value="1" checked>--}}
                                {{--<label for="reg_atasan">--}}
                                {{--Atasan Langsung--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="reg_gm" id="reg_gm" type="checkbox" value="1">--}}
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
                                {{--<input name="keg_atasan" id="keg_atasan" type="checkbox" value="1" checked>--}}
                                {{--<label for="keg_atasan">--}}
                                {{--Atasan Langsung--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--<div class="checkbox checkbox-primary">--}}
                                {{--<input name="keg_vendor" id="keg_vendor" type="checkbox" value="1">--}}
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
                                {{--<input type="checkbox" name="briefing" checked data-plugin="switchery" data-color="#039cfd" value="1"/>--}}
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
                    <div role="tabpanel" class="tab-pane fade in" id="dokumen" aria-labelledby="dokumen-tab">
                        <div class="row">
                            <div class="col-md-12">

                                {{--<div class="form-group {{($errors->has('dokumen'))?'has-danger':''}}">--}}
                                {{--<label for="dokumen" class="form-control-label">Surat/Dokumen</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::file('dokumen', ['class'=>'form-control form-control-danger', 'id'=>'dokumen']) !!}--}}
                                {{--</div>--}}
                                {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('file_cv'))?'has-danger':''}}">
                                    <label for="file_cv" class="form-control-label"><i class="fa fa-file-text-o"></i> Form Data Diri <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_cv', ['class'=>'form-control form-control-danger', 'id'=>'file_cv']) !!}
                                        @if($volunteer->file_cv!=null)
                                            <a href="{{asset('assets/doc/volunteer/'.$volunteer->file_cv)}}"
                                               target="_blank"
                                               class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                        @else
                                            No File
                                        @endif
                                    </div>
                                    {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                </div>

                                <div class="form-group {{($errors->has('file_surat_pernyataan'))?'has-danger':''}}">
                                    <label for="file_surat_pernyataan" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat
                                        Pernyataan  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_pernyataan', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_pernyataan']) !!}
                                        @if($volunteer->file_surat_pernyataan!=null)
                                            <a href="{{asset('assets/doc/volunteer/'.$volunteer->file_surat_pernyataan)}}"
                                               target="_blank"
                                               class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                        @else
                                            No File
                                        @endif
                                    </div>
                                    {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                </div>

                                {{--<div class="form-group {{($errors->has('file_surat_ijin_gm'))?'has-danger':''}}">--}}
                                {{--<label for="file_surat_ijin_gm" class="form-control-label">Surat Ijin GM</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::file('file_surat_ijin_gm', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_ijin_gm']) !!}--}}
                                {{--@if($volunteer->file_surat_ijin_gm!=null)--}}
                                {{--<a href="{{asset('assets/doc/volunteer/'.$volunteer->file_surat_ijin_gm)}}"--}}
                                {{--target="_blank"--}}
                                {{--class="btn btn-primary"><i class="fa fa-download"></i> Download</a>--}}
                                {{--@else--}}
                                {{--No File--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('file_surat_ijin_keluarga'))?'has-danger':''}}">
                                    <label for="file_surat_ijin_keluarga" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat Ijin
                                        Keluarga  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_ijin_keluarga', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_ijin_keluarga']) !!}
                                        @if($volunteer->file_surat_ijin_keluarga!=null)
                                            <a href="{{asset('assets/doc/volunteer/'.$volunteer->file_surat_ijin_keluarga)}}"
                                               target="_blank"
                                               class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                        @else
                                            No File
                                        @endif
                                    </div>
                                    {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                </div>

                                <div class="form-group {{($errors->has('file_surat_sehat'))?'has-danger':''}}">
                                    <label for="file_surat_ijin_keluarga" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat Keterangan
                                        Sehat  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_ijin_keluarga', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_ijin_keluarga']) !!}
                                        @if($volunteer->file_surat_sehat!=null)
                                            <a href="{{asset('assets/doc/volunteer/'.$volunteer->file_surat_sehat)}}"
                                               target="_blank"
                                               class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                        @else
                                            No File
                                        @endif
                                    </div>
                                    {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                </div>


                            </div>
                        </div>

                    </div>

                    {{--<div role="tabpanel" class="tab-pane fade in p-20" id="status" aria-labelledby="status-tab">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}
                    {{--<div class="p-20">--}}
                    {{--<table class="table">--}}
                    {{--<tbody>--}}
                    {{--@foreach($volunteer->statusVolunteer()->orderBy('id', 'desc')->get() as $status)--}}
                    {{--<tr>--}}
                    {{--<td scope="row">--}}
                    {{--@if($status->status == 'REG')--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-edit"></i></button>--}}
                    {{--@elseif($status->status == 'APRV-AT' || $status->status == 'APRV-GM' || $status->status == 'APRV-PST')--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-check"></i></button>--}}
                    {{--@elseif($status->status == 'REJ-AT' || $status->status == 'REJ-GM' || $status->status == 'REJ-PST')--}}
                    {{--<button class="btn waves-effect btn-danger"><i--}}
                    {{--class="fa fa-times"></i></button>--}}
                    {{--@elseif($status->status == 'BRFG')--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-comment-o"></i></button>--}}
                    {{--@elseif($status->status == 'ACTV')--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-heart-o"></i></button>--}}
                    {{--@elseif($status->status == 'COMP')--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-flag-checkered"></i></button>--}}
                    {{--@else--}}
                    {{--<button class="btn waves-effect btn-success"><i--}}
                    {{--class="fa fa-check"></i></button>--}}
                    {{--@endif--}}
                    {{--</td>--}}
                    {{--<td width="200">--}}
                    {{--<i class="fa fa-clock-o"></i> {{$status->created_at->format('d M Y')}}--}}
                    {{--, jam {{$status->created_at->format('H:i')}}--}}

                    {{--<br>--}}
                    {{--<small>{{$status->created_at->diffForHumans()}}</small>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                    {{--{{$status->message}}--}}
                    {{--</td>--}}
                    {{--<td width="200">--}}
                    {{--@if($status->approver_id!=null)--}}
                    {{--@if($status->status == 'APRV-AT' || $status->status == 'APRV-GM' || $status->status == 'APRV-PST')--}}
                    {{--<small>Disetujui oleh:</small>--}}
                    {{--@elseif($status->status == 'REJ-AT' || $status->status == 'REJ-GM' || $status->status == 'REJ-PST')--}}
                    {{--<small>Ditolak oleh:</small>--}}
                    {{--@endif--}}
                    {{--<br>--}}
                    {{--{{$status->approver->name}} ({{$status->approver->nip}})--}}
                    {{--@endif--}}
                    {{--</td>--}}
                    {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                    {{--</table>--}}
                    {{--</div>--}}

                    {{--</div>--}}
                    {{--</div>--}}

                    {{--</div>--}}

                </div>


                <div class="row m-t-20">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="history.back()">
                                <i class="fa fa-times"></i> Cancel
                            </button>

                            <button type="submit" class="btn btn-success btn-lg pull-right">
                                <i class="fa fa-save"></i> Save
                            </button>

                            {{--@if($volunteer->approval_atasan==null && Auth::user()->isStruktural() && $volunteer->status=='REG')--}}
                            {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                            {{--class="btn btn-danger btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('1');">--}}
                            {{--<i class="fa fa-times"></i> Reject--}}
                            {{--</a>--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Approve"--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                            {{--<i class="fa fa-check"></i> Approve--}}
                            {{--</a>--}}
                            {{--@elseif($volunteer->approval_gm==null && $volunteer->evp->reg_gm=='1' && Auth::user()->isGM() && $volunteer->status=='APRV-AT')--}}
                            {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                            {{--class="btn btn-danger btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('2');">--}}
                            {{--<i class="fa fa-times"></i> Reject--}}
                            {{--</a>--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Approve"--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                            {{--<i class="fa fa-check"></i> Approve--}}
                            {{--</a>--}}
                            {{--@elseif($volunteer->approval_pusat==null && Auth::user()->can('evp_approve') && $volunteer->status=='APRV-GM')--}}
                            {{--<a href="javascript:" data-toggle="modal" data-target="#rejectModal"--}}
                            {{--class="btn btn-danger btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:$('#volunteer_id').val({{$volunteer->id}});$('#approver').val('3');">--}}
                            {{--<i class="fa fa-times"></i> Reject--}}
                            {{--</a>--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Approve"--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai tersebut untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                            {{--<i class="fa fa-check"></i> Approve--}}
                            {{--</a>--}}
                            {{--@else--}}
                            {{--@endif--}}

                            {{--@if(Auth::user()->isGM() || Auth::user()->isStruktural())--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-danger btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Reject"--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menolak pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/reject/'.$volunteer->id)}}'}">--}}
                            {{--<i class="fa fa-times"></i> Reject--}}
                            {{--</a>--}}
                            {{--<a href="javascript:"--}}
                            {{--class="btn btn-success btn-lg waves-effect waves-light pull-right"--}}
                            {{--title="Approve"--}}
                            {{--@if($volunteer->approval_atasan==null)--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/1')}}'}">--}}
                            {{--@elseif($volunteer->approval_gm==null)--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/2')}}'}">--}}
                            {{--@elseif($volunteer->approval_pusat==null)--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='{{url('evp/approval/'.$volunteer->id.'/3')}}'}">--}}
                            {{--@else--}}
                            {{--onclick="javascript:if(confirm('Apakah Anda yakin ingin menyetujui pegawai Anda untuk mengikuti kegiatan ini?')){window.location.href='#'}">--}}
                            {{--@endif--}}
                            {{--<i class="fa fa-check"></i> Approve--}}
                            {{--</a>--}}
                            {{--@endif--}}

                            {{--<button type="submit" class="btn btn-primary btn-lg pull-right"><i--}}
                            {{--class="fa fa-paper-plane"></i>--}}
                            {{--Submit--}}
                            {{--</button>--}}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


    {{--<!-- sample modal content -->--}}
    {{--<div id="rejectModal" class="modal fade" role="dialog" aria-labelledby="rejectModalLabel"--}}
    {{--aria-hidden="true">--}}
    {{--{!! Form::open(['url'=>'evp/reject', 'id'=>'form_reject']) !!}--}}
    {{--{!! Form::hidden('volunteer_id', $volunteer->id, ['id'=>'volunteer_id']) !!}--}}
    {{--{!! Form::hidden('approver', '', ['id'=>'approver']) !!}--}}
    {{--<div class="modal-dialog">--}}
    {{--<div class="modal-content">--}}
    {{--<div class="modal-header">--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>--}}
    {{--<h4 class="modal-title" id="myModalLabel">Reject</h4>--}}
    {{--</div>--}}
    {{--<form id="form_ruling">--}}
    {{--<div class="modal-body">--}}
    {{--<div class="m-l-20">--}}
    {{--<div class="form-group">--}}
    {{--<label>Kegiatan</label>--}}

    {{--<div>--}}
    {{--{!! Form::text('kegiatan_wp',null,['class'=>'form-control', 'id'=>'kegiatan_wp']) !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
    {{--<label>Kegiatan</label>--}}

    {{--<div>--}}
    {{--{!! Form::text('kegiatan_wp',null,['class'=>'form-control', 'id'=>'kegiatan_wp']) !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
    {{--<label>Lokasi</label>--}}

    {{--<div>--}}
    {{--{!! Form::text('lokasi_wp',null,['class'=>'form-control', 'id'=>'lokasi_wp']) !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
    {{--<label>Masukkan keterangan / alasan penolakan</label>--}}

    {{--<div>--}}
    {{--{!! Form::textarea('alasan_ditolak',null,['class'=>'form-control', 'id'=>'alasan_ditolak']) !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--<div class="modal-footer">--}}

    {{--<button type="submit" class="btn btn-success waves-effect waves-light" id="btn_submit"><i--}}
    {{--class="fa fa-paper-plane"></i>--}}
    {{--Sumbit--}}
    {{--</button>--}}
    {{--<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i--}}
    {{--class="fa fa-times"></i> Close--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--<!-- /.modal-content -->--}}
    {{--</div>--}}

    {{--{!! Form::close() !!}--}}
    {{--<!-- /.modal-dialog -->--}}
    {{--{!! Form::close() !!}--}}
    {{--</div><!-- /.modal -->--}}
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $("#do_dont").select2();
            $("#jenis_coc_id").select2();

//            $("#company_code").select2();
//            $("#business_area").select2();
            $("#orgeh").select2();
//            $("#materi_id").select2();
            $("#tema_id").select2();
            jQuery('#coc_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            $('.clockpicker').clockpicker({
                donetext: 'Done'
            });
            jQuery('#date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });

            tinymce.init({
                selector: '#answer_tertarik', height: 250,
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
                selector: '#answer_tepat', height: 250,
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