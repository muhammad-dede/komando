@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Registrasi Employee Volunteer Program</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'evp/register/', 'files'=>true]) !!}
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
                           role="tab" aria-controls="home" aria-expanded="true">Volunteer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen"
                           role="tab" aria-controls="dokumen" aria-expanded="true">Dokumen Persyaratan</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active p-20" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">

                                {{--<div class="form-group {{($errors->has('tema'))?'has-danger':''}}">--}}
                                    {{--<label for="tema_id" class="form-control-label">Tema</label>--}}

                                    {{--<div>--}}
                                        {{--{!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'form-control select2', 'id'=>'tema_id']) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group {{($errors->has('judul_coc'))?'has-danger':''}}">
                                    <label for="judul_coc" class="form-control-label">Judul CoC</label>

                                    <div>
                                        {!! Form::text('judul_coc',null, ['class'=>'form-control form-control-danger', 'id'=>'judul']) !!}
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('pernr_leader'))?'has-danger':''}}">
                                    <label for="pernr_leader" class="form-control-label">CoC Leader</label>

                                    <div>
                                        <select class="itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"
                                                style="width: 100% !important; padding: 0; z-index:10000;"></select>
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
                                <h4>Statistik CoC</h4>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah menghadiri CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah tugas di luar kantor ketika CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah ijin ketika CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah sakit ketika CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah membaca materi CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="jml_hadir"
                                           class="col-sm-5 form-control-label">Jumlah menjadi leader CoC</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="jml_hadir" value="1000" style="text-align:right;">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-10" id="dokumen" aria-labelledby="dokumen-tab">
                        <div class="row">
                            <div class="col-md-12">
                                File Surat Pernyataan
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


        });



    </script>
@stop