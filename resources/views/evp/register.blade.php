@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Registrasi Relawan</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'evp/register/'.$evp->id, 'files'=>true]) !!}
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

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">

                                {{--<div class="form-group {{($errors->has('tema'))?'has-danger':''}}">--}}
                                {{--<label for="tema_id" class="form-control-label">Tema</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('nama_kegiatan'))?'has-danger':''}} m-t-15">

                                    <h4 class="card-title"><a href="{{url('evp/detail/'.$evp->id)}}"
                                                              style="color: black; text-decoration-color: black;">{{$evp->nama_kegiatan}}</a>
                                    </h4>
                                    <h6 class="card-subtitle text-muted">
                                        <i class="fa fa-calendar"></i> {{$evp->waktu_awal->format('d M Y')}}
                                        - {{$evp->waktu_akhir->format('d M Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-globe"></i> {{$evp->jenisEVP->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-map-marker"></i> Lokasi: {{$evp->tempat}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </h6>
                                    {{--<label for="judul_coc" class="form-control-label">Nama Kegiatan</label>--}}

                                    {{--@if($evp->jenis_evp_id=='1')--}}
                                    {{--<span class="label label-danger">Nasional</span>--}}
                                    {{--@else--}}
                                    {{--<span class="label label-success">Lokal</span>--}}
                                    {{--@endif--}}
                                    {{--<div>--}}
                                    {!! Form::hidden('evp_id', $evp->id) !!}
                                    {{--{!! Form::text('nama_kegiatan',$evp->nama_kegiatan, ['class'=>'form-control form-control-danger', 'id'=>'judul', 'placeholder'=>'Nama Kegiatan', 'readonly']) !!}--}}
                                    {{--</div>--}}
                                </div>

                                <div class="form-group {{($errors->has('answer_tertarik'))?'has-danger':''}}" style="margin-top: 20px;">
                                    <h5 for="answer_tertarik" class="form-control-label"><i class="fa fa-comments-o" style="font-size: 25px;"></i> Jelaskan mengapa Anda tertarik untuk
                                        menjadi relawan pada kegiatan ini? <span class="text-danger">*</span></h5>

                                    <div style="padding: 10px 10px 10px 10px;">
                                        {!! Form::textarea('answer_tertarik', null, ['class'=>'form-control form-control-danger', 'id'=>'answer_tertarik',
                                        'placeholder'=>'Masukkan jawaban Anda', 'rows'=>'10']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('answer_tepat'))?'has-danger':''}}" style="margin-top: 20px;">
                                    <h5 for="answer_tepat" class="form-control-label"><i class="fa fa-comments-o" style="font-size: 25px;"></i> Jelaskan mengapa Anda adalah relawan
                                        yang tepat untuk kegiatan ini? <span class="text-danger">*</span></h5>

                                    <div style="padding: 10px 10px 10px 10px;">
                                        {!! Form::textarea('answer_tepat', null, ['class'=>'form-control form-control-danger', 'id'=>'answer_tepat',
                                        'placeholder'=>'Masukkan jawaban Anda', 'rows'=>'10']) !!}
                                        {{--<select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-5 hidden-md-down">
                                <div class="form-group" align="center">
                                    @if(Auth::user()->foto!='')
                                        <img class="img-fluid img-thumbnail" src="{{url('user/foto')}}" alt="user"
                                             width="200">
                                    @else
                                        <img class="img-fluid img-thumbnail" src="{{asset('assets/images/user.jpg')}}"
                                             alt="user" width="200">
                                    @endif
                                    <h3 class="m-t-10">{{@Auth::user()->strukturJabatan->cname}}</h3>
                                </div>
                                <div class="form-group">

                                    <label for="nama" class="form-control-label">NIP</label>

                                    <div>
                                        {!! Form::text('nama',@Auth::user()->nip, ['class'=>'form-control form-control-danger', 'id'=>'nama', 'placeholder'=>'Nama', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jabatan" class="form-control-label">Jabatan</label>

                                    <div>
                                        {!! Form::text('jabatan',@Auth::user()->strukturPosisi()->stext, ['class'=>'form-control form-control-danger', 'id'=>'jabatan', 'placeholder'=>'Jabatan', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="bidang" class="form-control-label">Bidang</label>

                                    <div>
                                        {!! Form::text('bidang',@Auth::user()->strukturOrganisasi()->stext, ['class'=>'form-control form-control-danger', 'id'=>'bidang', 'placeholder'=>'Bidang', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="business_area" class="form-control-label">Business Area</label>

                                    <div>
                                        {!! Form::text('business_area',Auth::user()->business_area.' - '.Auth::user()->businessArea->description, ['class'=>'form-control form-control-danger', 'id'=>'business_area', 'placeholder'=>'Business Area', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="company_code" class="form-control-label">Company Code</label>

                                    <div>
                                        {!! Form::text('company_code',Auth::user()->company_code.' - '.Auth::user()->companyCode->description, ['class'=>'form-control form-control-danger', 'id'=>'company_code', 'placeholder'=>'Company Code', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="form-group m-t-20 ">
                                    <label for="atasan" class="form-control-label">Atasan Langsung / Jabatan
                                        (NIP) *</label>

                                    <div>
                                        {!! Form::hidden('pernr_atasan', $pernr_atasan) !!}
                                        {!! Form::text('atasan',$atasan->cname.' / '.$atasan->strukturPosisi->stext.' ('.$atasan->nip.')', ['class'=>'form-control form-control-danger', 'id'=>'atasan', 'placeholder'=>'Atasan Langsung', 'readonly']) !!}
                                    </div>
                                </div>
                                @if($evp->reg_gm=='1')
                                <div class="form-group ">
                                    <label for="gm" class="form-control-label">General Manager (NIP) *</label>

                                    <div>
                                        {!! Form::hidden('pernr_gm', $gm->pernr) !!}
                                        {!! Form::text('gm',$gm->cname.' ('.$gm->nip.')', ['class'=>'form-control form-control-danger', 'id'=>'gm', 'placeholder'=>'General Manager', 'readonly']) !!}
                                    </div>
                                </div>
                                @endif

                                <small class="text-muted">* Jika ada kesalahan atasan langsung atau General Manager, harap
                                    menghubungi Administrator.
                                </small>
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
                                        <small class="text-muted">Ukuran file maksimal 3 MB.</small>
                                    </div>

                                </div>

                                <div class="form-group {{($errors->has('file_surat_pernyataan'))?'has-danger':''}}">
                                    <label for="file_surat_pernyataan" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat
                                        Pernyataan  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_pernyataan', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_pernyataan']) !!}
                                        <small class="text-muted">Ukuran file maksimal 3 MB.</small>
                                    </div>

                                </div>

                                {{--<div class="form-group {{($errors->has('file_surat_ijin_gm'))?'has-danger':''}}">--}}
                                {{--<label for="file_surat_ijin_gm" class="form-control-label">Surat Ijin GM</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::file('file_surat_ijin_gm', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_ijin_gm']) !!}--}}
                                {{--</div>--}}
                                {{--<small class="text-muted">Ukuran file maksimal 5MB.</small>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('file_surat_ijin_keluarga'))?'has-danger':''}}">
                                    <label for="file_surat_ijin_keluarga" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat Ijin
                                        Keluarga  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_ijin_keluarga', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_ijin_keluarga']) !!}
                                        <small class="text-muted">Ukuran file maksimal 3 MB.</small>
                                    </div>

                                </div>

                                <div class="form-group {{($errors->has('file_surat_sehat'))?'has-danger':''}}">
                                    <label for="file_surat_ijin_keluarga" class="form-control-label"><i class="fa fa-file-text-o"></i> Surat Keterangan
                                        Sehat  <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('file_surat_sehat', ['class'=>'form-control form-control-danger', 'id'=>'file_surat_sehat']) !!}
                                        {{--@if($volunteer->file_surat_sehat!=null)--}}
                                        {{--<a href="{{asset('assets/doc/volunteer/'.$volunteer->file_surat_sehat)}}"--}}
                                        {{--target="_blank"--}}
                                        {{--class="btn btn-primary"><i class="fa fa-download"></i> Download</a>--}}
                                        {{--@else--}}
                                        {{--No File--}}
                                        {{--@endif--}}
                                        <small class="text-muted">Ukuran file maksimal 3 MB.</small>
                                    </div>

                                </div>

                            </div>
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
                            <button type="submit" class="btn btn-primary btn-lg pull-right" onclick="return confirm('Apakah Anda yakin ingin mendaftar? Jika Ya, sistem akan mengirimkan notifikasi approval ke atasan Anda.')"><i
                                        class="fa fa-paper-plane"></i>
                                Submit
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
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