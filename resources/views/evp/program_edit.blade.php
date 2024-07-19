@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Edit Employee Volunteer Program - {{$evp->jenisEVP->description}}</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'evp/edit/'.$evp->id, 'files'=>true]) !!}
    {!! Form::hidden('jenis_evp_id', $evp->jenisEVP->id) !!}
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
                           role="tab" aria-controls="home" aria-expanded="true">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tempattanggal-tab" data-toggle="tab" href="#tempattanggal"
                           role="tab" aria-controls="tempattanggal" aria-expanded="true">Tempat & Tanggal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="unit-tab" data-toggle="tab" href="#unit"
                           role="tab" aria-controls="unit" aria-expanded="true">Unit Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen"
                           role="tab" aria-controls="dokumen" aria-expanded="true">Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="vendor-tab" data-toggle="tab" href="#vendor"
                           role="tab" aria-controls="vendor" aria-expanded="true">Vendor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="setting-tab" data-toggle="tab" href="#setting"
                           role="tab" aria-controls="setting" aria-expanded="true">Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="workplan-tab" data-toggle="tab" href="#workplan"
                           role="tab" aria-controls="workplan" aria-expanded="true">Workplan</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">

                                {{--<div class="form-group {{($errors->has('tema'))?'has-danger':''}}">--}}
                                {{--<label for="tema_id" class="form-control-label">Tema</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('nama_kegiatan'))?'has-danger':''}}">
                                    <label for="judul_coc" class="form-control-label">Nama Kegiatan</label>

                                    <div>
                                        {!! Form::text('nama_kegiatan',$evp->nama_kegiatan, ['class'=>'form-control form-control-danger', 'id'=>'judul', 'placeholder'=>'Nama Kegiatan']) !!}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">
                                    <label for="pernr_leader" class="form-control-label">Deskripsi</label>

                                    <div>
                                        {!! Form::textarea('deskripsi', $evp->deskripsi, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',
                                        'placeholder'=>'Masukkan Deskripsi Program', 'rows'=>'20']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('kriteria_peserta'))?'has-danger':''}}">
                                    <label for="pernr_leader" class="form-control-label">Kriteria Peserta</label>

                                    <div>
                                        {!! Form::textarea('kriteria_peserta', $evp->kriteria_peserta, ['class'=>'form-control form-control-danger', 'id'=>'kriteria_peserta',
                                        'placeholder'=>'Masukkan Kriteria Peserta', 'rows'=>'20']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-5">

                                {{--<div class="form-group {{($errors->has('tempat'))?'has-danger':''}}">--}}
                                {{--<label for="tempat" class="form-control-label">Lokasi</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('tempat',null, ['class'=>'form-control form-control-danger', 'id'=>'tempat', 'placeholder'=>'Lokasi']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group" {{($errors->has('waktu_awal') || $errors->has('waktu_akhir'))?'has-danger':''}}>--}}
                                {{--<label>Tanggal Kegiatan</label>--}}

                                {{--<div>--}}
                                {{--<div class="input-daterange input-group" id="date-range">--}}
                                {{--<input type="text" class="form-control" name="waktu_awal"/>--}}
                                {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                {{--<input type="text" class="form-control" name="waktu_akhir"/>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group {{($errors->has('jenis_waktu_evp_id'))?'has-danger':''}}">
                                    <label for="jenis_waktu_evp_id" class="form-control-label">Jenis Waktu</label>

                                    <div>
                                        {!! Form::select('jenis_waktu_evp_id',$jenis_waktu_list, ($evp->jenisEVP->id==1)?'1':'2', ['class'=>'select2 form-control form-control-danger', 'id'=>'jenis_waktu_evp_id']) !!}
                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('kuota'))?'has-danger':''}}">
                                    <label for="tempat" class="form-control-label">Kuota</label>

                                    <div>
                                        {!! Form::number('kuota',$evp->kuota, ['class'=>'form-control form-control-danger', 'id'=>'kuota', 'placeholder'=>'Kuota']) !!}
                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('foto'))?'has-danger':''}}">
                                    <label for="foto" class="form-control-label">Banner/Foto</label>

                                    <div>
                                        {!! Form::file('foto', ['class'=>'form-control form-control-danger', 'id'=>'foto']) !!}
                                    </div>
                                    <small class="text-muted">Format file gambar (JPG,JPEG,PNG). Ukuran file maksimal
                                        1MB.
                                    </small>
                                </div>

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
                    <div role="tabpanel" class="tab-pane fade in p-20" id="tempattanggal"
                         aria-labelledby="tempattanggal-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">

                                <div class="form-group {{($errors->has('tempat'))?'has-danger':''}}">
                                    <label for="tempat" class="form-control-label">Lokasi EVP</label>

                                    <div>
                                        {!! Form::text('tempat',$evp->tempat, ['class'=>'form-control form-control-danger', 'id'=>'tempat', 'placeholder'=>'Lokasi']) !!}
                                    </div>
                                </div>
                                <div class="form-group" {{($errors->has('tgl_registrasi_awal') || $errors->has('tgl_registrasi_akhir'))?'has-danger':''}}>
                                    <label>Tanggal Pendaftaran</label>

                                    <div>
                                        <div class="input-daterange input-group" id="date-range-reg">
                                            <input type="text" class="form-control" name="tgl_registrasi_awal"
                                                   value="{{@$evp->tgl_awal_registrasi->format('d-m-Y')}}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input type="text" class="form-control" name="tgl_registrasi_akhir"
                                                   value="{{@$evp->tgl_akhir_registrasi->format('d-m-Y')}}"
                                                   autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Pengumuman</label>

                                    <div>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-danger"
                                                   placeholder="dd-mm-yyyy" id="tgl_pengumuman"
                                                   name="tgl_pengumuman"
                                                   value="{{@$evp->tgl_pengumuman->format('d-m-Y')}}"
                                                   autocomplete="off">
                                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" {{($errors->has('waktu_awal') || $errors->has('waktu_akhir'))?'has-danger':''}}>
                                    <label>Tanggal Kegiatan EVP</label>

                                    <div>
                                        <div class="input-daterange input-group" id="date-range">
                                            <input type="text" class="form-control" name="waktu_awal"
                                                   value="{{@$evp->waktu_awal->format('d-m-Y')}}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0">to</span>
                                            <input type="text" class="form-control" name="waktu_akhir"
                                                   value="{{@$evp->waktu_akhir->format('d-m-Y')}}"
                                                   autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group {{($errors->has('briefing'))?'has-danger':''}}">
                                    <label for="briefing" class="form-control-label">Briefing</label>

                                    <div>
                                        <input type="checkbox" name="briefing"
                                               {{($evp->briefing=='1')?'checked':''}} data-plugin="switchery"
                                               id="briefing"
                                               data-color="#039cfd" value="1"/>
                                    </div>
                                </div>

                                <div id="tempat_tanggal_briefing"
                                     style="display: {{($evp->briefing=='1')?'block':'none'}};">
                                    <div class="form-group {{($errors->has('tempat_briefing'))?'has-danger':''}}">
                                        <label for="tempat" class="form-control-label">Lokasi Briefing</label>

                                        <div>
                                            {!! Form::text('tempat_briefing',$evp->tempat_briefing, ['class'=>'form-control form-control-danger', 'id'=>'tempat_briefing', 'placeholder'=>'Lokasi']) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal dan Jam Briefing</label>

                                        <div class="row">
                                            <div class="input-group col-md-6">
                                                <input type="text" class="form-control form-control-danger"
                                                       placeholder="dd-mm-yyyy" id="tgl_briefing"
                                                       name="tgl_briefing"
                                                       value="{{@$evp->tgl_jam_briefing->format('d-m-Y')}}">
                                                <span class="input-group-addon bg-custom b-0"><i
                                                            class="icon-calender"></i></span>
                                            </div>


                                            <div class="input-group clockpicker col-md-6" data-placement="top"
                                                 data-align="top"
                                                 data-autoclose="true">
                                                <input type="text" class="form-control form-control-danger"
                                                       placeholder="Masukkan Jam" id="jam_briefing" name="jam_briefing"
                                                       value="{{@$evp->tgl_jam_briefing->format('H:i')}}">
                                                <span class="input-group-addon"> <span
                                                            class="zmdi zmdi-time"></span> </span>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade in p-20" id="unit" role="tabpanel"
                         aria-labelledby="unit-tab">
                        <div class="col-md-12 col-xs-12">
                            {{--<div class="card-box">--}}
                            {{--{!! Form::open(['url'=>'user-management/user/'.$user->id.'/update-role']) !!}--}}
                            <div class="row">

                                <div class="col-md-6">
                                    <label>Company Code</label>
                                    <select name="company_code[]" class="multi-select" multiple=""
                                            id="my_multi_select4">
                                        @foreach($cc_list as $cc)
                                            {{--<option value="{{$ba->id}}" {{($user->hasRole($role->name))? 'selected' : ''}}>{{$role->display_name}}</option>--}}
                                            <option value="{{$cc->id}}" {{($evp->companyCode()->where('id', $cc->id)->first()!=null)?'selected':''}}>{{$cc->company_code}}
                                                - {{$cc->description}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-6">
                                    <label>Business Area</label>
                                    <select name="business_area[]" class="multi-select" multiple=""
                                            id="my_multi_select3">
                                        @foreach($ba_list as $ba)
                                            {{--<option value="{{$ba->id}}" {{($user->hasRole($role->name))? 'selected' : ''}}>{{$role->display_name}}</option>--}}
                                            <option value="{{$ba->id}}" {{($evp->businessArea()->where('id', $ba->id)->first()!=null)?'selected':''}}>{{$ba->business_area}}
                                                - {{$ba->description}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            {{--<div class="row m-t-30">--}}
                            {{--<div class="col-md-12">--}}
                            {{--<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Apply--}}
                            {{--</button>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--{!! Form::close() !!}--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-20" id="dokumen" aria-labelledby="dokumen-tab">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group {{($errors->has('dokumen'))?'has-danger':''}}">
                                    <label for="dokumen" class="form-control-label">Surat/Dokumen</label>

                                    <div>
                                        @if($evp->dokumen!=null)
                                            <a href="{{asset('assets/doc/evp/'.$evp->dokumen)}}" target="_blank"><i
                                                        class="fa fa-download"></i> {{$evp->dokumen}}</a>
                                        @endif
                                        {!! Form::file('dokumen', ['class'=>'form-control form-control-danger', 'id'=>'dokumen']) !!}
                                    </div>
                                    <small class="text-muted">Ukuran file maksimal 5MB.</small>
                                </div>

                                <div class="form-group {{($errors->has('data_diri'))?'has-danger':''}}">
                                    <label for="data_diri" class="form-control-label">Form Data Diri</label>

                                    <div>
                                        @if($evp->data_diri!=null)
                                            <a href="{{asset('assets/doc/evp/'.$evp->data_diri)}}" target="_blank"><i
                                                        class="fa fa-download"></i> {{$evp->data_diri}}</a>
                                        @endif
                                        {!! Form::file('data_diri', ['class'=>'form-control form-control-danger', 'id'=>'data_diri']) !!}
                                    </div>
                                    <small class="text-muted">Ukuran file maksimal 5MB.</small>
                                </div>

                                <div class="form-group {{($errors->has('surat_pernyataan'))?'has-danger':''}}">
                                    <label for="surat_pernyataan" class="form-control-label">Surat Pernyataan</label>

                                    <div>
                                        @if($evp->surat_pernyataan!=null)
                                            <a href="{{asset('assets/doc/evp/'.$evp->surat_pernyataan)}}" target="_blank"><i
                                                        class="fa fa-download"></i> {{$evp->surat_pernyataan}}</a>
                                        @endif
                                        {!! Form::file('surat_pernyataan', ['class'=>'form-control form-control-danger', 'id'=>'surat_pernyataan']) !!}
                                    </div>
                                    <small class="text-muted">Ukuran file maksimal 5MB.</small>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-20" id="vendor"
                         aria-labelledby="vendor-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group {{($errors->has('nama_vendor'))?'has-danger':''}}">
                                    <label for="nama_vendor" class="form-control-label">Nama Vendor</label>

                                    <div>
                                        {!! Form::text('nama_vendor',$evp->nama_vendor, ['class'=>'form-control form-control-danger', 'id'=>'nama_vendor', 'placeholder'=>'Nama Vendor']) !!}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('email_vendor'))?'has-danger':''}}">
                                    <label for="email_vendor" class="form-control-label">Email Vendor</label>

                                    <div>
                                        {!! Form::text('email_vendor',$evp->email_vendor, ['class'=>'form-control form-control-danger', 'id'=>'email_vendor', 'placeholder'=>'Email Vendor']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">


                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-20" id="setting"
                         aria-labelledby="setting-tab">
                        <div class="row">

                            <div class="col-md-5">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{($errors->has('reg_atasan') || $errors->has('reg_gm'))?'has-danger':''}}">
                                            <label for="materi" class="form-control-label">Approval Registrasi</label>

                                            <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                <input name="reg_atasan" id="reg_atasan" type="checkbox" value="1"
                                                       {{($evp->reg_atasan=='1')?'checked':''}}>
                                                <label for="reg_atasan">
                                                    Atasan Langsung
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                <input name="reg_admin_lv1" id="reg_admin_lv1" type="checkbox" value="1" {{($evp->reg_admin_lv1=='1')?'checked':''}}>
                                                <label for="reg_admin_lv1">
                                                    Admin Unit Induk
                                                </label>
                                            </div>
                                            @if($evp->jenis_evp_id=='1')
                                            <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                <input name="reg_gm" id="reg_gm" type="checkbox" value="1"
                                                        {{($evp->reg_gm=='1')?'checked':''}}>
                                                <label for="reg_gm">
                                                    General Manager
                                                </label>
                                            </div>
                                            @endif
                                            @if($evp->jenis_evp_id=='1')
                                                <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                    <input name="reg_admin_pusat" id="reg_admin_pusat" type="checkbox" value="1" {{($evp->reg_admin_pusat=='1')?'checked':''}}>
                                                    <label for="reg_admin_pusat">
                                                        Admin Kantor Pusat
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{($errors->has('keg_atasan') || $errors->has('keg_vendor'))?'has-danger':''}}">
                                            <label for="materi" class="form-control-label">Approval Kegiatan</label>

                                            <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                <input name="keg_atasan" id="keg_atasan" type="checkbox" value="1"
                                                        {{($evp->keg_atasan=='1')?'checked':''}}>
                                                <label for="keg_atasan">
                                                    Atasan Langsung
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox-primary" style="padding-left: 50px;">
                                                <input name="keg_vendor" id="keg_vendor" type="checkbox" value="1"
                                                        {{($evp->keg_vendor=='1')?'checked':''}}>
                                                <label for="keg_vendor">
                                                    Vendor
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="col-md-7 col-xs-12">

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade in p-20" id="workplan"
                         aria-labelledby="workplan-tab">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <a href="javascript:" data-toggle="modal" data-target="#workplanModal"
                                   class="btn btn-success waves-effect waves-light"><i class="fa fa-plus"></i> Add
                                    Workplan</a>
                                {{--<a href="" class="btn btn-primary pd-x-20 mg-b-10" data-toggle="modal"--}}
                                {{--data-target="#modalworkplan">--}}
                                {{--<i class="fa fa-plus mg-r-10"></i> Add Workplan--}}
                                {{--</a>--}}
                            </div>
                        </div>

                        <div class="col-md-12 table-responsive m-t-20">
                            <table id="table_workplan" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th class="center hidden-xs" style="text-align: center">Tanggal</th>
                                    <th class="center hidden-xs" style="text-align: center">Jam</th>
                                    <th class="center hidden-xs" style="text-align: center">Lokasi</th>
                                    <th class="center hidden-xs" style="text-align: center">Kegiatan</th>
                                    <th class="center hidden-xs" style="text-align: center">Penanggungjawab</th>
                                    <th class="center hidden-xs" style="text-align: center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x=1;?>
                                @foreach($evp->rundownEVP()->orderBy('id', 'asc')->get() as $rundown)
                                    <tr id="col-wp-{{$x}}">
                                        @if(@$rundown->tgl_jam_awal->format('d-m-Y')==@$rundown->tgl_jam_akhir->format('d-m-Y'))
                                            <td>{{@$rundown->tgl_jam_awal->format('d-m-Y')}}</td>
                                        @else
                                            <td>{{@$rundown->tgl_jam_awal->format('d-m-Y')}} - {{@$rundown->tgl_jam_akhir->format('d-m-Y')}}</td>
                                        @endif
                                        <td>{{@$rundown->tgl_jam_awal->format('H:i')}} - {{@$rundown->tgl_jam_akhir->format('H:i')}}</td>
                                        <td>{{$rundown->lokasi}}</td>
                                        <td>{{$rundown->kegiatan}}</td>
                                        <td>{{$rundown->penanggungjawab}}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="del_wp({{$x++}})"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                {{--@foreach($coc->attendants as $user)--}}
                                {{--<tr>--}}
                                {{--<td style="text-align: center">--}}
                                {{--@if(Auth::user()->hasRole('root'))--}}
                                {{--<a href = "{{url('user-management/user/'.@$user->user->id)}}">--}}
                                {{--@endif--}}
                                {{--@if(@$user->user->foto!='')--}}
                                {{--<img src="{{asset('assets/images/users/foto-thumb/'.@$user->user->foto)}}" alt="user" class="img-fluid img-thumbnail" width="64">--}}
                                {{--@else--}}
                                {{--<img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-fluid img-thumbnail" width="64">--}}
                                {{--@endif--}}
                                {{--@if(Auth::user()->hasRole('root'))--}}
                                {{--</a>--}}
                                {{--@endif--}}
                                {{--</td>--}}
                                {{--<td class="hidden-xs">{{@$user->user->strukturJabatan->nip}}</td>--}}
                                {{--<td>{{@$user->user->name}}</td>--}}
                                {{--<td class="hidden-xs">{{@$user->user->business_area}}--}}
                                {{--- {{@$user->user->businessArea->description}}</td>--}}
                                {{--<td class="hidden-xs">{{@$user->user->strukturPosisi()->stxt2}}</td>--}}
                                {{--<td class="hidden-xs">{{@$user->user->strukturPosisi()->stext}}</td>--}}
                                {{--<td class="hidden-xs">--}}
                                {{--@if(@$user->status_checkin_id=='1')--}}
                                {{--<span class="label label-success"><b>{{@$user->statusCheckin->status}}</b></span>--}}
                                {{--@else--}}
                                {{--<span class="label label-danger"><b>{{@$user->statusCheckin->status}}</b></span>--}}
                                {{--@endif--}}
                                {{--</td>--}}
                                {{--<td class="hidden-xs">--}}
                                {{--{{@$user->check_in->format('Y-m-d H:i')}}<br>--}}
                                {{--<small class="text-muted">{{@$user->check_in->diffForHumans()}}</small>--}}
                                {{--</td>--}}
                                {{--@if($coc->materi!=null)--}}
                                {{--<td class="hidden-xs">--}}
                                {{--{{(@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)!=null)?@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)->created_at->format('Y-m-d H:i'):''}}<br>--}}
                                {{--<small class="text-muted">{{(@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)!=null)?@$user->user->hasReadMateriCoc(@$coc->materi->id, $coc->id)->created_at->diffForHumans():''}}</small>--}}
                                {{--</td>--}}
                                {{--@endif--}}
                                {{--</tr>--}}
                                {{--@endforeach--}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>


                <div class="row m-t-20">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{url('evp/program')}}';"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>
                                Save
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::hidden('arr_tgl_awal_wp', null, ['id'=>'arr_tgl_awal_wp']) !!}
    {!! Form::hidden('arr_tgl_akhir_wp', null, ['id'=>'arr_tgl_akhir_wp']) !!}
    {!! Form::hidden('arr_jam_awal_wp', null, ['id'=>'arr_jam_awal_wp']) !!}
    {!! Form::hidden('arr_jam_akhir_wp', null, ['id'=>'arr_jam_akhir_wp']) !!}
    {!! Form::hidden('arr_lokasi_wp', null, ['id'=>'arr_lokasi_wp']) !!}
    {!! Form::hidden('arr_kegiatan_wp', null, ['id'=>'arr_kegiatan_wp']) !!}
    {!! Form::hidden('arr_penanggungjawab_wp', null, ['id'=>'arr_penanggungjawab_wp']) !!}

    {!! Form::close() !!}


            <!-- sample modal content -->
    <div id="workplanModal" class="modal fade" role="dialog" aria-labelledby="workplanModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Workplan</h4>
                </div>
                <form id="form_ruling">
                    <div class="modal-body">
                        <div class="m-l-20">
                            <div class="form-group">
                                <label>Tanggal</label>

                                <div>
                                    <div class="input-daterange input-group" id="date-range-workplan">
                                        <input type="text" class="form-control" name="start_date_wp"
                                               id="start_date_wp"/>
                                        <span class="input-group-addon bg-custom b-0">to</span>
                                        <input type="text" class="form-control" name="end_date_wp" id="end_date_wp"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tema" class="form-control-label">Jam</label>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group clockpicker col-md-6" data-placement="top"
                                             data-align="top"
                                             data-autoclose="true">
                                            <input type="text" class="form-control form-control-danger"
                                                   placeholder="Masukkan jam awal" id="jam_awal_wp" name="jam_awal_wp">
                                                <span class="input-group-addon"> <span
                                                            class="zmdi zmdi-time"></span> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group clockpicker col-md-6" data-placement="top"
                                             data-align="top"
                                             data-autoclose="true">
                                            <input type="text" class="form-control form-control-danger"
                                                   placeholder="Masukkan jam akhir" id="jam_akhir_wp"
                                                   name="jam_akhir_wp">
                                                <span class="input-group-addon"> <span
                                                            class="zmdi zmdi-time"></span> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>

                                <div>
                                    {!! Form::text('lokasi_wp',null,['class'=>'form-control', 'id'=>'lokasi_wp']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kegiatan</label>

                                <div>
                                    {!! Form::text('kegiatan_wp',null,['class'=>'form-control', 'id'=>'kegiatan_wp']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Penanggungjawab</label>

                                <div>
                                    {!! Form::text('penanggungjawab_wp',null,['class'=>'form-control', 'id'=>'penanggungjawab_wp']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-success waves-effect waves-light" id="btn_add_wp"><i
                                    class="fa fa-plus"></i>
                            Add
                        </button>
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Close
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        {{--{!! Form::close() !!}--}}
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"
            type="text/javascript"></script>
    {{--<script type="text/javascript" src="{{asset('assets/pages/jquery.formadvanced.init.js')}}"></script>--}}

    <script type="text/javascript" src="{{asset('assets/plugins/jquery-quicksearch/jquery.quicksearch.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"
            type="text/javascript"></script>

    <script>
        var t3 = $('#table_workplan').DataTable({
            "searching": false,
            "bLengthChange": false,
        });
        var counter_wp = {{$evp->rundownEVP->count()+1}};

        var arr_tgl_awal_wp = [];
        var arr_tgl_akhir_wp = [];
        var arr_jam_awal_wp = [];
        var arr_jam_akhir_wp = [];
        var arr_lokasi_wp = [];
        var arr_kegiatan_wp = [];
        var arr_penanggungjawab_wp = [];

        <?php $x=1;?>
        @foreach($evp->rundownEVP()->orderBy('id', 'asc')->get() as $rundown)
             arr_tgl_awal_wp[{{$x}}] = "{{@$rundown->tgl_jam_awal->format('d-m-Y')}}";
             arr_tgl_akhir_wp[{{$x}}] = "{{@$rundown->tgl_jam_akhir->format('d-m-Y')}}";
             arr_jam_awal_wp[{{$x}}] = "{{@$rundown->tgl_jam_awal->format('H:i')}}";
             arr_jam_akhir_wp[{{$x}}] = "{{@$rundown->tgl_jam_akhir->format('H:i')}}";
             arr_lokasi_wp[{{$x}}] = "{{$rundown->lokasi}}";
             arr_kegiatan_wp[{{$x}}] = "{{$rundown->kegiatan}}";
             arr_penanggungjawab_wp[{{$x++}}] = "{{$rundown->penanggungjawab}}";
        @endforeach

        $(document).ready(function () {

            $('#arr_tgl_awal_wp').val(arr_tgl_awal_wp);
            $('#arr_tgl_akhir_wp').val(arr_tgl_akhir_wp);
            $('#arr_jam_awal_wp').val(arr_jam_awal_wp);
            $('#arr_jam_akhir_wp').val(arr_jam_akhir_wp);
            $('#arr_lokasi_wp').val(arr_lokasi_wp);
            $('#arr_kegiatan_wp').val(arr_kegiatan_wp);
            $('#arr_penanggungjawab_wp').val(arr_penanggungjawab_wp);

            $('#datatable').DataTable();
//advance multiselect start
            $('#my_multi_select3').multiSelect({
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                afterInit: function (ms) {
                    var that = this,
                            $selectableSearch = that.$selectableUl.prev(),
                            $selectionSearch = that.$selectionUl.prev(),
                            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                            .on('keydown', function (e) {
                                if (e.which === 40) {
                                    that.$selectableUl.focus();
                                    return false;
                                }
                            });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                            .on('keydown', function (e) {
                                if (e.which == 40) {
                                    that.$selectionUl.focus();
                                    return false;
                                }
                            });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });

            $('#my_multi_select4').multiSelect({
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                afterInit: function (ms) {
                    var that = this,
                            $selectableSearch = that.$selectableUl.prev(),
                            $selectionSearch = that.$selectionUl.prev(),
                            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                            .on('keydown', function (e) {
                                if (e.which === 40) {
                                    that.$selectableUl.focus();
                                    return false;
                                }
                            });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                            .on('keydown', function (e) {
                                if (e.which == 40) {
                                    that.$selectionUl.focus();
                                    return false;
                                }
                            });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });

            $(".select2").select2();

//            $("#jenis_coc_id").select2();

//            $("#company_code").select2();
//            $("#business_area").select2();
//            $("#orgeh").select2();
//            $("#materi_id").select2();
//            $("#tema_id").select2();
            jQuery('#tgl_pengumuman').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#tgl_briefing').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
//            $('.clockpicker').clockpicker({
//                donetext: 'Done'
//            });

            jQuery('#date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#date-range-reg').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#date-range-workplan').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });

            $('.clockpicker').clockpicker({
                donetext: 'Done'
            });

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

            $("#briefing").change(function () {
                if ($(this).is(":checked") == false) {
                    $("#tempat_tanggal_briefing").hide();
                }
                else {
                    $("#tempat_tanggal_briefing").show();
                }
            });

            $('#btn_add_wp').on('click', function () {
//                console.log(counter_wp);

                arr_tgl_awal_wp[counter_wp] = $('#start_date_wp').val();
                arr_tgl_akhir_wp[counter_wp] = $('#end_date_wp').val();
                arr_jam_awal_wp[counter_wp] = $('#jam_awal_wp').val();
                arr_jam_akhir_wp[counter_wp] = $('#jam_akhir_wp').val();
                arr_lokasi_wp[counter_wp] = $('#lokasi_wp').val();
                arr_kegiatan_wp[counter_wp] = $('#kegiatan_wp').val();
                arr_penanggungjawab_wp[counter_wp] = $('#penanggungjawab_wp').val();

                var tanggal;
                if (arr_tgl_awal_wp[counter_wp] == arr_tgl_akhir_wp[counter_wp]) {
                    tanggal = arr_tgl_awal_wp[counter_wp];
                }
                else {
                    tanggal = arr_tgl_awal_wp[counter_wp] + ' - ' + arr_tgl_akhir_wp[counter_wp];
                }

                t3.row.add([
                    tanggal,
                    arr_jam_awal_wp[counter_wp] + ' - ' + arr_jam_akhir_wp[counter_wp],
                    arr_lokasi_wp[counter_wp],
                    arr_kegiatan_wp[counter_wp],
                    arr_penanggungjawab_wp[counter_wp],
                    '<button type="button" class="btn btn-danger btn-sm" onclick="del_wp(' + counter_wp + ')"><i class="fa fa-trash"></i></button>'
                ]).node().id = 'col-wp-' + counter_wp;
                t3.draw(false);
                counter_wp++;

                $('#arr_tgl_awal_wp').val(arr_tgl_awal_wp);
                $('#arr_tgl_akhir_wp').val(arr_tgl_akhir_wp);
                $('#arr_jam_awal_wp').val(arr_jam_awal_wp);
                $('#arr_jam_akhir_wp').val(arr_jam_akhir_wp);
                $('#arr_lokasi_wp').val(arr_lokasi_wp);
                $('#arr_kegiatan_wp').val(arr_kegiatan_wp);
                $('#arr_penanggungjawab_wp').val(arr_penanggungjawab_wp);

//                console.log(arr_tgl_awal_wp);
//                console.log(arr_tgl_akhir_wp);
//                console.log(arr_jam_awal_wp);
//                console.log(arr_jam_akhir_wp);
//                console.log(arr_lokasi_wp);
//                console.log(arr_kegiatan_wp);

                $('#workplanModal').modal('hide');
            });

        });


        function del_wp(x) {
//            var index = x;
//            console.log('Del ' + x + ': ' + counter_wp);
//            t.row(':eq(0)').delete();
            t3.row('#col-wp-' + x).remove().draw();

            // pop array
            arr_tgl_awal_wp.splice(x, 1);
            arr_tgl_akhir_wp.splice(x, 1);
            arr_jam_awal_wp.splice(x, 1);
            arr_jam_akhir_wp.splice(x, 1);
            arr_lokasi_wp.splice(x, 1);
            arr_kegiatan_wp.splice(x, 1);
            arr_penanggungjawab_wp.splice(x, 1);

            // decrease
            counter_wp--;

            // update array
            $('#arr_tgl_awal_wp').val(arr_tgl_awal_wp);
            $('#arr_tgl_akhir_wp').val(arr_tgl_akhir_wp);
            $('#arr_jam_awal_wp').val(arr_jam_awal_wp);
            $('#arr_jam_akhir_wp').val(arr_jam_akhir_wp);
            $('#arr_lokasi_wp').val(arr_lokasi_wp);
            $('#arr_kegiatan_wp').val(arr_kegiatan_wp);
            $('#arr_penanggungjawab_wp').val(arr_penanggungjawab_wp);
        }

    </script>
@stop