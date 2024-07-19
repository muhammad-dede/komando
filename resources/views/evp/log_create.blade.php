@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Create Activity Log : {{$evp->nama_kegiatan}}</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'/evp/log/create/'.$volunteer->id, 'files'=>true]) !!}
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
                           role="tab" aria-controls="home" aria-expanded="true">Activity</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen"
                           role="tab" aria-controls="dokumen" aria-expanded="true">Foto Kegiatan</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">
                                <div class="form-group {{($errors->has('aktivitas'))?'has-danger':''}}" style="">
                                    <h5 for="aktivitas" class="form-control-label"><i class="fa fa-edit" style="font-size: 25px;"></i> Deskripsi Kegiatan <span class="text-danger">*</span></h5>

                                    <div style="padding: 10px 10px 10px 10px;">
                                        {!! Form::textarea('aktivitas', null, ['class'=>'form-control form-control-danger', 'id'=>'aktivitas',
                                        'placeholder'=>'Masukkan deskripsi kegiatan', 'rows'=>'20']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group {{($errors->has('tanggal') || $errors->has('jam'))?'has-danger':''}}">

                                    <label for="tanggal" class="form-control-label"><i class="fa fa-calendar"></i> Tanggal, Jam <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="input-group col-md-6">
                                            {!! Form::text('tanggal',null, ['class'=>'form-control form-control-danger', 'id'=>'tanggal', 'placeholder'=>'dd-mm-yyyy', 'autocomplete'=>'off']) !!}
                                                <span class="input-group-addon bg-custom b-0"><i
                                                            class="icon-calender"></i></span>
                                        </div>

                                        <div class="input-group clockpicker col-md-6" data-placement="top"
                                             data-align="top"
                                             data-autoclose="true">
                                            {!! Form::text('jam',null, ['class'=>'form-control form-control-danger', 'id'=>'jam', 'placeholder'=>'Masukkan Jam', 'autocomplete'=>'off']) !!}
                                                <span class="input-group-addon"> <span
                                                            class="zmdi zmdi-time"></span> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('lokasi'))?'has-danger':''}}">
                                    <label for="lokasi" class="form-control-label"><i class="fa fa-map-marker"></i> Lokasi <span class="text-danger">*</span></label>

                                    <div>
                                        {!! Form::text('lokasi',null, ['class'=>'form-control form-control-danger', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in p-20" id="dokumen" aria-labelledby="dokumen-tab">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group {{($errors->has('foto_1'))?'has-danger':''}}">
                                    <label for="foto_1" class="form-control-label"><i class="fa fa-photo"></i> Foto <span class="text-danger">*</span></label>

                                    <div style="padding-left: 30px;">
                                        {!! Form::file('foto_1', ['class'=>'form-control form-control-danger', 'id'=>'foto_1']) !!}
                                        <small class="text-muted">Ukuran foto maksimal 3 MB.</small>
                                    </div>

                                </div>

                                {{--<div class="form-group {{($errors->has('foto_2'))?'has-danger':''}}">--}}
                                    {{--<label for="foto_2" class="form-control-label"><i class="fa fa-photo"></i> Foto 2 </label>--}}

                                    {{--<div style="padding-left: 30px;">--}}
                                        {{--{!! Form::file('foto_2', ['class'=>'form-control form-control-danger', 'id'=>'foto_2']) !!}--}}
                                        {{--<small class="text-muted">Ukuran foto maksimal 3 MB.</small>--}}
                                    {{--</div>--}}

                                {{--</div>--}}

                                {{--<div class="form-group {{($errors->has('foto_3'))?'has-danger':''}}">--}}
                                    {{--<label for="foto_3" class="form-control-label"><i class="fa fa-photo"></i> Foto 3 </label>--}}

                                    {{--<div style="padding-left: 30px;">--}}
                                        {{--{!! Form::file('foto_3', ['class'=>'form-control form-control-danger', 'id'=>'foto_3']) !!}--}}
                                        {{--<small class="text-muted">Ukuran foto maksimal 3 MB.</small>--}}
                                    {{--</div>--}}

                                {{--</div>--}}

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
                                    onclick="window.location.href='{{url('evp/log/list/'.$volunteer->id)}}';"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i
                                        class="fa fa-save"></i>
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

//            $("#company_code").select2();
//            $("#business_area").select2();
            $("#orgeh").select2();
//            $("#materi_id").select2();
            $("#tema_id").select2();
            jQuery('#tanggal').datepicker({
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
                selector: '#aktivitas', height: 250,
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

//            tinymce.init({
//                selector: '#answer_tepat', height: 250,
//                menubar: false,
//                plugins: [
//                    'advlist autolink lists link image charmap print preview anchor',
//                    'searchreplace visualblocks code fullscreen',
//                    'insertdatetime media table contextmenu paste code'
//                ],
//                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
//                content_css: [
//                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
//                    '//www.tinymce.com/css/codepen.min.css']
//            });

        });


    </script>
@stop