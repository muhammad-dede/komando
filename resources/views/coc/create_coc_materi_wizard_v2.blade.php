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
                        {!! Form::open(['url'=>'coc/create/'.$materi->id.'/store', 'files'=>true, 'id'=>'wizard-vertical']) !!}
                            <h3>Code of Conduct</h3>
                            <section>
                                <div class="row">
                                    <div class="col-md-5 col-xs-12">
                                        <div class="form-group {{($errors->has('judul_coc'))?'has-danger':''}}">
                                            <label for="judul_coc" class="form-control-label">Judul CoC <span class="text-danger">*</span></label>
        
                                            <div>
                                                {!! Form::text('judul',$materi->judul, ['class'=>'required form-control form-control-danger', 'id'=>'judul','autocomplete'=>'none']) !!}
                                            </div>
                                        </div>
        
                                        <div class="form-group {{($errors->has('nip_pemateri'))?'has-danger':''}}">
                                            <label for="nip_pemateri" class="form-control-label">CoC Leader <span class="text-danger">*</span></label>
        
                                            <div>
                                                <select class="required itemName form-control form-control-danger" name="nip_pemateri" id="nip_leader"
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
                                                                   name="tanggal_coc" value="{{(old('tanggal_coc'))?old('tanggal_coc'):$tanggal_materi->format('d-m-Y')}}" autocomplete="none">
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
                                                {!! Form::select('orgeh', $orgehList, $selected_kode_org,
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

                                @include('coc.ritual_coc_akhlak')
                                
                            </section>

                            <h3>Isu Nasional</h3>
                            <section>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div align="left">
                                            <img src="{{asset('assets/images/globe-grid.png')}}" width="100"
                                                 class="img-responsive" align="left" style="margin-top: 10px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div id='label_isu' class="text-danger text-error m-t-10" style="font-weight: bold;"></div>

                                <div class="row">
                                    {{-- include form_isu_nasional --}}
                                    @include('coc.form_isu_nasional')
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
                                                                Pelanggaran Disiplin
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
                                         aria-labelledby="pelanggaran-history-tab" style="overflow: auto;height: 400px; padding-top:10px;">
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
                                                    Pelanggaran Disiplin
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
                            <h3>Materi</h3>
                            <section>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        @if($materi->energize_day=='1')
                                            <img src="{{asset('assets/images/PLN.png')}}" alt="Energize Day"  class="img-fluid img-thumbnail" width="128">
                                        @elseif(@$materi->penulis->user->foto!='')
                                            <img src="{{asset('assets/images/users/foto-thumb/'.@$materi->penulis->user->foto)}}" alt="user"  class="img-fluid img-thumbnail" width="128">
                                        @else
                                            <img src="{{asset('assets/images/user.jpg')}}" alt="user"  class="img-fluid img-thumbnail" width="128">
                                        @endif
        {{--                                @if($coc->scope!='nasional')--}}
        {{--                                    <img src="{{(@$materi->penulis->user->foto!='') ? url('user/foto-thumb/'.$materi->penulis->user->id) : url('user/foto-pegawai-thumb/'.$materi->penulis->nip)}}"--}}
                                            {{--<img src="{{(@$materi->penulis->user->foto!='') ? url('user/foto-thumb/'.$materi->penulis->user->id) : asset('assets/images/user.jpg')}}"--}}
                                                 {{--alt="User"--}}
                                                 {{--class="img-thumbnail" width="128">--}}
                                        {{--@endif--}}
                                        <hr>
                                        {{--<i class="fa fa-clock-o"></i> --}}
                                        <table>
                                            <tr>
                                                <td>
                                                    <span class="display-4">{{$materi->event->start->format('d')}}</span>
                                                </td>
                                                <td style="padding-left: 10px;">
                                                    <span style="font-size: 18px">{{$materi->event->start->format('F')}}</span><br>
                                                    <span style="font-size: 18px"
                                                          class="text-muted">{{$materi->event->start->format('l')}}</span>
        
                                                </td>
                                            </tr>
                                        </table>
                                        <hr>
        
                                        <fieldset class="form-group m-t-30">
                                            <label>Tema</label>
                                            <p>
                                                {{$materi->tema->tema}}
                                            </p>
                                        </fieldset>
        
                                        @if($materi->attachments->count()>0)
                                            <fieldset class="form-group m-t-30">
                                                <label>Attachments</label>
        
                                                @foreach($materi->attachments as $data)
                                                    <p>
                                                        <a href="{{url('coc/atch/'.$data->id)}}">
                                                            <i class="fa fa-paperclip"></i> {{$data->filename}}
                                                        </a>
                                                    </p>
                                                @endforeach
                                            </fieldset>
                                        @endif
                                        <fieldset class="form-group m-b-30">
                                            @if($materi->jenis_materi_id == '3')
                                                <span class="label label-primary">Local</span>
                                            @elseif($materi->jenis_materi_id == '2')
                                                <span class="label label-warning">General Manager</span>
                                            @elseif($materi->jenis_materi_id == '1')
                                                <span class="label label-danger">Kantor Pusat</span>
                                            @endif
                                        </fieldset>
                                    </div>
                                    <div class="col-md-9">
                                        <h2 class="card-title">{{$materi->judul}}</h2>
        
                                        <div id="default" class="m-b-10"></div>
                                        <small class="text-muted">
                                            @if($materi->energize_day=='1')
                                                Energize Day
                                            @else
                                                by {{@$materi->penulis->cname}}
                                                -
                                                {{@$materi->penulis->strukturPosisi->stext}}
                                            @endif
                                                &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{@$materi->event->start->diffForHumans()}}
                                                {{--&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;--}}
                                                {{--                                    {{$coc->tanggal_jam->format('d F Y, H:i A')}}--}}
                                                {{--{{$coc->lokasi}}--}}
                                                {{--&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;--}}
                                                {{--                                    {{$coc->attendants->count()}} member(s)--}}
                                        </small>
        
                                        <div class="m-t-10">
                                            {!! @$materi->deskripsi !!}
                                        </div>
                                    </div>
                                </div>

                            </section>
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
    <script src="{{ asset('assets/pages/jquery.wizard-init-v2.js')}}" type="text/javascript"></script>
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

            //ajaxPerilaku();
            ajaxPelanggaran();

            $('#loading_jml_pegawai').html('Loading...');

            //$("#history").html('Loading...');

            $("#history_pelanggaran").html('Loading...');
            ajaxHistoryPelanggaran();

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/coc/ajax-leader',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.nip+' - '+item.name,
                                    id: item.nip
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
                 //ajaxPerilaku(); 
                 ajaxPelanggaran(); 
                /*$("#history").html('Loading...');
                $.ajax({
                    type:'GET',
                    url:'{{url('ajax/get-history/')}}'+'/'+$('#orgeh').val(),
                    success:function(data){
                    // console.log(data);
                        $("#history").html(data);
                    }
                });*/
                $("#history_pelanggaran").html('Loading...');
                ajaxHistoryPelanggaran();

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
                //ajaxPerilaku();
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
//                    $('#total_daya_terpasang').val(data.totalTata Nilai_daya_terpasang);
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

        function ajaxHistoryPelanggaran(){
            $.ajax({
                    type:'GET',
                    url:'{{url('ajax/get-history-pelanggaran/')}}'+'/'+$('#orgeh').val(),
                    success:function(data){
                    //console.log(data);
                        $("#history_pelanggaran").html(data);
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
                    ajaxHistoryPelanggaran();
                }
            });
        }

    </script>
@stop