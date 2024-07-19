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

    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

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

                <div class="row m-t-10">
                    <div class="col-sm-12 col-xs-12">
                        {{--  <h4 class="header-title m-t-0">Wizard with Validation</h4>
                        <p class="text-muted font-13 m-b-30">
                            Your awesome text goes here.Your awesome text goes here.
                        </p>  --}}

                        {{-- <form id="wizard-vertical" method="POST" target=""> --}}
                        {!! Form::open(['url'=>'coc/create/local', 'files'=>true, 'id'=>'wizard-vertical']) !!}
                            <h3>Code of Conduct</h3>
                            <section>
                                <div class="row">
                                    <div class="col-md-5 col-xs-12">
                                        <div class="form-group {{($errors->has('tema'))?'has-danger':''}}">
                                            <label for="tema_id" class="form-control-label">Tema <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::select('tema_id', $tema_list, $tema_id, ['class'=>'required form-control select2', 'id'=>'tema_id']) !!}
                                            </div>
                                        </div>
        
                                        <div class="form-group {{($errors->has('judul_coc'))?'has-danger':''}}">
                                            <label for="judul_coc" class="form-control-label">Judul CoC <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::text('judul_coc',null, ['class'=>'required form-control form-control-danger', 'id'=>'judul','autocomplete'=>'none']) !!}
                                            </div>
                                        </div>
        
                                        <div class="form-group {{($errors->has('pernr_leader'))?'has-danger':''}}">
                                            <label for="pernr_leader" class="form-control-label">CoC Leader <span class="text-danger">*</span></label>
        
                                            <div>
                                                <select class="required itemName form-control form-control-danger" name="pernr_leader" id="pernr_leader"
                                                        style="width: 100% !important; padding: 0; z-index:10000;"></select>
                                            </div>
                                        </div>
        
                                        <div class="form-group {{($errors->has('lokasi'))?'has-danger':''}}">
                                            <label for="lokasi" class="form-control-label">Lokasi <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::text('lokasi',null, ['class'=>'required form-control form-control-danger', 'id'=>'lokasi', 'placeholder'=>'Lokasi']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{($errors->has('tanggal_coc'))?'has-danger':''}}">
                                                    <label for="coc_date"
                                                           class="form-control-label">Tanggal <span class="text-danger">*</span></label>
                
                                                    <div>
                
                                                        <div class="input-group">
                                                            <input type="text" class="required form-control form-control-danger" placeholder="dd-mm-yyyy" id="coc_date"
                                                                   name="tanggal_coc" value="{{(old('tanggal_coc'))?old('tanggal_coc'):date('d-m-Y')}}">
                                                            <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                                        </div>
                
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{($errors->has('jam'))?'has-danger':''}}">
                                                    <label for="jam"
                                                           class="form-control-label">Jam <span class="text-danger">*</span></label>
                
                                                    <div>
                
                                                        <div class="input-group clockpicker" data-placement="top" data-align="top"
                                                             data-autoclose="true">
                                                            <input type="text" class="required form-control form-control-danger" placeholder="Masukkan Jam" id="jam" name="jam"
                                                                value="{{old('jam')}}" autocomplete="none">
                                                            <span class="input-group-addon"> <span class="zmdi zmdi-time"></span> </span>
                                                        </div>
                
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        
                                    </div>
                                    <div class="col-md-5">
        
                                        <div class="form-group">
                                            <label for="company_code" class="form-control-label">Company Code <span class="text-danger">*</span></label>
        
                                            <div>
                                                @if(Auth::user()->hasRole('root'))
                                                {!! Form::select('company_code', $coCodeList, $cc_selected,
                                                    ['class'=>'required form-control select2',
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
                                            <label for="business_area" class="form-control-label">Business Area <span class="text-danger">*</span></label>
        
                                            <div>
                                                @if(Auth::user()->hasRole('root'))
                                                    {!! Form::select('business_area', $bsAreaList, $ba_selected,
                                                        ['class'=>'required form-control select2',
                                                        'id'=>'business_area']) !!}
                                                @else
                                                    {!! Form::select('_business_area', $bsAreaList, $ba_selected,
                                                        ['class'=>'form-control select2',
                                                        'id'=>'business_area', 'disabled']) !!}
                                                    {!! Form::hidden('business_area', $ba_selected) !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="orgeh" class="form-control-label">Organisasi <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::select('orgeh', $orgehList, $org_selected,
                                                    ['class'=>'required form-control select2',
                                                    'id'=>'orgeh']) !!}
                                            </div>
                                        </div>
        
                                        <div class="form-group {{($errors->has('jml_peserta'))?'has-danger':''}}">
                                            <label for="jml_peserta"
                                                   class="form-control-label">Jumlah Pegawai <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::number('jml_peserta',null, ['class'=>'required form-control form-control-danger', 'id'=>'jml_peserta', 'placeholder'=>'', 'min'=>'1']) !!}
                                                <small id='loading_jml_pegawai'></small>
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group clearfix">
                                    <label class="col-lg-12 control-label ">(<span class="text-danger">*</span>) Mandatory</label>
                                </div>
                            </section>
                            <h3>Ritual</h3>
                            <section>
                                
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
                                            <div class="col-md-12">
                                                <div align="left">
                                                    <img src="{{asset('assets/images/logo_pln_terbaik.png')}}" width="100"
                                                         class="img-responsive" align="left" style="margin-top: 10px;">
                                                </div>
                                            </div>
                                         </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{($errors->has('pedoman_perilaku_id'))?'has-danger':''}}">
                                                    <label for="do_dont" class="form-control-label">Pedoman Perilaku *</label>
        
                                                    <div>
                                                        {!! Form::select('pedoman_perilaku_id',$do_dont_list, null, ['class'=>'form-control form-control-danger', 'id'=>'do_dont', 'style'=>'width:100%']) !!}
                                                    </div>
                                                </div>
                                                <div id='label_perilaku' class="text-danger text-error" style="font-weight: bold;"></div>
                                                <div class="row">
                                                    <div class="col-md-6">
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
                                                    </div>
                                                    <div class="col-md-6">
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
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">* Pilih maksimal 2 perilaku (1 Do dan 1 Don't / 2 Do / 2 Don't)</small>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <button type="button" class="btn btn-success" id="btn_clear" onclick="clearHistory()"><i class="fa fa-trash"></i> Clear History</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 form-horizontal">
                                                <div class="form-group m-t-20 {{($errors->has('visi'))?'has-danger':''}}">
                                                    <label for="visi" class="form-control-label">Visi</label>
        
                                                    <span>
                                                        <input type="checkbox" name="visi" checked data-plugin="switchery" data-color="#039cfd" readonly value="1"/>
                                                    </span>
                                                    <label for="misi" class="form-control-label" style="margin-left: 20px;">Misi</label>
        
                                                    <span>
                                                        <input type="checkbox" name="misi" data-plugin="switchery" data-color="#039cfd" value="1"/>
                                                    </span>
                                                </div>
                                                <div class="form-group {{($errors->has('tata_nilai'))?'has-danger':''}} m-t-30">
                                                    <label for="" class="form-control-label">Tata Nilai <span class="text-danger">*</span></label>
                                                </div>
                                                <div id='label_sipp' class="text-danger text-error" style="font-weight: bold;"></div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="saling_percaya" class="form-control-label"
                                                                   style="color: #01a3bc; font-size: 17px;">Sinergi</label>
        
                                                            <div>
                                                                <input type="checkbox" name="sipp[]" checked
                                                                       data-plugin="switchery" data-color="#039cfd" value="1"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-- </div>
                                                <div class="row"> --}}
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="integritas" class="form-control-label"
                                                                   style="color: #01a3bc; font-size: 17px;">Profesionalisme</label>
        
                                                            <div>
                                                                <input type="checkbox" name="sipp[]" checked
                                                                       data-plugin="switchery" data-color="#039cfd" value="2"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-- </div>
                                                <div class="row"> --}}
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="peduli" class="form-control-label"
                                                                   style="color: #01a3bc; font-size: 17px;">Berkomitmen pada
                                                                Pelanggan</label>
        
                                                            <div>
                                                                <input type="checkbox" name="sipp[]" checked
                                                                       data-plugin="switchery" data-color="#039cfd" value="3"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
        
        
                                            </div>
                                        </div>
        
        
                                    </div>
                                    <div class="tab-pane fade" id="history" role="tabpanel"
                                         aria-labelledby="history-tab" style="overflow: auto;height: 400px; padding-top:10px;">
                                    </div>
        
                                </div>

                            </section>
                            <h3>Pelanggaran</h3>
                            <section>
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
                                            <div class="col-md-12">
                                                <div align="left">
                                                    <img src="{{asset('assets/images/stop.png')}}" width="100"
                                                         class="img-responsive" align="left" style="margin-top: 10px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id='label_pelanggaran' class="text-danger text-error m-t-10" style="font-weight: bold;"></div>

                                        <div class="row">
                                            <div style="margin-left:15px;"><small class="text-muted">* Pilih salah satu pelanggaran disiplin untuk dibaca waktu CoC.</small></div>
                                            <div class="col-md-12" style="overflow: auto;height: 350px;" id="tabel_pelanggaran">
        {{--                                        <h3 class="m-t-10">Pelanggaran Disiplin</h3>--}}
                                                
                                                <table class="table-responsive m-t-10">
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
                                         aria-labelledby="pelanggaran-history-tab" style="overflow: auto;height: 350px; padding-top:10px;">
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
                            </section>
                            {{-- <h3>Materi</h3>
                            <section>
                                
                            </section> --}}
                        {{-- </form> --}}
                        {!! Form::close() !!}
                        <!-- End #wizard-vertical -->

                    </div>

                </div>
                <!-- end row -->


                
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <!--wizard initialization-->
    <script src="{{ asset('assets/pages/jquery.wizard-init.js')}}" type="text/javascript"></script>
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

            $('#loading_jml_pegawai').html('Loading...');

            $("#history").html('Loading...');

            $("#history_pelanggaran").html('Loading...');

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

            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-jml-pegawai/')}}'+'/'+$('#orgeh').val(),
                success:function(data){
                    // console.log(data);
                    $('#jml_peserta').val(data);
                    $('#loading_jml_pegawai').html('');
                }
            });

            $('#orgeh').change(function(){
                 ajaxPerilaku(); 
                 ajaxPelanggaran(); 
                $("#history").html('Loading...');
                $.ajax({
                    type:'GET',
                    url:'{{url('ajax/get-history/')}}'+'/'+$('#orgeh').val(),
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
                    //console.log(data);
                        $("#history_pelanggaran").html(data);
                    }
                });

                $('#jml_peserta').val('');
                $('#loading_jml_pegawai').html('Loading...');

                $.ajax({
                    type:'GET',
                    url:'{{url('ajax/get-jml-pegawai/')}}'+'/'+$('#orgeh').val(),
                    //data:'_token = <?php echo csrf_token() ?>',
                    success:function(data){
                        // console.log(data);
                    $('#jml_peserta').val(data);
                    $('#loading_jml_pegawai').html('');
                    }
                });
            });

            $('#do_dont').change(function(){
    //            console.log('ok');
                ajaxPerilaku();
            })

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

        function ajaxPerilaku(){
            $("#do_list").html('Loading...');
            $("#dont_list").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('ajax/get-perilaku/')}}'+'/'+$('#do_dont').val()+'/'+$('#orgeh').val()+'/1',
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
                        $("#history").html('Loading...');
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