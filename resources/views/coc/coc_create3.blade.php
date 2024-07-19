@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Create Jadwal CoC</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'coc/create/kantor-induk', 'files'=>true]) !!}
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
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="profile">Materi</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group {{($errors->has('tema_coc'))?'has-danger':''}}">
                                    <label for="tema_id" class="form-control-label">Tema</label>

                                    <div>
                                        {!! Form::select('tema_id_unit', $tema_list, null, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}
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

                                <div class="form-group {{($errors->has('pedoman_perilaku_id'))?'has-danger':''}}">
                                    <label for="do_dont" class="form-control-label">DOs & DON'Ts</label>

                                    <div>
                                        {!! Form::select('pedoman_perilaku_id',$do_dont_list, null, ['class'=>'form-control form-control-danger', 'id'=>'do_dont']) !!}
                                    </div>
                                </div>

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
                                <div class="form-group {{($errors->has('jml_peserta'))?'has-danger':''}}">
                                    <label for="jml_peserta"
                                           class="form-control-label">Perkiraan Jumlah Peserta</label>

                                    <div>

                                        <div>
                                            {!! Form::text('jml_peserta',null, ['class'=>'form-control form-control-danger', 'id'=>'jml_peserta', 'placeholder'=>'Jumlah Peserta']) !!}
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="col-md-6">

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
                    <div class="tab-pane fade" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-8 p-20">

                                <div class="form-group {{($errors->has('judul_materi'))?'has-danger':''}}">
                                    {{--<label for="topik" class="form-control-label">Judul CoC</label>--}}

                                    <div>
                                        {!! Form::text('judul_materi', null, ['class'=>'form-control form-control-danger', 'id'=>'topik', 'placeholder'=>'Judul Materi']) !!}
                                    </div>
                                </div>
                                {{--<div class="form-group">--}}
                                {{--<label for="tanggal"--}}
                                {{--class="form-control-label">Tema</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('tema', null, ['class'=>'form-control', 'id'=>'tema', 'placeholder'=>'Tema CoC']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">
                                    {{--<label for="topik" class="form-control-label">Deskripsi</label>--}}

                                    <div>
                                        {!! Form::textarea('deskripsi', null, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',
                                                'placeholder'=>'Masukkan Materi CoC', 'rows'=>'20']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 p-20">
                                {{--@if($scope == 'unit' || $scope=='local')--}}
                                <div class="form-group {{($errors->has('pernr_penulis'))?'has-danger':''}}">
                                    <label for="pernr_penulis" class="form-control-label">Penulis</label>

                                    <div>
                                        <select class="itemName form-control" name="pernr_penulis" id="pernr_penulis"
                                                style="width: 100% !important; padding: 0; z-index:10000;"></select>
                                    </div>
                                </div>
                                {{--@endif--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="pemateri_id" class="form-control-label">Pemateri</label>--}}

                                {{--<div>--}}
                                {{--<select class="itemName form-control" name="pernr_penulis"--}}
                                {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="pemateri_id" class="form-control-label">Pemateri</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('tema_id', $tema_list, null, ['class'=>'form-control select2', 'id'=>'tema_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}--}}
                                {{--<select class="itemName form-control" name="tema_id"--}}
                                {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="pemateri_id" class="form-control-label">Pemateri</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('jenis_materi_id', $jenis_list, null, ['class'=>'form-control select2', 'id'=>'jenis_materi_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}--}}
                                {{--<select class="itemName form-control" name="tema_id"--}}
                                {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group {{($errors->has('materi'))?'has-danger':''}}">
                                    <label for="materi" class="form-control-label">File Materi</label>

                                    <div>
                                        {!! Form::file('materi', ['class'=>'form-control form-control-danger', 'id'=>'materi']) !!}
                                    </div>
                                    <small class="text-muted">Format file PDF. Ukuran file maksimal 1MB.</small>
                                </div>

                                {{--<div class="form-group">--}}
                                {{--<label for="business_area" class="form-control-label">Business Area</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::select('business_area', $bsAreaList, $ba_selected,--}}
                                {{--['class'=>'form-control select2',--}}
                                {{--'id'=>'business_area']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--@if($scope=='local')--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="lokasi" class="form-control-label">Lokasi</label>--}}

                                        {{--<div>--}}
                                            {{--{!! Form::text('lokasi', null, ['class'=>'form-control', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                                {{--<div class="form-group" style="margin-top: 50px;">--}}
                                {{--<label for="jam"--}}
                                {{--class="form-control-label">Company Code</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('company_code', null, ['class'=>'form-control', 'id'=>'company_code', 'placeholder'=>'Company Code']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="pemateri_id" class="form-control-label">Business Area</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('business_area', null, ['class'=>'form-control', 'id'=>'business_area', 'placeholder'=>'Business Area']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="topik" class="form-control-label">Organisasi</label>--}}

                                {{--<div>--}}
                                {{--{!! Form::text('orgeh', null, ['class'=>'form-control', 'id'=>'orgeh', 'placeholder'=>'Organisasi']) !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-12 pull-right">--}}
                                        {{--<div class="button-list">--}}
                                            {{--<button type="button" class="btn btn-warning btn-lg pull-right"--}}
                                                    {{--onclick="window.location.href='{{url('coc')}}';"><i--}}
                                                        {{--class="fa fa-times"></i> Cancel--}}
                                            {{--</button>--}}
                                            {{--<button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>--}}
                                                {{--Save--}}
                                            {{--</button>--}}

                                        {{--</div>--}}

                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
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
            $("#tema_id").select2();
            jQuery('#coc_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            $('.clockpicker').clockpicker({
                donetext: 'Done'
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
                                    text: item.nip+' - '+item.cname,
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

    </script>
@stop