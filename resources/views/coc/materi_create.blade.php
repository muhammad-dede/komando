@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

@stop

@section('title')
    <h4 class="page-title">Create Materi {{($scope=='unit')? 'General Manager' : ucfirst($scope)}}</h4>
@stop

@section('content')
    {{--<div class="row">--}}
    {{--<div class="col-md-1">--}}
    {{--ID--}}
    {{--</div>--}}
    {{--<div class="col-md-2">--}}
    {{--{!! Form::text('system_id','%00000000001',['class'=>'form-control','readonly']) !!}--}}
    {{--</div>--}}
    {{--<div class="col-md-2">--}}
    {{--{!! Form::text('system_status','CRTD',['class'=>'form-control','readonly']) !!}--}}
    {{--</div>--}}
    {{--<div class="col-md-4">--}}
    {{----}}
    {{--</div>--}}
    {{--</div>--}}
    {!! Form::open(['url'=>'coc/create/materi/'.$scope, 'files'=>true]) !!}
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
                {{--<h4>Tema : Judul Tema</h4>--}}
                <div class="row">
                    <div class="col-md-8 p-20">

                        <div class="form-group {{($errors->has('judul'))?'has-danger':''}}">
                            {{--<label for="topik" class="form-control-label">Judul CoC</label>--}}

                            <div>
                                    {!! Form::text('judul', null, ['class'=>'form-control form-control-danger', 'id'=>'topik', 'placeholder'=>'Judul Materi']) !!}
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
                                        'placeholder'=>'Masukkan Deskripsi CoC', 'rows'=>'20']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 p-20">
                        @if($scope=='nasional')
                        {{-- <div class="form-group {{($errors->has('energize_day'))?'has-danger':''}}" align="left">


                            <div class="switchery-demo">
                                <label for="materi" class="form-control-label">Energize Day</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#039cfd" value="1" name="energize_day" id="energize_day"/>
                            </div>

                        </div> --}}

                        <div class="form-group">
                            <label for="jenis_materi" class="form-control-label">Jenis Materi</label>

                            <div>
                                <select class="select2 form-control" name="jenis_materi" id="jenis_materi">
                                    <option value="1">Nasional</option>
                                    <option value="2">Energize Day</option>
                                    <option value="3">Rubrik Transformasi</option>
                                </select>
                            </div>
                        </div>

                        @endif

                        <div class="form-group">
                            <label for="tanggal_coc"
                            class="form-control-label">Tanggal</label>

                            <div>
                                {{--<input type="text" class="form-control" id="tanggal" name="tanggal"--}}
                                {{--placeholder="Tanggal">--}}

                                <div class="input-group {{($errors->has('tanggal_coc'))?'has-danger':''}}">
                                    <input type="text" class="form-control form-control-danger" placeholder="dd-mm-yyyy" id="coc_date"
                                           name="tanggal_coc" value="{{date('d-m-Y')}}">
                                    <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                </div>

                                <!-- input-group -->
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                        {{--<label for="jam"--}}
                        {{--class="form-control-label">Jam</label>--}}

                        {{--<div>--}}

                        {{--<div class="input-group clockpicker" data-placement="top" data-align="top"--}}
                        {{--data-autoclose="true">--}}
                        {{--<input type="text" class="form-control" placeholder="Masukkan Jam" id="jam" name="jam">--}}
                        {{--<span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>--}}
                        {{--</div>--}}

                        {{--</div>--}}
                        {{--</div>--}}
                        {{--@if($scope == 'unit' || $scope=='local')--}}

                        <div class="form-group {{($errors->has('pernr_penulis'))?'has-danger':''}}" id="penulis">
                            <label for="pernr_penulis" class="form-control-label">Penulis</label>

                            <div>
                                <select class="itemName form-control form-control-danger" name="pernr_penulis"
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
                            <label for="materi" class="form-control-label">Attachment</label>

                            <div>
                                {!! Form::file('materi', ['class'=>'form-control form-control-danger', 'id'=>'materi']) !!}
                            </div>
                            <small class="text-muted">Format file PDF. Ukuran file maksimal 5MB.</small>
                        </div>

                        {{--<div class="form-group">--}}
                        {{--<label for="business_area" class="form-control-label">Business Area</label>--}}

                        {{--<div>--}}
                        {{--{!! Form::select('business_area', $bsAreaList, $ba_selected,--}}
                        {{--['class'=>'form-control select2',--}}
                        {{--'id'=>'business_area']) !!}--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        @if($scope=='local')
                            <div class="form-group">
                                <label for="lokasi" class="form-control-label">Lokasi</label>

                                <div>
                                    {!! Form::text('lokasi', null, ['class'=>'form-control', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}
                                </div>
                            </div>
                        @endif
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
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <div class="button-list">
                                    <button type="button" class="btn btn-warning btn-lg pull-right"
                                            onclick="window.location.href='{{url('coc')}}';"><i
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
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('javascript')
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
    <script src="{{asset('vendor/summernote/summernote.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    {{--<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5xdx03sf9su4xu54au8ldf6x928x1chd5qqjyfg2blmqi15q"></script>--}}

    <!-- Autocomplete -->
    {{--<script type="text/javascript" src="{{asset('assets/plugins/autocomplete/jquery.mockjax.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets/plugins/autocomplete/jquery.autocomplete.min.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets/plugins/autocomplete/countries.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{asset('assets/pages/jquery.autocomplete.init.js')}}"></script>--}}

    <script>

        $(document).ready(function () {

            {{-- $('#energize_day').on("change", function () {
                // console.log($('#energize_day').prop('checked'));
                if($('#energize_day').prop('checked')){
                    $('#penulis').hide();
                }
                else{
                    $('#penulis').show();
                }
            }); --}}


            $("#jenis_materi").select2();

            $('#jenis_materi').on("change", function () {
                 //console.log($('#jenis_materi').val());
                if($('#jenis_materi').val()=='2'){
                    $('#penulis').hide();
                }
                else{
                    $('#penulis').show();
                }
            });

            $("#tema_id").select2();
            jQuery('#coc_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#date-range').datepicker({
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
//            $('#jam').clockpicker({
//                placement: 'bottom',
//                align: 'left',
//                autoclose: true,
//                'default': 'now'
//            });
            $('.clockpicker').clockpicker({
                donetext: 'Done'
            });

            $('.itemName').select2({
                placeholder: 'Select Penulis',
                ajax: {
                    url: '/coc/ajax-pemateri',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
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
                // content_css: [
                //     '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                //     '//www.tinymce.com/css/codepen.min.css']
            });

        });

    </script>

@stop