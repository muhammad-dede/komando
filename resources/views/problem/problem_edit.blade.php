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
    {!! Form::open(['url'=>'report/problem/'.$problem->id, 'files'=>true]) !!}
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
                <div class="row m-b-15">
                    <div class="col-md-2">
                            <label for="id" class="form-control-label">ID</label>
                            <div>
                                {!! Form::text('id', str_pad($problem->id,8,'0', STR_PAD_LEFT), ['class'=>'form-control form-control-danger', 'id'=>'id', 'placeholder'=>'ID', 'readonly']) !!}
                            </div>
                    </div>
                    <div class="col-md-2">
                            <label for="status" class="form-control-label">Status</label>
                            <div>
                                {{--@if($problem->status=='1')--}}
                                    {{--<span class="label label-danger">{{$problem->statusProblem->status}}</span>--}}
                                {{--@elseif($problem->status=='2')--}}
                                    {{--<span class="label label-warning">{{$problem->statusProblem->status}}</span>--}}
                                {{--@elseif($problem->status=='3')--}}
                                    {{--<span class="label label-success">{{$problem->statusProblem->status}}</span>--}}
                                {{--@else--}}
                                    {{--<span class="label label-primary">{{$problem->statusProblem->status}}</span>--}}
                                {{--@endif--}}
                                {!! Form::text('status', strtoupper($problem->statusProblem->status), ['class'=>'form-control form-control-danger', 'id'=>'status', 'placeholder'=>'Status', 'readonly']) !!}
                            </div>

                    </div>
                    <div class="col-md-8">
                        {{--<label for="status" class="form-control-label">Status</label>--}}
                        <div>
                            <div class="button-list">
                                <button type="button" class="btn btn-warning pull-right"
                                        onclick="window.location.href='{{url('report/problem')}}';"><i
                                            class="fa fa-times"></i> Cancel
                                </button>
                                @if($problem->status!=4)
                                <a href="javascript:" class="btn btn-success pull-right"
                                   title="Problem solved"
                                   onclick="javascript:closeReport('{{$problem->id}}')"><i
                                            class="fa fa-check"></i>
                                    Problem Solved
                                </a>
                                <button type="submit" class="btn btn-primary pull-right"><i
                                            class="fa fa-save"></i>
                                    Update Report
                                </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="problem-tab" data-toggle="tab" href="#problem"
                           role="tab" aria-controls="problem" aria-expanded="true">Problem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="resolution-tab" data-toggle="tab" href="#resolution"
                           role="tab" aria-controls="resolution">Resolution</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="problem"
                         aria-labelledby="problem-tab">
                        <div class="row">
                            <div class="col-md-8 p-20">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group {{($errors->has('nama'))?'has-danger':''}}">
                                            <label for="nama" class="form-control-label">Nama</label>
                                            <div>
                                                {!! Form::text('nama', $problem->nama, ['class'=>'form-control form-control-danger', 'id'=>'nama', 'placeholder'=>'Nama Pegawai']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group {{($errors->has('nip'))?'has-danger':''}}">
                                            <label for="nip" class="form-control-label">NIP</label>
                                            <div>
                                                {!! Form::text('nip', $problem->nip, ['class'=>'form-control form-control-danger', 'id'=>'nip', 'placeholder'=>'NIP Pegawai']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{($errors->has('unit'))?'has-danger':''}}">
                                    <label for="unit" class="form-control-label">Unit/Area</label>
                                    <div>
                                        {!! Form::text('unit', $problem->unit, ['class'=>'form-control form-control-danger', 'id'=>'unit', 'placeholder'=>'Unit/Area']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{($errors->has('username'))?'has-danger':''}}">
                                            <label for="username" class="form-control-label">User ESS</label>
                                            <div>
                                                {!! Form::text('username', $problem->username, ['class'=>'form-control form-control-danger', 'id'=>'username', 'placeholder'=>'Domain\Username']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{($errors->has('email'))?'has-danger':''}}">
                                            <label for="email" class="form-control-label">Email Korporat</label>
                                            <div>
                                                {!! Form::text('email', $problem->email, ['class'=>'form-control form-control-danger', 'id'=>'email', 'placeholder'=>'email@pln.co.id']) !!}
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
                                                        <option value="{{$grup->id}}" {{($problem->grup_id==$grup->id)?'selected':''}}>{{$grup->masalah}}</option>
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
                                                    <option value="1" {{($problem->server_id=='1')?'selected':''}}>Production</option>
                                                    <option value="2" {{($problem->server_id=='2')?'selected':''}}>Training</option>
                                                </select>
                                                {{--                                        {!! Form::text('server_id', null, ['class'=>'form-control form-control-danger', 'id'=>'user_ess', 'placeholder'=>'Domain\Username']) !!}--}}
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group {{($errors->has('deskripsi'))?'has-danger':''}}">
                                    <label for="topik" class="form-control-label">Deskripsi Masalah</label>

                                    <div>
                                        {!! Form::textarea('deskripsi', $problem->deskripsi, ['class'=>'form-control form-control-danger', 'id'=>'deskripsi',
                                                'placeholder'=>'Masukkan deskripsi masalah', 'rows'=>'10']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 p-20">
                                <div class="form-group">
                                    <label for="tgl_kejadian" class="form-control-label">Case Owner</label>
                                    <div>
                                        <?php $caseOwner = $problem->caseOwner;?>
                                        <div class="input-group">
                                            {!! Form::text('case_owner', @$caseOwner->name.' / '.@$caseOwner->nip, ['class'=>'form-control form-control-danger', 'id'=>'case_owner', 'placeholder'=>'Case Owner', 'readonly']) !!}
                                            
                                        </div>
                                        @if(Auth::user()->hasRole('root'))
                                        <a href="{{url('impersonation/'.@$caseOwner->id)}}" class="btn btn-primary btn-sm m-t-10" target="blank"><i class="fa fa-external-link"></i> Impersonate</a>
                                        @endif
                                        <!-- input-group -->
                                    </div>
                                </div>
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
                                                   name="tgl_kejadian" value="{{($problem->tgl_kejadian!='')?$problem->tgl_kejadian->format('d-m-Y'):''}}">
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
                                        @if($problem->foto!='')
                                            <a href="{{url('report/problem/foto/'.$problem->id)}}" target="blank"><img src="{{url('report/problem/foto/'.$problem->id)}}" class="img-responsive img-thumbnail"></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="resolution" role="tabpanel"
                         aria-labelledby="resolution-tab">
                        <div class="row">
                            <div class="col-md-6 p-20">
                                <div class="form-group {{($errors->has('konfirmasi'))?'has-danger':''}}">
                                    <label for="konfirmasi" class="form-control-label">Konfirmasi</label>

                                    <div>
                                        {!! Form::textarea('konfirmasi', $problem->konfirmasi, ['class'=>'form-control form-control-danger', 'id'=>'konfirmasi',
                                                'placeholder'=>'Masukkan konfirmasi permasalahan', 'rows'=>'10']) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 p-20">
                                @if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group {{($errors->has('status'))?'has-danger':''}}">
                                            <label for="status" class="form-control-label">Status</label>
                                            <div>
                                                {{--                                        {!! Form::text('email_korporat', null, ['class'=>'form-control form-control-danger', 'id'=>'email_korporat', 'placeholder'=>'email@pln.co.id']) !!}--}}
                                                <select class="form-control" id="status" name="status">
                                                    @foreach($list_status as $status)
                                                        <option value="{{$status->id}}" {{($problem->status==$status->id)?'selected':''}}>{{$status->status}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="col-md-6">--}}
                                        {{--<div class="form-group {{($errors->has('server_id'))?'has-danger':''}}">--}}
                                            {{--<label for="server_id" class="form-control-label">Server</label>--}}
                                            {{--<div>--}}
                                                {{--<select class="form-control" id="server_id" name="server_id">--}}
                                                    {{--<option value="1" {{($problem->server_id=='1')?'selected':''}}>Production</option>--}}
                                                    {{--<option value="2" {{($problem->server_id=='2')?'selected':''}}>Training</option>--}}
                                                {{--</select>--}}
                                                {{--                                        {!! Form::text('server_id', null, ['class'=>'form-control form-control-danger', 'id'=>'user_ess', 'placeholder'=>'Domain\Username']) !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                </div>
                                @endif


                                <div class="form-group {{($errors->has('cause'))?'has-danger':''}}">
                                    <label for="cause" class="form-control-label">Cause</label>

                                    <div>
                                        @if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat'))
                                        {!! Form::textarea('cause', $problem->cause, ['class'=>'form-control form-control-danger', 'id'=>'cause',
                                                'placeholder'=>'Masukkan sumber masalah', 'rows'=>'5']) !!}
                                        @else
                                            {!! $problem->cause !!}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{($errors->has('resolution'))?'has-danger':''}}">
                                    <label for="resolution_text" class="form-control-label">Resolution</label>

                                    <div>

                                        @if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat'))
                                            {!! Form::textarea('resolution', $problem->resolution, ['class'=>'form-control form-control-danger', 'id'=>'resolution_text',
                                                'placeholder'=>'Masukkan resolusi', 'rows'=>'5']) !!}
                                        @else
                                            {!! $problem->resolution !!}
                                        @endif
                                    </div>
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

            tinymce.init({
                selector: '#konfirmasi', height: 500,
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
                selector: '#cause', height: 250,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
//                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });

            tinymce.init({
                selector: '#resolution_text', height: 250,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
//                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css']
            });
//
//            tinymce.init({
//                selector: '#konfirmasi', height: 500,
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

        //Warning Message
        function closeReport(id) {
//            var coc = coc_id;
            swal({
                title: "Are you sure?",
                text: "Permasalahan sudah terselesaikan.",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, problem solved!",
                closeOnConfirm: false
            }, function () {
//                alert('id: '+coc);
                window.location.href = '{{url('report/problem/close/')}}/'+id;
            });
        }



    </script>

@stop