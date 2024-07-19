@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@stop

@section('title')
    <h4 class="page-title">Daftar Peserta Self Assessment</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                {{-- {!! Form::open(['url'=>'self-assessment/daftar-peserta']) !!}
                <div class="form-group row">
                    <div class="col-md-3">
                        @if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
                            {!! Form::select('company_code', $coCodeList, $cc_selected->company_code,
                                ['class'=>'form-control select2',
                                'id'=>'company_code']) !!}
                        @else
                            {!! Form::select('_company_code', $coCodeList, $cc_selected->company_code,
                            ['class'=>'form-control select2',
                            'id'=>'company_code', 'disabled']) !!}
                            {!! Form::hidden('company_code', $cc_selected->company_code) !!}
                        @endif

                    </div>
                    <div class="col-md-3">
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start_date" placeholder="dd-mm-yyyy" value="{{$tgl_awal->format('d-m-Y')}}"/>
                            <span class="input-group-addon bg-custom b-0">to</span>
                            <input type="text" class="form-control" name="end_date" placeholder="dd-mm-yyyy" value="{{$tgl_akhir->format('d-m-Y')}}"/>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <select name="jenis_coc_id" class="form-control">
                            <option value="1" {{($jenis_coc_id=='1')?'selected':''}}>Nasional</option>
                            <option value="2" {{($jenis_coc_id=='2')?'selected':''}}>GM</option>
                            <option value="5" {{($jenis_coc_id=='5')?'selected':''}}>Local</option>
                        </select>
                    </div>
                    <div class="col-md-5 button-list">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a href="{{url('report/persentase-coc/export/'.$cc_selected->company_code.'/'.$tgl_awal->format('d-m-Y').'/'.$tgl_akhir->format('d-m-Y').'/'.$jenis_coc_id)}}" id="post" type="submit"
                           class="btn btn-success waves-effect waves-light">
                            <i class="fa fa-file-excel-o"></i> &nbsp;Export</a>
                    </div>
                </div>
                {!! Form::close() !!} --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="button-list m-b-20">
                            @if(Auth::user()->can('report_assessment'))
                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modal_add_peserta" onclick="">
                               <span class="btn-label"><i class="fa fa-plus"></i></span>Add Peserta
                            </button>
                            <a href="{{url('self-assessment/export-rekap?tahun='.$tahun.'&bulan='.$bulan.'&company_code='.$cc_selected)}}" target="blank" class="btn btn-success waves-effect waves-light" onclick="">
                                <span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Export Rekap
                            </a>
                            @endif
                            <a href="{{asset('assets/doc/panduan-self-assessment.pdf')}}" target="blank" class="btn btn-primary waves-effect waves-light" onclick="">
                                <span class="btn-label"><i class="fa fa-question-circle"></i></span>Panduan Self Assessment
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div style="float: right; margin-left: 20px; ">
                                <button type="submit" class="btn btn-success waves-effect waves-light">
                                    <span class="btn-label"><i class="fa fa-search"></i></span>Search
                                </button>
                            </div>
                            <div style="float: right; margin-left: 20px;">
                                <select name="tahun" class="form-control" id="tahun" style="width: 100px" title="Tahun">
                                    @for($x=2021;$x<=date('Y');$x++)
                                        <option value="{{$x}}" {{($x==$tahun)?'selected':''}}>{{$x}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div style="float: right; margin-left: 20px;">
                                <select name="bulan" class="form-control" id="bulan" style="width: 100px" title="Bulan">
                                    @for($x=1;$x<=12;$x++)
                                        <option value="{{$x}}" {{($x==$bulan)?'selected':''}}>{{$arr_bulan[$x]}}</option>
                                    @endfor
                                </select>
                            </div>
                            @if(Auth::user()->can('report_assessment'))
                                <div style="float: right;">
                                    {!! Form::select('company_code', $cc_list, $cc_selected,
                                                ['class'=>'form-control select2',
                                                'id'=>'company_code']) !!}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                

                <div class="row m-t-10">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="text-align: center" >UNIT</th>
                                <th style="text-align: center" >NIP</th>
                                <th style="text-align: center" >PEGAWAI</th>
                                <th style="text-align: center" >JABATAN SAP</th>
                                <th style="text-align: center" >JABATAN ASSESSMENT</th>
                                <th style="text-align: center" >VERIFIKATOR</th>
                                <th style="text-align: center" >JML KKJ</th>
                                <th style="text-align: center" >JML GAP</th>
                                <th style="text-align: center" >STATUS</th>
                                <th style="text-align: center" >ACTION</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($daftar_peserta as $peserta)
                                <tr>
                                    <td>{{ @$peserta->businessArea->description }}</td>
                                    <td align="center">{{ $peserta->nip_pegawai }}</td>
                                    <td><a href="{{ url('self-assessment/detail-peserta/'.$peserta->id) }}" target="blank">{{ $peserta->nama_pegawai }}</a></td>
                                    <td>{{ $peserta->posisi }}</td>
                                    <td>
                                        {{ @$peserta->jabatanPeserta->sebutan_jabatan }}<br>
                                        <small class="text-muted">{{ @$peserta->jabatanPeserta->organisasi }}</small><br>
                                        <a href="javascript:" title="Update Jabatan" data-toggle="modal" data-target="#modal_update_jabatan" onclick="javascript:$('#peserta_id').val({{ $peserta->id }})"><small><i class="fa fa-pencil-square-o"></i> Edit</small></a>
                                    </td>
                                    <td>
                                        {{ $peserta->nama_verifikator }} / {{ $peserta->nip_verifikator }}<br>
                                        <a href="javascript:" title="Update Verifikator" data-toggle="modal" data-target="#modal_update_verifikator" onclick="javascript:$('#peserta_id_verifikator').val({{ $peserta->id }})"><small><i class="fa fa-pencil-square-o"></i> Edit</small></a>
                                    </td>
                                    <td align="center">{{ $peserta->getJumlahKompetensi() }}</td>
                                    <td align="center">{{ $peserta->getJumlahGap() }}</td>
                                    <td>
                                        @if($peserta->status_assessment=='Belum dilakukan penilaian')
                                            <span class="label label-danger">{{ $peserta->status_assessment }}</span>
                                        @elseif($peserta->status_assessment=='Disetujui')
                                            <span class="label label-success">{{ $peserta->status_assessment }}</span>
                                        @else
                                            <span class="label label-info">{{ $peserta->status_assessment }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="button-list">
                                            <a href="javascript:" class="btn waves-effect waves-light btn-primary" title="Hasil Pengukuran" data-toggle="modal" data-target="#detailkkj" onclick="javascript:getDetailKKJ('{{$peserta->id}}')"> <i class="fa fa-graduation-cap"></i> </a>
                                            <a href="{{ url('self-assessment/detail-peserta/'.$peserta->id) }}" target="blank" class="btn waves-effect waves-light btn-success" title="Verifikasi"> <i class="fa fa-check"></i> </a>

                                            @if(Auth::user()->can('report_assessment'))
                                            <a href="javascript:" onclick="javascript:if(confirm('Apakah Anda yakin ingin menghapus?')){return window.location.href='{{ url('self-assessment/remove-peserta/'.$peserta->id) }}';}" class="btn waves-effect waves-light btn-danger" title="Hapus"> <i class="fa fa-trash-o"></i> </a>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>

                            {{-- <tfoot>
                            <tr>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                
                            </tr>
                            </tfoot> --}}
                        </table>
                        <!-- end row -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal KKJ -->
    <div id="detailkkj" class="modal fade" role="dialog" aria-labelledby="detailKKJModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabel">Hasil Pengukuran Pegawai </h4>
                </div>
                <div class="modal-body">

                    <div id="detail_kkj">Loading...</div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Update Jabatan -->
    <div id="modal_update_jabatan" class="modal fade" role="dialog" aria-labelledby="updateJabatanModalLabel"
         aria-hidden="true">
         {!! Form::open(['url'=>'self-assessment/update-jabatan']) !!}
         {!! Form::hidden('peserta_id','',['id'=>'peserta_id']) !!}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" >Update Jabatan</h4>
                </div>
                <div class="modal-body">

                    
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Jabatan</label>
                        <div>
                            {!! Form::select('jabatan_id', $jabatan_list, null, ['class'=>'form-control select2', 'id'=>'jabatan_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Update
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        {!! Form::close() !!}
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Update Verifikator -->
    <div id="modal_update_verifikator" class="modal fade" role="dialog" aria-labelledby="updateVerifikatorModalLabel"
         aria-hidden="true">
         {!! Form::open(['url'=>'self-assessment/update-verifikator']) !!}
         {!! Form::hidden('peserta_id_verifikator','',['id'=>'peserta_id_verifikator']) !!}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" >Update Verifikator</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="pemateri_id" class="form-control-label">Verifikator</label>

                        <div>
                            <select class="itemName form-control" name="nip_verifikator"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Update
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        {!! Form::close() !!}
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Update Verifikator -->
    <div id="modal_add_peserta" class="modal fade" role="dialog" aria-labelledby="addPesertaModalLabel"
         aria-hidden="true">
         {!! Form::open(['url'=>'self-assessment/add-peserta']) !!}
         {{-- {!! Form::hidden('peserta_id_verifikator','',['id'=>'peserta_id_verifikator']) !!} --}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" >Add Peserta</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="nip_peserta" class="form-control-label">Nama Pegawai</label>

                        <div>
                            <select class="itemName form-control" name="nip_peserta"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="jabatan_peserta_id" class="form-control-label">Jabatan Assessment</label>
                        <div>
                            {!! Form::select('jabatan_peserta_id', $jabatan_list, null, ['class'=>'form-control select2', 'id'=>'jabatan_peserta_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nip_verifikator_peserta" class="form-control-label">Verifikator</label>

                        <div>
                            <select class="itemName form-control" name="nip_verifikator_peserta"
                                    style="width: 100% !important; padding: 0; z-index:10000;"></select>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Save
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        {!! Form::close() !!}
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script type="text/javascript">

    $(window).load(function () {
        if (document.cookie.indexOf('modal_shown=') >= 0) {
            //do nothing if modal_shown cookie is present
        } else {
            $('#modalWelcome').modal('show');
            document.cookie = 'modal_shown=seen'; //set cookie modal_shown
            //cookie will expire when browser is closed
        }

        @if(session('feedback'))
            // if (document.cookie.indexOf('feedback_shown=') >= 0) {
                //do nothing if modal_shown cookie is present
            // } else {
                $('#modalFeedback').modal({backdrop: 'static', keyboard: false}); 
                $('#modalFeedback').modal('show');
                // document.cookie = 'feedback_shown=seen'; //set cookie modal_shown
                //cookie will expire when browser is closed
            // }
        @endif
    });

        $(document).ready(function () {
            $("#company_code").select2();

            $("#jabatan_id").select2();
            $("#tahun").select2();
            $("#bulan").select2();
            $("#jabatan_peserta_id").select2();

            $('.itemName').select2({
                placeholder: 'Select Pegawai',
                ajax: {
                    url: '/self-assessment/ajax-verfikator',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.pa0032.nip+' - '+item.sname,
                                    id: item.pa0032.nip
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
        $(document).ready(function () {
            $('#datatable').DataTable({
                fixedHeader: {
                    header: true,
                    footer: true
                }
            });
        });
        jQuery('#date-range').datepicker({
            autoclose: true,
            toggleActive: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

        function getDetailKKJ(peserta_id){
            $("#detail_kkj").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('self-assessment/get-detail-kkj-verifikator/')}}'+'/'+peserta_id,
                success:function(data){
    //                    console.log(data.judul);
                    // $('#coc_id').val(coc_id);
                $("#detail_kkj").html(data);
                }
            });
        }

    </script>

@stop
