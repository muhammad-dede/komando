@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Create Jadwal CoC Unit</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'coc/create/local', 'files'=>true]) !!}
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
                           role="tab" aria-controls="home" aria-expanded="true">Code of Conduct</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ritual-tab" data-toggle="tab" href="#ritual"
                           role="tab" aria-controls="ritual" aria-expanded="true">PLN Terbaik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ritual-tab" data-toggle="tab" href="#pelanggaran"
                           role="tab" aria-controls="pelanggaran" aria-expanded="true">Pelanggaran Disiplin</a>
                    </li>
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"--}}
                           {{--role="tab" aria-controls="profile">Materi</a>--}}
                    {{--</li>--}}

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                {{--<div class="form-group {{($errors->has('materi_coc'))?'has-danger':''}}">--}}
                                    {{--<label for="tema_id" class="form-control-label">Materi</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::select('materi_id', $materi_list, null, ['class'=>'form-control select2', 'id'=>'materi_id']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('tema'))?'has-danger':''}}">
                                    <label for="tema_id" class="form-control-label">Tema</label>

                                    <div>
                                        {!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('judul_coc'))?'has-danger':''}}">
                                    <label for="judul_coc" class="form-control-label">Judul CoC</label>

                                    <div>
                                        {!! Form::text('judul_coc',null, ['class'=>'form-control form-control-danger', 'id'=>'judul']) !!}
                                    </div>
                                </div>

                                {{--<div class="form-group {{($errors->has('jenis_coc_id'))?'has-danger':''}}">--}}
                                    {{--<label for="jenis_coc_id" class="form-control-label">Level CoC</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::select('jenis_coc_id',$jenis_coc_list, null, ['class'=>'form-control form-control-danger', 'id'=>'jenis_coc_id']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('pernr_leader'))?'has-danger':''}}">
                                    <label for="pernr_leader" class="form-control-label">CoC Leader</label>

                                    <div>
                                        <select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"
                                                style="width: 100% !important; padding: 0; z-index:10000;"></select>
                                    </div>
                                </div>

                                {{--<div class="form-group">--}}
                                    {{--<label for="pemateri_id" class="form-control-label">Tata Nilai</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::text('tata_nilai',null, ['class'=>'form-control', 'id'=>'tata_nilai']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('pedoman_perilaku_id'))?'has-danger':''}}">--}}
                                    {{--<label for="do_dont" class="form-control-label">DOs & DON'Ts</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::select('pedoman_perilaku_id',$do_dont_list, null, ['class'=>'form-control form-control-danger', 'id'=>'do_dont']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('lokasi'))?'has-danger':''}}">
                                    <label for="lokasi" class="form-control-label">Lokasi</label>

                                    <div>
                                        {!! Form::text('lokasi',null, ['class'=>'form-control form-control-danger', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}
                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('tanggal_coc'))?'has-danger':''}}">
                                    <label for="coc_date"
                                           class="form-control-label">Tanggal</label>

                                    <div>

                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-danger" placeholder="dd-mm-yyyy" id="coc_date"
                                                   name="tanggal_coc" value="{{(old('tanggal_coc'))?old('tanggal_coc'):date('d-m-Y')}}">
                                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('jam'))?'has-danger':''}}">
                                    <label for="jam"
                                           class="form-control-label">Jam</label>

                                    <div>

                                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                                             data-autoclose="true">
                                            <input type="text" class="form-control form-control-danger" placeholder="Masukkan Jam" id="jam" name="jam"
                                                value="{{old('jam')}}">
                                            <span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="col-md-5">

                                <div class="form-group">
                                    <label for="company_code" class="form-control-label">Company Code</label>

                                    <div>
                                        @if(Auth::user()->hasRole('root'))
                                        {!! Form::select('company_code', $coCodeList, $cc_selected,
                                            ['class'=>'form-control select2',
                                            'id'=>'company_code']) !!}
                                        @else
                                            {!! Form::select('_company_code', $coCodeList, $cc_selected,
                                            ['class'=>'form-control select2',
                                            'id'=>'company_code', 'disabled']) !!}
                                            {!! Form::hidden('company_code', $cc_selected) !!}
                                        @endif
                                        {{--                                        <p>{{@$coc->company_code}} - {{@$coc->companyCode->description}}</p>--}}
{{--                                        {!! Form::text('company_code',@$coc->company_code.' - '.@$coc->companyCode->description, ['class'=>'form-control', 'id'=>'company_code', 'readonly']) !!}--}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="business_area" class="form-control-label">Business Area</label>

                                    <div>
                                        @if(Auth::user()->hasRole('root'))
                                            {!! Form::select('business_area', $bsAreaList, $ba_selected,
                                                ['class'=>'form-control select2',
                                                'id'=>'business_area']) !!}
                                        @else
                                            {!! Form::select('_business_area', $bsAreaList, $ba_selected,
                                                ['class'=>'form-control select2',
                                                'id'=>'business_area', 'disabled']) !!}
                                            {!! Form::hidden('business_area', $ba_selected) !!}
                                        @endif
                                        {{--                                        <p>{{@$coc->company_code}} - {{@$coc->companyCode->description}}</p>--}}
{{--                                        {!! Form::text('business_area',@$coc->business_area.' - '.@$coc->businessArea->description, ['class'=>'form-control', 'id'=>'business_area', 'readonly']) !!}--}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="orgeh" class="form-control-label">Organisasi</label>

                                    <div>
                                        {!! Form::select('orgeh', $orgehList, $org_selected,
                                            ['class'=>'form-control select2',
                                            'id'=>'orgeh']) !!}
                                        {{--                                        <p>{{@$coc->company_code}} - {{@$coc->companyCode->description}}</p>--}}
                                        {{--{!! Form::text('organisasi',@$coc->orgeh.' - '.@$coc->organisasi->stext, ['class'=>'form-control', 'id'=>'organisasi', 'readonly']) !!}--}}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('jml_peserta'))?'has-danger':''}}">
                                    <label for="jml_peserta"
                                           class="form-control-label">Jumlah Pegawai</label>

                                    <div>

                                        <div>
                                            {!! Form::text('jml_peserta',null, ['class'=>'form-control form-control-danger', 'id'=>'jml_peserta', 'placeholder'=>'']) !!}
                                        </div>

                                    </div>
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<label for="organisasi" class="form-control-label">Status</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::text('status',@$coc->status, ['class'=>'form-control', 'id'=>'status', 'readonly']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--@if(Auth::user()->can('input_coc_local'))--}}
                                    {{--@if($coc->status!='COMP')--}}
                                        {{--<div class="row m-t-30">--}}
                                            {{--<div class="col-md-12">--}}
                                                {{--<a href="javascript:" id="post" type="submit"--}}
                                                   {{--class="btn btn-success btn-lg pull-right waves-effect waves-light"--}}
                                                   {{--data-toggle="modal"--}}
                                                   {{--data-target="#completeModal"><i class="fa fa-flag-checkered"></i>--}}
                                                    {{--&nbsp;Complete CoC</a>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                {{--@endif--}}

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade in p-10" id="ritual" aria-labelledby="ritual-tab">
                        <ul class="nav nav-tabs m-b-10" id="subTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pedoman-tab" data-toggle="tab" href="#pedoman"
                                   role="tab" aria-controls="pedoman" aria-expanded="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history"
                                   role="tab" aria-controls="history">History</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="subTabContent">
                            <div role="tabpanel" class="tab-pane fade in active" id="pedoman"
                                 aria-labelledby="pedoman-tab">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div align="center">
                                            <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="200"
                                                 class="img-responsive" align="center" style="margin-top: 20px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{($errors->has('pedoman_perilaku_id'))?'has-danger':''}}">
                                            <label for="do_dont" class="form-control-label">Pedoman Perilaku *</label>

                                            <div>
                                                {!! Form::select('pedoman_perilaku_id',$do_dont_list, null, ['class'=>'form-control form-control-danger', 'id'=>'do_dont', 'style'=>'width:100%']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{($errors->has('perilaku'))?'has-danger':''}}">
                                                    <h4 for="do" class="form-control-label text-success"><i class="fa fa-thumbs-up"></i> Do</h4>

                                                    <div id="do_list">
                                                        @foreach($list_do as $wa)
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="checkbox{{$wa->id}}" type="checkbox" name="perilaku[]" value="{{$wa->id}}">
                                                                <label for="checkbox{{$wa->id}}">
                                                                    {{$wa->perilaku}}
                                                                </label>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>

                                                <div class="form-group {{($errors->has('perilaku'))?'has-danger':''}}">
                                                    <h4 for="dont" class="form-control-label text-danger"><i class="fa fa-thumbs-down"></i> Don't</h4>

                                                    <div id="dont_list">
                                                        @foreach($list_dont as $wa)
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="checkbox{{$wa->id}}" type="checkbox" name="perilaku[]" value="{{$wa->id}}">
                                                                <label for="checkbox{{$wa->id}}">
                                                                    {{$wa->perilaku}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <small class="text-muted">* Pilih maksimal 2 perilaku (1 Do dan 1 Don't / 2 Do / 2 Don't)</small>
                                            </div>
                                            {{--<div class="col-md-6">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label for="history" class="form-control-label">History</label>--}}

                                            {{--<div id="history">--}}


                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                        </div>

                                    </div>
                                    <div class="col-md-4 form-horizontal">
                                        <div class="form-group m-t-20 {{($errors->has('visi'))?'has-danger':''}}">
                                            <label for="visi" class="form-control-label">Visi</label>

                                            <span>
                                                <input type="checkbox" name="visi" checked data-plugin="switchery" data-color="#039cfd" readonly value="1"/>
                                            </span>
                                            {{--</div>--}}
                                            {{--<div class="form-group {{($errors->has('misi'))?'has-danger':''}}">--}}
                                            <label for="misi" class="form-control-label" style="margin-left: 20px;">Misi</label>

                                            <span>
                                                <input type="checkbox" name="misi" data-plugin="switchery" data-color="#039cfd" value="1"/>
                                            </span>
                                        </div>
                                        <div class="form-group {{($errors->has('tata_nilai'))?'has-danger':''}} m-t-30">
                                            <label for="" class="form-control-label">Tata Nilai</label>
                                        </div>
                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-3">--}}
                                                {{--<img src="{{asset('assets/images/s.png')}}" width="45" class="img-responsive">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="saling_percaya" class="form-control-label">Saling Percaya</label>--}}

                                                    {{--<div>--}}
                                                        {{--<input type="checkbox" name="sipp[]" checked data-plugin="switchery" data-color="#039cfd" value="1"/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-3">--}}
                                                {{--<img src="{{asset('assets/images/i.png')}}" width="45" class="img-responsive">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="integritas" class="form-control-label">Integritas</label>--}}

                                                    {{--<div>--}}
                                                        {{--<input type="checkbox" name="sipp[]" checked data-plugin="switchery" data-color="#039cfd" value="2"/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-3">--}}
                                                {{--<img src="{{asset('assets/images/p.png')}}" width="45" class="img-responsive">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="peduli" class="form-control-label">Peduli</label>--}}

                                                    {{--<div>--}}
                                                        {{--<input type="checkbox" name="sipp[]" checked data-plugin="switchery" data-color="#039cfd" value="3"/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-3">--}}
                                                {{--<img src="{{asset('assets/images/pp.png')}}" width="45" class="img-responsive">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<label for="pembelajar" class="form-control-label">Pembelajar</label>--}}

                                                    {{--<div>--}}
                                                        {{--<input type="checkbox" name="sipp[]" checked data-plugin="switchery" data-color="#039cfd" value="4"/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        <div class="row">
                                            <div class="col-md-12">
                                                {{--                                                <img src="{{asset('assets/images/s.png')}}" width="45" class="img-responsive">--}}
                                                <div class="form-group">
                                                    <label for="saling_percaya" class="form-control-label"
                                                           style="color: #01a3bc; font-size: 20px;">Sinergi</label>

                                                    <div>
                                                        <input type="checkbox" name="sipp[]" checked
                                                               data-plugin="switchery" data-color="#039cfd" value="1"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{--<img src="{{asset('assets/images/i.png')}}" width="45" class="img-responsive">--}}
                                                <div class="form-group">
                                                    <label for="integritas" class="form-control-label"
                                                           style="color: #01a3bc; font-size: 20px;">Profesionalisme</label>

                                                    <div>
                                                        <input type="checkbox" name="sipp[]" checked
                                                               data-plugin="switchery" data-color="#039cfd" value="2"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{--<img src="{{asset('assets/images/p.png')}}" width="45" class="img-responsive">--}}
                                                <div class="form-group">
                                                    <label for="peduli" class="form-control-label"
                                                           style="color: #01a3bc; font-size: 20px;">Berkomitmen pada
                                                        Pelanggan</label>

                                                    <div>
                                                        <input type="checkbox" name="sipp[]" checked
                                                               data-plugin="switchery" data-color="#039cfd" value="3"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    {{--<div class="col-md-2">--}}
                                    {{----}}

                                    {{--</div>--}}
                                    {{--<div class="col-md-2">--}}

                                    {{--</div>--}}
                                </div>


                            </div>
                            <div class="tab-pane fade" id="history" role="tabpanel"
                                 aria-labelledby="history-tab" style="overflow: auto;height: 400px;">
                                {{--<div class="row">--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="org_history" class="form-control-label">Admin CoC</label>--}}

                                {{--<span>--}}
                                {{--{{Auth::user()->name}} ({{Auth::user()->nip}})--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="org_history" class="form-control-label">Organisasi</label>--}}

                                {{--<span id="org_history">--}}
                                {{--Organisasi--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<table class="table table-bordered">--}}
                                <?php //$x=1;?>
                                {{--@foreach($pedoman_list as $pedoman)--}}
                                {{--<tr>--}}
                                {{--<th width="10" rowspan="3">--}}
                                {{--{{$x++}}.--}}
                                {{--</th>--}}
                                {{--<th colspan="2">--}}
                                {{--{{$pedoman->pedoman_perilaku}}--}}
                                {{--</th>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                {{--<th>--}}
                                {{--Do--}}
                                {{--</th>--}}
                                {{--<th>--}}
                                {{--Don't--}}
                                {{--</th>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                {{--<td>--}}
                                {{--<ul>--}}
                                {{--@foreach($pedoman->perilaku()->where('jenis','1')->orderBy('nomor_urut', 'asc')->get() as $perilaku)--}}
                                {{--<li>{!!$perilaku->perilaku!!}</li>--}}
                                {{--@endforeach--}}
                                {{--</ul>--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                {{--<ul>--}}
                                {{--@foreach($pedoman->perilaku()->where('jenis','2')->orderBy('nomor_urut', 'asc')->get() as $perilaku)--}}
                                {{--<li>{!!$perilaku->perilaku!!}</li>--}}
                                {{--@endforeach--}}
                                {{--</ul>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</table>--}}
                            </div>

                        </div>


                    </div>

                    <div role="tabpanel" class="tab-pane fade in p-10" id="pelanggaran" aria-labelledby="pelanggaran-tab">
                        <ul class="nav nav-tabs m-b-10" id="subTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pelanggaran-general-tab" data-toggle="tab" href="#pelanggaran-general"
                                   role="tab" aria-controls="pelanggaran-general" aria-expanded="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pelanggaran-history-tab" data-toggle="tab" href="#history_pelanggaran"
                                   role="tab" aria-controls="history_pelanggaran">History</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="subTabContentPelanggaran">
                            <div role="tabpanel" class="tab-pane fade in active" id="pelanggaran-general"
                                 aria-labelledby="pelanggaran-general-tab">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div align="center">
                                            <img src="{{asset('assets/images/stop.png')}}" width="200"
                                                 class="img-responsive" align="center" style="margin-top: 20px;">
                                        </div>
                                    </div>
                                    <div class="col-md-10" style="overflow: auto;height: 450px;" id="tabel_pelanggaran">
{{--                                        <h3 class="m-t-10">Pelanggaran Disiplin</h3>--}}
                                        <table class="table m-t-10">
                                            <thead>
                                                <tr>
                                                    <th>

                                                    </th>
                                                    <th>
                                                        Pelanggaran
                                                    </th>
                                                    <th>
                                                        Klasifikasi
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($pelanggaran_list as $data)
                                                <tr>
                                                    <td width="20">
                                                        <label class="c-input c-radio">
                                                            <input id="radio_pelanggaran_{{$data->id}}" name="pelanggaran" type="radio" value="{{$data->id}}">
                                                            <span class="c-indicator"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        {{$data->description}}
                                                    </td>
                                                    <td>
                                                        {{$data->jenisPelanggaran->description}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="history_pelanggaran" role="tabpanel"
                                 aria-labelledby="pelanggaran-history-tab" style="overflow: auto;height: 400px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success" id="btn_clear_pelanggaran" onclick="clearHistoryPelanggaran()"><i class="fa fa-trash"></i> Clear History</button>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="org_history" class="form-control-label">Admin CoC</label>

                                            <span>
                                                {{Auth::user()->name}} ({{Auth::user()->nip}})
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="org_history" class="form-control-label">Organisasi</label>

                                            <span id="org_history">
                                                {{$organisasi_selected->stext}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <table class="table m-t-10">
                                    <thead>
                                    <tr>
                                        <th>
                                            No
                                        </th>
                                        <th>
                                            Pelanggaran
                                        </th>
                                        <th>
                                            Klasifikasi
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $x=1;?>
                                    @foreach($pelanggaran_history as $data)
                                        <tr>
                                            <td width="20">
                                                {{$x++}}
                                            </td>
                                            <td>
                                                {{$data->description}}
                                            </td>
                                            <td>
                                                {{$data->jenisPelanggaran->description}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>


                    </div>

                    {{--<div class="tab-pane fade" id="profile" role="tabpanel"--}}
                         {{--aria-labelledby="profile-tab">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-8 p-20">--}}

                                {{--<div class="form-group {{($errors->has('judul_materi'))?'has-danger':''}}">--}}

                                    {{--<div>--}}
                                        {{--{!! Form::text('judul_materi', null, ['class'=>'form-control form-control-danger', 'id'=>'topik', 'placeholder'=>'Judul Materi']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">--}}

                                    {{--<div>--}}
                                        {{--{!! Form::textarea('deskripsi', null, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',--}}
                                                {{--'placeholder'=>'Masukkan Materi CoC', 'rows'=>'20']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4 p-20">--}}
                                {{--<div class="form-group {{($errors->has('pernr_penulis'))?'has-danger':''}}">--}}
                                    {{--<label for="pernr_penulis" class="form-control-label">Penulis</label>--}}

                                    {{--<div>--}}
                                        {{--<select class="itemName form-control" name="pernr_penulis" id="pernr_penulis"--}}
                                                {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group {{($errors->has('materi'))?'has-danger':''}}">--}}
                                    {{--<label for="materi" class="form-control-label">File Materi</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::file('materi', ['class'=>'form-control form-control-danger', 'id'=>'materi']) !!}--}}
                                    {{--</div>--}}
                                    {{--<small class="text-muted">Format file PDF. Ukuran file maksimal 1MB.</small>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>



                <div class="row m-t-20">
                    <div class="col-md-6">
                        {{--<a href="{{url('coc')}}" type="button" class="btn btn-primary btn-lg pull-left">--}}
                            {{--<i class="fa fa-chevron-circle-left"></i> Back</a>--}}
                    </div>
                    <div class="col-md-6 pull-right">
                            <div class="button-list">
                                <button type="button" class="btn btn-warning btn-lg pull-right"
                                        onclick="window.location.href='{{url('coc')}}';"><i
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

            $("#company_code").select2();
            $("#business_area").select2();
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

            ajaxPerilaku();
            ajaxPelanggaran();

            $('#jml_peserta').val('Loading...');

            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-jml-pegawai/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                    // console.log(data);
                    $('#jml_peserta').val(data);
                }
            });

            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-history/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data);
                    $("#history").html(data);
                }
            });

            $("#history_pelanggaran").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-history-pelanggaran')}}'+'/'+$('#orgeh').val(),
                success:function(data){
//                    console.log(data);
                    $("#history_pelanggaran").html(data);
                }
            });

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/coc/ajax-pemateri',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.pa0032.nip+' - '+item.sname,
                                    id: item.pernr
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            tinymce.init({
                selector: '#deskripsi', height: 500,
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

        $('#do_dont').change(function(){
//            console.log('ok');
            ajaxPerilaku();
        })

        $('#orgeh').change(function(){
            ajaxPerilaku();
            ajaxPelanggaran();
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-history/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                   // console.log(data);
                    $("#history").html(data);
                }
            });
            $("#history_pelanggaran").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-history-pelanggaran/')}}'+'/'+$('#orgeh').val(),
                success:function(data){
//                    console.log(data);
                    $("#history_pelanggaran").html(data);
                }
            });

            $('#jml_peserta').val('Loading...');

            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-jml-pegawai/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                    // console.log(data);
                   $('#jml_peserta').val(data);
                }
            });
        });

        function ajaxPerilaku(){
            $("#do_list").html('Loading...');
            $("#dont_list").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-perilaku/')}}'+'/'+$('#do_dont').val()+'/'+$('#orgeh').val()+'/1',
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                    // console.log(data);
//                    $('#total_daya_terpasang').val(data.total_daya_terpasang);
//                    $('#unit_max').val(data.unit_max);
//                    $('#dmn').val(data.dmn);
                    $("#do_list").html(data);
                }
            });

            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-perilaku/')}}'+'/'+$('#do_dont').val()+'/'+$('#orgeh').val()+'/2',
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data);
//                    $('#total_daya_terpasang').val(data.total_daya_terpasang);
//                    $('#unit_max').val(data.unit_max);
//                    $('#dmn').val(data.dmn);
                    $("#dont_list").html(data);
                }
            });
        }

        function ajaxPelanggaran(){
            $("#tabel_pelanggaran").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-pelanggaran/')}}'+'/'+$('#orgeh').val(),
                success:function(data){
                    // console.log(data);
                    $("#tabel_pelanggaran").html(data);
                }
            });
        }

        function clearHistory(){
//            console.log('ok');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/clear-history/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data);
                    swal({
                        title: "Success!",
                        text: "History Cleared",
                        type: "success",
                        showCancelButton: false,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: 'OK',
                    });
//                    $("#history").html(data);
                    ajaxPerilaku();
                    $.ajax({
                        type:'GET',
                        url:'{{url('ajax/get-history/')}}'+'/'+$('#orgeh').val(),
                        //data:'_token = <?php echo csrf_token() ?>',
                        success:function(data){
//                    console.log(data);
                            $("#history").html(data);
                        }
                    });
                }
            });
        }

        function clearHistoryPelanggaran(){
//            console.log('ok');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/clear-history-pelanggaran/')}}'+'/'+$('#orgeh').val(),
                //data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
//                    console.log(data);
                    swal({
                        title: "Success!",
                        text: "History Cleared",
                        type: "success",
                        showCancelButton: false,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: 'OK',
                    });
//                    $("#history").html(data);
                    ajaxPelanggaran();
                    $("#history_pelanggaran").html('Loading...');
                    $.ajax({
                        type:'GET',
                        url:'{{url('ajax/get-history-pelanggaran/')}}'+'/'+$('#orgeh').val(),
                        //data:'_token = <?php echo csrf_token() ?>',
                        success:function(data){
//                    console.log(data);
                            $("#history_pelanggaran").html(data);
                        }
                    });
                }
            });
        }

    </script>
@stop