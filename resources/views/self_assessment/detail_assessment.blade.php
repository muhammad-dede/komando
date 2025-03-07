@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Verifikasi Self Assessment Pegawai</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {{-- <div class="alert alert-warning alert-dismissible fade in" role="alert" style="font-size: 16px;">
                    <button type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-warning"></i> &nbsp;&nbsp;<strong>INFORMASI</strong> 
                    <p>Self Assessment ini ditujukan untuk pengembangan kemampuan teknis (pengetahuan & keterampilan melalui diklat/penugasan/sertifiaksi), dan tidak dikaitkan dengan kinerja maupun remunerasi/penghargaan.</p>
                </div> --}}

                {{-- <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-12">
                        <a href="{{url('self-assessment/panduan')}}" id="buku" class="btn btn-success w-lg waves-effect waves-light">
                            <i class="fa fa-book"></i> &nbsp;Panduan Self Assessment</a>
                    </div>
                </div> --}}

                <div>
                    <div class="row">
                        {{-- @foreach ($daftar_peserta as $peserta) --}}
                        <div class="col-sm-6 col-lg-6 col-xs-12">
    
                            <div class="card">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="user-image">
                                                <div class="fileinput-new thumbnail">
                                                    <img src="{{ app_user_avatar($peserta->nip_pegawai) }}" alt="user" class="img img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="card-title" style="margin-bottom: 20px;">{{ $peserta->nama_pegawai }} {!! ($peserta->status_assessment=='Disetujui')?'<i class="fa fa-check-circle text-success" style="font-size:32px;"></i>':'' !!}</h4>
                                            <p class="text-muted">NIP : {{ $peserta->nip_pegawai }}</p>
                                            <p class="text-muted">Jabatan : {{ $peserta->jabatan_pegawai }}</p>
                                            <p class="text-muted">Unit : {{ @$peserta->companyCode->description }}</p>

                                            {{-- <div>
                                                @if($peserta->status_assessment=='Terkirim')
                                                    <button type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light active" onclick="javascript:if(confirm('Apakah Anda yakin untuk menyetujui hasil pengukuran ini?')){return window.location.href='{{ url('self-assessment/approve/'.$peserta->id) }}'}"><i class="fa fa-check"></i> Approve</button>
                                                @elseif($peserta->status_assessment=='Disetujui')
                                                    <button type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light active" onclick="javascript:if(confirm('Apakah Anda yakin untuk membatalkan persetujuan hasil pengukuran ini?')){return window.location.href='{{ url('self-assessment/unapprove/'.$peserta->id) }}'}"><i class="fa fa-times"></i> Unapprove</button>
                                                @endif
                                            </div> --}}

                                            <div>
                                                @if($peserta->status_assessment == 'Siap diverifikasi' || $peserta->status_assessment == 'Penilaian lengkap' || $peserta->status_assessment == 'Persetujuan dibatalkan')
                                                    <button type="button" class="btn btn-lg btn-success waves-effect waves-light active" onclick="javascript:if(confirm('Apakah Anda yakin untuk menyetujui hasil pengukuran ini? Pastikan Anda telah memilih Prioritas Pengembangan Pegawai.')){return window.location.href='{{ url('self-assessment/approve/'.$peserta->id) }}'}"><i class="fa fa-check"></i> Approve Assessment</button>
                                                @elseif($peserta->status_assessment=='Disetujui')
                                                    <button type="button" class="btn btn-lg btn-warning waves-effect waves-light active" onclick="javascript:if(confirm('Apakah Anda yakin untuk membatalkan persetujuan hasil pengukuran ini?')){return window.location.href='{{ url('self-assessment/unapprove/'.$peserta->id) }}'}"><i class="fa fa-times"></i> Cancel Approval</button>
                                                @else 
                                                    <button type="button" class="btn btn-lg btn-secondary waves-effect waves-light active muted disabled"><i class="fa fa-check"></i> Approve Assessment</button>
                                                @endif

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-lg btn-secondary dropdown-toggle waves-effect waves-light {{ ($peserta->status_assessment=='Disetujui')?'disabled':'' }}" data-toggle="dropdown" aria-expanded="false"><span class="caret"><i class="fa fa-gear"></i></span></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target="#modal_update_jabatan" onclick="javascript:$('#peserta_id').val({{ $peserta->id }})">Update Jabatan</a>
                                                        <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target="#modal_update_verifikator" onclick="javascript:$('#peserta_id_verifikator').val({{ $peserta->id }})">Update Verifikator</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="card">
                                                <h3 class="card-header">{{ ($peserta->jabatan_id!=null)?@$peserta->jabatanPeserta->sebutan_jabatan:'-' }}</h3>
                                                <div class="card-block">
                                                    
                                                    
                                                    @php
                                                        $jabatan = $peserta->jabatanPeserta;
                                                    @endphp

                                                    @if($jabatan!=null)

                                                    <div class="button-list m-b-20">
                                                        <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#detailkkj" onclick="javascript:getDetailKKJ('{{$peserta->id}}')">
                                                        <span class="btn-label"><i class="fa fa-graduation-cap"></i></span>Hasil Pengukuran Pegawai
                                                        </button>
            
                                                        <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#modal_kamus_level">
                                                            <span class="btn-label"><i class="fa fa-info-circle"></i></span>Petunjuk Level Profisiensi
                                                        </button>
            
                                                        <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target="#modal_prioritas_pengembangan" onclick="javascript:prioritasPengembangan('{{$peserta->id}}')">
                                                            <span class="btn-label"><i class="fa fa-bookmark"></i></span>Prioritas Pengembangan
                                                        </button>
                                                    </div>
                                                    
                                                    <p style="font-size: medium;"><b>Periode</b> : {{ $periode_bulan }}</p>

                                                    {{-- jika jabatan sudah ada --}}
                                                    <table class="table table-hover">
                                                        @foreach(@$peserta->jabatanPeserta->kompetensi()->orderBy('kode_kompetensi','asc')->get() as $kompetensi)

                                                        @php
                                                            $penilaian_pegawai = $peserta->getPenilaianPegawai($kompetensi->kode_kompetensi);
                                                        @endphp

                                                        <tr>
                                                            <td><a href="javascript:" data-toggle="modal" data-target="#modal_detail_kompetensi" onclick="javascript:editKompetensi('{{ $peserta->id }}', '{{$kompetensi->kode_kompetensi}}',0)"><b>{{ @$kompetensi->kompetensi->judul_kompetensi }}</b></a>  {!! ($penilaian_pegawai!=null && $penilaian_pegawai->prioritas==1)?'&nbsp;&nbsp;<span class="text-danger"><i class="fa fa-bookmark"></i></span>':'' !!}</td>
                                                            <td>
                                                                 {{-- jika sudah menilai --}}
                                                                 @if($penilaian_pegawai!=null)

                                                                    @if($penilaian_pegawai->tanggal_approve!=null || $penilaian_pegawai->tanggal_approve!='')
                                                                    {{-- <div id="readOnly" title="regular">
                                                                    
                                                                        @for($x=1;$x<=$penilaian_pegawai->level_final;$x++)
                                                                            <i class="fa fa-star text-warning" title="regular" data-score="1"></i>&nbsp;
                                                                        @endfor

                                                                        @for($x=1;$x<=6-$penilaian_pegawai->level_final;$x++)
                                                                            <i class="fa fa-star-o text-muted" title="regular" data-score="1"></i>&nbsp;
                                                                        @endfor

                                                                    </div> --}}
                                                                        <div id="level_{{ $kompetensi->id }}_{{ $peserta->id }}" class="rating-md"></div>
                                                                        @push('skrip')
                                                                        <script type="text/javascript">
                                                                            $(document).ready(function () {
                                                                                $('#level_{{ $kompetensi->id }}_{{ $peserta->id }}').raty({
                                                                                    readOnly: true,
                                                                                    number: {{ $jml_level }},
                                                                                    score: {{ $penilaian_pegawai->level_final }},
                                                                                    starOff: 'fa fa-star-o text-muted',
                                                                                    starOn: 'fa fa-star text-danger',
                                                                                    hints: [{!! $hint !!}]
                                                                                });
                                                                            });
                                                                            </script>
                                                                        @endpush
                                                                    @else
                                                                    <div id="level_{{ $kompetensi->id }}_{{ $peserta->id }}" class="rating-md"></div>
                                                                    @push('skrip')
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function () {
                                                                            $('#level_{{ $kompetensi->id }}_{{ $peserta->id }}').raty({
                                                                                number: {{ $jml_level }},
                                                                                score: {{ $penilaian_pegawai->level_final }},
                                                                                starOff: 'fa fa-star-o text-muted',
                                                                                starOn: 'fa fa-star text-danger',
                                                                                hints: [{!! $hint !!}],
                                                                                click: function (score, evt) {
                                                                                    //alert('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
                                                                                    editKompetensi('{{ $peserta->id }}', '{{$kompetensi->kode_kompetensi}}', score);
                                                                                    $("#modal_detail_kompetensi").modal('show');
                                                                                }
                                                                            });
                                                                        });
                                                                        </script>
                                                                    @endpush
                                                                @endif

                                                                 {{-- jika belum menilai --}}
                                                                 @else
                                                                    <div id="level_{{ $kompetensi->id }}_{{ $peserta->id }}" class="rating-md"></div>
                                                                    @push('skrip')
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function () {
                                                                            $('#level_{{ $kompetensi->id }}_{{ $peserta->id }}').raty({
                                                                                number: {{ $jml_level }},
                                                                                starOff: 'fa fa-star-o text-muted',
                                                                                starOn: 'fa fa-star text-danger',
                                                                                hints: [{!! $hint !!}],
                                                                                click: function (score, evt) {
                                                                                    //alert('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
                                                                                    editKompetensi('{{ $peserta->id }}', '{{$kompetensi->kode_kompetensi}}', score);
                                                                                    $("#modal_detail_kompetensi").modal('show');
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>
                                                                    @endpush
                                                                    {{-- <div id="click" style="cursor: pointer;">
                                                                        <i class="fa fa-fw fa-star-o" title="Level 1" data-score="1"></i>&nbsp;
                                                                        <i class="fa fa-fw fa-star-o" title="Level 2" data-score="2"></i>&nbsp;
                                                                        <i class="fa fa-fw fa-star-o" title="Level 3" data-score="3"></i>&nbsp;
                                                                        <i class="fa fa-fw fa-star-o" title="Level 4" data-score="4"></i>&nbsp;
                                                                        <i class="fa fa-fw fa-star-o" title="Level 5" data-score="5"></i>
                                                                        <input type="hidden" name="score" value="0">
                                                                    </div> --}}
                                                                 @endif

                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    <small><span class="text-danger" style="fon-weight: bold;"><i class="fa fa-bookmark"></i></span> &nbsp;&nbsp;<span class="text-muted">Prioritas pengembangan</span></small>
                                                    @else
                                                        {{-- jika jabatan belum ada --}}
                                                        <div>
                                                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                                <strong>Perhatian!</strong> Jabatan self assessment tidak ditemukan. Silakan memilih jabatan pada menu "Update Jabatan" atau <a href="javascript:" data-toggle="modal" data-target="#modal_update_jabatan" onclick="javascript:$('#peserta_id').val({{ $peserta->id }})" style="font-weight: bold">klik di sini</a>.
                                                            </div>
                                                            
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- @if($jabatan!=null)
                                                    <div class="button-list">
                                                           <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#detailkkj" onclick="javascript:getDetailKKJ('{{$peserta->id}}')">
                                                              <span class="btn-label"><i class="fa fa-graduation-cap"></i></span>Hasil Pengukuran Pegawai
                                                            </button>
                
                                                            <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#modal_kamus_level">
                                                                <span class="btn-label"><i class="fa fa-info-circle"></i></span>Level Profisiensi
                                                            </button>
                                                    </div>
                                                    @endif --}}
                                                    
                                                </div>
                                            </div>
                                            <div class="row m-t-20">
                                                <div class="col-md-6 col-xs-12"><b>Status:</b><br> {{ $peserta->status_assessment }}</div>
                                                <div class="col-md-6 col-xs-12" style="text-align: left"><b>Verifikator:</b><br> {!! ($peserta->nip_verifikator!=null)?$peserta->nama_verifikator:'<b style="color:red">Belum dipilih</b>' !!}</div>
                                            </div>
                                            @if($peserta->nip_verifikator==null)
                                            <div class="alert alert-danger alert-dismissible fade in m-t-20" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong>Perhatian!</strong> Verifikator belum dipilih. Silakan memilih verifikator pada menu "Update Verifikator" atau <a href="javascript:" data-toggle="modal" data-target="#modal_update_verifikator" onclick="javascript:$('#peserta_id_verifikator').val({{ $peserta->id }})" style="font-weight: bold">klik di sini</a>.
                                            </div>
                                            @endif
                                        </div>
                                        {{-- <div class="col-lg-4 col-md-4 col-xs-12">
                                            <div class="button-list">
                                                @if($jabatan!=null)
                                                    <button type="button" class="btn btn-block btn-lg btn-info waves-effect waves-light active" data-toggle="modal" data-target="#detailkkj" onclick="javascript:getDetailKKJ('{{$peserta->id}}')"><i class="fa fa-graduation-cap"></i> Hasil Pengukuran</button>
                                                    <button type="button" class="btn btn-block btn-md btn-info waves-effect waves-light active" data-toggle="modal" data-target="#modal_kamus_level">Level Profisiensi</button>
                                                    <br>
                                                    <br>
                                                @endif

                                                <button type="button" class="btn btn-block btn-md btn-success waves-effect waves-light active" data-toggle="modal" data-target="#modal_update_jabatan" onclick="javascript:$('#peserta_id').val({{ $peserta->id }})">Update Jabatan</button>
                                                <button type="button" class="btn btn-block btn-md btn-success waves-effect waves-light active" data-toggle="modal" data-target="#modal_update_verifikator" onclick="javascript:$('#peserta_id_verifikator').val({{ $peserta->id }})">Update Verifikator</button>
                                            </div>
                                        </div> --}}

                                    </div>
                                    
                                </div>
                                <hr style="margin-left: 20px;margin-right: 20px;">
                                <div class="card-block">
                                    <h5 class="card-text">Hardskill yang paling dikuasai:</h5>
                                    {{-- <small>Contoh: ICOFR, Proses Billing, Programming</small> --}}
                                    <div style="margin:10px;">
                                        {{-- <textarea class="form-control" name="hardskill_read_{{ @$peserta->id }}" id="hardskill_read_{{ @$peserta->id }}" rows="3" readonly>{{ @$peserta->hardskill }}</textarea> --}}
                                        <pre>{{ @$peserta->hardskill }}</pre>
                                    </div>
                                    
                                    @if(!($peserta->status_assessment=='Disetujui' || $peserta->status_assessment=='Belum dilakukan penilaian'))
                                    <div class="m-t-20" style="">
                                        <button type="button" class="btn btn-sm btn-success waves-effect waves-light active" data-toggle="modal" data-target="#modal_update_hardskill"><i class="fa fa-pencil"></i> Edit Hardskill</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
    
                        </div><!-- end col -->
                            
                        {{-- @endforeach --}}
                        
                    </div>
                </div>


                
            </div>
        </div>
    </div>

    <!-- Modal Prioritas Pengembangan -->
    <div id="modal_prioritas_pengembangan" class="modal fade" role="dialog" aria-labelledby="modal_prioritas_pengembanganModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" id="myModalLabelmodal_prioritas_pengembangan">Prioritas Pengembangan </h4>
                </div>
                <div class="modal-body">

                    <div id="data_prioritas_pengembangan">Loading...</div>
                    
                </div>
                <div class="modal-footer">
                    {{-- <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Submit
                    </button> --}}
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
                    {{-- <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Submit
                    </button> --}}
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Update Kompetensi  -->
    <div id="modal_detail_kompetensi" class="modal fade" role="dialog" aria-labelledby="detailKompetensiModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title" >Update Kompetensi</h4>
                </div>
                <div class="modal-body">

                    <div id="div_detail_kompetensi">Loading...</div>
                    
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

    <!-- Modal Kamus Level -->
    @include('self_assessment.modal_kamus_level')

    <!-- Modal Hardskill -->
    <div id="modal_update_hardskill" class="modal fade" role="dialog" aria-labelledby="HardskillModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {!! Form::open(['url'=>'self-assessment/update-hardskill']) !!}
                {!! Form::hidden('peserta_id_hardskill',@$peserta->id,['id'=>'peserta_id_hardskill']) !!}
                {!! Form::hidden('peserta_id',@$peserta->id) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Hardskill yang paling dikuasai </h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="hardskill" class="form-control-label">Contoh: ICOFR, Proses Billing, Programming</label>

                        <div>
                            <textarea class="form-control" name="hardskill" id="hardskill" rows="5">{{ @$peserta->hardskill }}</textarea>
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
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modalWelcome" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWelcome" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title" id="myModalLabel">Self Assessment Pegawai</h4>
                </div>
                <div class="modal-body" align="left">
                    <img src="{{asset('assets/images/banner_self_assessment.png')}}" class="img-fluid center">
                    <div class="m-t-20 p-20">
                        <h4>Selamat Datang,</h4>
                        <p class="m-t-20">
                            Fitur <i>Self Assessment</i> ini merupakan salah satu tool yang digunakan untuk mendapatkan informasi seputar kompetensi bidang/teknis pegawai. Adapun tujuan dari pelaksaan <i>Self Assessment</i> ini adalah untuk pemetaan sekaligus pengembangan kompetensi bidang/teknis berdasarkan level profisiensi kompetensi sesuai KKJ (Kebutuhan Kompetensi Jabatan).
                        </p>
                        <p>
                            Sesuai hasil benchmark ke beberapa BUMN, maka untuk tahap awal teknis pengukuran kompetensi bidang/teknis menggunakan metode <i>Self Assessment</i>, yaitu dengan memilih di level profisiensi mana pegawai berada.
                        </p>
                        <p>
                            Silahkan mengikuti <i>Self Assessment</i> ini dengan kondisi riil dan seobjektif mungkin terkait kompetensi bidang/teknis yang dimiliki. pegawai. sehingga hasil pemetaan dan pemilihan program pengembangan pegawai (diklat/penugasan/sertifikasi, dll) dapat lebih efektif.
                        </p>
                        {{-- <p>Demikian kami sampaikan, terimakasih.</p>
                        <p>Salam,<br>
                            ADMIN KOMANDO
                        </p> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect" data-dismiss="modal">Mulai</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal Feedback -->
    <div id="modalFeedback" class="modal fade" role="dialog" aria-labelledby="FeedbackModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {!! Form::open(['url'=>'self-assessment/feedback']) !!}
                {{-- {!! Form::hidden('peserta_id',@$peserta->id,['id'=>'peserta_id']) !!} --}}
                <div class="modal-header">
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button> --}}
                    <h4 class="modal-title">Feedback Self Assessment</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="feedback" class="form-control-label">Terimakasih telah berpartisipasi dalam Self Assessment Hard Competency. Mohon kesediaannya untuk memberikan feedback terhadap tools Self Assessment kami pada form di bawah ini.</label>
                        <div>
                            <textarea class="form-control" name="feedback" id="feedback" rows="5" placeholder="Feedback / Kritik / Saran" required></textarea>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                                class="fa fa-save"></i>
                        Submit
                    </button>
                    {{-- <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Close
                    </button> --}}
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('javascript')
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/raty-fa/jquery.raty-fa.js')}}"></script>

@stack('skrip')
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
        $("#jabatan_id").select2();

        $('.itemName').select2({
            placeholder: 'Masukkan nama pegawai',
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

        function getDetailKKJ(peserta_id){
            $("#detail_kkj").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('self-assessment/get-detail-kkj-verifikator/')}}'+'/'+peserta_id,
                success:function(data){
                   $("#detail_kkj").html(data);
                }
            });
        }

        function prioritasPengembangan(peserta_id){
            $("#data_prioritas_pengembangan").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('self-assessment/prioritas-pengembangan/')}}'+'/'+peserta_id,
                success:function(data){
                   $("#data_prioritas_pengembangan").html(data);
                }
            });
        }

    function editKompetensi(peserta_id, kode_kompetensi, score){
            $("#div_detail_kompetensi").html('Loading...');
            $.ajax({
                type:'GET',
                url:'{{url('self-assessment/edit-kompetensi-verifikator')}}'+'?peserta_id='+peserta_id+'&kode_kompetensi='+kode_kompetensi+'&score='+score,
                success:function(data){
                   $("#div_detail_kompetensi").html(data);
                }
            });
        }
</script>
@stop