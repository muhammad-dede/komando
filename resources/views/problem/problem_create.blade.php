@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

@stop

@section('title')
    <h4 class="page-title">Report Problem</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'report/problem/create', 'files'=>true]) !!}
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
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group {{($errors->has('nama'))?'has-danger':''}}">
                                    <label for="nama" class="form-control-label">Nama</label>
                                    <div>
                                        {!! Form::text('nama', null, ['class'=>'form-control form-control-danger', 'id'=>'nama', 'placeholder'=>'Nama Pegawai']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{($errors->has('nip'))?'has-danger':''}}">
                                    <label for="nip" class="form-control-label">NIP</label>
                                    <div>
                                        {!! Form::text('nip', null, ['class'=>'form-control form-control-danger', 'id'=>'nip', 'placeholder'=>'NIP Pegawai']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{($errors->has('unit'))?'has-danger':''}}">
                            <label for="unit" class="form-control-label">Unit/Area</label>
                            <div>
                                {!! Form::text('unit', null, ['class'=>'form-control form-control-danger', 'id'=>'unit', 'placeholder'=>'Unit/Area']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('username'))?'has-danger':''}}">
                                    <label for="username" class="form-control-label">User ESS</label>
                                    <div>
                                        {!! Form::text('username', null, ['class'=>'form-control form-control-danger', 'id'=>'username', 'placeholder'=>'Domain\Username']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('email'))?'has-danger':''}}">
                                    <label for="email" class="form-control-label">Email Korporat</label>
                                    <div>
                                        {!! Form::text('email', null, ['class'=>'form-control form-control-danger', 'id'=>'email', 'placeholder'=>'email@pln.co.id']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('grup_id'))?'has-danger':''}}">
                                    <label for="grup_id" class="form-control-label">Kategori Masalah</label>
                                    <div>
                                        {{--                                        {!! Form::text('email_korporat', null, ['class'=>'form-control form-control-danger', 'id'=>'email_korporat', 'placeholder'=>'email@pln.co.id']) !!}--}}
                                        <select class="form-control" id="grup_id" name="grup_id">
                                            @foreach($list_grup as $grup)
                                                <option value="{{$grup->id}}">{{$grup->masalah}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('server_id'))?'has-danger':''}}">
                                    <label for="server_id" class="form-control-label">Server</label>
                                    <div>
                                        <select class="form-control" id="server_id" name="server_id">
                                            <option value="1">Production</option>
                                            <option value="2">Training</option>
                                        </select>
{{--                                        {!! Form::text('server_id', null, ['class'=>'form-control form-control-danger', 'id'=>'user_ess', 'placeholder'=>'Domain\Username']) !!}--}}
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">
                            <label for="topik" class="form-control-label">Deskripsi Masalah</label>

                            <div>
                                {!! Form::textarea('deskripsi', null, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',
                                        'placeholder'=>'Masukkan deskripsi masalah', 'rows'=>'10']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-20">
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
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="business_area" class="form-control-label">Business Area</label>

                            <div>
                                {{--@if(Auth::user()->hasRole('root'))--}}
                                    {!! Form::select('business_area', $bsAreaList, $ba_selected,
                                        ['class'=>'form-control select2',
                                        'id'=>'business_area']) !!}
                                {{--@else--}}
                                    {{--{!! Form::select('_business_area', $bsAreaList, $ba_selected,--}}
                                        {{--['class'=>'form-control select2',--}}
                                        {{--'id'=>'business_area', 'disabled']) !!}--}}
                                    {{--{!! Form::hidden('business_area', $ba_selected) !!}--}}
                                {{--@endif--}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kejadian" class="form-control-label">Tanggal kejadian</label>
                            <div>

                                <div class="input-group {{($errors->has('tgl_kejadian'))?'has-danger':''}}">
                                    <input type="text" class="form-control form-control-danger" placeholder="dd-mm-yyyy" id="tgl_kejadian"
                                           name="tgl_kejadian" value="{{date('d-m-Y')}}">
                                    <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                </div>

                                <!-- input-group -->
                            </div>
                        </div>
                        {{--<div class="form-group {{($errors->has('pernr_penulis'))?'has-danger':''}}">--}}
                            {{--<label for="pemateri_id" class="form-control-label">Pemateri</label>--}}

                            {{--<div>--}}
                                {{--<select class="itemName form-control form-control-danger" name="pernr_penulis"--}}
                                        {{--style="width: 100% !important; padding: 0; z-index:10000;"></select>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group {{($errors->has('materi'))?'has-danger':''}}">
                            <label for="foto" class="form-control-label">Evidence</label>

                            <div>
                                {!! Form::file('foto', ['class'=>'form-control form-control-danger', 'id'=>'foto']) !!}
                            </div>
                            <small class="text-muted">Format file gambar (JPG). Ukuran file maksimal 1MB.</small>
                        </div>

                        {{--@if($scope=='local')--}}
                            {{--<div class="form-group">--}}
                                {{--<label for="lokasi" class="form-control-label">Lokasi</label>--}}

                                {{--<div>--}}
                                    {{--{!! Form::text('lokasi', null, ['class'=>'form-control', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <div class="button-list">
                                    <button type="button" class="btn btn-warning btn-lg pull-right"
                                            onclick="window.location.href='{{url('report/problem')}}';"><i
                                                class="fa fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg pull-right"><i
                                                class="fa fa-send"></i>
                                        Send
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


    <!-- Autocomplete -->

    <script>
        $(document).ready(function () {

            $("#tema_id").select2();
//            $("#server_id").select2();
//            $("#grup_id").select2();
            $("#company_code").select2();
            $("#business_area").select2();
            jQuery('#tgl_kejadian').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#date-range').datepicker({
                toggleActive: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
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
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });

        });

    </script>

@stop